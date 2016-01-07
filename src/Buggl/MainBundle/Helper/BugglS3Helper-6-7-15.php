<?php

/** 
 *
 * @author Nash Lesigon <nashlesigon@gmail.com>
 * @version - 1.0
 * @created - Feb. 26, 2014
 * @description - A generic helper/wrapper for the AWS SDK. This class is being used in uploading photos for guide and spot
 *	to amazon s3. Also used in uploading/downloading the travel guide pdf.
 * @updated - Feb. 27, 2014
 * @description - Added uploadDirectory method for bulk uploading whole folder. Being used in BugglCloudUploadCommand class.
 *
 */
    
            
namespace Buggl\MainBundle\Helper;

use Aws\S3\S3Client;
use Symfony\Component\Finder\Finder;
use Aws\S3\Sync\UploadSyncBuilder;

class BugglS3Helper
{
	private $key = 'AKIAIPNBFGDYCKNVUYVA';
	private $secret = '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';
	private $bucket = 'buggl-assets';
	private $client = null;

	public function __construct()
    {
        $this->client = S3Client::factory(array(
		    'key'    => $this->key,
		    'secret' => $this->secret,
		));
    }

    public function upload($sourceFile, $key)
    {
    	$result = $this->client->putObject(array(
		    'Bucket'     => $this->bucket,
		    'Key'        => $key,
		    'SourceFile' => $sourceFile
		));
    }

    public function download($key)
    {
    	$result = null;
    	
    	if ($this->client->doesObjectExist($this->bucket, $key))
    	{
    		$result = $this->client->getObject(array(
			    'Bucket' => $this->bucket,
			    'Key'    => $key
			));
    	}
    	

		return $result;
    }

    public function uploadDirectory($directoryToUpload, $key)
    {
    	$bucket = $this->bucket . "/" . $key;
    	$this->client->uploadDirectory( $directoryToUpload, $bucket, null, array('concurrency' => 3, 'debug' => true) );
    }
}