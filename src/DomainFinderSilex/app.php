<?php
$app = new Silex\Application();
$app['debug'] = isset( $config['debug'] )? $config['debug'] : true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider(), array(
    'session.test' => isset( $config['session.test'] )? $config['session.test'] : true
));
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
            // array(
            //     "type" 		=> 'annotation',
            //     "namespace" => 'DomainFinder\Entity',
            //     "path" 		=> __DIR__ . '/../DomainFinder/Entity',
            // ),
            array(
                "type"      => 'yml',
                "namespace" => 'DomainFinder\Entity',
                "path"      => __DIR__ . '/../mapping',
            )
        )
    )
));

$app['repositories.user_array'] = $app->share(function() use ($app){
    $users = array(
        new \DomainFinder\Entity\User( 'existing@email.com', 'correct_password' ),
        new \DomainFinder\Entity\User( 'jack@email.com', 'correct_password' )
    );
    return new \DomainFinder\Entity\UserArrayRepository( $users );
});

$app['repositories.user'] = $app->share(function() use ($app){
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\User' );
});

// Requests
$app['requests.base'] = $app->share(function() use ($app){
    return new DomainFinder\UseCase\BaseRequest();
});

$app['requests.login_required'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\LoginRequiredRequest( $app['requests.base'], $app['session'], $app['orm.em']->getRepository( 'DomainFinder\Entity\User' ) );
});

$app['requests.query_list'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\QueryListRequest( $app['requests.login_required'] );
});

// Use Cases
$app['use_cases.save_query'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\SaveQuery(
        $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' ),
        $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' )
    );
});
$app['use_cases.update_user'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\UpdateUser( $app['orm.em']->getRepository( 'DomainFinder\Entity\User' ) );
});

return $app;