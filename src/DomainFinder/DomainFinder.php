<?php
namespace DomainFinder;

use Goutte\Client;

class DomainFinder
{
	/**
	 * The domain that we are looking for.
	 * @var string
	 */
	public $domain;

	/**
	 * Maximum number of google results pages we want to check.
	 * @var integer
	 */
	public $max_google_page;

	/**
	 * Whether we have already found our domain our not.
	 * @var boolean
	 */
	public $found = false;

	/**
	 * How many google results have we checked already.
	 * @var integer
	 */
	public $number_of_results = 0;

	/**
	 * Current google search results page.
	 * @var integer
	 */
	public $current_page = 1;

	/**
	 * Dispatcher that sends events while searching our domain.
	 * @var \DomainFinder\Event\EventDispatcher
	 */
	public $event_dispatcher;

	/**
	 * The google search results.
	 * @var \DomainFinder\GoogleSearchResultCollection
	 */
	public $google_results;

	public function __construct( $domain, $max_google_page = 10, \DomainFinder\Event\EventDispatcher $event_dispatcher )
	{
		$this->domain 			= $domain;
		$this->max_google_page 	= $max_google_page;
		$this->dispatcher 		= $event_dispatcher;
		$this->google_results 	= new GoogleSearchResultCollection();
	}

	public function domainHasBeenFound()
	{
		return $this->found;
	}

	public function find( $query )
	{
		$this->client		= new ClientCache( new Client(), new ResponseCache( new FileCache() ) );
		$this->crawler		= $this->client->request( 'GET', 'https://www.google.es/search?q=' . urlencode( $query ) );

		while( !$this->domainHasBeenFound() && ( $this->current_page < $this->max_google_page ) )
		{
			$this->findDomainInSearchResultsForCurrentPage();
			if ( !$this->domainHasBeenFound() ){
				$this->current_page++;
				$this->crawler = $this->client->request( 'GET', $this->getNextPageUrl() );
			}
		}

		if ( $this->current_page >= $this->max_google_page ){
			$this->dispatcher->dispatch( 'maxPageLimitReached' );
		}
	}

	public function setFound( $was_found )
	{
		if ( !$this->domainHasBeenFound() && $was_found ){
			$this->found = $was_found;
			$this->dispatcher->dispatch( 'found', array( 'number_of_results' => $this->number_of_results, 'current_page' => $this->current_page ) );
		}
	}

	public function getGoogleResults()
	{
		return $this->google_results;
	}

	private function findDomainInSearchResultsForCurrentPage()
	{
		return $this->crawler->filter('h3.r')->each( $this->getCallbackToCheckIfResultMatchesDomain() );
	}

	private function getCallbackToCheckIfResultMatchesDomain()
	{
		$that = $this;
		return function( $e ) use ( &$that ){
			( $that->domainHasBeenFound() )?: $that->number_of_results++;
			try{
				$url 			= new GoogleSearchResult( $e->filter( 'a' )->attr( 'href' ) );
				$that->setFound( $url->is( $that->domain ) );
				$that->google_results->attach( $url, date('d/m/Y') );
			}catch( \RunTimeException $exception ){
				$that->dispatcher->dispatch( 'cantParseUrl', array( 'url' => $e->filter( 'a' )->attr( 'href' ) ) );
			}
		};
	}

	private function getNextPageUrl()
	{
		$link = $this->crawler->selectLink( 'Siguiente' );
		if ( count( $link ) ){
			$anchor 	= $link->link();
			$url 		= $anchor->getUri();
			return $url;
		}else{
			return false;
		}
	}
}