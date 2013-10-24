<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="marque")
 */
class Marque
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $marque_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $marque_nom;

    /**
     * Get marque_id
     *
     * @return integer 
     */
    public function getMarqueId()
    {
        return $this->marque_id;
    }

    /**
     * Set marque_nom
     *
     * @param string $marqueNom
     * @return Marque
     */
    public function setMarqueNom($marqueNom)
    {
        $this->marque_nom = $marqueNom;

        return $this;
    }

    /**
     * Get marque_nom
     *
     * @return string 
     */
    public function getMarqueNom()
    {
        return $this->marque_nom;
    }
}
