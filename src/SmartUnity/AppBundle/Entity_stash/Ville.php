<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ville")
 */
class Ville
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $ville_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $ville_nom;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $ville_cp;

    /**
     * Get ville_id
     *
     * @return integer 
     */
    public function getVilleId()
    {
        return $this->ville_id;
    }

    /**
     * Set ville_nom
     *
     * @param string $villeNom
     * @return Ville
     */
    public function setVilleNom($villeNom)
    {
        $this->ville_nom = $villeNom;

        return $this;
    }

    /**
     * Get ville_nom
     *
     * @return string 
     */
    public function getVilleNom()
    {
        return $this->ville_nom;
    }

    /**
     * Set ville_cp
     *
     * @param string $villeCp
     * @return Ville
     */
    public function setVilleCp($villeCp)
    {
        $this->ville_cp = $villeCp;

        return $this;
    }

    /**
     * Get ville_cp
     *
     * @return string 
     */
    public function getVilleCp()
    {
        return $this->ville_cp;
    }
}
