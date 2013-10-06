<?php

namespace DomainFinder\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rank
 */
class Rank
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var \DomainFinder\Entity\Query
     */
    private $query;

    /**
     * @var \DomainFinder\Entity\Domain
     */
    private $domain;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Rank
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Rank
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set query
     *
     * @param \DomainFinder\Entity\Query $query
     * @return Rank
     */
    public function setQuery(\DomainFinder\Entity\Query $query = null)
    {
        $this->query = $query;
    
        return $this;
    }

    /**
     * Get query
     *
     * @return \DomainFinder\Entity\Query 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set domain
     *
     * @param \DomainFinder\Entity\Domain $domain
     * @return Rank
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
}
