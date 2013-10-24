<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="note_question")
 */
class NoteQuestion
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Question")
	 * @ORM\JoinColumn(name="note_question_question_id", referencedColumnName="question_id")
	 */
	protected $note_question_question_id;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Membre")
	 * @ORM\JoinColumn(name="note_question_membre_id", referencedColumnName="membre_id")
	 */
	protected $note_question_membre_id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $note_question_isgood;		

    /**
     * Set note_question_isgood
     *
     * @param boolean $noteQuestionIsgood
     * @return NoteQuestion
     */
    public function setNoteQuestionIsgood($noteQuestionIsgood)
    {
        $this->note_question_isgood = $noteQuestionIsgood;

        return $this;
    }

    /**
     * Get note_question_isgood
     *
     * @return boolean 
     */
    public function getNoteQuestionIsgood()
    {
        return $this->note_question_isgood;
    }

    /**
     * Set note_question_question_id
     *
     * @param \smartunity\ModeleBundle\Entity\Question $noteQuestionQuestionId
     * @return NoteQuestion
     */
    public function setNoteQuestionQuestionId(\smartunity\ModeleBundle\Entity\Question $noteQuestionQuestionId)
    {
        $this->note_question_question_id = $noteQuestionQuestionId;

        return $this;
    }

    /**
     * Get note_question_question_id
     *
     * @return \smartunity\ModeleBundle\Entity\Question 
     */
    public function getNoteQuestionQuestionId()
    {
        return $this->note_question_question_id;
    }

    /**
     * Set note_question_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $noteQuestionMembreId
     * @return NoteQuestion
     */
    public function setNoteQuestionMembreId(\smartunity\ModeleBundle\Entity\Membre $noteQuestionMembreId)
    {
        $this->note_question_membre_id = $noteQuestionMembreId;

        return $this;
    }

    /**
     * Get note_question_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getNoteQuestionMembreId()
    {
        return $this->note_question_membre_id;
    }
}
