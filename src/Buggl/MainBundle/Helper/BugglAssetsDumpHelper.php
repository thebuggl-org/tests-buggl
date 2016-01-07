<?php

/** 
 *
 * @author Nash Lesigon <nashlesigon@gmail.com>
 * @version - 1.0
 * @created - Jan. 31, 2014
 * @description - This class is a helper that uploads/updates the public assets specified in the $includedFolders
 * 	global parameter to the amazon s3 bucket using the AWS SDK for PHP. This class is being used in the 
 *	BugglAssetsDumpCommand
 *
 */    

namespace Buggl\MainBundle\Helper;

use Aws\S3\S3Client;
use Symfony\Component\Finder\Finder;
use Aws\S3\Sync\UploadSyncBuilder;

class BugglAssetsDumpHelper
{
	private $key = 'AKIAI6IZ3EM3IM4PD2XQ';
	private $secret = '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';
	private $bucket = 'buggl';
	private $client = null;
	private $path = "";

	private $includedFolders = array('css', 'images', 'fonts', 'js');

	public function __construct($path)
    {
        $this->client = S3Client::factory(array(
		    'key'    => $this->key,
		    'secret' => $this->secret,
		));

		$this->path = $path;
    }

    public function execute()
    {
    	$holder = opendir($this->path);
    	while (false !== ($foldername = readdir($holder))) {
		    if(!preg_match("/^\./", $foldername) && in_array($foldername, $this->includedFolders))
		    {
		    	$dir = $this->path . $foldername;
		    	$bucket = $this->bucket . "/bundles/bugglmain/" . $foldername;
		    	
		    	$this->client->uploadDirectory( $dir, $bucket, null, array('concurrency' => 3, 'debug' => true) );
		  	
		    }

		}
			
    }
}