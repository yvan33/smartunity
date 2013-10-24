<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="os")
 */
class Os
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $os_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $os_nom;

    /**
     * Get os_id
     *
     * @return integer 
     */
    public function getOsId()
    {
        return $this->os_id;
    }

    /**
     * Set os_nom
     *
     * @param string $osNom
     * @return Os
     */
    public function setOsNom($osNom)
    {
        $this->os_nom = $osNom;

        return $this;
    }

    /**
     * Get os_nom
     *
     * @return string 
     */
    public function getOsNom()
    {
        return $this->os_nom;
    }
}
