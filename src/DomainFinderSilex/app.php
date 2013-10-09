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

// Repositories
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

$app['repositories.query'] = $app->share(function() use ($app){
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' );
});

$app['repositories.domain'] = $app->share(function() use ($app){
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' );
});

$app['repositories.application'] = $app->share(function() use ($app){
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Application' );
});

$app['repositories.rank'] = $app->share(function() use ($app){
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Rank' );
});

// Requests
$app['requests.factory'] = $app->share(function() use ($app){
    return new DomainFinder\UseCase\RequestFactory( $app );
});

$app['requests.base'] = $app->share(function() use ($app){
    return new DomainFinder\UseCase\BaseRequest();
});

$app['requests.login_required'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\LoginRequiredRequest( $app['requests.base'], $app['session'], $app['repositories.user'] );
});

$app['requests.query_list'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\QueryListRequest( $app['requests.login_required'] );
});

// Use Cases
$app['use_cases.query.create'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\SaveQuery(
        $app['repositories.query'],
        $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' )
    );
});
$app['use_cases.user.register'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\RegisterUser( $app['repositories.user'] );
});
$app['use_cases.user.update'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\UpdateUser( $app['repositories.user'] );
});
$app['use_cases.user.login'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\Login( $app['repositories.user'], $app['session'] );
});
$app['use_cases.user.logout'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\Logout( $app['session'] );
});
$app['use_cases.query.show'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\ShowQuery( $app['repositories.query'] );
});
$app['use_cases.query.update'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\UpdateQuery( $app['repositories.query'], $app['repositories.domain'] );
});
$app['use_cases.query.delete'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\DeleteQuery( $app['repositories.query'] );
});
$app['use_cases.query.list'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\ListDomains(
        $app['repositories.query'],
        $app['repositories.domain'],
        $app['repositories.rank'],
        $app['request']->getSession()
    );
});
$app['use_cases.rank.show'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\ShowRank(
        $app['repositories.rank'],
        $app['repositories.query'],
        $app['repositories.domain']
    );
});
$app['use_cases.install'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\Install( $app['repositories.user'], $app['session'], $app['orm.em']->getConnection() );
});




return $app;