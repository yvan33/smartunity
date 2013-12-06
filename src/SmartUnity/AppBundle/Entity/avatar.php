<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Imagine\Gd\Imagine;
use Imagine\Gd\Image;
use Imagine\Image\Box;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class avatar {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */

    private $id;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
//        die($this->temp);
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getFile()) {
            $this->path = $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }
        
        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            
            unlink($this->temp);

            // clear the temp image path
            $this->temp = null;
            
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move($this->getUploadRootDir(), $this->id . '.' . $this->getFile()->guessExtension());
                

        $this->resizeAvatarAction();

        $this->setFile(null);
        unlink($this->getAbsolutePath());
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove() {
        $this->temp = $this->getAbsolutePathPng();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->id . '.' . $this->path;
    }
    
    public function getAbsolutePathPng() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->id . '.' . 'png';
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($userid) {
        $this->id = $userid;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->id . '.' . 'png';
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    
    /**
     * Set name
     *
     * @param string $name
     * @return avatar
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return avatar
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }



    public function resizeAvatarAction() {


        $imagine = new Imagine();
        $size = new Box(150, 150);

        $image = $imagine->open($this->getAbsolutePath());

        $image = $image->thumbnail($size, 'inset');
        $this->pad($image, $size)
                ->save($this->getAbsolutePathPng());

    }

    public function pad(Image $img, Box $size, $fcolor = 'fff', $ftransparency = 100) {
        $tsize = $img->getSize();
        $x = $y = 0;
        if ($size->getWidth() > $tsize->getWidth()) {
            $x = round(($size->getWidth() - $tsize->getWidth()) / 2);
        } elseif ($size->getHeight() > $tsize->getHeight()) {
            $y = round(($size->getHeight() - $tsize->getHeight()) / 2);
        }
        $pasteto = new \Imagine\Image\Point($x, $y);
        $imagine = new \Imagine\Gd\Imagine();
        $color = new \Imagine\Image\Color($fcolor, $ftransparency);
        $image = $imagine->create($size, $color);

        $image->paste($img, $pasteto);

        return $image;
    }

}

