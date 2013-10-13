<?php
namespace DomainFinder\Infrastructure;

use Doctrine\ORM\EntityRepository;
use DomainFinder\Entity\Query;

class QueryRepository extends EntityRepository
{
	protected $already_asked_programs = array();

	public function add( Query $query )
	{
		$this->_em->persist( $query );
		$this->_em->flush();
	}

	public function remove( Query $query )
	{
		$this->_em->remove( $query );
		$this->_em->flush();
	}
}