<?php

namespace DomainFinder\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 */
class Domain
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var integer
     */
    private $competitor;

    /**
     * @var \DomainFinder\Entity\Query
     */
    private $query;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $positions;

    /**
     * @var \DomainFinder\Entity\Application
     */
    private $application;

    /**
     * Constructor
     */
    public function __construct( $url, $competitor = 0 )
    {
        $this->url          = $url;
        $this->competitor   = $competitor;
        $this->positions    = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->url;
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
     * Set url
     *
     * @param string $url
     * @return Domain
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set competitor
     *
     * @param integer $competitor
     * @return Domain
     */
    public function setCompetitor($competitor)
    {
        $this->competitor = $competitor;
    
        return $this;
    }

    /**
     * Get competitor
     *
     * @return integer 
     */
    public function getCompetitor()
    {
        return $this->competitor;
    }

    /**
     * Set query
     *
     * @param \DomainFinder\Entity\Query $query
     * @return Domain
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
     * Add positions
     *
     * @param \DomainFinder\Entity\Rank $positions
     * @return Domain
     */
    public function addPosition(\DomainFinder\Entity\Rank $positions)
    {
        $this->positions[] = $positions;
    
        return $this;
    }

    /**
     * Remove positions
     *
     * @param \DomainFinder\Entity\Rank $positions
     */
    public function removePosition(\DomainFinder\Entity\Rank $positions)
    {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPositions()
    {
        return $this->positions;
    }
}
