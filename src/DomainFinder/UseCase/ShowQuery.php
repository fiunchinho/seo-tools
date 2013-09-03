<?php
namespace DomainFinder\UseCase;

class ShowQuery
{
	public function __construct( $query_repository )
	{
		$this->repo = $query_repository;
	}

	public function run( $request = array() )
	{
		return array( 'query' => $this->repo->findOneByQuery( $request['query'] ) );
	}
}