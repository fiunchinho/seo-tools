<?php
namespace DomainFinder\Infrastructure;

use Doctrine\ORM\EntityRepository;
use DomainFinder\Entity\User;

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