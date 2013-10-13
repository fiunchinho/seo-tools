<?php

require_once __DIR__ . '/../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class DomainHelper
{
    private $user_repo;
    private $response;
    private $email;

    public function __construct()
    {
        $domains = array(
            // new \DomainFinder\Entity\User( 'existing@email.com', 'correct_password' ),
            // new \DomainFinder\Entity\User( 'jack@email.com', 'correct_password' )
        );

        $this->repo       = new \DomainFinder\Infrastructure\DomainArrayRepository( array() );
        // $config             = require __DIR__ . '/../../config/config.php';
        // $this->container    = require __DIR__ . '/../../src/DomainFinderSilex/app.php';        
        // $this->session      = $this->container['session'];
        // $this->db           = $this->container['orm.em']->getConnection();
    }

    public function register( $domain )
    {
        $use_case = new DomainFinder\UseCase\RegisterDomain( $this->repo );

        try {
            $this->response = $use_case->execute( array( 'domain' => $domain ) );
        } catch ( \Exception $e ) {
            $this->response = $e;
        }

        return $this->response;
    }

    public function assertDomainIsRegistered($domain)
    {
        assertNotNull( $this->repo->findOneByDomain( $domain ) );
    }
}