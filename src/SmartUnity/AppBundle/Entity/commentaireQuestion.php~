<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * commentaireQuestion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\commentaireQuestionRepository")
 */
class commentaireQuestion
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
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\question", inversedBy="commentaireQuestions")
    * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
    */
    private $question;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="commentaireQuestions")
    * @ORM\JoinColumn(name="membre_id", referencedColumnName="id", nullable=false)
    */
    private $membre;

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
     * @return commentaireQuestion
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
     * @return commentaireQuestion
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
     * @return commentaireQuestion
     */
    public function setQuestion(\SmartUnity\AppBundle\Entity\question $question)
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
     * @return commentaireQuestion
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
     * Set signaler
     *
     * @param boolean $signaler
     * @return commentaireQuestion
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
}