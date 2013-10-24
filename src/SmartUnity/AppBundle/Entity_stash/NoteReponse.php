<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="note_reponse")
 */
class NoteReponse
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Reponse")
	 * @ORM\JoinColumn(name="note_reponse_reponse_id", referencedColumnName="reponse_id")
	 */
	protected $note_reponse_reponse_id;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Membre")
	 * @ORM\JoinColumn(name="note_reponse_membre_id", referencedColumnName="membre_id")
	 */
	protected $note_reponse_membre_id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $note_reponse_isgood;		

    /**
     * Set note_reponse_isgood
     *
     * @param boolean $noteReponseIsgood
     * @return NoteReponse
     */
    public function setNoteReponseIsgood($noteReponseIsgood)
    {
        $this->note_reponse_isgood = $noteReponseIsgood;

        return $this;
    }

    /**
     * Get note_reponse_isgood
     *
     * @return boolean 
     */
    public function getNoteReponseIsgood()
    {
        return $this->note_reponse_isgood;
    }

    /**
     * Set note_reponse_reponse_id
     *
     * @param \smartunity\ModeleBundle\Entity\Reponse $noteReponseReponseId
     * @return NoteReponse
     */
    public function setNoteReponseReponseId(\smartunity\ModeleBundle\Entity\Reponse $noteReponseReponseId)
    {
        $this->note_reponse_reponse_id = $noteReponseReponseId;

        return $this;
    }

    /**
     * Get note_reponse_reponse_id
     *
     * @return \smartunity\ModeleBundle\Entity\Reponse 
     */
    public function getNoteReponseReponseId()
    {
        return $this->note_reponse_reponse_id;
    }

    /**
     * Set note_reponse_membre_id
     *
     * @param \smartunity\ModeleBundle\Entity\Membre $noteReponseMembreId
     * @return NoteReponse
     */
    public function setNoteReponseMembreId(\smartunity\ModeleBundle\Entity\Membre $noteReponseMembreId)
    {
        $this->note_reponse_membre_id = $noteReponseMembreId;

        return $this;
    }

    /**
     * Get note_reponse_membre_id
     *
     * @return \smartunity\ModeleBundle\Entity\Membre 
     */
    public function getNoteReponseMembreId()
    {
        return $this->note_reponse_membre_id;
    }
}
