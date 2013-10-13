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
            array(
                "type"      => 'yml',
                "namespace" => 'DomainFinder\Entity',
                "path"      => __DIR__ . '/../DomainFinder/Infrastructure/mapping',
            )
        )
    )
));

$app['twig']->addExtension(new \Twig_Extensions_Extension_Text); 

$app['password_encoder'] = $app->share(function() use ($app){
    return new \DomainFinder\PasswordEncoder();
});

// Repositories
$app['repositories.user'] = $app->share(function() use ($app){
    if ( $app['acceptance_testing'] )
    {
        $encoder    = $app['password_encoder'];
        $user_repo  = new \DomainFinder\Infrastructure\UserArrayRepository();
        $user_repo->add( new \DomainFinder\Entity\User( 'existing@email.com', $encoder->hash( 'correct_password' ) ) );
        $user_repo->add( new \DomainFinder\Entity\User( 'jack@email.com', $encoder->hash( 'correct_password' ) ) );

        return $user_repo;
    }
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\User' );
});

$app['repositories.query'] = $app->share(function() use ($app){
    if ( $app['acceptance_testing'] )
    {
        $queries = array(
            new \DomainFinder\Entity\Query( 'asdasd' ),
            new \DomainFinder\Entity\Query( 'qweqre' )
        );
        return new \DomainFinder\Infrastructure\QueryArrayRepository( $queries );
    }
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Query' );
});

$app['repositories.domain'] = $app->share(function() use ($app){
    if ( $app['acceptance_testing'] )
    {
        $domains = array(
            new \DomainFinder\Entity\Domain( 'google.es' ),
            new \DomainFinder\Entity\Domain( 'yahoo.com' )
        );
        return new \DomainFinder\Infrastructure\DomainArrayRepository( $domains );
    }
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Domain' );
});

$app['repositories.application'] = $app->share(function() use ($app){
    if ( $app['acceptance_testing'] )
    {
        $applications = array(
            new \DomainFinder\Entity\Application( 'google.es' ),
            new \DomainFinder\Entity\Application( 'yahoo.com' )
        );
        return new \DomainFinder\Infrastructure\ApplicationArrayRepository( $applications );
    }
    return $app['orm.em']->getRepository( 'DomainFinder\Entity\Application' );
});

$app['repositories.rank'] = $app->share(function() use ($app){
    if ( $app['acceptance_testing'] )
    {
        $rankings = array(
            new \DomainFinder\Entity\Rank( 'google.es' ),
            new \DomainFinder\Entity\Rank( 'yahoo.com' )
        );
        return new \DomainFinder\Infrastructure\RankArrayRepository( $rankings );
    }
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
    return new DomainFinder\UseCase\RegisterUser( $app['repositories.user'], $app['password_encoder'], $app['use_cases.user.login'] );
});
$app['use_cases.user.update'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\UpdateUser( $app['repositories.user'] );
});
$app['use_cases.user.login'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\Login( $app['repositories.user'], $app['password_encoder'], $app['session'] );
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
    return new DomainFinder\UseCase\ListQueries(
        $app['repositories.query'],
        $app['repositories.domain'],
        $app['repositories.rank']
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
$app['use_cases.application.list'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\ListApplications( $app['repositories.application'] );
});
$app['use_cases.application.save'] = $app->share(function() use ($app) {
    return new DomainFinder\UseCase\SaveApplication(
        $app['repositories.application'],
        $app['repositories.user'],
        $app['session']
    );
});



return $app;