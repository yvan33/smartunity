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
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\modele")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\os")
     * @ORM\JoinColumn(nullable=false)
     */
    private $os;

    

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
}