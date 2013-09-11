<?php
namespace DomainFinder;

use Goutte\Client;
use Symfony\Component\EventDispatcher\GenericEvent;

class DomainFinder
{
	/**
	 * The domain in which we are going to perform the searcht, f.i. google.fr
	 * @var string
	 */
	public $google_domain = 'google.com';

	/**
	 * The domain that we are looking for.
	 * @var string
	 */
	public $domain;

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

	public function __construct( \Symfony\Component\EventDispatcher\EventDispatcher $event_dispatcher )
	{
		$this->dispatcher 		= $event_dispatcher;
		$this->google_results 	= new GoogleSearchResultCollection();
	}

	public function domainHasBeenFound()
	{
		return $this->found;
	}

	public function find( $domain, $query, $max_google_page = 10, $google_domain = 'google.com', $language = 'en' )
	{
		$this->domain 		 = $domain;
		$this->google_domain = $google_domain;
		$this->query 		 = $query;
		$this->client		 = new ClientCache( new Client(), new ResponseCache( new FileCache() ) );
		$url = "https://www.$google_domain/search?q=" . urlencode( $query ) . "&hl=$language";
		$this->crawler		 = $this->client->request( 'GET', $url );

		while( !$this->domainHasBeenFound() && ( $this->current_page < $max_google_page ) )
		{
			$this->findDomainInSearchResultsForCurrentPage();
			if ( !$this->domainHasBeenFound() ){
				$this->current_page++;
				$this->crawler = $this->client->request( 'GET', $this->getNextPageUrl() );
				 $this->dispatcher->dispatch( 'nextPage', new GenericEvent( $this, array( 'current_page' => $this->current_page ) ) );
			}
		}

		if ( $this->current_page >= $max_google_page ){
			$this->dispatcher->dispatch( 'maxPageLimitReached' );
		}
	}

	public function setAsFound( $was_found )
	{
		if ( !$this->domainHasBeenFound() && $was_found ){
			$this->found = $was_found;
			$this->dispatcher->dispatch( 'found', new GenericEvent( $this, array( 'number_of_results' => $this->number_of_results, 'current_page' => $this->current_page, 'domain' => $this->domain, 'query' => $this->query ) ) );
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
				$that->setAsFound( $url->is( $that->domain ) );
				$that->google_results->attach( $url, date('d/m/Y') );
			}catch( \RunTimeException $exception ){
				( $that->domainHasBeenFound() )?: $that->number_of_results--;
				$that->dispatcher->dispatch( 'cantParseUrl', new GenericEvent( $that, array( 'url' => $e->filter( 'a' )->attr( 'href' ) ) ) );
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
