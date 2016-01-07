<?php

namespace Buggl\MainBundle\Twig;

class ImageHelperExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
			'gravatar' => new \Twig_Filter_Method($this, 'gravatar'),
			'profilePic' => new \Twig_Filter_Method($this, 'getProfilePic'),
			'profilePicFileName' => new \Twig_Filter_Method($this, 'getProfilePicFileName'),
			'userPic' => new \Twig_Filter_Method($this, 'getUserPic'),
        );
    }
	
	public function gravatar($email)
	{
		return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email)));
	}
	
	public function getProfilePic($profile)
	{
		if(!is_null($profile) && !is_null($profile->getImageWebPath())){
			return $profile->getImageWebPath();
		}
		
		// change this to actual default profile pic
		return 'bundles/bugglmain/images/profile-big.jpg';
	}
	
	public function getProfilePicFileName($profile)
	{
		if(!is_null($profile) && !is_null($profile->getProfilePic())){
			return $profile->getProfilePic();
		}
		
		return '';
	}
	
	public function getUserPic($user)
	{
		if(strpos(get_class($user),'AdminUsers') === false){
			return $this->getProfilePic($user->getProfile());
		}
		
		// return admin photo
		return 'bundles/bugglmain/images/profile-big.jpg';
	}

    public function getName()
    {
        return 'buggl_image_helper_extension';
    }
}