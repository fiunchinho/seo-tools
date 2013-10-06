<?php
namespace DomainFinder\Entity;

class UserArrayRepository implements \Doctrine\Common\Persistence\ObjectRepository
{
	private $users;

    public function __construct( array $users = array() )
    {
        $this->users = $users;
    }

    function find( $id )
    {
    	foreach ( $this->users as $user )
    	{
            if ( $user->getId() === $id )
            {
                return $user;
            }
        }

        return null;
    }

    public function findOneByEmail( $email )
    {
    	foreach ( $this->users as $user )
    	{
            if ( $user->getEmail() === $email )
            {
                return $user;
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

    public function add( User $user_to_add )
    {
        foreach ( $this->users as $user )
        {
            if ( $user_to_add->getEmail() === $user->getEmail() )
            {
                throw new \DomainFinder\Exception\AlreadyRegisteredException( 'User already exists' );
            }
        }

        $this->users[] = $user_to_add;
    }
}