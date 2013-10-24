<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="commente_question")
 */
class CommenteQuestion
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Membre")
	 * @ORM\JoinColumn(name="commente_question_membre_id", referencedColumnName="membre_id")
	 */
	protected $commente_question_membre_id;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Question")
	 * @ORM\JoinColumn(name="commente_question_question_id", referencedColumnName="question_id")
	 */
	protected $commente_question_question_id;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $commente_question_commentaire;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $commente_question_date;
	

    /**
     * Set commente_question_commentaire
     *
     * @param string $commenteQuestionCommentaire
     * @return CommenteQuestion
     */
    public function setCommenteQuestionCommentaire($commenteQuestionCommentaire)
    {
        $this->commente_question_commentaire = $commenteQuestionCommentaire;

        return $this;
    }

    /**
     * Get commente_question_commentaire
     *
     * @return string 
     */
    public function getCommenteQuestionCommentaire()
    {
        return $this->commente_question_commentaire;
    }

    /**
     * Set commente_question_date
     *
     * @param \DateTime $commenteQuestionDate
     * @return CommenteQuestion
     */
    public function setCommenteQuestionDate($commenteQuestionDate)
    {
        $this->commente_question_date = $commenteQuestionDate;

        return $this;
    }

    /**
     * Get commente_question_date
     *
     * @return \DateTime 
     */
    public function getCommenteQuestionDate()
    {
        return $this->commente_question_date;
    }

    /**
     * Set commente_question_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $commenteQuestionMembreId
     * @return CommenteQuestion
     */
    public function setCommenteQuestionMembreId(\smartunity\ModeleBundle\Entity\Membre $commenteQuestionMembreId)
    {
        $this->commente_question_membre_id = $commenteQuestionMembreId;

        return $this;
    }

    /**
     * Get commente_question_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getCommenteQuestionMembreId()
    {
        return $this->commente_question_membre_id;
    }

    /**
     * Set commente_question_question_id
     *
     * @param \smartunity\ModeleBundle\Entity\Question $commenteQuestionQuestionId
     * @return CommenteQuestion
     */
    public function setCommenteQuestionQuestionId(\smartunity\ModeleBundle\Entity\Question $commenteQuestionQuestionId)
    {
        $this->commente_question_question_id = $commenteQuestionQuestionId;

        return $this;
    }

    /**
     * Get commente_question_question_id
     *
     * @return \smartunity\ModeleBundle\Entity\Question 
     */
    public function getCommenteQuestionQuestionId()
    {
        return $this->commente_question_question_id;
    }
}
