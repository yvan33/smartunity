<?php

namespace SmartUnity\AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class membre extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    protected $nom;
    
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="cagnotte", type="integer", options={"default":0})
//     */
//    protected $cagnotte;
//    
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="reputation", type="integer", options={"default":0})
//     */
//    protected $reputation;
// 
//    /**
//     * @var boolean
//     *
//     * @ORM\Column(name="sexe", type="boolean", options={"default":0})
//     */
//    protected $sexe;    

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
     * Set nom
     *
     * @param string $nom
     * @return Membre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set cagnotte
     *
     * @param integer $cagnotte
     * @return membre
     */
    public function setCagnotte($cagnotte)
    {
        $this->cagnotte = $cagnotte;
    
        return $this;
    }

    /**
     * Get cagnotte
     *
     * @return integer 
     */
    public function getCagnotte()
    {
        return $this->cagnotte;
    }

    /**
     * Set reputation
     *
     * @param integer $reputation
     * @return membre
     */
    public function setReputation($reputation)
    {
        $this->reputation = $reputation;
    
        return $this;
    }

    /**
     * Get reputation
     *
     * @return integer 
     */
    public function getReputation()
    {
        return $this->reputation;
    }

    /**
     * Set sexe
     *
     * @param boolean $sexe
     * @return membre
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    
        return $this;
    }

    /**
     * Get sexe
     *
     * @return boolean 
     */
    public function getSexe()
    {
        return $this->sexe;
    }
}