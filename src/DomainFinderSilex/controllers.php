<?php
$app->get('/application/{application_id}/saved_queries', function($application_id) use ($app) {
	$application 	= $app['orm.em']->getRepository( 'DomainFinder\Entity\Application' )->find( $application_id );
	$queries 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' )->findByApplication( $application );
	$params 		= array( 'saved_queries' => $queries, 'application_id' => $application_id );

	return $app['twig']->render('select_query.twig', $params );
})
->bind( 'saved_queries' );

$app->get('/application/{application_id}/new', function($application_id) use ($app) {
	$params = array( 'application_id' => $application_id );
	try{
		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'save_query', $params );
		$errors 			= $app['request']->getSession()->getFlashBag()->get('errors');

		if ( !empty( $errors ) )
		{
			$params['errors'] = $errors;
		}

		//$domains = $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' )->findByQuery( 1 );
		$query 		= $app['orm.em']->createQuery('SELECT u FROM DomainFinder\Entity\Domain u JOIN u.query a WHERE a.application = ' . $application_id);
		$domains 	= $query->getResult();
		$urls 		= array();
		foreach ( $domains as $domain ) {
			$urls[] = $domain->getUrl();
		}

		$params['domains'] = array_unique( $urls );

		return $app['twig']->render('new.twig', $params );
	}
	catch ( \DomainFinder\Exception\LoginRequiredException $e )
	{
		return $app->redirect( $app['url_generator']->generate('login') );
	}
})->bind( 'new_query' );

$app->post('/application/{application_id}/new', function($application_id) use ($app) {
	try
	{
		$params = array(
			'query' 		=> $app['request']->request->get( 'query' ),
			'domains' 		=> $app['request']->request->get( 'domains' ),
			'application_id'=> $application_id
		);

		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'query.save', $params );
		$use_case 			= $app['use_cases.query.create'];

		$response 			= $use_case->execute( $request );

		return $app->redirect( $app['url_generator']->generate('queries', array( 'application_id' => $application_id ) ) );
	}
	catch ( \DomainFinder\Exception\LoginRequiredException $e )
	{
		return $app->redirect( $app['url_generator']->generate('login') );
	}
	catch ( \Exception $e)
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The query \'' . $app['request']->request->get( 'query' ) . "' already exists. " . $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('new_query') );
	}
});

$app->get('/application/{application_id}/edit/{query}', function($application_id, $query) use ($app) {
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'query.show', array( 'application_id' => $application_id, 'query' => $query ) );
	$use_case 			= $app['use_cases.query.show'];

	$response 			= $use_case->execute( $request );

	$params 			= array(
		'application_id' 	=> $application_id,
		'errors' 			=> $app['request']->getSession()->getFlashBag()->get('errors')
	);

	return $app['twig']->render('edit.twig', array_merge( $params, $response ) );
})
->bind('edit_query');

$app->post('/application/{application_id}/edit/{query}', function( $application_id, $query ) use ($app) {
	try
	{
		$params = array(
			'domains' 			=> $app['request']->request->get( 'domains' ),
			'query' 			=> $app['request']->request->get( 'query' ),
			'original_query' 	=> $app['request']->request->get( 'original_query' )
		);
		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'query.update', $params );
		$use_case 			= $app['use_cases.query.update'];

		$response 			= $use_case->execute( $request );

		return $app->redirect( $app['url_generator']->generate('queries', array( 'application_id' => $application_id ) ) );
	}
	catch ( \Exception $e)
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The query \'' . $app['request']->request->get( 'query' ) . "' already exists. " . $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate( 'edit_query', array( 'application_id' => $application_id, 'query' => $app['request']->request->get( 'original_query' ) ) ) );
	}
})->bind('edit_query_post');

$app->get('/application/{application_id}/delete/{query}', function($application_id, $query) use ($app) {
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'query.show', array( 'application_id' => $application_id, 'query' => $query ) );
	$use_case 			= $app['use_cases.query.show'];

	$response 			= $use_case->execute( $request );
	$response['application_id'] = $application_id;

	return $app['twig']->render('delete.twig', $response );
})->bind('delete_query');

$app->post('/application/{application_id}/delete', function($application_id) use ($app) {
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'query.delete', array( 'query' => $app['request']->request->get( 'query' ) ) );
	$use_case 			= $app['use_cases.query.delete'];

	$response 			= $use_case->execute( $request );

	return $app->redirect( $app['url_generator']->generate('queries', array( 'application_id' => $application_id ) ) );
})->bind('delete_query_post');

$app->get('/application/{application_id}', function( $application_id ) use ($app) {
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'query.list', array( 'application_id' => $application_id ) );
	$use_case 			= $app['use_cases.query.list'];

	$response 			= $use_case->execute( $request );

	return $app['twig']->render( 'queries.twig', array_merge( array( 'application_id' => $application_id ), $response ) );
})
->bind( 'queries' );

$app->get('/application/{application_id}/chart', function( $application_id ) use ($app) {
	$query 				= $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' )->findOneByQuery( $app['request']->query->get( 'q' ) );
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'rank.show', array( 'query' => $query ) );
	$use_case 			= $app['use_cases.rank.show'];

	$response 	= $use_case->execute( $request );
	
	$domains 	= array();
	$dates 		= array();
	foreach ( $response['ranking'] as $domain ) {
		foreach ($domain as $rank ) {
			$dates[$rank->getDate()->getTimestamp()][] 	= $rank;
		}
	}

    return $app['twig']->render('chart.twig', array(
    	'application_id' 	=> $application_id,
    	'query' 			=> $query->getQuery(),
    	'domains' 			=> $response['ranking'],
    	'dates'				=> $dates
    ));
})
->bind('chart');

$app->get('/register', function() use ($app) {
	$params = array();
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}

	return $app['twig']->render('register.twig', $params);
})
->bind('register');

$app->post('/register', function() use ($app) {
	try {
		$params = array(
			'name' 		=> $app['request']->request->get( 'name' ),
			'email' 	=> $app['request']->request->get( 'email' ),
			'password' 	=> $app['request']->request->get( 'password' ),
			'terms' 	=> $app['request']->request->get( 'terms' )
		);
		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'user.register', $params );
		$use_case 			= $app['use_cases.user.register'];

		$response 			= $use_case->execute( $request );

		return $app->redirect( $app['url_generator']->generate('index') );
	}
	catch ( \InvalidArgumentException $e )
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('register') );
	}
});

$app->get('/login', function() use ($app) {
	$params = array();
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}
	
	return $app['twig']->render('login.twig', $params);
})
->bind('login');

$app->post('/login', function() use ($app) {
	try {
		$params 	= array(
			'email' 	=> $app['request']->request->get( 'email' ),
			'password' 	=> $app['request']->request->get( 'password' )
		);
		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'user.login', $params );
		$use_case 			= $app['use_cases.user.login'];

		$response 			= $use_case->execute( $request );
	}
	catch ( \InvalidArgumentException $e )
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('login') );
	}

	return $app->redirect( $app['url_generator']->generate('index') );
});

$app->get('/logout', function() use ($app) {
	$use_case = $app['use_cases.user.logout'];
	$use_case->execute();
	
	return $app->redirect( $app['url_generator']->generate('login') );
})
->bind('logout');

$app->get('/install', function() use ($app) {
	$params = array();
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}

	return $app['twig']->render('install.twig', $params);
})
->bind('install');

$app->post('/install', function() use ($app) {
	try {
		$params = array(
			'email' 	=> $app['request']->request->get( 'email' ),
			'password' 	=> $app['request']->request->get( 'password' )
		);
		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'install', $params );
		$use_case 			= $app['use_cases.install'];

		$response 			= $use_case->execute( $request );
	}
	catch ( \Exception $e )
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('install') );
	}

	var_dump( $response, $app['session']->get('current_user'), $user_repo);die;

	return $app->redirect( $app['url_generator']->generate('index') );
});
/*
$app->get('/domain/{domain}', function( $domain ) use ($app) {
	try {
		$repo 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' );
		$use_case 	= new DomainFinder\UseCase\ListQueries(
			$repo,
			$app['request']->getSession()
		);
		$response 	= $use_case->run( $app['requests.query_list'] );
	} catch ( \InvalidArgumentException $e) {
		return $app->redirect( $app['url_generator']->generate('login') );
	}

	return $app['twig']->render( 'queries_new.twig', $response );

})->bind( 'queries_list' );

$app->get('/domain/{domain}/query/{query}', function( $domain, $query ) use ($app) {
	$query 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' )->findOneByQuery( $query );
    $use_case 	= new DomainFinder\UseCase\ShowRank( $app['orm.em']->getRepository( 'DomainFinder\Entity\Rank' ), $query );
	$response 	= $use_case->run( array( 'query' => $query ) );

	$domains 	= array();
	$dates 		= array();
	foreach ( $response['ranking'] as $rank ) {
		$domains[$query->getQuery()][] 				= $rank;
		$dates[$rank->getDate()->getTimestamp()][] 	= $rank;
	}

    return $app['twig']->render('chart.twig', array(
    	'query' 	=> $query->getQuery(),
    	'domains' 	=> $domains,
    	'dates'		=> $dates
    ));

});
*/
$app->get('/user', function() use ($app) {
	$params = array(
		'user' => $app['orm.em']->getRepository( 'DomainFinder\Entity\User' )->find( $app['session']->get( 'current_user_id' ) )
	);

    return $app['twig']->render( 'user.twig', $params );
});

$app->post('/user', function() use ($app) {
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'update_user', array( 'email' => $app['request']->request->get( 'email' ) ) );
	$use_case 			= $app['use_cases.user.update'];

	$response 			= $use_case->execute( $request );

    return $app->redirect( '/' );

})->bind('user');

$app->get('/', function() use ($app) {
	$request_factory 	= $app['requests.factory'];
	$request 			= $request_factory->get( 'application.list' );
	$use_case 			= $app['use_cases.application.list'];

	$response 			= $use_case->execute( $request );

    return $app['twig']->render( 'applications.twig', $response );

})->bind('index');

$app->get('/new', function() use ($app) {
	$params 			= array();
	$errors 			= $app['request']->getSession()->getFlashBag()->get('errors');

	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}

	return $app['twig']->render('new_application.twig', $params );

})->bind( 'new_application' );

$app->post('/new', function() use ($app) {
	try
	{
		$params = array(
			'name' => $app['request']->request->get( 'name' )
		);

		$request_factory 	= $app['requests.factory'];
		$request 			= $request_factory->get( 'application.save', $params );
		$use_case 			= $app['use_cases.application.save'];

		$response 			= $use_case->execute( $request );

		return $app->redirect( $app['url_generator']->generate('new_query', array( 'application_id' => $response['application']->getId() ) ) );
	}
	catch ( \Exception $e)
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The application \'' . $app['request']->request->get( 'query' ) . "' already exists. " . $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('new_application') );
	}
});

$app->error(function (\Exception $e, $code) use ($app) {
	if ( $e instanceof \DomainFinder\Exception\LoginRequiredException )
	{
		return $app->redirect( $app['url_generator']->generate('login') );
	}

    if ( $app['debug'] ) {
        return;
    }

    $page = 404 == $code ? '404.twig' : '500.twig';

    return new \Symfony\Component\HttpFoundation\Response($app['twig']->render($page, array('code' => $code)), $code);
});