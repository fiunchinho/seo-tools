<?php
namespace DomainFinder\Infrastructure;

use Doctrine\ORM\EntityRepository;
use DomainFinder\Entity\Application;

class ApplicationRepository extends EntityRepository
{
	public function add( Application $application )
	{
		$this->_em->persist( $application );
		$this->_em->flush();
	}

	public function remove( Application $application )
	{
		$this->_em->remove( $application );
		$this->_em->flush();
	}
}