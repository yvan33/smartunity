<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * marque
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\marqueRepository")
 */
class marque
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="SmartUnity\AppBundle\Entity\modele", mappedBy="marque")
     *
     */
    private $modeles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modeles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return marque
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
     * Add modeles
     *
     * @param \SmartUnity\AppBundle\Entity\modele $modeles
     * @return marque
     */
    public function addModele(\SmartUnity\AppBundle\Entity\modele $modeles)
    {
        $this->modeles[] = $modeles;
    
        return $this;
    }

    /**
     * Remove modeles
     *
     * @param \SmartUnity\AppBundle\Entity\modele $modeles
     */
    public function removeModele(\SmartUnity\AppBundle\Entity\modele $modeles)
    {
        $this->modeles->removeElement($modeles);
    }

    /**
     * Get modeles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModeles()
    {
        return $this->modeles;
    }
}