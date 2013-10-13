<?php
namespace DomainFinder\UseCase;

class ListApplications
{
	public function __construct( $application_repo )
	{
		$this->application_repo	= $application_repo;
	}

	public function execute( $request )
	{
		$applications = $this->application_repo->findBy( array( 'user' => $request['current_user'] ) );

		return array( 'applications' => $applications );
	}
}