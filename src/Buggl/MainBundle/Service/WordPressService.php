<?php

namespace Buggl\MainBundle\Service;

class WordPressService
{

	public function __construct()
	{
	}
    

    public function getPosts()
    {
    	$results = findPostsForHomePage();

		$posts = array();
		foreach($results as $post){
			$postDetail = array(
				'title' => $post->post_title,
  				'link' => "http://".$_SERVER['HTTP_HOST']."/blog/?p=" . $post->ID
			);
			$posts[] = $postDetail;
		}

    	return $posts;
    }
}