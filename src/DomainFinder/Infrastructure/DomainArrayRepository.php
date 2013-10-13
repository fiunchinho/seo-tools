<?php
namespace DomainFinder\Infrastructure;

use DomainFinder\Entity\Domain;

class DomainArrayRepository implements \Doctrine\Common\Persistence\ObjectRepository
{
	private $domains;

    public function __construct( array $domains = array() )
    {
        $this->domains = $domains;
    }

    function find( $id )
    {
    	foreach ( $this->domains as $domain )
    	{
            if ( $domain->getId() === $id )
            {
                return $domain;
            }
        }

        return null;
    }

    public function findOneByDomain( $domain_name )
    {
    	foreach ( $this->domains as $domain )
    	{
            if ( $domain->getUrl() === $domain_name )
            {
                return $domain;
            }
        }

        return null;
    }

    function findAll()
    {

    }

    function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {

    }

    function findOneBy(array $criteria)
    {

    }

    function getClassName()
    {

    }

    public function add( Domain $domain_to_add )
    {
        foreach ( $this->domains as $domain )
        {
            if ( $domain_to_add->getDomain() === $domain->getDomain() )
            {
                throw new \DomainFinder\Exception\AlreadyRegisteredException( 'Domain already exists' );
            }
        }

        $this->domains[] = $domain_to_add;
    }
}