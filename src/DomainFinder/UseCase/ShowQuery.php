<?php
namespace DomainFinder\UseCase;

class ShowQuery
{
	public function __construct( $query_repository )
	{
		$this->query_repo = $query_repository;
	}

	public function execute( $request = array() )
	{
		$query = $this->query_repo->findOneByQuery( $request['query'] );
		if ( $query->getApplication() !== $request['application'] )
		{
			throw new \InvalidArgumentException( 'You don\'t have permissions to do this' );
		}

		return array( 'query' => $query );
	}
}