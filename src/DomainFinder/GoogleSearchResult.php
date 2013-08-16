<?php
namespace DomainFinder;

class GoogleSearchResult
{
	public function __construct( $url )
	{
		$this->raw_url 		= $url;
		$this->parsed_url 	= parse_url( ltrim( $url, '/url?q=' ) );
		if ( !isset( $this->parsed_url['host'] ) ){
			throw new \RunTimeException( 'Can\'t parse the url' );
		}
	}

	public function is( $domain )
	{
		return ( false !== strpos( $this->parsed_url['host'], $domain ) );
	}

	public function getHost()
	{
		return $this->parsed_url['host'];
	}

	public function getUrl()
	{
		return $this->parsed_url['host'] . $this->parsed_url['path'];
	}
}