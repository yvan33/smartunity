<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="modele")
 */
class Modele
{
	/**
	 * @ORM\Id
	 * @ORM\Column(name="modele_id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $modele_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $modele_nom;

	/**
	 * @ORM\ManyToOne(targetEntity="Marque")
	 * @ORM\JoinColumn(name="modele_marque_id", referencedColumnName="marque_id")
	 */
	protected $modele_marque_id;

	/**
	 * @ORM\ManyToOne(targetEntity="Os")
	 * @ORM\JoinColumn(name="modele_os_id", referencedColumnName="os_id")
	 */
	protected $modele_os_id;


    /**
     * Get modele_id
     *
     * @return integer 
     */
    public function getModeleId()
    {
        return $this->modele_id;
    }

    /**
     * Set modele_nom
     *
     * @param string $modeleNom
     * @return Modele
     */
    public function setModeleNom($modeleNom)
    {
        $this->modele_nom = $modeleNom;

        return $this;
    }

    /**
     * Get modele_nom
     *
     * @return string 
     */
    public function getModeleNom()
    {
        return $this->modele_nom;
    }

    /**
     * Set modele_marque_id
     *
     * @param \smartunity\ModeleBundle\Entity\Marque $modeleMarqueId
     * @return Modele
     */
    public function setModeleMarqueId(\smartunity\ModeleBundle\Entity\Marque $modeleMarqueId = null)
    {
        $this->modele_marque_id = $modeleMarqueId;

        return $this;
    }

    /**
     * Get modele_marque_id
     *
     * @return \smartunity\ModeleBundle\Entity\Marque 
     */
    public function getModeleMarqueId()
    {
        return $this->modele_marque_id;
    }

    /**
     * Set modele_os_id
     *
     * @param \smartunity\ModeleBundle\Entity\Os $modeleOsId
     * @return Modele
     */
    public function setModeleOsId(\smartunity\ModeleBundle\Entity\Os $modeleOsId = null)
    {
        $this->modele_os_id = $modeleOsId;

        return $this;
    }

    /**
     * Get modele_os_id
     *
     * @return \smartunity\ModeleBundle\Entity\Os 
     */
    public function getModeleOsId()
    {
        return $this->modele_os_id;
    }
}
