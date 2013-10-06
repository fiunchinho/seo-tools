<?php
namespace DomainFinder\UseCase;

class Logout
{
	public function __construct( $session )
	{
		$this->session 	= $session;
	}

	public function execute()
	{
        $this->session->invalidate();
        $this->session->clear();
        return array();
	}
}