<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\questionRepository")
 */
class question
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
     * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
     */
    private $sujet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="questions")
     * @ORM\JoinColumn(name="membre_id", referencedColumnName="id", nullable=false)
     */
    private $membre;

    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\os", inversedBy="questions")
     * @ORM\JoinColumn(name="os_id", referencedColumnName="id", nullable=true)
     */
    private $os;
    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\modele", inversedBy="questions")
     * @ORM\JoinColumn(name="modele_id", referencedColumnName="id", nullable=true)
     */
    private $modele;
    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\marque", inversedBy="questions")
     * @ORM\JoinColumn(name="marque_id", referencedColumnName="id", nullable=true)
     */
    private $marque;

    /**
     * @var string
     * 
     * @ORM\Column(name="slug", type="string", unique=TRUE, length=150, nullable=false)
     */
    private $slug;

    /**
     * @var integer
     * 
     * @ORM\Column(name="remuneration", type="integer", nullable=false)
     */
    private $remuneration;
    
    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\reponse", mappedBy="question")
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\typeQuestion", inversedBy="questions")
     * @ORM\JoinColumn(name="typeQuestion_id", referencedColumnName="id", nullable=false)
     */
    private $typeQuestion;

    /**
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="soutienQuestions")
     * @ORM\JoinTable(name="soutien")
     */
    private $soutienMembres;

    /**
     * @var boolean
     *
     * @ORM\Column(name="signaler", type="boolean")
     */
    private $signaler;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soutienMembres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sujet
     *
     * @param string $sujet
     * @return question
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
    
        return $this;
    }

    /**
     * Get sujet
     *
     * @return string 
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return question
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
     * Set description
     *
     * @param string $description
     * @return question
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return question
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set remuneration
     *
     * @param integer $remuneration
     * @return question
     */
    public function setRemuneration($remuneration)
    {
        $this->remuneration = $remuneration;
    
        return $this;
    }

    /**
     * Get remuneration
     *
     * @return integer 
     */
    public function getRemuneration()
    {
        return $this->remuneration;
    }

    /**
     * Set signaler
     *
     * @param boolean $signaler
     * @return question
     */
    public function setSignaler($signaler)
    {
        $this->signaler = $signaler;
    
        return $this;
    }

    /**
     * Get signaler
     *
     * @return boolean 
     */
    public function getSignaler()
    {
        return $this->signaler;
    }

    /**
     * Set membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     * @return question
     */
    public function setMembre(\SmartUnity\AppBundle\Entity\membre $membre)
    {
        $this->membre = $membre;
    
        return $this;
    }

    /**
     * Get membre
     *
     * @return \SmartUnity\AppBundle\Entity\membre 
     */
    public function getMembre()
    {
        return $this->membre;
    }

    /**
     * Set os
     *
     * @param \SmartUnity\AppBundle\Entity\os $os
     * @return question
     */
    public function setOs(\SmartUnity\AppBundle\Entity\os $os = null)
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
     * Set modele
     *
     * @param \SmartUnity\AppBundle\Entity\modele $modele
     * @return question
     */
    public function setModele(\SmartUnity\AppBundle\Entity\modele $modele = null)
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
     * Set marque
     *
     * @param \SmartUnity\AppBundle\Entity\marque $marque
     * @return question
     */
    public function setMarque(\SmartUnity\AppBundle\Entity\marque $marque = null)
    {
        $this->marque = $marque;
    
        return $this;
    }

    /**
     * Get marque
     *
     * @return \SmartUnity\AppBundle\Entity\marque 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Add reponses
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponses
     * @return question
     */
    public function addReponse(\SmartUnity\AppBundle\Entity\reponse $reponses)
    {
        $this->reponses[] = $reponses;
    
        return $this;
    }

    /**
     * Remove reponses
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponses
     */
    public function removeReponse(\SmartUnity\AppBundle\Entity\reponse $reponses)
    {
        $this->reponses->removeElement($reponses);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    /**
     * Set typeQuestion
     *
     * @param \SmartUnity\AppBundle\Entity\typeQuestion $typeQuestion
     * @return question
     */
    public function setTypeQuestion(\SmartUnity\AppBundle\Entity\typeQuestion $typeQuestion)
    {
        $this->typeQuestion = $typeQuestion;
    
        return $this;
    }

    /**
     * Get typeQuestion
     *
     * @return \SmartUnity\AppBundle\Entity\typeQuestion 
     */
    public function getTypeQuestion()
    {
        return $this->typeQuestion;
    }

    /**
     * Add soutienMembres
     *
     * @param \SmartUnity\AppBundle\Entity\membre $soutienMembres
     * @return question
     */
    public function addSoutienMembre(\SmartUnity\AppBundle\Entity\membre $soutienMembres)
    {
        $this->soutienMembres[] = $soutienMembres;
    
        return $this;
    }

    /**
     * Remove soutienMembres
     *
     * @param \SmartUnity\AppBundle\Entity\membre $soutienMembres
     */
    public function removeSoutienMembre(\SmartUnity\AppBundle\Entity\membre $soutienMembres)
    {
        $this->soutienMembres->removeElement($soutienMembres);
    }

    /**
     * Get soutienMembres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoutienMembres()
    {
        return $this->soutienMembres;
    }
}