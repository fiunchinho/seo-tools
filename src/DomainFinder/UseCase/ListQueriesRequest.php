<?php
namespace DomainFinder\UseCase;

class ListQueriesRequest extends BaseRequest
{
	private $request;

	public function __construct( BaseRequest $request, $application_repository )
	{
		$request['application'] = $application_repository->find( $request['application_id'] );

		if ( $request['application']->getUser() !== $request['current_user'] )
		{
			throw new \UnexpectedValueException( 'You don\'t have permissions to do this' );
		}

		parent::__construct( $request );
	}
}