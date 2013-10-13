<?php
namespace DomainFinder\Infrastructure;

use Doctrine\ORM\EntityRepository;
use DomainFinder\Entity\Domain;

class DomainRepository extends EntityRepository
{
	public function add( Domain $domain )
	{
		$this->_em->persist( $domain );
		$this->_em->flush();
	}

	public function remove( Domain $domain )
	{
		$this->_em->remove( $domain );
		$this->_em->flush();
	}
}