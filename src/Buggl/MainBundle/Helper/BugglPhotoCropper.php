<?php

/** 
 * @author Nash Lesigon <nashlesigon@gmail.com>
 * @version 1.0
 * Oct. 10, 2013
 * A server side jcrop helper for symfony 2
 */

namespace Buggl\MainBundle\Helper;

class BugglPhotoCropper 
{
	// default image quality
	private $quality = 90;
	public function __construct(){}

	/** 
	 * @description - method to crop a photo
	 * @param array $options 
	 * @param (required) $options[ start_x ] = the starting point in x axis
	 * @param (required) $options[ start_y ] = the starting point in y axis
	 * @param (required) $options[ target_x ] = the target point in x axis
	 * @param (required) $options[ target_y ] = the target point in y axis
	 * @param (required) $options[ width ] = the desired width
	 * @param (required) $options[ height ] = the desired height
	 * @param (required) $options[ src ] = the image to be cropped
	 * @param (required) $options[ dir ] = path to where the cropped photo will be stored
	 * @param (optional) $options[ quality ] = the cropped image quality
	 */
	// TODO (NRL): add parameter validator.
	public function crop(array $options)
	{
		$pathInfo = pathinfo( $options['src'] );
		if($pathInfo['extension'] == 'png'){
			$source = imagecreatefrompng( $options['src'] );
		}
		else {
			$source = imagecreatefromjpeg( $options['src'] );
		}

		$product = ImageCreateTrueColor( $options['width'], $options['height'] );

		imagecopyresampled(
			$product,
			$source,
			0,0,
			$options[ 'start_x' ], $options[ 'start_y' ],
			$options['width'], $options['height'],
			$options[ 'target_x' ], $options[ 'target_y' ] );

		$dir = $options[ 'dir' ];
		if(!is_writable( $dir )){
            mkdir("$dir", 0755);
        }

		$target = $dir . $pathInfo['filename'] . '.' . $pathInfo['extension'];
		$quality = ( isset( $options['quality'] ) ) ? $options['quality'] : $this->quality;
		imagejpeg($product, $target, $quality);
	}
	    
}