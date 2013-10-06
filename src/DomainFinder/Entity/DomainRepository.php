<?php
namespace DomainFinder\Entity;

use Doctrine\ORM\EntityRepository;

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