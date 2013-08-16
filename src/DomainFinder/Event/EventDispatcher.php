<?php
namespace DomainFinder\Event;

class EventDispatcher
{
	public function addSubscriber( $listener )
	{
		foreach ( $listener->getSubscribedEvents() as $event_name ) {
			$this->listeners[$event_name][] = $listener;
		}
	}

	public function dispatch( $event_name, $params = array() )
	{
		foreach ( $this->listeners[$event_name] as $listener ) {
			$listener->$event_name( $params );
		}
	}
}