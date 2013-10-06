<?php
namespace DomainFinder\UseCase;

class Login
{
	public function __construct( $user_repository, $session )
	{
		$this->repo 	= $user_repository;
		$this->session 	= $session;
	}

	public function execute( $request = array() )
	{
		$user = $this->repo->findOneByEmail( $request['email'] );

        if ( !$user ) {
            throw new \DomainFinder\Exception\UserNotFoundException( 'No user found for the email: ' . $request['email'] );
        }

        if ( $user->getPassword() != sha1( $request['password'] ) ) {
            throw new \DomainFinder\Exception\IncorrectPasswordException( 'Incorrect password for email: ' . $request['email'] );
        }

        $this->session->set( 'current_user_id', $user->getId() );

		return array( 'user' => $user );
	}
}