<?php

namespace DomainFinder\Entity;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $applications;

    /**
     * Constructor
     */
    public function __construct( $email, $password )
    {
        $this->setEmail( $email );
        $this->setPassword( $password );
        $this->applications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add applications
     *
     * @param \DomainFinder\Entity\Application $applications
     * @return User
     */
    public function addApplication(\DomainFinder\Entity\Application $applications)
    {
        $this->applications[] = $applications;
    
        return $this;
    }

    /**
     * Remove applications
     *
     * @param \DomainFinder\Entity\Application $applications
     */
    public function removeApplication(\DomainFinder\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Get selected application
     *
     * @param int $application_id
     * @return \DomainFinder\Entity\Application
     */
    public function getApplication( $application_id )
    {
        foreach ( $this->applications as $application ) {
            if ( $application_id == $application->getId() )
            {
                return $application;
            }
        }

        throw new \InvalidArgumentException( 'Application not found' );
        
    }
}
