<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="statut")
 */
class Statut
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $statut_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $statut_nom;


    /**
     * Get statut_id
     *
     * @return integer 
     */
    public function getStatutId()
    {
        return $this->statut_id;
    }

    /**
     * Set statut_nom
     *
     * @param string $statutNom
     * @return Statut
     */
    public function setStatutNom($statutNom)
    {
        $this->statut_nom = $statutNom;

        return $this;
    }

    /**
     * Get statut_nom
     *
     * @return string 
     */
    public function getStatutNom()
    {
        return $this->statut_nom;
    }
}
