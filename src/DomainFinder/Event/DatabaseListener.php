<?php
namespace DomainFinder\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class DatabaseListener implements EventSubscriberInterface
{
	public function __construct( \Doctrine\Common\Persistence\ObjectRepository $repository, $override_existing = false )
	{
		$this->repository     = $repository;
        $this->override       = $override_existing;
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
        $date             = date( 'Ymd' );
        $rank             = new \DomainFinder\Entity\Rank( $query, $domain, $date, $number_of_results );
        $existing_rank    = $this->repository->findOneBy( array( 'query' => $query, 'domain' => $domain, 'date' => $date ) );
        if ( !$this->override && $existing_rank )
        {
            return $existing_rank;
        }
        if ( $existing_rank )
        {
            $existing_rank->setPosition( $number_of_results );
            $rank = $existing_rank;
        }

        $this->repository->add( $rank );
        return $rank;
    }
}