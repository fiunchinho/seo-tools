<?php
namespace DomainFinder\UseCase;

class LoginRequiredRequest extends BaseRequest
{
	private $request;

	public function __construct( BaseRequest $request, $session, $user_repository )
	{
		$this->repo 		= $user_repository;
		$this->request 		= $request;
		$current_user_id 	= $session->get( 'current_user_id' );
		if ( empty( $current_user_id ) )
		{
			throw new \InvalidArgumentException( 'You must be logged in' );
		}

		$this->request['current_user'] = $this->repo->find( $current_user_id );
		parent::__construct( $request );
	}
}