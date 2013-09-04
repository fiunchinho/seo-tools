<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use DomainFinder\DomainFinder;

//$console = new Application('My Silex Application', 'n/a');


//$dm             = new DocumentManagerHelper( $app['doctrine.odm.mongodb.dm'] );
//$softonic       = new SoftonicApiHelper( $app['softonic'] );
//$console->getHelperSet()->set( $dm, 'dm' );
//$console->getHelperSet()->set( $softonic, 'softonic' );

//$app->register(new Silex\Provider\SwiftmailerServiceProvider());

// Add Doctrine ODM commands
/*
$console->addCommands(array(
    new Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateDocumentsCommand(),
    new Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateHydratorsCommand(),
    new Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateProxiesCommand(),
    new Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateRepositoriesCommand(),
    new Doctrine\ODM\MongoDB\Tools\Console\Command\QueryCommand(),
    new Doctrine\ODM\MongoDB\Tools\Console\Command\ClearCache\MetadataCommand(),
    new DomainFinder\Command\FindDomainOnDemand(),
    new DomainFinder\Command\FindSavedDomains(),
));
*/


$console = new Application('SEO Tools', '1.0');

$console
    ->register('find:interactive')
    ->setDefinition(array(
        new InputOption( 'log', null, InputOption::VALUE_NONE, 'Whether or not you want to log the results so you can use them later', null),
        new InputOption( 'max_page_to_look', null, InputOption::VALUE_REQUIRED, 'To avoid being blocked by google, this tool will stop when reaching this page number', 10),
        new InputArgument( 'query', InputArgument::REQUIRED, 'Keywords to search in Google. If you need more than one keyword, you can use quotes like "buy kindle"'),
        new InputArgument( 'domains', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Domains, separated by spaces, to find in the search results. Better without the www'),
    ))
    ->setDescription('Find the position of your domain in google search results')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($console,$container){
        $time       = microtime(true);
        $pdo        = $container['pdo'];
        $domains    = $input->getArgument('domains');

        $event_dispatcher   = \Symfony\Component\EventDispatcher\EventDispatcher();
        $event_dispatcher->addSubscriber( new \DomainFinder\Event\OutputListener( $output ) );
        if ( $input->getOption('log') ){
            $rank_repository = $container['orm.em']->getRepository( 'DomainFinder\Entity\Rank' );
            $event_dispatcher->addSubscriber( new \DomainFinder\Event\DatabaseListener( $rank_repository ) );
        }

        $domain_finder = new DomainFinder( $event_dispatcher );

        foreach ( $domains as $domain ){
            $table  = new \Symfony\Component\Console\Helper\TableHelper();
            $table->setHeaders( array( '#', 'Url', 'Is Yours?' ) );

            
            $domain_finder->find( $domain, $input->getArgument('query'), $input->getOption('max_page_to_look') );

            $counter = 0;
            foreach ( $domain_finder->getGoogleResults() as $result ) {
                $table->addRow( array( ++$counter, $result->getHost(), ( $result->is($domain)?'[X]':'[]' ) ) );
            }

            $table->render( $output );
        }
        
        $output->writeln( "<info>Elapsed time: " . round( microtime(true) - $time, 3 ) . "</info>");
    })
;

$console
    ->register('find:daemon')
    ->setDefinition(array(
        new InputOption( 'max_page_to_look', null, InputOption::VALUE_REQUIRED, 'To avoid being blocked by google, this tool will stop when reaching this page number', 10),
    ))
    ->setDescription( 'Take the queries saved in the backend and try to find the selected domains for those google queries. It saves the results so you can see the results in the backend.' )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($console,$container){
        $time               = microtime(true);
        $rank_repository    = $container['orm.em']->getRepository( 'DomainFinder\Entity\Rank' );
        $event_dispatcher   = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $event_dispatcher->addSubscriber( new \DomainFinder\Event\DatabaseListener( $rank_repository ) );
        $event_dispatcher->addSubscriber( new \DomainFinder\Event\OutputListener( $output ) );
        $queries            = $container['orm.em']->getRepository( 'DomainFinder\Entity\Query' )->findAll();

        foreach ( $queries as $query ){
            //$domains = explode( ' ', $query['domain'] );
            $domains = $query->getDomains();
            foreach ( $domains as $domain ){
                $domain_finder = new DomainFinder( $event_dispatcher );
                $domain_finder->find( $domain, $query->getQuery(), $input->getOption('max_page_to_look') );
            }
        }
        
        $output->writeln( "<info>Elapsed time: " . round( microtime(true) - $time, 3 ) . "</info>");
    })
;

return $console;