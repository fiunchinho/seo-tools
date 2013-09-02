<?php

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../../web/templates',
    'debug' 	=> true,
));

$app['pdo'] = $app->share( function() use ($app){
	return new \PDO('sqlite:/var/www/google/log.db');
});

return $app;