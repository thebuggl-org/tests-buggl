<?php

/** 
 * @author Nash Lesigon <nashlesigon@gmail.com>
 * @version 1.0
 * Jan. 16, 2014
 * Dependency: Router, root directory
 */
namespace Buggl\MainBundle\Helper;


class BugglSitemapHelper
{
	private $router;
	private $rootDir;
	private $em;
	private $slugifier;
	private $constants;
	private $domain;

	public function __construct($_router, $_rootDir, $_entityManager, $_slugifier, $_constants)
	{
		$this->router = $_router;
		$this->rootDir = $_rootDir;
		$this->em = $_entityManager;
		$this->slugifier = $_slugifier;
		$this->constants = $_constants;
	}

	
	public function execute($domain)
	{
		$this->domain = "http://" . $domain;
		
		$this->generateFile();
	}

	private function generateFile()
	{
		$xmlFile = $this->rootDir.'/../web/sitemap.xml';

		$urls = array_merge(
            $this->generateStaticUrls(),
            $this->generateLocalAuthorsUrl(),
            $this->generateGuidesUrl()
        );

		$header = '<?xml version="1.0" encoding="UTF-8"?>' .
					'<?xml-stylesheet type="text/xsl" href="' . $this->domain . '/bundles/bugglmain/xsl/sitemap.xsl"?>' .
					'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		if( file_exists($xmlFile) ){
			$newXmlFile = $this->rootDir.'/../web/sitemap-bak-'.date('Ymd').'-'.time().'.xml';
			rename($xmlFile, $newXmlFile);
		}

		$fp = fopen($xmlFile, 'w');
		fwrite($fp, $header);

		foreach($urls as $url)
		{
			echo " . ";
			$str = '<url>';
			foreach($url as $attr => $val)
			{
				$str .= '<' . $attr . '>' . $val . '</' . $attr . '>';
			}

			$str .= '</url>';
    		fwrite($fp, $str);
		}

		fwrite($fp, '</urlset>');
		 
		fclose($fp); 
		
	}

	private function generateStaticUrls()
	{
		$homepage = array(
			'loc' 			=> $this->domain . $this->router->generate('buggl_homepage'),
			'changefreq' 	=> 'weekly',
			'priority'		=> '1.0'
			);

		$links = array( $homepage );

        $routes = array(
        	'buggl_static_jobs', 'buggl_static_press', 'buggl_static_faq', 
        	'buggl_static_terms', 'buggl_static_contact_us', 'buggl_static_our_tribe',
        	'buggl_static_our_mission', 'buggl_static_privacy_policy', 'buggl_write_a_guide',
        	'buggl_how_it_works', 'buggl_password_reset_request', 'login', 'registration');
        foreach ($routes as $route) {
            $changefreq = 'monthly';
            $priority = '0.5';
            $links[] = array(
                'loc'          => $this->domain . $this->router->generate($route),
                'changefreq'    => $changefreq,
                'priority'      => $priority
            );
        }

        return $links;
	}

	private function generateLocalAuthorsUrl()
	{
		$links = array();
		$authors = $this->em->getRepository('BugglMainBundle:LocalAuthor')->findAllLocalAuthor( $this->constants->get('allowed_user') );
		foreach($authors as $author)
		{
			$links[] = array(
                'loc'          => $this->domain . $this->router->generate('local_author_profile', array('slug' => $author->getSlug())),
                'changefreq'    => 'monthly',
                'priority'      => '1.0'
            );

            $approvedGuideCount = $this->em->getRepository('BugglMainBundle:EGuide')->countApprovedByLocalAuthor($author);
            if( 0 < $approvedGuideCount )
            {
            	$links[] = array(
	                'loc'          => $this->domain . $this->router->generate('local_author_guide_list', array('slug' => $author->getSlug())),
	                'changefreq'    => 'weekly',
	                'priority'      => '0.7'
	            );
            }

		}

		return $links;
	}

	private function generateGuidesUrl()
	{
		$links = array();
		$routes = array('buggl_eguide_full', 'buggl_eguide_secrets', 'buggl_eguide_overview');
		$guides = $this->em->getRepository('BugglMainBundle:EGuide')->findBy( array( 'status' => $this->constants->get('published_guide') ) );
		foreach($guides as $guide)
		{
			foreach($routes as $route)
			{
				$links[] = array(
	                'loc'          => $this->domain . $this->router->generate($route, array('slug' => $guide->getSlug())),
	                'changefreq'    => 'monthly',
	                'priority'      => '1.0'
	            );
			}

			// guide spots
			// $egts = $this->em->getRepository('BugglMainBundle:EGuideToSpotDetail')->getByEGuide($guide, 0);
			$egts = $this->em->getRepository('BugglMainBundle:EGuideToSpotDetail')->findBy(array('eGuide' => $guide, 'isFeatured' => 1));
			if(count($egts))
			{
				foreach($egts as $obj)
				{
					$spotSlug = $this->slugifier->format($obj->getSpotDetail()->getSpot()->getName() )->getSlug();
					$spotDetailId = $obj->getSpotDetail()->getID();
					$links[] = array(
		                'loc'          => $this->domain . $this->router->generate('buggl_eguide_spot', array('slug' => $guide->getSlug(), 'spotSlug' => $spotSlug, 'spotDetailId' => $spotDetailId)),
		                'changefreq'    => 'monthly',
		                'priority'      => '0.9'
		            );
				}
			}
			
		}

		return $links;
	}

}