<?php

// namespace SmartUnity\Utilisateur\Form\DataTransformer
 
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
 
class HiddenEntityDataTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    private $entity;
 
    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, $entity)
    {
        $this->om = $om;
        $this->entity = $entity;
    }
 
    /**
     * Transforms an object to a hidden id.
     *
     * @param  Issue|null $issue
     * @return id
     */
    public function transform($object)
    {
        if (null === $object) {
            return "";
        }
 
        return $object->getId();
    }
 
    /**
     * Transforms an id to an object.
     *
     * @param  string $number
     *
     * @return Object|null
     *
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }
 
        $object = $this->om
            ->getRepository($this->entity)
            ->find($id)
        ;
 
        if (null === $object) {
            throw new TransformationFailedException();
        }
 
        return $object;
    }
}