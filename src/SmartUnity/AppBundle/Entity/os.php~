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
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\os", inversedBy="enfants")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     *
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\os", mappedBy="parent")
     *
     */
    private $enfants;

    /**
     *
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\question", mappedBy="os")
     *
     */
    private $questions;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->appareils = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enfants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set parent
     *
     * @param \SmartUnity\AppBundle\Entity\os $parent
     * @return os
     */
    public function setParent(\SmartUnity\AppBundle\Entity\os $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \SmartUnity\AppBundle\Entity\os 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add enfants
     *
     * @param \SmartUnity\AppBundle\Entity\os $enfants
     * @return os
     */
    public function addEnfant(\SmartUnity\AppBundle\Entity\os $enfants)
    {
        $this->enfants[] = $enfants;
    
        return $this;
    }

    /**
     * Remove enfants
     *
     * @param \SmartUnity\AppBundle\Entity\os $enfants
     */
    public function removeEnfant(\SmartUnity\AppBundle\Entity\os $enfants)
    {
        $this->enfants->removeElement($enfants);
    }

    /**
     * Get enfants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnfants()
    {
        return $this->enfants;
    }

    /**
     * Add questions
     *
     * @param \SmartUnity\AppBundle\Entity\question $questions
     * @return os
     */
    public function addQuestion(\SmartUnity\AppBundle\Entity\question $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \SmartUnity\AppBundle\Entity\question $questions
     */
    public function removeQuestion(\SmartUnity\AppBundle\Entity\question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}