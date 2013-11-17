<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * commentaireReponse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\commentaireReponseRepository")
 */
class commentaireReponse
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
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\reponse", inversedBy="commentaireReponses")
    * @ORM\JoinColumn(name="reponse_id", referencedColumnName="id", nullable=false)
    */
    private $reponse;

    /**
    * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", inversedBy="commentaireReponses")
    * @ORM\JoinColumn(name="membre_id", referencedColumnName="id", nullable=false)
    */
    private $membre;

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
     * @return commentaireReponse
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
     * @return commentaireReponse
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
     * Set reponse
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponse
     * @return commentaireReponse
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

    /**
     * Set membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     * @return commentaireReponse
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
}