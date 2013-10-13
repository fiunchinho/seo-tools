<?php
namespace DomainFinder\UseCase;

use \DomainFinder\Exception\IncorrectPasswordException;
use \DomainFinder\Exception\UserNotFoundException;

class Login
{
	public function __construct( $user_repository, $encoder, $session )
	{
		$this->repo 			= $user_repository;
		$this->password_encoder = $encoder;
		$this->session 			= $session;
	}

	public function execute( $request = array() )
	{
		$user = $this->repo->findOneByEmail( $request['email'] );

        if ( !$user ) {
            throw new UserNotFoundException( 'No user found for the email: ' . $request['email'] );
        }

        if ( !$this->password_encoder->verify( $request['password'], $user->getPassword() ) ) {
            throw new IncorrectPasswordException( 'Incorrect password for email: ' . $request['email'] );
        }

        $this->session->set( 'current_user_id', $user->getId() );

		return array( 'user' => $user );
	}
}