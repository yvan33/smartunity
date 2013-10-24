<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="preference")
 */
class Preference
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $preference_id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $preference_mp;

	/**
	 * @ORM\Column(type="boolean")
	 */
	
	protected $preference_smartcafe;
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $preference_comm;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $preference_rep;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $preference_rep_validee;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $preference_rep_certifiee;

    /**
     * Get preference_id
     *
     * @return integer 
     */
    public function getPreferenceId()
    {
        return $this->preference_id;
    }

    /**
     * Set preference_mp
     *
     * @param boolean $preferenceMp
     * @return Preference
     */
    public function setPreferenceMp($preferenceMp)
    {
        $this->preference_mp = $preferenceMp;

        return $this;
    }

    /**
     * Get preference_mp
     *
     * @return boolean 
     */
    public function getPreferenceMp()
    {
        return $this->preference_mp;
    }

    /**
     * Set preference_smartcafe
     *
     * @param boolean $preferenceSmartcafe
     * @return Preference
     */
    public function setPreferenceSmartcafe($preferenceSmartcafe)
    {
        $this->preference_smartcafe = $preferenceSmartcafe;

        return $this;
    }

    /**
     * Get preference_smartcafe
     *
     * @return boolean 
     */
    public function getPreferenceSmartcafe()
    {
        return $this->preference_smartcafe;
    }

    /**
     * Set preference_comm
     *
     * @param boolean $preferenceComm
     * @return Preference
     */
    public function setPreferenceComm($preferenceComm)
    {
        $this->preference_comm = $preferenceComm;

        return $this;
    }

    /**
     * Get preference_comm
     *
     * @return boolean 
     */
    public function getPreferenceComm()
    {
        return $this->preference_comm;
    }

    /**
     * Set preference_rep
     *
     * @param boolean $preferenceRep
     * @return Preference
     */
    public function setPreferenceRep($preferenceRep)
    {
        $this->preference_rep = $preferenceRep;

        return $this;
    }

    /**
     * Get preference_rep
     *
     * @return boolean 
     */
    public function getPreferenceRep()
    {
        return $this->preference_rep;
    }

    /**
     * Set preference_rep_validee
     *
     * @param boolean $preferenceRepValidee
     * @return Preference
     */
    public function setPreferenceRepValidee($preferenceRepValidee)
    {
        $this->preference_rep_validee = $preferenceRepValidee;

        return $this;
    }

    /**
     * Get preference_rep_validee
     *
     * @return boolean 
     */
    public function getPreferenceRepValidee()
    {
        return $this->preference_rep_validee;
    }

    /**
     * Set preference_rep_certifiee
     *
     * @param boolean $preferenceRepCertifiee
     * @return Preference
     */
    public function setPreferenceRepCertifiee($preferenceRepCertifiee)
    {
        $this->preference_rep_certifiee = $preferenceRepCertifiee;

        return $this;
    }

    /**
     * Get preference_rep_certifiee
     *
     * @return boolean 
     */
    public function getPreferenceRepCertifiee()
    {
        return $this->preference_rep_certifiee;
    }
}
