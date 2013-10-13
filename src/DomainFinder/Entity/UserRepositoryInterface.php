<?php
namespace DomainFinder\Entity;

interface UserRepositoryInterface
{
    public function find( $id );

    public function findOneByEmail( $email );

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findOneBy(array $criteria);

    public function add( User $user_to_add );
}