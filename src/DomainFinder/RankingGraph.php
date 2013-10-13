<?php
namespace DomainFinder;

class RankingGraph
{
	public function __construct( $queries, $domain_repository, $rank_repository )
	{
		$this->queries 		= $queries;
		$this->domain_repo 	= $domain_repository;
		$this->rank_repo 	= $rank_repository;
		$this->init();
	}

	public function getQueries()
	{
		return $this->queries;
	}

	public function getDomains()
	{
		return $this->domains;
	}

	public function getDates()
	{
		return $this->dates;
	}

	private function init()
	{
		$domains 	= array();
		$dates 		= array();
		foreach ( $this->queries as $query ) {
			$main_domain 	= $this->domain_repo->findOneBy( array( 'query' => $query, 'competitor' => 0 ) );
			$positions 		= $this->rank_repo->findByDomain( $main_domain );
			foreach ( $positions as $position ) {
				$id = $query->getQuery() . '(' . $main_domain->getUrl() . ')';
				$domains[$id][] = $position;
				$dates[$position->getDate()->getTimestamp()][] = $position;
			}
		}

		$this->domains 	= $domains;
		$this->dates 	= $dates;
	}
}