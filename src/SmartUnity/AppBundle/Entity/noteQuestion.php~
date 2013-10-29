<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * noteQuestion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\noteQuestionRepository")
 */
class noteQuestion
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre")
     */
    private $membre;

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\question")
     */
    private $question;

    /**
     * @var integer
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;


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
     * Set note
     *
     * @param integer $note
     * @return noteQuestion
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return integer 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     * @return noteQuestion
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
     * Set question
     *
     * @param \SmartUnity\AppBundle\Entity\question $question
     * @return noteQuestion
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
}