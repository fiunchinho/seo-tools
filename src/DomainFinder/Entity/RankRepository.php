<?php
namespace DomainFinder\Entity;

use Doctrine\ORM\EntityRepository;

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