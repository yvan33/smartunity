<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="commente_reponse")
 */
class CommenteReponse
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Membre")
	 * @ORM\JoinColumn(name="commente_reponse_membre_id", referencedColumnName="membre_id")
	 */
	protected $commente_reponse_membre_id;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Reponse")
	 * @ORM\JoinColumn(name="commente_reponse_reponse_id", referencedColumnName="reponse_id")
	 */
	protected $commente_reponse_reponse_id;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $commente_reponse_commentaire;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $commente_reponse_date;
	

    /**
     * Set commente_reponse_commentaire
     *
     * @param string $commenteReponseCommentaire
     * @return CommenteReponse
     */
    public function setCommenteReponseCommentaire($commenteReponseCommentaire)
    {
        $this->commente_reponse_commentaire = $commenteReponseCommentaire;

        return $this;
    }

    /**
     * Get commente_reponse_commentaire
     *
     * @return string 
     */
    public function getCommenteReponseCommentaire()
    {
        return $this->commente_reponse_commentaire;
    }

    /**
     * Set commente_reponse_date
     *
     * @param \DateTime $commenteReponseDate
     * @return CommenteReponse
     */
    public function setCommenteReponseDate($commenteReponseDate)
    {
        $this->commente_reponse_date = $commenteReponseDate;

        return $this;
    }

    /**
     * Get commente_reponse_date
     *
     * @return \DateTime 
     */
    public function getCommenteReponseDate()
    {
        return $this->commente_reponse_date;
    }

    /**
     * Set commente_reponse_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $commenteReponseMembreId
     * @return CommenteReponse
     */
    public function setCommenteReponseMembreId(\smartunity\ModeleBundle\Entity\Membre $commenteReponseMembreId)
    {
        $this->commente_reponse_membre_id = $commenteReponseMembreId;

        return $this;
    }

    /**
     * Get commente_reponse_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getCommenteReponseMembreId()
    {
        return $this->commente_reponse_membre_id;
    }

    /**
     * Set commente_reponse_reponse_id
     *
     * @param \smartunity\ModeleBundle\Entity\Reponse $commenteReponseReponseId
     * @return CommenteReponse
     */
    public function setCommenteReponseReponseId(\smartunity\ModeleBundle\Entity\Reponse $commenteReponseReponseId)
    {
        $this->commente_reponse_reponse_id = $commenteReponseReponseId;

        return $this;
    }

    /**
     * Get commente_reponse_reponse_id
     *
     * @return \smartunity\ModeleBundle\Entity\Reponse 
     */
    public function getCommenteReponseReponseId()
    {
        return $this->commente_reponse_reponse_id;
    }
}
