<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * appareil
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\appareilRepository")
 */
class appareil
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
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\modele", inversedBy="appareils")
     * @ORM\JoinColumn(name="modele_id", referencedColumnName="id", nullable=false)
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\os", inversedBy="appareils")
     * @ORM\JoinColumn(name="os_id", referencedColumnName="id", nullable=false)
     */
    private $os;

    /**
     *
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\question", mappedBy="appareils")
     *
     */
    private $questions;

    /**
     *
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\membre", mappedBy="appareils")
     *
     */
    private $membres;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set modele
     *
     * @param \SmartUnity\AppBundle\Entity\modele $modele
     * @return appareil
     */
    public function setModele(\SmartUnity\AppBundle\Entity\modele $modele)
    {
        $this->modele = $modele;
    
        return $this;
    }

    /**
     * Get modele
     *
     * @return \SmartUnity\AppBundle\Entity\modele 
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set os
     *
     * @param \SmartUnity\AppBundle\Entity\os $os
     * @return appareil
     */
    public function setOs(\SmartUnity\AppBundle\Entity\os $os)
    {
        $this->os = $os;
    
        return $this;
    }

    /**
     * Get os
     *
     * @return \SmartUnity\AppBundle\Entity\os 
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * Add questions
     *
     * @param \SmartUnity\AppBundle\Entity\question $questions
     * @return appareil
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

    /**
     * Add membres
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membres
     * @return appareil
     */
    public function addMembre(\SmartUnity\AppBundle\Entity\membre $membres)
    {
        $this->membres[] = $membres;
    
        return $this;
    }

    /**
     * Remove membres
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membres
     */
    public function removeMembre(\SmartUnity\AppBundle\Entity\membre $membres)
    {
        $this->membres->removeElement($membres);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembres()
    {
        return $this->membres;
    }
}