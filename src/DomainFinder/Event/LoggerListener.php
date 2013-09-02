<?php
namespace DomainFinder\Event;

class LoggerListener
{
	public function __construct( \PDO $pdo )
	{
		$this->pdo = $pdo;
		//$statement = $this->pdo->query( 'CREATE TABLE `logs` ( `id` INT(10), `domain` VARCHAR(255), `query` VARCHAR(255), `date` VARCHAR(255), `position` INT(4), PRIMARY KEY (id) ) ;' );
		//$statement = $this->pdo->query( 'CREATE UNIQUE INDEX `log_UNIQUE` ON `logs` (`domain`, `query`, `date`);' );
		//$statement = $this->pdo->query( 'UPDATE `logs` SET `date` = "20130827", `position` = 13' );
	}

	public function getSubscribedEvents()
	{
		return array(
			'found'
		);
	}

	public function found( $params )
	{
		$date 	= date( 'Ymd' );
		$sql 	= <<<QUERY
INSERT INTO	`logs`
	( `domain`, `query`, `date`, `position` )
VALUES
	( :domain, :query, :date, :position )
QUERY;
		$statement = $this->pdo->prepare( $sql );
		$statement->bindParam( ':domain', $params['domain'], \PDO::PARAM_STR );
		$statement->bindParam( ':query', $params['query'], \PDO::PARAM_STR );
		$statement->bindParam( ':date', $date, \PDO::PARAM_STR );
		$statement->bindParam( ':position', $params['number_of_results'], \PDO::PARAM_INT );
		$statement->execute();

		// $statement = $this->pdo->query( "SELECT * FROM logs" );
		// var_dump( $statement->fetchAll( \PDO::FETCH_ASSOC ) );die;
	}
}