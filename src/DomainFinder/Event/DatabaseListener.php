<?php
namespace DomainFinder\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use DomainFinder\Entity\RankRepositoryInterface;

class DatabaseListener implements EventSubscriberInterface
{
    const OVERRIDE_EXISTING_RANKING         = true;
    const DONT_OVERRIDE_EXISTING_RANKING    = false;

	public function __construct( RankRepositoryInterface $rank_repository, $override_existing = false )
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
        $date             = new \DateTime( date( 'Y-m-d' ) );
        $rank             = new \DomainFinder\Entity\Rank( $event['domain'], $date, $event['number_of_results'] );
        $existing_rank    = $this->rank_repository->findOneBy( array( 'domain' => $event['domain'], 'date' => $date ) );
        if ( !$this->override && $existing_rank )
        {
            return $existing_rank;
        }
        if ( $existing_rank )
        {
            $existing_rank->setPosition( $event['number_of_results'] );
            $rank = $existing_rank;
        }

        $this->rank_repository->add( $rank );
        return $rank;
    }
}