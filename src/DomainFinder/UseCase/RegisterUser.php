<?php
namespace DomainFinder\UseCase;

class RegisterUser
{
	public function __construct( $user_repository )
	{
		$this->repo = $user_repository;
	}

	public function execute( $request = array() )
	{
		$user = new \DomainFinder\Entity\User( $request['email'], $request['password'] );
        $this->repo->add( $user );

		return array( 'user' => $user );
	}
}