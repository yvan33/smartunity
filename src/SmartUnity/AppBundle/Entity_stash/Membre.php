<?php

namespace SmartUnity\AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="membre")
 */
class Membre extends BaseUser
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $membre_id;

   /**
	 * @ORM\Column(type="string", length=100)
	 */
protected $membre_nom;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	protected $membre_prenom;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $membre_cagnotte;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $membre_reputation;
	/**
	 * @ORM\Column(type="text")
	 */
	protected $membre_avatar;
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $membre_date;
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $membre_sexe;
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $membre_date_inscription;
	/**
	 * @ORM\ManyToOne(targetEntity="Role")
	 * @ORM\JoinColumn(name="membre_role_id", referencedColumnName="role_id")
	 */
	protected $membre_role_id;
	/**
	 * @ORM\ManyToOne(targetEntity="Preference")
	 * @ORM\JoinColumn(name="membre_pref_id", referencedColumnName="preference_id")
	 */
	protected $membre_pref_id;
	/**
	 * @ORM\ManyToOne(targetEntity="Ville")
	 * @ORM\JoinColumn(name="membre_ville_id", referencedColumnName="ville_id")
	 */
	protected $membre_ville_id;
	/**
     * @ORM\ManyToMany(targetEntity="Modele")
     * @ORM\JoinTable(name="membres_modeles",
     *      joinColumns={@ORM\JoinColumn(name="membre_id", referencedColumnName="membre_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="modele_id", referencedColumnName="modele_id")}
     *      )
     */
	 protected $membre_modeles_id;

	
    /**
     * Get membre_id
     *
     * @return integer 
     */
    public function getMembreId()
    {
        return $this->membre_id;
    }

    /**
     * Set membre_nom
     *
     * @param string $membreNom
     * @return Membre
     */
    public function setMembreNom($membreNom)
    {
        $this->membre_nom = $membreNom;
    
        return $this;
    }

    /**
     * Get membre_nom
     *
     * @return string 
     */
    public function getMembreNom()
    {
        return $this->membre_nom;
    }

    /**
     * Set membre_prenom
     *
     * @param string $membrePrenom
     * @return Membre
     */
    public function setMembrePrenom($membrePrenom)
    {
        $this->membre_prenom = $membrePrenom;
    
        return $this;
    }

    /**
     * Get membre_prenom
     *
     * @return string 
     */
    public function getMembrePrenom()
    {
        return $this->membre_prenom;
    }

    /**
     * Set membre_cagnotte
     *
     * @param integer $membreCagnotte
     * @return Membre
     */
    public function setMembreCagnotte($membreCagnotte)
    {
        $this->membre_cagnotte = $membreCagnotte;
    
        return $this;
    }

    /**
     * Get membre_cagnotte
     *
     * @return integer 
     */
    public function getMembreCagnotte()
    {
        return $this->membre_cagnotte;
    }

    /**
     * Set membre_reputation
     *
     * @param integer $membreReputation
     * @return Membre
     */
    public function setMembreReputation($membreReputation)
    {
        $this->membre_reputation = $membreReputation;
    
        return $this;
    }

    /**
     * Get membre_reputation
     *
     * @return integer 
     */
    public function getMembreReputation()
    {
        return $this->membre_reputation;
    }

    /**
     * Set membre_avatar
     *
     * @param string $membreAvatar
     * @return Membre
     */
    public function setMembreAvatar($membreAvatar)
    {
        $this->membre_avatar = $membreAvatar;
    
        return $this;
    }

    /**
     * Get membre_avatar
     *
     * @return string 
     */
    public function getMembreAvatar()
    {
        return $this->membre_avatar;
    }

    /**
     * Set membre_date
     *
     * @param \DateTime $membreDate
     * @return Membre
     */
    public function setMembreDate($membreDate)
    {
        $this->membre_date = $membreDate;
    
        return $this;
    }

    /**
     * Get membre_date
     *
     * @return \DateTime 
     */
    public function getMembreDate()
    {
        return $this->membre_date;
    }

    /**
     * Set membre_sexe
     *
     * @param boolean $membreSexe
     * @return Membre
     */
    public function setMembreSexe($membreSexe)
    {
        $this->membre_sexe = $membreSexe;
    
        return $this;
    }

    /**
     * Get membre_sexe
     *
     * @return boolean 
     */
    public function getMembreSexe()
    {
        return $this->membre_sexe;
    }

    /**
     * Set membre_date_inscription
     *
     * @param \DateTime $membreDateInscription
     * @return Membre
     */
    public function setMembreDateInscription($membreDateInscription)
    {
        $this->membre_date_inscription = $membreDateInscription;
    
        return $this;
    }

    /**
     * Get membre_date_inscription
     *
     * @return \DateTime 
     */
    public function getMembreDateInscription()
    {
        return $this->membre_date_inscription;
    }

    /**
     * Set membre_role_id
     *
     * @param \SmartUnity\AppBundle\Entity\Role $membreRoleId
     * @return Membre
     */
    public function setMembreRoleId(\SmartUnity\AppBundle\Entity\Role $membreRoleId = null)
    {
        $this->membre_role_id = $membreRoleId;
    
        return $this;
    }

    /**
     * Get membre_role_id
     *
     * @return \SmartUnity\AppBundle\Entity\Role 
     */
    public function getMembreRoleId()
    {
        return $this->membre_role_id;
    }
}