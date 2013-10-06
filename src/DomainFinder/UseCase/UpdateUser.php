<?php
namespace DomainFinder\UseCase;

use DomainFinder\Entity\User;

class UpdateUser
{
	public function __construct( $user_repository )
	{
		$this->repo = $user_repository;
	}

	public function execute( $request = array() )
	{
		$request['current_user']->setEmail( $request['email'] );
		$this->repo->add( $request['current_user'] );

		return array( 'user' => $request['current_user'] );
	}
}