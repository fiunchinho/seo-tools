<?php

namespace DomainFinder\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Query
 */
class Query
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $query;

    /**
     * @var \DomainFinder\Entity\Domain
     */
    private $domain;

    /**
     * @var \DomainFinder\Entity\Application
     */
    private $application;

    /**
     * Constructor
     */
    public function __construct( $query )
    {
        $this->query = $query;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set query
     *
     * @param string $query
     * @return Query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    
        return $this;
    }

    /**
     * Get query
     *
     * @return string 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set domain
     *
     * @param \DomainFinder\Entity\Domain $domain
     * @return Query
     */
    public function setDomain(\DomainFinder\Entity\Domain $domain = null)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return \DomainFinder\Entity\Domain 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set application
     *
     * @param \DomainFinder\Entity\Application $application
     * @return Query
     */
    public function setApplication(\DomainFinder\Entity\Application $application = null)
    {
        $this->application = $application;
    
        return $this;
    }

    /**
     * Get application
     *
     * @return \DomainFinder\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }
}
