<?php
namespace DomainFinder\Infrastructure;
use DomainFinder\Entity\User;

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
        if ( array_key_exists( $email, $this->users ) )
        {
            return $this->users[$email];
        }

        return null;
    }

    function findAll()
    {
        return $this->users;
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
        $this->users[$user_to_add->getEmail()] = $user_to_add;
    }
}