<?php

namespace Buggl\MainBundle\Service;

use Buggl\PhotoBundle\Component\PhotoUploader;

/**
 * BugglPhotoUploaderService
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 *
 * @copyright 2013 (c) Buggl.com
 */
class BugglPhotoUploaderService
{
    /**
     * @var Boolean
     */
    private $initiliazed = null;

    /**
     * @var Buggl\PhotoBundle\Component\PhotoUploader;
     */
    private $photoUploader = null;

    /**
     * @var Array
     */
    private $options = null;

    /**
     * @var Array
     */
    private $paths = null;

    const UPLOAD_PATH = 'uploads';
    const TEMP_PATH = 'temp';

    /**
     * constructor
     * @param PhotoUploader $photoUploader [description]
     */
    public function __construct( PhotoUploader $photoUploader )
    {
        $this->initiliazed = false;

        $this->photoUploader = $photoUploader;

        $this->options = array();
        $this->paths = array();

        $this->setDefaults();
    }

    /**
     * @param Boolean $isAjax  []
     * @param Array   $options []
     *
     * @return self
     */
    public function setOptions($isAjax,$options)
    {
        $this->options['isAjax'] = $isAjax;

        if ($isAjax) {
            $this->options['file'] = $this->generateGlobalFilesFormat($options);
        } else {
            $this->options['file'] = $this->generateGlobalFilesFormat($options);
        }

        $this->photoUploader->setOptions($this->options);

        return $this;
    }

    /**
     * set the default config
     *
     * @return self
     */
    private function setDefaults()
    {
        $uploadPath = self::UPLOAD_PATH;
        $temp = self::TEMP_PATH;

        $imageUploadPath = $uploadPath.'/images';
        $tempPath = $uploadPath.'/'.$temp;

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath);
        }

        if (!file_exists($tempPath)) {
            mkdir($tempPath);
        }

        $this->paths['temp-path'] = $tempPath;

        return $this->setPath($imageUploadPath);
    }

    /**
     * set upload path
     * @param String $imageUploadPath
     *
     * @return self
     */
    public function setPath($imageUploadPath)
    {
        if (!file_exists($imageUploadPath)) {
            mkdir($imageUploadPath);
        }


        $this->paths['uploaded-image-path'] = $imageUploadPath;

        $this->initialized = true;

        return $this;
    }

    /**
     * upload pic to specified upload path
     * @param String $prefix
     *
     * @return PhotoUploader
     */
    public function upload($prefix = null)
    {
        if ($this->initialized) {
            $this->photoUploader->setPaths($this->paths);
            $this->photoUploader->upload();

            return $this->photoUploader;
        }

        return null;
    }

    /**
     * @return String upload path
     */
    public function getPath()
    {
        return $this->paths['uploaded-image-path'];
    }

    /**
     * @param UploadedFile $uploadedFile
     *
     * @return Array
     */
    private function generateGlobalFilesFormat($uploadedFile = null)
    {
        if (is_null($uploadedFile)) {
            return null;
        }

        $file = array();

        $file['error'] = $uploadedFile->getError();
        $file['size'] = $uploadedFile->getClientSize();
        $file['type'] = $uploadedFile->getClientMimeType();
        $file['name'] = $uploadedFile->getClientOriginalName();
        $file['tmp_name'] = $uploadedFile->getPathName();

        return $file;
    }
}