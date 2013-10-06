<?php
namespace DomainFinder\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	public function add( User $user )
	{
		$this->_em->persist( $user );
		$this->_em->flush();
	}

	public function remove( User $user )
	{
		$this->_em->remove( $user );
		$this->_em->flush();
	}
}