<?php
namespace DomainFinder\Infrastructure;

use Doctrine\ORM\EntityRepository;
use DomainFinder\Entity\Rank;

class RankRepository extends EntityRepository
{
	public function add( Rank $rank )
	{
		$this->_em->persist( $rank );
		$this->_em->flush();
	}

	public function remove( Rank $rank )
	{
		$this->_em->remove( $rank );
		$this->_em->flush();
	}
}