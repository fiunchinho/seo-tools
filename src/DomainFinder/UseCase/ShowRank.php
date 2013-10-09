<?php
namespace DomainFinder\UseCase;

class ShowRank
{
	public function __construct( $rank_repository, $query_repository, $domain_repository )
	{
		$this->rank_repo 	= $rank_repository;
		$this->query_repo 	= $query_repository;
		$this->domain_repo 	= $domain_repository;
	}

	public function execute( $request = array() )
	{
		$query = $request['query'];
		$domains 	= $this->domain_repo->findByQuery( $query );
		foreach ( $domains as $domain ) {
			$ranking[$domain->getUrl()] = $this->rank_repo->findByDomain( $domain );
		}

		return array( 'ranking' => $ranking );
	}
}