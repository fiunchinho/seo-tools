<?php
$app->get('/new', function() use ($app) {
	$params = array();
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');
	if ( !empty( $errors ) )
	{
		$params['errors'] = $errors;
	}
	
	return $app['twig']->render('new.twig', $params);
})->bind( 'new_query' );

$app->post('/new', function() use ($app) {
	$query 		= $app['request']->request->get( 'query' );

	$sql 		= <<<QUERY
INSERT INTO	`queries`
	( `domain`, `query` )
VALUES
	( :domain, :query )
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$insert 	= $statement->execute(
		array(
			':domain' 	=> $app['request']->request->get( 'domains' ),
			':query' 	=> $query
		)
	);
	if ( $insert )
	{
		return $app->redirect( $app['url_generator']->generate('queries') );
	}

	$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The query \'' . $query . '\' already exists.' ) );

	return $app->redirect( $app['url_generator']->generate('new_query') );
	
	
});

$app->get('/edit/{query}', function($query) use ($app) {
	$errors = $app['request']->getSession()->getFlashBag()->get('errors');

	$sql 		= <<<QUERY
SELECT
	*
FROM
	`queries`
WHERE
	query = :query
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$statement->execute( array( ':query' => $query ) );

	$saved_queries 	= $statement->fetchAll( \PDO::FETCH_ASSOC );
	$query = $saved_queries[0]['query'];
	foreach ( $saved_queries as $saved_query ) {
		$domains[] = $saved_query['domain'];
	}

	return $app['twig']->render('edit.twig', array( 'query' => array( 'query' => $query, 'domains' => $domains ), 'errors' => $errors ) );
})->bind('edit_query');

$app->post('/edit', function() use ($app) {
	$sql 		= <<<QUERY
UPDATE 	`queries` SET
	domain = :domain,
	query = :query
WHERE
	query = :original_query
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$insert 	= $statement->execute(
		array(
			':domain' 			=> $app['request']->request->get( 'domains' ),
			':query' 			=> $app['request']->request->get( 'query' ),
			':original_query' 	=> $app['request']->request->get( 'original_query' )
		)
	);

	if ( $insert )
	{
		return $app->redirect( $app['url_generator']->generate('queries') );
	}

	$app['request']->getSession()->getFlashBag()->set( 'errors', array( 'The query \'' . $query . '\' already exists.' ) );
	
	return $app->redirect( $app['url_generator']->generate( 'edit_query', array( 'query' => $app['request']->request->get( 'original_query' ) ) ) );
})->bind('edit_query_post');

$app->get('/delete/{query}', function($query) use ($app) {
	$sql 		= <<<QUERY
SELECT
	*
FROM
	`queries`
WHERE
	query = :query
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$statement->execute( array( ':query' => $query ) );

	$saved_queries 	= $statement->fetchAll( \PDO::FETCH_ASSOC );
	$query = $saved_queries[0]['query'];
	foreach ( $saved_queries as $saved_query ) {
		$domains[] = $saved_query['domain'];
	}

	return $app['twig']->render('delete.twig', array( 'query' => array( 'query' => $query, 'domains' => $domains ) ) );
})->bind('delete_query');

$app->post('/delete', function() use ($app) {
	$query 		= $app['request']->request->get( 'query' );

	$sql 		= <<<QUERY
DELETE FROM
	`queries`
WHERE
	query = :query
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$statement->execute( array( ':query' => $query ) );
	
	return $app->redirect( $app['url_generator']->generate('queries') );
})->bind('delete_query_post');

$app->get('/', function() use ($app) {
	$sql 		= <<<QUERY
SELECT
	*
FROM
	`queries`
ORDER BY
	id
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$statement->execute();
	$saved_queries 	= $statement->fetchAll( \PDO::FETCH_ASSOC );
	foreach ( $saved_queries as $query ) {
		$queries[$query['query']] 	= explode( ' ', $query['domain'] );
		//$queries[$query['query']][] = $query['domain'];
	}

	return $app['twig']->render('queries.twig', array(
        'queries' => $queries
    ));
})->bind( 'queries' );

$app->get('/chart', function() use ($app) {
	$path 		= $app['request']->getBasepath();
	$query 		= $app['request']->query->get( 'q' );
    $domains 	= array();
	$statement 	= $app['pdo']->query( "SELECT * FROM logs WHERE query = '". $query ."' ORDER BY date, domain" );
	foreach ( $statement->fetchAll( \PDO::FETCH_ASSOC ) as $log ) {
	  $domains[] = $log['domain'];
	  $points[$log['date']][] = array(
	    'domain'    => $log['domain'],
	    'position'  => $log['position']
	  );
	}
	if ( empty( $points ) )
	{
		return $app['twig']->render('no_chart.twig');
	}

	$domains  = array_unique( $domains );
	$domains  = array_values ( $domains );
	$rows     = array();
	$rows[]   = array_merge( array( 'date' ), $domains );
	foreach ( $points as $date => $logs ) {
	  $row = array( date( 'd/m/Y', strtotime( $date ) ) );
	  foreach ( $logs as $log ) {
	    $column_for_chart = array_search( $log['domain'], $domains );
	    $row[$column_for_chart+1] = (int)$log['position'];
	  }

	  for($i = 0; $i < count($row); $i++)
	  {
	      if(!isset($row[$i]))
	      {
	          $row[$i] = null;
	      }
	  }


	  $keys = array_keys($row);
	  natsort($keys);

	  foreach ($keys as $k)
	    $row2[$k] = $row[$k];


	  if ( ( count( $row2 ) - 1 ) < count( $domains ) ){
	    $row2 = array_merge( $row2, array( null ) );
	  }
	  $rows[] = $row2;
	}

    return $app['twig']->render('chart.twig', array(
        'query' => $query,
        'chart' => json_encode( $rows )
    ));
})->bind('chart');

$app['saved_queries'] = function() use ($app) {
	$sql 		= <<<QUERY
SELECT
	DISTINCT(query)
FROM
	`queries`
QUERY;
	$statement 	= $app['pdo']->prepare( $sql );
	$statement->execute();
	$saved_queries = $statement->fetchAll( \PDO::FETCH_COLUMN );

	return $app['twig']->render('select_query.twig', array( 'saved_queries' => $saved_queries ) );
};