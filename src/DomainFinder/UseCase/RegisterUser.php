<?php
namespace DomainFinder\UseCase;

use \DomainFinder\Entity\User;
use \DomainFinder\Exception\AlreadyRegisteredException;

class RegisterUser
{
	public function __construct( $user_repository, $encoder, $login_use_case )
	{
		$this->user_repo 		= $user_repository;
		$this->password_encoder = $encoder;
		$this->login_use_case 	= $login_use_case;
	}

	public function execute( $request = array() )
	{
		if ( empty( $request['name'] ) || empty( $request['email'] ) || empty( $request['password'] ) || empty( $request['terms'] ) ){
			throw new \InvalidArgumentException( 'Mandatory field is missing' );
		}

		if ( $this->user_repo->findOneByEmail( $request['email'] ) )
		{
			throw new AlreadyRegisteredException( 'User already exists' );
		}

		$user = new User( $request['email'], $this->password_encoder->hash( $request['password'] ) );
        $this->user_repo->add( $user );

        $this->login_use_case->execute( array( 'email' => $user->getEmail(), 'password' => $request['password'] ) );

		return array( 'user' => $user );
	}
}