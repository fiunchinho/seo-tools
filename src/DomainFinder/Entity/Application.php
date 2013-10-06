<?php

namespace DomainFinder\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 */
class Application
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DomainFinder\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $queries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->queries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Application
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param \DomainFinder\Entity\User $user
     * @return Application
     */
    public function setUser(\DomainFinder\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \DomainFinder\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add queries
     *
     * @param \DomainFinder\Entity\Query $queries
     * @return Application
     */
    public function addQuerie(\DomainFinder\Entity\Query $queries)
    {
        $this->queries[] = $queries;
    
        return $this;
    }

    /**
     * Remove queries
     *
     * @param \DomainFinder\Entity\Query $queries
     */
    public function removeQuerie(\DomainFinder\Entity\Query $queries)
    {
        $this->queries->removeElement($queries);
    }

    /**
     * Get queries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQueries()
    {
        return $this->queries;
    }

    public function __toString()
    {
        return $this->name;
    }
}
