<?php
$app['saved_queries'] = function() use ($app) {
	return $app['twig']->render('select_query.twig', array( 'saved_queries' => $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' )->findAll() ) );
};

$app->get('/application/{application_id}/new', function($application_id) use ($app) {
	try{
		$request_factory 	= new \DomainFinder\UseCase\RequestFactory( $app );
		$request = $request_factory->get( 'save_query' );
	} catch ( \InvalidArgumentException $e) {
		return $app->redirect( $app['url_generator']->generate('login') );
	}

	$params = array( 'application_id' => $application_id );
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}
	$domains = $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' )->findAll();
	foreach ($domains as $domain ) {
		$urls[] = $domain->getUrl();
	}
	$params['domains'] = array_unique( $urls );
	
	return $app['twig']->render('new.twig', $params );
})->bind( 'new_query' );

$app->post('/application/{application_id}/new', function($application_id) use ($app) {
	$request_factory = new \DomainFinder\UseCase\RequestFactory( $app );
	$params = array(
		'query' 		=> $app['request']->request->get( 'query' ),
		'domains' 		=> $app['request']->request->get( 'domains' ),
		'application'	=> $application_id
	);

	try
	{
		$request 	= $request_factory->get( 'save_query', $params );
		$use_case 	= $app['use_cases.save_query'];
		$response 	= $use_case->run( $request );
		return $app->redirect( $app['url_generator']->generate('queries', array( 'application_id' => $application_id ) ) );
	} catch ( \InvalidArgumentException $e) {
		var_dump( $e->getMessage() );die;
		return $app->redirect( $app['url_generator']->generate('login') );
	}
	catch ( \Exception $e)
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The query \'' . $app['request']->request->get( 'query' ) . "' already exists. " . $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('new_query') );
	}
});

$app->get('/edit/{query}', function($query) use ($app) {
	$errors 	= $app['request']->getSession()->getFlashBag()->get('errors');
	$use_case 	= new DomainFinder\UseCase\ShowQuery( $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' ) );
	$response 	= $use_case->run( array( 'query' => $query ) );
	
	$params 	= array_merge( $response, array( 'errors' => $errors ) );

	return $app['twig']->render('edit.twig', $params );
})->bind('edit_query');

$app->post('/application/{application_id}/edit', function( $application_id ) use ($app) {
	$request = array(
		'domains' 			=> $app['request']->request->get( 'domains' ),
		'query' 			=> $app['request']->request->get( 'query' ),
		'original_query' 	=> $app['request']->request->get( 'original_query' )
	);

	try
	{
		$use_case 	= new DomainFinder\UseCase\UpdateQuery( $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' ) );
		$response = $use_case->run( $request );
		return $app->redirect( $app['url_generator']->generate('queries', array( 'application_id' => $application_id ) ) );
	}
	catch ( \Exception $e)
	{
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The query \'' . $app['request']->request->get( 'query' ) . "' already exists. " . $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate( 'edit_query', array( 'query' => $app['request']->request->get( 'original_query' ) ) ) );
	}
})->bind('edit_query_post');

$app->get('/delete/{query}', function($query) use ($app) {
	$use_case 	= new DomainFinder\UseCase\ShowQuery( $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' ) );
	$response 	= $use_case->run( array( 'query' => $query ) );

	return $app['twig']->render('delete.twig', $response );
})->bind('delete_query');

$app->post('/application/{application_id}/delete', function() use ($app) {
	$use_case 	= new DomainFinder\UseCase\DeleteQuery( $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' ) );
	$response 	= $use_case->run( array( 'query' => $app['request']->request->get( 'query' ) ) );
	
	return $app->redirect( $app['url_generator']->generate('queries', array( 'application_id' => $application_id ) ) );
})->bind('delete_query_post');

$app->get('/application/{application_id}', function( $application_id ) use ($app) {
	try {
		$repo 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' );
		$repo 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Rank' );
		$repo 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' );
		$use_case 	= new DomainFinder\UseCase\ListDomains(
			$repo,
			$app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' ),
			$app['orm.em']->getRepository( 'DomainFinder\Entity\Rank' ),
			$app['request']->getSession()
		);
		$response 	= $use_case->run( $app['requests.query_list'] );
	} catch ( \InvalidArgumentException $e) {
		return $app->redirect( $app['url_generator']->generate('login') );
	}

	return $app['twig']->render( 'domains.twig', array_merge( array( 'application_id' => $application_id ), $response ) );

})->bind( 'queries' );

$app->get('/application/{application_id}/chart', function( $application_id ) use ($app) {
	$query 		= $app['request']->query->get( 'q' );
	$query 		= $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' )->findOneByQuery( $query );
    $use_case 	= new DomainFinder\UseCase\ShowRank(
    	$app['orm.em']->getRepository( 'DomainFinder\Entity\Rank' ),
    	$app['orm.em']->getRepository( 'DomainFinder\Entity\Query' ),
    	$app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' )
    );
	$response 	= $use_case->run( array( 'query' => $query ) );

	$domains 	= array();
	$dates 		= array();
	foreach ( $response['ranking'] as $domain ) {
		foreach ($domain as $rank ) {
			$dates[$rank->getDate()->getTimestamp()][] 	= $rank;
		}
	}

    return $app['twig']->render('chart.twig', array(
    	'application_id' => $application_id,
    	'query' 	=> $query->getQuery(),
    	'domains' 	=> $response['ranking'],
    	'dates'		=> $dates
    ));
})->bind('chart');

$app->get('/register', function() use ($app) {
	return $app['twig']->render('register.twig');
})->bind('register');

$app->post('/register', function() use ($app) {
	//$use_case 	= new DomainFinder\UseCase\RegisterUser( $app['orm.em']->getRepository( 'DomainFinder\Entity\User' ) );
	$user_repo 	= new \DomainFinder\Entity\UserArrayRepository();
	$user_repo 	= $app['repositories.user'];
	$use_case 	= new DomainFinder\UseCase\RegisterUser( $user_repo );
	$params 	= array(
		'email' 	=> $app['request']->request->get( 'email' ),
		'password' 	=> $app['request']->request->get( 'password' )
	);
	$response 	= $use_case->execute( $params );

	return $app->redirect( $app['url_generator']->generate('index') );
});

$app->get('/login', function() use ($app) {
	$params = array();
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}
	
	return $app['twig']->render('login.twig', $params);
})->bind('login');

$app->post('/login', function() use ($app) {
	try {
		//$use_case 	= new DomainFinder\UseCase\RegisterUser( $app['orm.em']->getRepository( 'DomainFinder\Entity\User' ) );
		$user_repo 	= $app['repositories.user_array'];
		$user_repo 	= $app['repositories.user'];
		$use_case 	= new DomainFinder\UseCase\Login( $user_repo, $app['session'] );
		$request 	= array(
			'email' 	=> $app['request']->request->get( 'email' ),
			'password' 	=> $app['request']->request->get( 'password' )
		);
		$response 	= $use_case->execute( $request );

		//$app['session']->set( 'current_user', $response['user'] );
	} catch ( \Exception $e ) {
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('login') );
	}

	return $app->redirect( $app['url_generator']->generate('index') );
});

$app->get('/logout', function() use ($app) {
	$use_case 	= new DomainFinder\UseCase\Logout( $app['session'] );
	$use_case->execute();
	
	return $app->redirect( $app['url_generator']->generate('login') );
})->bind('logout');

$app->get('/install', function() use ($app) {
	$params = array();
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}

	return $app['twig']->render('install.twig', $params);
})->bind('install');

$app->post('/install', function() use ($app) {
	try {
		$user_repo 	= $app['repositories.user_array'];
		$user_repo 	= $app['orm.em']->getRepository( '\DomainFinder\Entity\User' );
		$use_case 	= new DomainFinder\UseCase\Install( $user_repo, $app['session'], $app['orm.em']->getConnection() );
		$request 	= array(
			'email' 	=> $app['request']->request->get( 'email' ),
			'password' 	=> $app['request']->request->get( 'password' )
		);
		$response 	= $use_case->execute( $request );
	} catch ( \Exception $e ) {
		$app['request']->getSession()->getFlashBag()->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('install') );
	}

	var_dump( $response, $app['session']->get('current_user'), $user_repo);die;

	return $app->redirect( $app['url_generator']->generate('index') );
});

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
	// var_dump($response);die;

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

$app->get('/user', function() use ($app) {
	$current_user_id = $app['session']->get( 'current_user_id' );
	$user = $app['orm.em']->getRepository( 'DomainFinder\Entity\User' )->find( $current_user_id );

    return $app['twig']->render( 'user.twig', array( 'user' => $user ) );

});

$app->post('/user', function() use ($app) {
	$request_factory = new \DomainFinder\UseCase\RequestFactory( $app );
	$request 	= $request_factory->get( 'update_user', array( 'email' => $app['request']->request->get( 'email' ) ) );
	$use_case 	= $app['use_cases.update_user'];
	$response 	= $use_case->execute( $request );

    return $app->redirect( '/' );

})->bind('user');

$app->get('/', function() use ($app) {
	$current_user_id = $app['session']->get( 'current_user_id' );
	$applications = $app['orm.em']->getRepository( 'DomainFinder\Entity\Application' )->findAll();

    return $app['twig']->render( 'applications.twig', array( 'applications' => $applications ) );

})->bind('index');

$app->error(function (\Exception $e, $code) use ($app) {
    if ( $app['debug'] ) {
        return;
    }


    $page = 404 == $code ? '404.twig' : '500.twig';

    return new \Symfony\Component\HttpFoundation\Response($app['twig']->render($page, array('code' => $code)), $code);
});