<?php
namespace DomainFinder\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class DatabaseListener implements EventSubscriberInterface
{
	public function __construct( \Doctrine\Common\Persistence\ObjectRepository $rank_repository, $override_existing = false )
	{
		$this->rank_repository  = $rank_repository;
        $this->override         = $override_existing;
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
        //$date             = date( 'Y-m-d' );
        $date             = new \DateTime( date( 'Y-m-d' ) );
        $rank             = new \DomainFinder\Entity\Rank( $domain, $date, $number_of_results );
        //$existing_rank    = $this->rank_repository->findOneBy( array( 'query' => $query, 'domain' => $domain, 'date' => $date ) );
        $existing_rank    = $this->rank_repository->findOneBy( array( 'domain' => $domain, 'date' => $date ) );
        if ( !$this->override && $existing_rank )
        {
            return $existing_rank;
        }
        if ( $existing_rank )
        {
            $existing_rank->setPosition( $number_of_results );
            $rank = $existing_rank;
        }

        $this->rank_repository->add( $rank );
        return $rank;
    }
}