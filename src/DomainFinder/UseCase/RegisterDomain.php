<?php
namespace DomainFinder\UseCase;

use \DomainFinder\Entity\Domain;
use \DomainFinder\Entity\Application;
use \DomainFinder\Entity\User;

class RegisterDomain
{
	public function __construct( $repository )
	{
		$this->repo = $repository;
	}

	public function execute( $request = array() )
	{
		$domain = new Domain( $request['domain'], new Application( 'test', new User( 'new@user.com', 'pass' ) ) );
        $this->repo->add( $domain );

		return array( 'domain' => $domain );
	}
}