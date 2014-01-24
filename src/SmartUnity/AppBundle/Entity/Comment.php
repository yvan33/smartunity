<?php

namespace SmartUnity\AppBundle\Entity;

use Symfony\Component\Validator\Constraints AS Assert;
use Mv\BlogBundle\Validator\Constraints AS MvAssert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Mv\BlogBundle\Entity\AdminBlog\Comment as BaseComment;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SmartUnity\AppBundle\Entity\CommentRepository")
 * 
 */
class Comment extends BaseComment
{

}

// * @ORM\AttributeOverrides({
// *      @ORM\AttributeOverride(name="pseudo",
// *          column=@ORM\Column(
// *              nullable = true
// *          )
// *      )
// * })