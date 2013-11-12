<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * os
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\osRepository")
 */
class os
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\appareil", mappedBy="os")
     *
     */
    private $appareils;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->appareils = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return os
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    
    /**
     * Add appareils
     *
     * @param \SmartUnity\AppBundle\Entity\appareil $appareils
     * @return os
     */
    public function addAppareil(\SmartUnity\AppBundle\Entity\appareil $appareils)
    {
        $this->appareils[] = $appareils;
    
        return $this;
    }

    /**
     * Remove appareils
     *
     * @param \SmartUnity\AppBundle\Entity\appareil $appareils
     */
    public function removeAppareil(\SmartUnity\AppBundle\Entity\appareil $appareils)
    {
        $this->appareils->removeElement($appareils);
    }

    /**
     * Get appareils
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAppareils()
    {
        return $this->appareils;
    }
}