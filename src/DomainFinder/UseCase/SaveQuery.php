<?php
namespace DomainFinder\UseCase;

class SaveQuery
{
	public function __construct( $query_repository )
	{
		$this->repo = $query_repository;
	}

	public function run( $request = array() )
	{
		$query = new \DomainFinder\Entity\Query( $request['query'], $request['domains'] );
        $this->repo->add( $query );

		return array();
	}
}