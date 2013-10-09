<?php
namespace DomainFinder\UseCase;

class DeleteQuery
{
	public function __construct( $query_repository )
	{
		$this->repo = $query_repository;
	}

	public function execute( $request = array() )
	{
        $this->repo->remove( $this->repo->findOneByQuery( $request['query'] ) );
		return array();
	}
}