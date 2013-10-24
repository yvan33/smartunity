<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="produit")
 */
class Produit
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $produit_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $produit_nom;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $produit_description;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $produit_qte_stock;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $produit_certifie_date;	


    /**
     * Get produit_id
     *
     * @return integer 
     */
    public function getProduitId()
    {
        return $this->produit_id;
    }

    /**
     * Set produit_nom
     *
     * @param string $produitNom
     * @return Produit
     */
    public function setProduitNom($produitNom)
    {
        $this->produit_nom = $produitNom;

        return $this;
    }

    /**
     * Get produit_nom
     *
     * @return string 
     */
    public function getProduitNom()
    {
        return $this->produit_nom;
    }

    /**
     * Set produit_description
     *
     * @param string $produitDescription
     * @return Produit
     */
    public function setProduitDescription($produitDescription)
    {
        $this->produit_description = $produitDescription;

        return $this;
    }

    /**
     * Get produit_description
     *
     * @return string 
     */
    public function getProduitDescription()
    {
        return $this->produit_description;
    }

    /**
     * Set produit_qte_stock
     *
     * @param integer $produitQteStock
     * @return Produit
     */
    public function setProduitQteStock($produitQteStock)
    {
        $this->produit_qte_stock = $produitQteStock;

        return $this;
    }

    /**
     * Get produit_qte_stock
     *
     * @return integer 
     */
    public function getProduitQteStock()
    {
        return $this->produit_qte_stock;
    }

    /**
     * Set produit_certifie_date
     *
     * @param \DateTime $produitCertifieDate
     * @return Produit
     */
    public function setProduitCertifieDate($produitCertifieDate)
    {
        $this->produit_certifie_date = $produitCertifieDate;

        return $this;
    }

    /**
     * Get produit_certifie_date
     *
     * @return \DateTime 
     */
    public function getProduitCertifieDate()
    {
        return $this->produit_certifie_date;
    }
}
