<?php
namespace DomainFinder\Entity;

class ApplicationArrayRepository implements \Doctrine\Common\Persistence\ObjectRepository
{
	private $applications;

    public function __construct( array $applications = array() )
    {
        $this->applications = $applications;
    }

    function find( $id )
    {
    	foreach ( $this->applications as $domain )
    	{
            if ( $domain->getId() === $id )
            {
                return $domain;
            }
        }

        return null;
    }

    public function findOneByName( $application_name )
    {
    	foreach ( $this->applications as $application )
    	{
            if ( $application->getName() === $application_name )
            {
                return $application;
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

    public function add( Application $app_to_add )
    {
        foreach ( $this->applications as $application )
        {
            if ( $app_to_add->getName() === $application->getName() )
            {
                throw new \DomainFinder\Exception\AlreadyRegisteredException( 'Application already exists' );
            }
        }

        $this->applications[] = $app_to_add;
    }
}