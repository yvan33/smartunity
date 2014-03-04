<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Membre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\membreRepository")
 * @UniqueEntity(fields="email", message="Cet adresse email est déjà utilisée par un autre utilisateur")
 * @UniqueEntity(fields="username", message="Ce pseudo est déjà utilisé par un autre utilisateur")
 */
class membre extends BaseUser {

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
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    protected $facebookId;

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
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var integer
     *
     * @ORM\Column(name="cagnotte", type="integer")
     */
    private $cagnotte = 50;

    /**
     * @var integer
     *
     * @ORM\Column(name="reputation", type="integer")
     */
    private $reputation = 0;

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
     * @var string
     *
     * @ORM\Column(name="info_plus", type="text", nullable=true)
     */
    private $info_plus;
    /**
     * @ORM\OneToMany(targetEntity="question", mappedBy="membre")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="reponse", mappedBy="membre")
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity="noteReponse", mappedBy="membre")
     */
    private $noteReponses;

    /**
     *
     * @ORM\ManyToMany(targetEntity="appareil", inversedBy="membres")
     *
     */
    private $appareils;

    /**
     *
     * @ORM\OneToMany(targetEntity="reponse", mappedBy="membreCertif")
     *
     */
    private $reponsesCertifiees;

    /**
     *
     * @ORM\ManyToMany(targetEntity="question", mappedBy="soutienMembres")
     * @ORM\JoinTable(name="soutien")
     */
    private $soutienQuestions;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ville", inversedBy="membres")
     * @ORM\JoinColumn(name="ville_id", referencedColumnName="id")
     */
    private $ville;

    /**
     *
     * @ORM\OneToMany(targetEntity="commentaireReponse", mappedBy="membre")
     *
     */
    private $commentaireReponses;

    /**
     *
     * @ORM\OneToMany(targetEntity="commentaireQuestion", mappedBy="membre")
     *
     */
    private $commentaireQuestions;

    /**
     *
     * @ORM\OneToMany(targetEntity="parrainage", mappedBy="membre")
     *
     */
    private $parrainages;

    /**
     *
     * @ORM\OneToMany(targetEntity="membre", mappedBy="parrain")
     *
     * */
    private $filleuls;

    /**
     *
     * @ORM\ManyToOne(targetEntity="membre", inversedBy="filleuls")
     * @ORM\JoinColumn(name="parrain_id", referencedColumnName="id")
     *
     * */
    private $parrain;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_inscription", type="string", length=50, nullable=true)
     */
    private $ip_inscription;
    
    /** @var string
     *
     * @ORM\Column(name="ip_confirmation", type="string", length=50, nullable=true)
     */
    private $ip_confirmation;

    /**	
     * @ORM\ManyToMany(targetEntity="SmartUnity\AppBundle\Entity\gift", inversedBy="membres")
     *
     */
    private $gifts;    

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->noteReponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->appareils = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reponsesCertifiees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soutienQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaireReponses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filleuls = new \Doctrine\Common\Collections\ArrayCollection();
        $date = new \DateTime('now');
        $this->setDateInscription($date);
        $this->setPrefMp(1);
        $this->setPrefSmartcafe(1);
        $this->setPrefComm(1);
        $this->setPrefRepValidee(1);
        $this->setPrefRepCertifiee(1);
        $this->setPrefRep(1);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return membre
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return membre
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return membre
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return membre
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * Set cagnotte
     *
     * @param integer $cagnotte
     * @return membre
     */
    public function setCagnotte($cagnotte) {
        $this->cagnotte = $cagnotte;

        return $this;
    }

    /**
     * Get cagnotte
     *
     * @return integer 
     */
    public function getCagnotte() {
        return $this->cagnotte;
    }

    /**
     * Set reputation
     *
     * @param integer $reputation
     * @return membre
     */
    public function setReputation($reputation) {
        $this->reputation = $reputation;

        return $this;
    }

    /**
     * Get reputation
     *
     * @return integer 
     */
    public function getReputation() {
        return $this->reputation;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return membre
     */
    public function setSexe($sexe) {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe() {
        return $this->sexe;
    }

    /**
     * Set date_naissance
     *
     * @param \DateTime $dateNaissance
     * @return membre
     */
    public function setDateNaissance($dateNaissance) {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get date_naissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance() {
        return $this->date_naissance;
    }

    /**
     * Set pref_mp
     *
     * @param boolean $prefMp
     * @return membre
     */
    public function setPrefMp($prefMp) {
        $this->pref_mp = $prefMp;

        return $this;
    }

    /**
     * Get pref_mp
     *
     * @return boolean 
     */
    public function getPrefMp() {
        return $this->pref_mp;
    }

    /**
     * Set pref_smartcafe
     *
     * @param boolean $prefSmartcafe
     * @return membre
     */
    public function setPrefSmartcafe($prefSmartcafe) {
        $this->pref_smartcafe = $prefSmartcafe;

        return $this;
    }

    /**
     * Get pref_smartcafe
     *
     * @return boolean 
     */
    public function getPrefSmartcafe() {
        return $this->pref_smartcafe;
    }

    /**
     * Set pref_comm
     *
     * @param boolean $prefComm
     * @return membre
     */
    public function setPrefComm($prefComm) {
        $this->pref_comm = $prefComm;

        return $this;
    }

    /**
     * Get pref_comm
     *
     * @return boolean 
     */
    public function getPrefComm() {
        return $this->pref_comm;
    }

    /**
     * Set pref_rep
     *
     * @param boolean $prefRep
     * @return membre
     */
    public function setPrefRep($prefRep) {
        $this->pref_rep = $prefRep;

        return $this;
    }

    /**
     * Get pref_rep
     *
     * @return boolean 
     */
    public function getPrefRep() {
        return $this->pref_rep;
    }

    /**
     * Set pref_repValidee
     *
     * @param boolean $prefRepValidee
     * @return membre
     */
    public function setPrefRepValidee($prefRepValidee) {
        $this->pref_repValidee = $prefRepValidee;

        return $this;
    }

    /**
     * Get pref_repValidee
     *
     * @return boolean 
     */
    public function getPrefRepValidee() {
        return $this->pref_repValidee;
    }

    /**
     * Set pref_repCertifiee
     *
     * @param boolean $prefRepCertifiee
     * @return membre
     */
    public function setPrefRepCertifiee($prefRepCertifiee) {
        $this->pref_repCertifiee = $prefRepCertifiee;

        return $this;
    }

    /**
     * Get pref_repCertifiee
     *
     * @return boolean 
     */
    public function getPrefRepCertifiee() {
        return $this->pref_repCertifiee;
    }

    /**
     * Add questions
     *
     * @param question $questions
     * @return membre
     */
    public function addQuestion(question $questions) {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param question $questions
     */
    public function removeQuestion(question $questions) {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions() {
        return $this->questions;
    }

    /**
     * Add reponses
     *
     * @param reponse $reponses
     * @return membre
     */
    public function addReponse(reponse $reponses) {
        $this->reponses[] = $reponses;

        return $this;
    }

    /**
     * Remove reponses
     *
     * @param reponse $reponses
     */
    public function removeReponse(reponse $reponses) {
        $this->reponses->removeElement($reponses);
    }

    /**
     * Get reponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponses() {
        return $this->reponses;
    }

    /**
     * Add noteReponses
     *
     * @param noteReponse $noteReponses
     * @return membre
     */
    public function addNoteReponse(noteReponse $noteReponses) {
        $this->noteReponses[] = $noteReponses;

        return $this;
    }

    /**
     * Remove noteReponses
     *
     * @param noteReponse $noteReponses
     */
    public function removeNoteReponse(noteReponse $noteReponses) {
        $this->noteReponses->removeElement($noteReponses);
    }

    /**
     * Get noteReponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNoteReponses() {
        return $this->noteReponses;
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
//        if ($this->username != $facebookId && $this->username != null){
//        }
//        else{
//                 $this->setUsername($facebookId);
//        }
        $this->salt = '';
    }

    /**
     * @return string
     */
    public function getFacebookId() {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata) { // C'est dans cette méthode que vous ajouterez vos informations
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
//            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['username'])) {
            $this->setUsername($fbdata['username']);
        }
        if (isset($fbdata['first_name'])) {
            $this->setPrenom($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setNom($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
        if (isset($fbdata['gender'])) {
            if($fbdata['gender'] == 'male'){
            $this->setSexe('m');                
            }
            elseif($fbdata['gender'] == 'female'){
            $this->setSexe('f');                
            }
            else {
            $this->setSexe('na');  
            }
        }
        $this->setPrefComm('1');
        $this->setPrefMp('1');
        $this->setPrefRep('1');
        $this->setPrefRepValidee('1');
        $this->setPrefSmartcafe('1');
        $this->setPrefRepCertifiee('1');
        
        if (isset($fbdata['birthday'])) {
           $this->setDateNaissance(new \DateTime($fbdata['birthday']));
        }   

    }

    /**
     * Add appareils
     *
     * @param appareil $appareils
     * @return membre
     */
    public function addAppareil(appareil $appareils) {
        $this->appareils[] = $appareils;

        return $this;
    }

    /**
     * Remove appareils
     *
     * @param appareil $appareils
     */
    public function removeAppareil(appareil $appareils) {
        $this->appareils->removeElement($appareils);
    }

    /**
     * Get appareils
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAppareils() {
        return $this->appareils;
    }

    /**
     * Add reponsesCertifiees
     *
     * @param reponse $reponsesCertifiees
     * @return membre
     */
    public function addReponsesCertifiee(reponse $reponsesCertifiees) {
        $this->reponsesCertifiees[] = $reponsesCertifiees;

        return $this;
    }

    /**
     * Remove reponsesCertifiees
     *
     * @param reponse $reponsesCertifiees
     */
    public function removeReponsesCertifiee(reponse $reponsesCertifiees) {
        $this->reponsesCertifiees->removeElement($reponsesCertifiees);
    }

    /**
     * Get reponsesCertifiees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReponsesCertifiees() {
        return $this->reponsesCertifiees;
    }

    /**
     * Add soutienQuestions
     *
     * @param question $soutienQuestions
     * @return membre
     */
    public function addSoutienQuestion(question $soutienQuestions) {
        $this->soutienQuestions[] = $soutienQuestions;
        return $this;
    }

    /**
     * Remove soutienQuestions
     *
     * @param question $soutienQuestions
     */
    public function removeSoutienQuestion(question $soutienQuestions) {
        $this->soutienQuestions->removeElement($soutienQuestions);
    }

    /**
     * Get soutienQuestions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoutienQuestions() {
        return $this->soutienQuestions;
    }

    /**
     * Set ville
     *
     * @param ville $ville
     * @return membre
     */
    public function setVille(ville $ville = null) {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return ville 
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * Add commentaireReponses
     *
     * @param commentaireReponse $commentaireReponses
     * @return membre
     */
    public function addCommentaireReponse(commentaireReponse $commentaireReponses) {
        $this->commentaireReponses[] = $commentaireReponses;

        return $this;
    }

    /**
     * Remove commentaireReponses
     *
     * @param commentaireReponse $commentaireReponses
     */
    public function removeCommentaireReponse(commentaireReponse $commentaireReponses) {
        $this->commentaireReponses->removeElement($commentaireReponses);
    }

    /**
     * Get commentaireReponses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaireReponses() {
        return $this->commentaireReponses;
    }

    /**
     * Set date_inscription
     *
     * @param \DateTime $dateInscription
     * @return membre
     */
    public function setDateInscription($dateInscription) {
        $this->date_inscription = $dateInscription;
        return $this;
    }

    /* Add commentaireQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\commentaireQuestion $commentaireQuestions
     * @return membre
     */

    public function addCommentaireQuestion(commentaireQuestion $commentaireQuestions) {
        $this->commentareQuestions[] = $commentaireQuestions;

        return $this;
    }

    /**
     * Get date_inscription
     *
     * @return \DateTime 
     */
    public function getDateInscription() {
        return $this->date_inscription;
    }

    /* Remove commentaireQuestions
     *
     * @param \SmartUnity\AppBundle\Entity\commentaireQuestion $commentaireQuestions
     */

    public function removeCommentaireQuestion(commentaireQuestion $commentaireQuestions) {
        $this->commentaireQuestions->removeElement($commentaireQuestions);
    }

    /**
     * Get commentaireQuestions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaireQuestions() {
        return $this->commentaireQuestions;
    }

    /**
     * Add parrainages
     *
     * @param parrainage $parrainages
     * @return membre
     */
    public function addParrainage(parrainage $parrainages) {
        $this->parrainages[] = $parrainages;

        return $this;
    }

    /**
     * Remove parrainages
     *
     * @param parrainage $parrainages
     */
    public function removeParrainage(parrainage $parrainages) {
        $this->parrainages->removeElement($parrainages);
    }

    /**
     * Get parrainages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParrainages() {
        return $this->parrainages;
    }

    /**
     * Add filleuls
     *
     * @param membre $filleuls
     * @return membre
     */
    public function addFilleul(membre $filleuls) {
        $this->filleuls[] = $filleuls;

        return $this;
    }

    /**
     * Remove filleuls
     *
     * @param membre $filleuls
     */
    public function removeFilleul(membre $filleuls) {
        $this->filleuls->removeElement($filleuls);
    }

    /**
     * Get filleuls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilleuls() {
        return $this->filleuls;
    }

    /**
     * Set parrain
     *
     * @param membre $parrain
     * @return membre
     */
    public function setParrain(membre $parrain = null) {
        $this->parrain = $parrain;

        return $this;
    }

    /**
     * Get parrain
     *
     * @return \SmartUnity\AppBundle\Entity\menbre 
     */
    public function getParrain() {
        return $this->parrain;
    }


    /**
     * Set info_plus
     *
     * @param string $infoPlus
     * @return membre
     */
    public function setInfoPlus($infoPlus)
    {
        $this->info_plus = $infoPlus;
    
        return $this;
    }

    /**
     * Get info_plus
     *
     * @return string 
     */
    public function getInfoPlus()
    {
        return $this->info_plus;
    }

    /**
     * Set ip_inscription
     *
     * @param string $ipInscription
     * @return membre
     */
    public function setIpInscription($ipInscription)
    {
        $this->ip_inscription = $ipInscription;
    
        return $this;
    }

    /**
     * Get ip_inscription
     *
     * @return string 
     */
    public function getIpInscription()
    {
        return $this->ip_inscription;
    }

    /**
     * Set ip_confirmation
     * @param string $ipConfirmation
     * @return membre
     */
    public function setIpConfirmation($ipConfirmation)
    {
        $this->ip_confirmation = $ipConfirmation;
    
        return $this;
    }

    /**
     * Get ip_confirmation
     *
     * @return string 
     */
    public function getIpConfirmation()
    {
        return $this->ip_confirmation;
    }

    /**
     * Add gifts
     *
     * @param \SmartUnity\AppBundle\Entity\gift $gifts
     * @return membre
     */
    public function addGift(\SmartUnity\AppBundle\Entity\gift $gifts)
    {
        $this->gifts[] = $gifts;
    
        return $this;
    }

    /**
     * Remove gifts
     *
     * @param \SmartUnity\AppBundle\Entity\gift $gifts
     */
    public function removeGift(\SmartUnity\AppBundle\Entity\gift $gifts)
    {
        $this->gifts->removeElement($gifts);
    }

    /**
     * Get gifts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGifts()
    {
        return $this->gifts;
    }
}