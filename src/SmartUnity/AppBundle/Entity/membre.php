<?php

namespace SmartUnity\AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\membreRepository")
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
    private $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=12, nullable=true)
     */
    private $telephone;

    /**
     * @var integer
     *
     * @ORM\Column(name="cagnotte", type="integer")
     */
    private $cagnotte=0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="reputation", type="integer")
     */
    private $reputation=0;
 
    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=2)
     */
    private $sexe;    
    
    /**
     * @var date
     *
     * @ORM\Column(name="date_naissance", type="datetime", nullable=true)
     */
    private $date_naissance; 

        /**
     * @var date
     *
     * @ORM\Column(name="date_inscription", type="datetime")
     */
    private $date_inscription; 
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_mp", type="boolean")
     */
    private $pref_mp;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_smartcafe", type="boolean")
     */
    private $pref_smartcafe;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_comm", type="boolean")
     */
    private $pref_comm;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_rep", type="boolean")
     */
    private $pref_rep;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_repValidee", type="boolean")
     */
    private $pref_repValidee;    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="pref_repCertifiee", type="boolean")
     */
    private $pref_repCertifiee;    


    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\question", mappedBy="membre")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\reponse", mappedBy="membre")
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\noteReponse", mappedBy="membre")
     */
    private $noteReponses;

    /**
     *
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\appareil", inversedBy="membres")
     *
     */
    private $appareils;

    /**
     *
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\reponse", mappedBy="membreCertif")
     *
     */
    private $reponsesCertifiees;

    /**
     *
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\question", mappedBy="soutienMembres")
     * @ORM\JoinTable(name="soutien")
     */
    private $soutienQuestions;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SmartUnity\AppBundle\Entity\ville", inversedBy="membres")
     * @ORM\JoinColumn(name="ville_id", referencedColumnName="id")
     */
    private $ville; 

    /**
     *
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\commentaireReponse", mappedBy="membre")
     *
     */
    private $commentaireReponses;
    
     /**
     *
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\commentaireQuestion", mappedBy="membre")
     *
     */
    private $commentaireQuestions;


    /**
     *
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\parrainage", mappedBy="membre")
     *
     */
    private $parrainages;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->noteReponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->appareils = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reponsesCertifiees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soutienQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaireReponses = new \Doctrine\Common\Collections\ArrayCollection();
        $date = new \DateTime('now');
        $this->setDateInscription($date);
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
     * Set adresse
     *
     * @param string $adresse
     * @return membre
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return membre
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
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

    /**
     * Add reponsesCertifiees
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponsesCertifiees
     * @return membre
     */
    public function addReponsesCertifiee(\SmartUnity\AppBundle\Entity\reponse $reponsesCertifiees)
    {
        $this->reponsesCertifiees[] = $reponsesCertifiees;
    
        return $this;
    }

    /**
     * Remove reponsesCertifiees
     *
     * @param \SmartUnity\AppBundle\Entity\reponse $reponsesCertifiees
     */
    public function removeReponsesCertifiee(\SmartUnity\AppBundle\Entity\reponse $reponsesCertifiees)
    {
        $this->reponsesCertifiees->removeElement($reponsesCertifiees);
    }

    /**
     * Get reponsesCertifiees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponsesCertifiees()
    {
        return $this->reponsesCertifiees;
    }

    /**
     * Add soutienQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\question $soutienQuestions
     * @return membre
     */
    public function addSoutienQuestion(\SmartUnity\AppBundle\Entity\question $soutienQuestions)
    {
        $this->soutienQuestions[] = $soutienQuestions;
    
        return $this;
    }

    /**
     * Remove soutienQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\question $soutienQuestions
     */
    public function removeSoutienQuestion(\SmartUnity\AppBundle\Entity\question $soutienQuestions)
    {
        $this->soutienQuestions->removeElement($soutienQuestions);
    }

    /**
     * Get soutienQuestions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoutienQuestions()
    {
        return $this->soutienQuestions;
    }

    /**
     * Set ville
     *
     * @param \SmartUnity\AppBundle\Entity\ville $ville
     * @return membre
     */
    public function setVille(\SmartUnity\AppBundle\Entity\ville $ville = null)
    {
        $this->ville = $ville;
    
        return $this;
    }

    /**
     * Get ville
     *
     * @return \SmartUnity\AppBundle\Entity\ville 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Add commentaireReponses
     *
     * @param \SmartUnity\AppBundle\Entity\commentaireReponse $commentaireReponses
     * @return membre
     */
    public function addCommentaireReponse(\SmartUnity\AppBundle\Entity\commentaireReponse $commentaireReponses)
    {
        $this->commentaireReponses[] = $commentaireReponses;
    
        return $this;
    }

    /**
     * Remove commentaireReponses
     *
     * @param \SmartUnity\AppBundle\Entity\commentaireReponse $commentaireReponses
     */
    public function removeCommentaireReponse(\SmartUnity\AppBundle\Entity\commentaireReponse $commentaireReponses)
    {
        $this->commentaireReponses->removeElement($commentaireReponses);
    }

    /**
     * Get commentaireReponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaireReponses()
    {
        return $this->commentaireReponses;
    }

    /**
     * Set date_inscription
     *
     * @param \DateTime $dateInscription
     * @return membre
     */
    public function setDateInscription($dateInscription)
    {
        $this->date_inscription = $dateInscription;
        return $this;
    }

     * Add commentaireQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\commentaireQuestion $commentaireQuestions
     * @return membre
     */
    public function addCommentaireQuestion(\SmartUnity\AppBundle\Entity\commentaireQuestion $commentaireQuestions)
    {
        $this->commentareQuestions[] = $commentaireQuestions;
    
        return $this;
    }

    /**
     * Get date_inscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

     * Remove commentaireQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\commentaireQuestion $commentaireQuestions
     */
    public function removeCommentaireQuestion(\SmartUnity\AppBundle\Entity\commentaireQuestion $commentaireQuestions)
    {
        $this->commentaireQuestions->removeElement($commentaireQuestions);
    }

    /**
     * Get commentaireQuestions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaireQuestions()
    {
        return $this->commentaireQuestions;

    }
}