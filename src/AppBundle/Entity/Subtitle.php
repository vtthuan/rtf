<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="subtitles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubtitleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Subtitle extends UpdatableEntity {

    const SERVER_PATH_TO_UPLOAD_SUBTITLE_FOLDER = 'uploads/subtitles/';
    const SERVER_PATH_TO_PREUPLOAD_SUBTITLE_FOLDER = 'uploads/import/PreUpSubtitle/';

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Application\Sonata\NewsBundle\Entity\Post",
     *      inversedBy="subtitles",
     *      cascade={"persist"}
     * )
     */
    private $post;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Language",
     *      inversedBy="subtitles",
     *      cascade={"persist"}
     * )
     */
    private $language;

    /**
     * @ORM\Column(type="string")
     */
    private $fileName;
    private $file;

    public static function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web/' . Subtitle::SERVER_PATH_TO_UPLOAD_SUBTITLE_FOLDER;
    }

    public static function getPreUpSubtitle() {
        // the absolute directory path where subtitles copied
        // documents should be saved
        return __DIR__ . '/../../../web/' . Subtitle::SERVER_PATH_TO_PREUPLOAD_SUBTITLE_FOLDER;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated() {
        $this->setModifiedAt(new \DateTime());
    }

    public static function getEntityName() {
        return get_called_class();
    }

    function get_file_extension($file_name) {
        return substr(strrchr($file_name, '.'), 1);
    }

    /**
     * Set video
     *
     * @param \Application\Sonata\NewsBundle\Entity\Post $post
     * @return Subtitle
     */
    public function setPost(\Application\Sonata\NewsBundle\Entity\Post $post = null) {
        $this->post = $post;

        return $this;
    }

    /**
     * Get video
     *
     * @return \Application\Sonata\NewsBundle\Entity\Post
     */
    public function getPost() {
        return $this->post;
    }

    /**
     * Set lang
     *
     * @param \AppBundle\Entity\Language $lang
     * @return Subtitle
     */
    public function setLanguage(\AppBundle\Entity\Language $lang = null) {
        $this->language = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return \AppBundle\Entity\Language 
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Subtitle
     */
    public function setFileName($fileName) {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName() {
        return $this->fileName;
    }

    public function getClientFilePath() {
        return Subtitle::SERVER_PATH_TO_UPLOAD_SUBTITLE_FOLDER . $this->getFileName();
    }

}
