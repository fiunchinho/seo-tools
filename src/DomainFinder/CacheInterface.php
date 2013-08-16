<?php
namespace DomainFinder;

interface CacheInterface
{
	public function get( $key );
	public function set( $key, $value );
}