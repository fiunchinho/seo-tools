<?php
namespace DomainFinder\UseCase;

class ListDomains
{
	public function __construct( $repo, $domain_repository, $rank_repository, $session )
	{
		$this->repo 		= $repo;
		$this->domain_repo 	= $domain_repository;
		$this->rank_repo 	= $rank_repository;
		$this->session 		= $session;
	}

	public function execute( $request )
	{
		$queries = $this->repo->findAll();
		$domains 	= array();
		$dates 		= array();

		foreach ( $queries as $query ) {
			$main_domain = $this->domain_repo->findOneBy( array( 'query' => $query, 'competitor' => 0 ) );
			// var_dump( $main_domain );
			// var_dump( $main_domain->getPositions() );
			// die;
			$positions = $this->rank_repo->findByDomain( $main_domain );
			foreach ( $positions as $position ) {
				$id = $query->getQuery() . '(' . $main_domain->getUrl() . ')';
				$domains[$id][] = $position;
				$dates[$position->getDate()->getTimestamp()][] = $position;
			}
		}

	    return array( 'queries' => $queries, 'domains' => $domains, 'dates' => $dates, 'query' => $query->getQuery() );


		// var_dump( $positions[0]->getQuery() );die;
		// return array( 'domains' => $this->repo->findAll() );
	}
}