<?php
namespace DomainFinder\UseCase;

class ListQueries
{
	public function __construct( $domain_repository, $session )
	{
		$this->repo 	= $domain_repository;
		$this->session 	= $session;
	}

	public function execute( $request )
	{
		$queries 	= $this->repo->findByDomain( 3 );
		$domains 	= array();
		$dates 		= array();
		foreach ( $queries as $query ) {
			foreach ( $query->getPositions() as $position ) {
				$domain = $query->getDomain()->getUrl();
				$domains[$query->getQuery()][] = $position;
				$dates[$position->getDate()->getTimestamp()][] = $position;
			}
		}

	    return array( 'domains' => $domains, 'dates' => $dates, 'query' => 'hola', 'domain' => $domain );
	}
}