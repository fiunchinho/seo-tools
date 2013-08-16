<?php
namespace DomainFinder;

class GoogleSearchResultCollection extends \SplObjectStorage
{
	public function getDomainPosition( $domain )
	{
		$counter = 0;
		foreach ( $this as $url ) {
			$counter++;
			if ( $url->is( $domain ) ){
				return $counter;
			}
		}

		return false;
	}

	public function export()
	{
		$urls = array();
		foreach ( $this as $key => $url ) {
			var_dump($key);die;
			$urls[$key] = $url;
		}

		return json_encode( $urls );
	}
}