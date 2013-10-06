<?php
namespace DomainFinder\UseCase;

class Install
{
	public function __construct( $user_repository, $session, $db )
	{
		$this->repo 	= $user_repository;
		$this->session 	= $session;
		$this->db 		= $db;
	}

	public function execute( $request = array() )
	{
		$sql = file_get_contents( __DIR__ . '/../../../install.sql' );
		$this->db->executeQuery( $sql );

		$register_request 	= array(
			'email' 	=> $request['email'],
			'password' 	=> $request['password']
		);
		$register_use_case 	= new RegisterUser( $this->repo );
		$register_response 	= $register_use_case->execute( $register_request );

		$login_use_case 	= new Login( $this->repo, $this->session );
		$response 			= $login_use_case->execute( $register_request );

		return array( 'user' => $response['user'], 'register' => $register_response );
	}
}