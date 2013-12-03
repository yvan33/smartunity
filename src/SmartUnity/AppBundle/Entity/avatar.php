<?php

namespace SmartUnity\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $ImagePath = $this->getUploadRootDir().$this->id . '.' . $this->getFile()->guessExtension();
        $this->getFile()->move($this->getUploadRootDir(), $this->id . '.' . $this->getFile()->guessExtension());
                

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove() {
        $this->temp = $this->getAbsolutePath();
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
//        return null === $this->path
//            ? null
//            : $this->getUploadDir().'/'.$this->id.'.'.$this->path;
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->id . '.' . $this->path;
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

    protected function getAvatarDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/avatars';
    }

    public function getAvatarPath() {
//        return null === $this->path
//            ? null
//            : $this->getUploadDir().'/'.$this->id.'.'.$this->path;
        return null === $this->path ? null : $this->getAvatarDir() . '/' . $this->id . '.' . $this->path;
    }

    public function resizeAvatarAction($file) {

        $imagine = new Imagine();

        $webPath = realpath(__DIR__ . '/../../../../web/');
        $avatarPath = $webPath . '/' . $avatar->getWebPath();
        $newAvatarPath = $webPath . '/' . $avatar->getAvatarPath();
//                    die($avatarPath);
        $image = $imagine->open($file);
//            die ($new = realpath($avatarPath.'/../'));
        $size = new Imagine\Image\Box(40, 40);

        $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $newImage= $image->thumbnail($size, $mode);
        return $newImage;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return avatar
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    
        public function __construct($id)
    {
            $this->id = $id;
    }
}
