<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * marque
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\parrainageRepository")
 */
class parrainage
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
     * @var email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**	
     * @ORM\OneToOne(targetEntity="SmartUnity\AppBundle\Entity\membre", mappedBy="parrain")
     * @ORM\JoinColumn(name="membre_id", referencedColumnName="id")
     */
    private $membre;

    /**
     * @var email
     *
     * @ORM\Column(name="code", type="string", length=50, nullable=false)
     */
    private $code;

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
     * Set email
     *
     * @param string $email
     * @return parrainage
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     * @return parrainage
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
     * Constructor
     */
    public function __construct()
    {
        $this->membre = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     * @return parrainage
     */
    public function addMembre(\SmartUnity\AppBundle\Entity\membre $membre)
    {
        $this->membre[] = $membre;
    
        return $this;
    }

    /**
     * Remove membre
     *
     * @param \SmartUnity\AppBundle\Entity\membre $membre
     */
    public function removeMembre(\SmartUnity\AppBundle\Entity\membre $membre)
    {
        $this->membre->removeElement($membre);
    }

    /**
     * Set code
     *
     * @param string $code
     * @return parrainage
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return parrainage
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    
        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime 
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }
}