<?php
namespace DomainFinder;

class PasswordEncoder
{
	public function hash( $password )
	{
		return sha1( $password );
	}

	public function verify( $password, $hash )
	{
		return ( sha1( $password ) == $hash );
	}
}