<?php
namespace DomainFinder\UseCase;

class ListQueries
{
	public function __construct( $repo, $domain_repository, $rank_repository )
	{
		$this->query_repo 	= $repo;
		$this->domain_repo 	= $domain_repository;
		$this->rank_repo 	= $rank_repository;
	}

	public function execute( ListQueriesRequest $request )
	{
		$queries 	= $this->query_repo->findByapplication( $request['application'] );
		$graph 		= new \DomainFinder\RankingGraph( $queries, $this->domain_repo, $this->rank_repo );

	    return array( 'queries' => $queries, 'graph' => $graph );
	}
}