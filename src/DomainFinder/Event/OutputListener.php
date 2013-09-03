<?php
namespace DomainFinder\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;

class OutputListener implements EventSubscriberInterface
{
	public function __construct( \Symfony\Component\Console\Output\OutputInterface $output )
	{
		$this->output = $output;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'maxPageLimitReached' 	=> 'onMaxPageLimitReached',
			'found' 				=> 'onFound',
			'cantParseUrl' 			=> 'onCantParseUrl',
			'nextPage' 				=> 'onNextPage'
		);
	}

	public function onNextPage( Event $event )
	{
		if ( $this->output->getVerbosity() > 1 ){
			$this->output->writeln( "<info>Going to google search results page number " . $event->getArgument('current_page') . "</info>");
		}
	}

	public function onMaxPageLimitReached( Event $event )
	{
		$this->output->writeln( "<error>Domain was not found: maximum google search page reached.</error>");
	}

	public function onFound( Event $event )
	{
		$this->output->writeln( "<question>Domain found in the position " . $event->getArgument('number_of_results') . ", in the page number " . $event->getArgument('current_page') . ".</question>");
	}

	public function onCantParseUrl( Event $event )
	{
		if ( $this->output->getVerbosity() > 1 ){
			$this->output->writeln( "<info>Can't read URL: " . $event->getArgument('url') . "</info>");
		}
	}
}