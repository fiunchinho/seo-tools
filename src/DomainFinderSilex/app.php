<?php
$app = new Silex\Application();
$app['debug'] = isset( $config['debug'] )? $config['debug'] : true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../../web/templates',
    'debug' 	=> $app['debug'],
));

$app->register(new \Silex\Provider\DoctrineServiceProvider, array(
    "db.options"    => $config['db']
));

$app->register(new \Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => __DIR__ . "/../DomainFinder/Proxy",
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

return $app;