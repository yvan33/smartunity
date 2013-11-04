<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\reponseRepository")
 */
class reponse
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateValidation", type="date", nullable=true)
     */
    private $dateValidation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCertification", type="date", nullable=true)
     */
    private $dateCertification;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\question", inversedBy="reponses")
    * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
    */
    private $question;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="reponses")
    * @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
    */
    private $membre;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\noteReponse", mappedBy="reponse")
     */
    private $noteReponses;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="reponsesCertifiees")
    * 
    */
    private $membreCertif;

    /**
     * Constructor
     */
    public function __construct()   
    {
        $this->noteReponses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return reponse
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
     * Set date
     *
     * @param \DateTime $date
     * @return reponse
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
     * Set question
     *
     * @param \SmartUnity\AppBundle\Entity\question $question
     * @return reponse
     */
    public function setQuestion(\SmartUnity\AppBundle\Entity\question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \SmartUnity\AppBundle\Entity\question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     * @return reponse
     */
    public function setMembre(\SmartUnity\AppBundle\Entity\membre $membre = null)
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
     * Add noteReponses
     *
     * @param \SmartUnity\AppBundle\Entity\noteReponse $noteReponses
     * @return reponse
     */
    public function addNoteReponse(\SmartUnity\AppBundle\Entity\noteReponse $noteReponses)
    {
        $this->noteReponses[] = $noteReponses;
    
        return $this;
    }

    /**
     * Remove noteReponses
     *
     * @param \SmartUnity\AppBundle\Entity\noteReponse $noteReponses
     */
    public function removeNoteReponse(\SmartUnity\AppBundle\Entity\noteReponse $noteReponses)
    {
        $this->noteReponses->removeElement($noteReponses);
    }

    /**
     * Get noteReponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNoteReponses()
    {
        return $this->noteReponses;
    }

    /**
     * Set dateValidation
     *
     * @param \DateTime $dateValidation
     * @return reponse
     */
    public function setDateValidation($dateValidation)
    {
        $this->dateValidation = $dateValidation;
    
        return $this;
    }

    /**
     * Get dateValidation
     *
     * @return \DateTime 
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }

    /**
     * Set dateCertification
     *
     * @param \DateTime $dateCertification
     * @return reponse
     */
    public function setDateCertification($dateCertification)
    {
        $this->dateCertification = $dateCertification;
    
        return $this;
    }

    /**
     * Get dateCertification
     *
     * @return \DateTime 
     */
    public function getDateCertification()
    {
        return $this->dateCertification;
    }

    /**
     * Set questionreponsevalidee
     *
     * @param \SmartUnity\AppBundle\Entity\question $questionreponsevalidee
     * @return reponse
     */
    public function setQuestionreponsevalidee(\SmartUnity\AppBundle\Entity\question $questionreponsevalidee = null)
    {
        $this->questionreponsevalidee = $questionreponsevalidee;
    
        return $this;
    }

    /**
     * Get questionreponsevalidee
     *
     * @return \SmartUnity\AppBundle\Entity\question 
     */
    public function getQuestionreponsevalidee()
    {
        return $this->questionreponsevalidee;
    }

    /**
     * Set membreCertif
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membreCertif
     * @return reponse
     */
    public function setMembreCertif(\SmartUnity\AppBundle\Entity\membre $membreCertif = null)
    {
        $this->membreCertif = $membreCertif;
    
        return $this;
    }

    /**
     * Get membreCertif
     *
     * @return \SmartUnity\AppBundle\Entity\membre 
     */
    public function getMembreCertif()
    {
        return $this->membreCertif;
    }
}