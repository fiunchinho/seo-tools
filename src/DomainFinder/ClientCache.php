<?php
namespace DomainFinder;

use Goutte\Client;
use \Symfony\Component\DomCrawler\Crawler;

class ClientCache
{
	public function __construct( Client $client, CacheInterface $cache )
	{
		$this->client 	= $client;
		$this->cache 	= $cache;
	}

	public function request( $method, $url )
	{
		if ( $response = $this->cache->get( $url ) ){
			$crawler = new Crawler( $response, $url );
		}else{
			$crawler = $this->client->request( $method, $url );
			$this->cache->set( $url, $crawler->html() );
		}

		return $crawler;
	}
}