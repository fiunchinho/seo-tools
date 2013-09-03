<?php

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../../web/templates',
    'debug' 	=> true,
));

$app->register(new \Silex\Provider\DoctrineServiceProvider, array(
    "db.options" => array(
        "driver" => "pdo_sqlite",
        "path" => "/var/www/google/log.db",
    ),
));

$app->register(new \Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => "/var/www/google/src/DomainFinder/Proxy",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" 		=> 'annotation',
                "namespace" => 'DomainFinder\Entity',
                "path" 		=> __DIR__ . '/../DomainFinder/Entity',
            )
        )
    )
));

$app['pdo'] = $app->share( function() use ($app){
	return new \PDO('sqlite:/var/www/google/log.db');
});

return $app;