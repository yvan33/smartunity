<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="question")
 */
class Question
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $question_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $question_sujet;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $question_description;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $question_date;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $question_dotation;

	/**
	 * @ORM\ManyToONe(targetEntity="Membre")
	 * @ORM\JoinColumn(name="question_membre_id", referencedColumnName="membre_id")
	 */
	protected $question_membre_id;

	/**
	 * @ORM\ManyToOne(targetEntity="TypeQuestion")
	 * @ORM\JoinColumn(name="question_type_question_id", referencedColumnName="type_question_id")
	 */
	protected $question_type_question_id;
	/**
	 * @ORM\ManyToOne(targetEntity="Statut")
	 * @ORM\JoinColumn(name="question_statut_id", referencedColumnName="statut_id")
	 */
	protected $question_statut_id;

	 /**
     * @ORM\ManyToMany(targetEntity="Modele")
     * @ORM\JoinTable(name="question_modeles",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="question_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="modele_id", referencedColumnName="modele_id")}
     *      )
     **/
	protected $question_modeles_concernes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->question_modeles_concernes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get question_id
     *
     * @return integer 
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * Set question_sujet
     *
     * @param string $questionSujet
     * @return Question
     */
    public function setQuestionSujet($questionSujet)
    {
        $this->question_sujet = $questionSujet;

        return $this;
    }

    /**
     * Get question_sujet
     *
     * @return string 
     */
    public function getQuestionSujet()
    {
        return $this->question_sujet;
    }

    /**
     * Set question_description
     *
     * @param string $questionDescription
     * @return Question
     */
    public function setQuestionDescription($questionDescription)
    {
        $this->question_description = $questionDescription;

        return $this;
    }

    /**
     * Get question_description
     *
     * @return string 
     */
    public function getQuestionDescription()
    {
        return $this->question_description;
    }

    /**
     * Set question_date
     *
     * @param \DateTime $questionDate
     * @return Question
     */
    public function setQuestionDate($questionDate)
    {
        $this->question_date = $questionDate;

        return $this;
    }

    /**
     * Get question_date
     *
     * @return \DateTime 
     */
    public function getQuestionDate()
    {
        return $this->question_date;
    }

    /**
     * Set question_dotation
     *
     * @param integer $questionDotation
     * @return Question
     */
    public function setQuestionDotation($questionDotation)
    {
        $this->question_dotation = $questionDotation;

        return $this;
    }

    /**
     * Get question_dotation
     *
     * @return integer 
     */
    public function getQuestionDotation()
    {
        return $this->question_dotation;
    }

    /**
     * Set question_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $questionMembreId
     * @return Question
     */
    public function setQuestionMembreId(\smartunity\ModeleBundle\Entity\Membre $questionMembreId = null)
    {
        $this->question_membre_id = $questionMembreId;

        return $this;
    }

    /**
     * Get question_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getQuestionMembreId()
    {
        return $this->question_membre_id;
    }

    /**
     * Set question_type_question_id
     *
     * @param \smartunity\ModeleBundle\Entity\TypeQuestion $questionTypeQuestionId
     * @return Question
     */
    public function setQuestionTypeQuestionId(\smartunity\ModeleBundle\Entity\TypeQuestion $questionTypeQuestionId = null)
    {
        $this->question_type_question_id = $questionTypeQuestionId;

        return $this;
    }

    /**
     * Get question_type_question_id
     *
     * @return \smartunity\ModeleBundle\Entity\TypeQuestion 
     */
    public function getQuestionTypeQuestionId()
    {
        return $this->question_type_question_id;
    }

    /**
     * Set question_statut_id
     *
     * @param \smartunity\ModeleBundle\Entity\Statut $questionStatutId
     * @return Question
     */
    public function setQuestionStatutId(\smartunity\ModeleBundle\Entity\Statut $questionStatutId = null)
    {
        $this->question_statut_id = $questionStatutId;

        return $this;
    }

    /**
     * Get question_statut_id
     *
     * @return \smartunity\ModeleBundle\Entity\Statut 
     */
    public function getQuestionStatutId()
    {
        return $this->question_statut_id;
    }

    /**
     * Add question_modeles_concernes
     *
     * @param \smartunity\ModeleBundle\Entity\Modele $questionModelesConcernes
     * @return Question
     */
    public function addQuestionModelesConcerne(\smartunity\ModeleBundle\Entity\Modele $questionModelesConcernes)
    {
        $this->question_modeles_concernes[] = $questionModelesConcernes;

        return $this;
    }

    /**
     * Remove question_modeles_concernes
     *
     * @param \smartunity\ModeleBundle\Entity\Modele $questionModelesConcernes
     */
    public function removeQuestionModelesConcerne(\smartunity\ModeleBundle\Entity\Modele $questionModelesConcernes)
    {
        $this->question_modeles_concernes->removeElement($questionModelesConcernes);
    }

    /**
     * Get question_modeles_concernes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionModelesConcernes()
    {
        return $this->question_modeles_concernes;
    }
}
