<?php
namespace DomainFinder\UseCase;

use \DomainFinder\Entity\Application;
use \DomainFinder\Entity\User;

class CreateApplication
{
	public function __construct( $repository )
	{
		$this->repo = $repository;
	}

	public function execute( $request = array() )
	{
		$app = new Application( $request['application'], new User( 'my@email.com', 'asdasd' ) );
        $this->repo->add( $app );

		return array( 'application' => $app );
	}
}