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
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\question", inversedBy="reponses")
    * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
    */
    protected $question;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="reponses")
    * @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
    */
    protected $membre;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\noteReponse", mappedBy="reponse")
     */
    private $noteReponses;

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
}