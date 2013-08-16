<?php
namespace DomainFinder;

class FileCache implements CacheInterface
{
	public function __construct()
	{
		$this->path = __DIR__ . '/../../cache/';
	}

	public function set( $key, $value )
	{
		return file_put_contents( $this->path . $key, $value );
	}

	public function get( $key )
	{
		return @file_get_contents( $this->path . $key );
	}
}