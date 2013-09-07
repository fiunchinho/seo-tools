<?php
namespace DomainFinder\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class DatabaseListener implements EventSubscriberInterface
{
	public function __construct( \Doctrine\ORM\EntityRepository $repository )
	{
		$this->repository = $repository;
	}

	public static function getSubscribedEvents()
    {
        return array(
            'found' => 'onFound'
        );
    }

    public function onFound( Event $event )
    {
    	extract( $event->getArguments() );
		$date 	= date( 'Ymd' );
		$rank 	= new \DomainFinder\Entity\Rank( $query, $domain, $date, $number_of_results );
		$this->repository->add( $rank );
    }
}