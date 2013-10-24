<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="soutien")
 */
class Soutien
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Question")
	 * @ORM\JoinColumn(name="soutien_question_id", referencedColumnName="question_id")
	 */
	protected $soutien_question_id;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Membre")
	 * @ORM\JoinColumn(name="soutien_membre_id", referencedColumnName="membre_id")
	 */
	protected $soutien_membre_id;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $soutien_valeur;		

    /**
     * Set soutien_valeur
     *
     * @param integer $soutienValeur
     * @return Soutien
     */
    public function setSoutienValeur($soutienValeur)
    {
        $this->soutien_valeur = $soutienValeur;

        return $this;
    }

    /**
     * Get soutien_valeur
     *
     * @return integer 
     */
    public function getSoutienValeur()
    {
        return $this->soutien_valeur;
    }

    /**
     * Set soutien_question_id
     *
     * @param \smartunity\ModeleBundle\Entity\Question $soutienQuestionId
     * @return Soutien
     */
    public function setSoutienQuestionId(\smartunity\ModeleBundle\Entity\Question $soutienQuestionId)
    {
        $this->soutien_question_id = $soutienQuestionId;

        return $this;
    }

    /**
     * Get soutien_question_id
     *
     * @return \smartunity\ModeleBundle\Entity\Question 
     */
    public function getSoutienQuestionId()
    {
        return $this->soutien_question_id;
    }

    /**
     * Set soutien_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $soutienMembreId
     * @return Soutien
     */
    public function setSoutienMembreId(\smartunity\ModeleBundle\Entity\Membre $soutienMembreId)
    {
        $this->soutien_membre_id = $soutienMembreId;

        return $this;
    }

    /**
     * Get soutien_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getSoutienMembreId()
    {
        return $this->soutien_membre_id;
    }
}
