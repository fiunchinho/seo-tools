<?php
namespace DomainFinder\Entity;
/**
 * @Entity(repositoryClass="DomainFinder\Entity\QueryRepository") @Table(name="queries")
 **/
class Query
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $query;
    /** @Column(type="string") **/
    protected $domain;

    protected $domains;

    public function __construct( $query, $domain )
    {
        $this->query    = $query;
        $this->domain   = $domain;
    }

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

    public function setDomains( array $domains )
    {
        $this->domains = $domains;
    }

    public function getDomains()
    {
        if ( !$this->domains )
        {
            $this->domains  = explode( ' ', $this->domain );
        }

        return $this->domains;
    }
}