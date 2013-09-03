<?php
namespace DomainFinder\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class DatabaseListener implements EventSubscriberInterface
{
	public function __construct( $pdo )
	{
		$this->pdo = $pdo;
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
    }
}