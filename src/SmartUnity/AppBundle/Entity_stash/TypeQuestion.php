<?php
namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="type_question")
 */
class TypeQuestion
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $type_question_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $type_question_description;

    /**
     * Get type_question_id
     *
     * @return integer 
     */
    public function getTypeQuestionId()
    {
        return $this->type_question_id;
    }

    /**
     * Set type_question_description
     *
     * @param string $typeQuestionDescription
     * @return TypeQuestion
     */
    public function setTypeQuestionDescription($typeQuestionDescription)
    {
        $this->type_question_description = $typeQuestionDescription;

        return $this;
    }

    /**
     * Get type_question_description
     *
     * @return string 
     */
    public function getTypeQuestionDescription()
    {
        return $this->type_question_description;
    }
}
