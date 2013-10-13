<?php
namespace DomainFinder\Entity;

interface RankRepositoryInterface
{
    public function find( $id );

    public function findOneByEmail( $email );

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findOneBy(array $criteria);

    public function add( Rank $user_to_add );
}