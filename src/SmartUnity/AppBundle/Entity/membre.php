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
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cagnotte", type="integer")
     */
    protected $cagnotte=0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="reputation", type="integer")
     */
    protected $reputation=0;
 
    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=2)
     */
    protected $sexe;    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_naissance", type="datetime")
     */
    protected $date_naissance; 
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_mp", type="boolean")
     */
    protected $pref_mp;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_smartcafe", type="boolean")
     */
    protected $pref_smartcafe;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_comm", type="boolean")
     */
    protected $pref_comm;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_rep", type="boolean")
     */
    protected $pref_rep;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_repValidee", type="boolean")
     */
    protected $pref_repValidee;    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_repCertifiee", type="boolean")
     */
    protected $pref_repCertifiee;    

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

    /**
     * Set date_naissance
     *
     * @param \DateTime $dateNaissance
     * @return membre
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;
    
        return $this;
    }

    /**
     * Get date_naissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * Set pref_mp
     *
     * @param boolean $prefMp
     * @return membre
     */
    public function setPrefMp($prefMp)
    {
        $this->pref_mp = $prefMp;
    
        return $this;
    }

    /**
     * Get pref_mp
     *
     * @return boolean 
     */
    public function getPrefMp()
    {
        return $this->pref_mp;
    }

    /**
     * Set pref_smartcafe
     *
     * @param boolean $prefSmartcafe
     * @return membre
     */
    public function setPrefSmartcafe($prefSmartcafe)
    {
        $this->pref_smartcafe = $prefSmartcafe;
    
        return $this;
    }

    /**
     * Get pref_smartcafe
     *
     * @return boolean 
     */
    public function getPrefSmartcafe()
    {
        return $this->pref_smartcafe;
    }

    /**
     * Set pref_comm
     *
     * @param boolean $prefComm
     * @return membre
     */
    public function setPrefComm($prefComm)
    {
        $this->pref_comm = $prefComm;
    
        return $this;
    }

    /**
     * Get pref_comm
     *
     * @return boolean 
     */
    public function getPrefComm()
    {
        return $this->pref_comm;
    }

    /**
     * Set pref_rep
     *
     * @param boolean $prefRep
     * @return membre
     */
    public function setPrefRep($prefRep)
    {
        $this->pref_rep = $prefRep;
    
        return $this;
    }

    /**
     * Get pref_rep
     *
     * @return boolean 
     */
    public function getPrefRep()
    {
        return $this->pref_rep;
    }

    /**
     * Set pref_repValidee
     *
     * @param boolean $prefRepValidee
     * @return membre
     */
    public function setPrefRepValidee($prefRepValidee)
    {
        $this->pref_repValidee = $prefRepValidee;
    
        return $this;
    }

    /**
     * Get pref_repValidee
     *
     * @return boolean 
     */
    public function getPrefRepValidee()
    {
        return $this->pref_repValidee;
    }

    /**
     * Set pref_repCertifiee
     *
     * @param boolean $prefRepCertifiee
     * @return membre
     */
    public function setPrefRepCertifiee($prefRepCertifiee)
    {
        $this->pref_repCertifiee = $prefRepCertifiee;
    
        return $this;
    }

    /**
     * Get pref_repCertifiee
     *
     * @return boolean 
     */
    public function getPrefRepCertifiee()
    {
        return $this->pref_repCertifiee;
    }
}