<?php

namespace Buggl\PhotoBundle\Service;


use Buggl\PhotoBundle\Component\PhotoUploader;

class PhotoUploaderService
{

    private $pathInfo;
    private $tempPath;

    private $uploadPath = 'uploads';
    private $imageUploadPath;
    private $temp = 'temp';

    private $photoUploader;

    public function __construct( PhotoUploader $photoUploader )
    {
        $this->pathInfo = null;
        $this->tempPath = null;
        $this->imageUploadPath = '';

        $this->photoUploader = $photoUploader;
    }

    public function createUploadPath()
    {
        if(!file_exists($this->uploadPath)){
            mkdir($this->uploadPath);
        }

        $this->tempPath = $this->uploadPath.'/'.$this->temp;

        if(!file_exists($this->tempPath)){
            mkdir($this->tempPath);
        }

        $this->imageUploadPath = $this->uploadPath.'/'.'images';

        if(!file_exists($this->imageUploadPath)){
            mkdir($this->imageUploadPath);
        }

        return $this;
    }

    public function setOptions($v = array())
    {
        $this->photoUploader->setOptions($v);
        
        return $this;
    }

    public function setPaths($paths = array())
    {
        $this->photoUploader->setPaths(array(
                'upload-path' => $this->uploadPath,
                'temp-path' => $this->tempPath,
                'uploaded-image-path' => $this->imageUploadPath
            ));

        return $this;
    }

    public function upload()
    {
        $this->photoUploader->upload();

        return $this;
    }

    public function getPhotoUploader()
    {
        return $this->photoUploader;
    }

    public function getImagePath()
    {
        return $this->uploadPath.'/images/';
    }
}