<?php
namespace DomainFinder\UseCase;

use DomainFinder\Entity\Query;
use DomainFinder\Entity\Domain;

class UpdateQuery
{
	public function __construct( $query_repository, $domain_repository )
	{
		$this->query_repo 		= $query_repository;
		$this->domain_repo 		= $domain_repository;
	}

	public function execute( $request = array() )
	{
		$query = $this->query_repo->findOneByQuery( $request['original_query'] );
		if ( $query->getApplication() !== $request['application'] )
		{
			throw new \InvalidArgumentException( 'You don\'t have permissions to do this' );
		}
		$request_domains = explode( ' ', $request['domains'] );

		$this->deleteDomainsThatAreDeleted( $query, $request_domains );
		$this->createDomainsThatAreNew( $query, $request_domains );
		$this->query_repo->add( $query );

		return array( 'query' => $query );
	}

	private function createDomainsThatAreNew( $query, $domains )
	{
		foreach( $domains as $domain_url )
		{
			$domain = $this->domain_repo->findOneBy( array( 'query' => $query, 'url' => $domain_url ) );
			if ( !$domain )
			{
				$domain_to_save = new Domain( $domain_url );
				$domain_to_save->setQuery( $query );
				$query->addDomain( $domain_to_save );
				$this->domain_repo->add( $domain_to_save );
			}
		}
	}

	private function deleteDomainsThatAreDeleted( $query, $domains )
	{
		foreach( $query->getDomains() as $domain ) {
			if ( !in_array( $domain->getUrl(), $domains ) )
			{
				$query->removeDomain( $domain );
				$domain = $this->domain_repo->findOneBy( array( 'query' => $query, 'url' => $domain->getUrl() ) );
				$this->domain_repo->remove( $domain );
			}
		}
	}
}