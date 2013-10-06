<?php

require_once __DIR__ . '/../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class ApplicationHelper
{
    private $user_repo;

    public function __construct()
    {
        $applications = array(
            // new \DomainFinder\Entity\User( 'existing@email.com', 'correct_password' ),
            // new \DomainFinder\Entity\User( 'jack@email.com', 'correct_password' )
        );

        $this->repo       = new \DomainFinder\Entity\ApplicationArrayRepository( array() );
    }

    public function create( $application )
    {
        $use_case = new DomainFinder\UseCase\CreateApplication( $this->repo );

        try {
            $this->response = $use_case->execute( array( 'application' => $application ) );
        } catch ( \Exception $e ) {
            $this->response = $e;
        }

        return $this->response;
    }

    public function assertApplicationIsRegistered($application)
    {
        assertNotNull( $this->repo->findOneByName( $application ) );
    }
}