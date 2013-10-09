<?php
namespace DomainFinder\UseCase;

use DomainFinder\Entity\Query;
use DomainFinder\Entity\Domain;

class SaveQuery
{
	public function __construct( $query_repository, $domain_repository )
	{
		$this->query_repo 		= $query_repository;
		$this->domain_repo 		= $domain_repository;
	}

	public function execute( $request = array() )
	{
		$query = new Query( $request['query'] );
		$query->setApplication( $request['current_user']->getApplication( $request['application'] ) );
		$this->query_repo->add( $query );

		foreach( $request['domains'] as $domain ) {
			$domain_to_save = new Domain( $domain );
			$domain_to_save->setQuery( $query );

			$this->domain_repo->add( $domain_to_save );
		}

		return array();
	}
}