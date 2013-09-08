<?php
namespace DomainFinder\UseCase;

class ShowRank
{
	public function __construct( $rank_repository )
	{
		$this->repo = $rank_repository;
	}

	public function run( $request = array() )
	{
		$ranking = $this->repo->findBy( array( 'query' => $request['query'] ), array( 'date' => 'asc', 'domain' => 'asc' ) );
		return array( 'ranking' => $ranking );
	}
}