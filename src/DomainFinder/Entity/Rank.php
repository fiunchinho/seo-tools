<?php
namespace DomainFinder\Entity;

/**
 * @Entity @Table(name="logs")
 **/
class Rank
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $query;
    /** @Column(type="string") **/
    protected $domain;
    /** @Column(type="string") **/
    protected $date;
    /** @Column(type="integer")**/
    protected $position;

    public function getId()
    {
        return $this->id;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery( $query )
    {
        $this->query = $query;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain( $domains )
    {
        $this->domain = $domains;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate( $date )
    {
        $this->date = $date;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition( $position )
    {
        $this->position = $position;
    }
}