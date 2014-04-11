<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * noteReponse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\noteReponseRepository")
 */
class noteReponse
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="noteReponses")
     */
    private $membre;

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\reponse", inversedBy="noteReponses")
     */
    private $reponse;

    /**
     * @var integer
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    private $note;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set note
     *
     * @param integer $note
     * @return noteReponse
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
     * @return noteReponse
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
     * Set reponse
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponse
     * @return noteReponse
     */
    public function setReponse(\SmartUnity\AppBundle\Entity\reponse $reponse)
    {
        $this->reponse = $reponse;
    
        return $this;
    }

    /**
     * Get reponse
     *
     * @return \SmartUnity\AppBundle\Entity\reponse 
     */
    public function getReponse()
    {
        return $this->reponse;
    }
}