<?php
namespace DomainFinder\UseCase;

class UpdateQuery
{
	public function __construct( $query_repository )
	{
		$this->repo = $query_repository;
	}

	public function run( $request = array() )
	{
		$query = $this->repo->findOneByQuery( $request['original_query'] );
		$query->setQuery( $request['query'] );
		$query->setDomain( $request['domains'] );
		$this->repo->add( $query );

		return array( 'query' => $query );
	}
}