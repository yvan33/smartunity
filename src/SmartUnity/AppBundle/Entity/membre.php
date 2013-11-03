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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    protected $prenom;
    
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
     * @ORM\Column(name="date_naissance", type="datetime", nullable=true)
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
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\question", mappedBy="membre")
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\reponse", mappedBy="membre")
     */
    protected $reponses;

        /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\noteQuestion", mappedBy="membre")
     */
    protected $noteQuestions;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\noteReponse", mappedBy="membre")
     */
    protected $noteReponses;

    /**
     *
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\appareil", inversedBy="membres")
     *
     */
    private $appareils;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->noteQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->noteReponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->appareils = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return membre
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
     * Set prenom
     *
     * @param string $prenom
     * @return membre
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
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
     * @param string $sexe
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
     * @return string 
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

    /**
     * Add questions
     *
     * @param \SmartUnity\AppBundle\Entity\question $questions
     * @return membre
     */
    public function addQuestion(\SmartUnity\AppBundle\Entity\question $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \SmartUnity\AppBundle\Entity\question $questions
     */
    public function removeQuestion(\SmartUnity\AppBundle\Entity\question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add reponses
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponses
     * @return membre
     */
    public function addReponse(\SmartUnity\AppBundle\Entity\reponse $reponses)
    {
        $this->reponses[] = $reponses;
    
        return $this;
    }

    /**
     * Remove reponses
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponses
     */
    public function removeReponse(\SmartUnity\AppBundle\Entity\reponse $reponses)
    {
        $this->reponses->removeElement($reponses);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    /**
     * Add noteQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\noteQuestion $noteQuestions
     * @return membre
     */
    public function addNoteQuestion(\SmartUnity\AppBundle\Entity\noteQuestion $noteQuestions)
    {
        $this->noteQuestions[] = $noteQuestions;
    
        return $this;
    }

    /**
     * Remove noteQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\noteQuestion $noteQuestions
     */
    public function removeNoteQuestion(\SmartUnity\AppBundle\Entity\noteQuestion $noteQuestions)
    {
        $this->noteQuestions->removeElement($noteQuestions);
    }

    /**
     * Get noteQuestions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNoteQuestions()
    {
        return $this->noteQuestions;
    }

    /**
     * Add noteReponses
     *
     * @param \SmartUnity\AppBundle\Entity\noteReponse $noteReponses
     * @return membre
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
     * Add appareils
     *
     * @param \SmartUnity\AppBundle\Entity\appareil $appareils
     * @return membre
     */
    public function addAppareil(\SmartUnity\AppBundle\Entity\appareil $appareils)
    {
        $this->appareils[] = $appareils;
    
        return $this;
    }

    /**
     * Remove appareils
     *
     * @param \SmartUnity\AppBundle\Entity\appareil $appareils
     */
    public function removeAppareil(\SmartUnity\AppBundle\Entity\appareil $appareils)
    {
        $this->appareils->removeElement($appareils);
    }

    /**
     * Get appareils
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAppareils()
    {
        return $this->appareils;
    }
}