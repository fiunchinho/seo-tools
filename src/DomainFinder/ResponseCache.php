<?php
namespace DomainFinder;

class ResponseCache implements CacheInterface
{
	public function __construct( CacheInterface $cache )
	{
		$this->cache = $cache;
	}

	public function get( $url )
	{
		return $this->cache->get( md5( $url . date( 'Ymd' ) ) );
	}

	public function set( $url, $response )
	{
		return $this->cache->set( md5( $url . date( 'Ymd' ) ), $response );
	}
}