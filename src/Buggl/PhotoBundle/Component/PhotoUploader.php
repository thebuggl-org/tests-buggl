<?php

namespace Buggl\PhotoBundle\Component;

class PhotoUploader
{

    private $options;
    private $paths;

    const LENGTH_LIMIT = 100;

    public function __construct()
    {
        $this->options = null;
        $this->paths = null;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        $this->error = false;
    }

    public function upload()
    {
        $isAjax = $this->options['isAjax'];

        if($isAjax){
            $this->submitProccess();
        }
        else{
            $this->submitProccess();
        }
    }

    public function setPaths($paths)
    {
        $this->paths = $paths;
    }

    public function ajaxProcess()
    {
        $upload_path = $this->paths['uploaded-image-path'];
        $newFilename = $this->_getNewFilename();

        $newFile = $upload_path.'/'.$newFilename;
        $input = fopen("php://input", "r");

        $temp_path = $this->paths['temp-path'];
        if(!is_writable($temp_path)){
            mkdir("$temp_path",0777);
        }

        $tempFilename = tempnam($temp_path, 'temp_');
        $tempfile = fopen($tempFilename, 'w');
        $filesize = stream_copy_to_stream($input, $tempfile);

        fclose($tempfile);
        fclose($input);

        if(rename($tempFilename,$newFile))
        {
            $this->uploadedFileName = $newFilename;
        }
        else
        {
            $this->error = true;
        }
    }

    public function submitProccess()
    {
        $upload_path = $this->paths['uploaded-image-path'];
        $newFilename = $this->_getNewFilename();

        // $file = array('name' => $this->options['file']['name'], 'tmp_name' => $this->options['file']['tmp_name']);
        $filesize = @filesize($this->options['file']['tmp_name']);

        $newFile = $upload_path.'/'.$newFilename;

        if(move_uploaded_file($this->options['file']['tmp_name'], $newFile))
        {
            $this->uploadedFileName = $newFilename;
        }
        else
        {
            $this->error = true;
        }
    }

    public function getFilename()
    {
        return $this->uploadedFileName;
    }

    private function _getNewFilename()
    {
        $fileInfo = pathinfo($this->options['file']['name']);

        $filename = time().'_'.$fileInfo['filename'];

        if(strlen($filename) > self::LENGTH_LIMIT){
            $filename = substr($filename, 0,self::LENGTH_LIMIT);
        }

        $newFilename = $filename.".".$fileInfo['extension'];

        return $newFilename;
    }

    public function getStatus()
    {
        return array('filename' => $this->uploadedFileName,'error'=> $this->error);
    }
}