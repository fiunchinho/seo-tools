<?php
namespace DomainFinder\UseCase;

use DomainFinder\Entity\Application;
use DomainFinder\Exceptions\ApplicationAlreadyExists;

class SaveApplication
{
	public function __construct( $application_repository, $user_repository )
	{
		$this->application_repository 	= $application_repository;
		$this->user_repository 			= $user_repository;
	}

	public function execute( $request = array() )
	{
		$application_with_same_name = $this->application_repository->findByName( $request['name'] );
		if ( $application_with_same_name )
		{
			throw new ApplicationAlreadyExists( 'An application with the same name already exists' );
		}

		$user 			= $request['current_user'];
		$application 	= new Application( $request['name'], $user );
		$this->application_repository->add( $application );

		$user->addApplication( $application );
		$this->user_repository->add( $user );

		return array( 'application' => $application );
	}
}