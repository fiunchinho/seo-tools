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

		/*
		$sql 	= <<<QUERY
INSERT INTO	`logs`
	( `domain`, `query`, `date`, `position` )
VALUES
	( :domain, :query, :date, :position )
QUERY;
		$statement = $this->pdo->prepare( $sql );
		$statement->bindParam( ':domain', $domain, \PDO::PARAM_STR );
		$statement->bindParam( ':query', $query, \PDO::PARAM_STR );
		$statement->bindParam( ':date', $date, \PDO::PARAM_STR );
		$statement->bindParam( ':position', $number_of_results, \PDO::PARAM_INT );
		$statement->execute();
		*/
    }
}