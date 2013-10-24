<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $role_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $role_nom;

    /**
     * Get role_id
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Set role_nom
     *
     * @param string $roleNom
     * @return Role
     */
    public function setRoleNom($roleNom)
    {
        $this->role_nom = $roleNom;

        return $this;
    }

    /**
     * Get role_nom
     *
     * @return string 
     */
    public function getRoleNom()
    {
        return $this->role_nom;
    }
}
