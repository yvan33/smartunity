<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reponse")
 */
class Reponse
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $reponse_id;


	/**
	 * @ORM\Column(type="text")
	 */
	protected $reponse_description;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $reponse_date;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $reponse_date_validation;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $reponse_date_certification;

	/**
	 * @ORM\ManyToOne(targetEntity="Membre")
	 * @ORM\JoinColumn(name="reponse_membre_id", referencedColumnName="membre_id")
	 */
	protected $reponse_membre_id;
	/**
	 * @ORM\ManyToOne(targetEntity="Question")
	 * @ORM\JoinColumn(name="reponse_question_id", referencedColumnName="question_id")
	 */
	protected $reponse_question_id;

    /**
     * Get reponse_id
     *
     * @return integer 
     */
    public function getReponseId()
    {
        return $this->reponse_id;
    }

    /**
     * Set reponse_description
     *
     * @param string $reponseDescription
     * @return Reponse
     */
    public function setReponseDescription($reponseDescription)
    {
        $this->reponse_description = $reponseDescription;

        return $this;
    }

    /**
     * Get reponse_description
     *
     * @return string 
     */
    public function getReponseDescription()
    {
        return $this->reponse_description;
    }

    /**
     * Set reponse_date
     *
     * @param \DateTime $reponseDate
     * @return Reponse
     */
    public function setReponseDate($reponseDate)
    {
        $this->reponse_date = $reponseDate;

        return $this;
    }

    /**
     * Get reponse_date
     *
     * @return \DateTime 
     */
    public function getReponseDate()
    {
        return $this->reponse_date;
    }

    /**
     * Set reponse_date_validation
     *
     * @param \DateTime $reponseDateValidation
     * @return Reponse
     */
    public function setReponseDateValidation($reponseDateValidation)
    {
        $this->reponse_date_validation = $reponseDateValidation;

        return $this;
    }

    /**
     * Get reponse_date_validation
     *
     * @return \DateTime 
     */
    public function getReponseDateValidation()
    {
        return $this->reponse_date_validation;
    }

    /**
     * Set reponse_date_certification
     *
     * @param \DateTime $reponseDateCertification
     * @return Reponse
     */
    public function setReponseDateCertification($reponseDateCertification)
    {
        $this->reponse_date_certification = $reponseDateCertification;

        return $this;
    }

    /**
     * Get reponse_date_certification
     *
     * @return \DateTime 
     */
    public function getReponseDateCertification()
    {
        return $this->reponse_date_certification;
    }

    /**
     * Set reponse_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $reponseMembreId
     * @return Reponse
     */
    public function setReponseMembreId(\smartunity\ModeleBundle\Entity\Membre $reponseMembreId = null)
    {
        $this->reponse_membre_id = $reponseMembreId;

        return $this;
    }

    /**
     * Get reponse_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getReponseMembreId()
    {
        return $this->reponse_membre_id;
    }

    /**
     * Set reponse_question_id
     *
     * @param \smartunity\ModeleBundle\Entity\Question $reponseQuestionId
     * @return Reponse
     */
    public function setReponseQuestionId(\smartunity\ModeleBundle\Entity\Question $reponseQuestionId = null)
    {
        $this->reponse_question_id = $reponseQuestionId;

        return $this;
    }

    /**
     * Get reponse_question_id
     *
     * @return \smartunity\ModeleBundle\Entity\Question 
     */
    public function getReponseQuestionId()
    {
        return $this->reponse_question_id;
    }
}
