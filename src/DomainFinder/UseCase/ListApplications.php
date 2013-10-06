<?php
namespace DomainFinder\UseCase;

class ListApplications
{
	public function __construct( $application_repo, $session )
	{
		$this->repo 	= $application_repo;
		$this->session 	= $session;
	}

	public function run( $request )
	{
		$current_user = $this->session->get( 'current_user' );
		$applications = $this->repo->findBy( array( 'user' => $current_user ) );
		var_dump($applications[0]->getDomains()->toArray());die;
		return array( 'applications' => $applications );
	}
}