<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

use Buggl\MainBundle\Entity\LocalAuthor;

class LocalAuthorController extends Controller
{
    public function profileAction(LocalAuthor $localAuthor)
    {
        // $id = $this->get('buggl_main.slugifier')
        //            ->explode($slug)->reverse()
        //            ->get();

        // $localAuthor = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:LocalAuthor',$id);

        $profile = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:Profile')
                        ->getByLocalAuthor($localAuthor,true);
		$entityManager = $this->getDoctrine()->getEntityManager();
		$socialMedia = $entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);

        if(is_null($localAuthor)){
            throw $this->createNotFoundException('Local author does not exists');
        }

        $breadcrumbs = $this->get('buggl_main.breadcrumbs')->init('profile_view')
                            ->setParameters(array('local-author'=>$localAuthor->getName()))
                            ->getBreadcrumbs();
		$metas = $this->get('buggl_seo.author_profile')->buildMetaAttributes($localAuthor);
		
        return $this->render('BugglMainBundle:Frontend/LocalAuthor:profile.html.twig',array(
                'localAuthor' => $localAuthor,
                'profile' => $profile,
                'current' => $breadcrumbs['current'],
                'links' => $breadcrumbs['links'],
				'socialMedia' => $socialMedia,
				'metas' => $metas
            ));
    }

    public function localInterestAction( LocalAuthor $localAuthor )
    {
        $interests = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:LocalPassion')
                          ->getByLocalAuthor($localAuthor);


        return $this->render('BugglMainBundle:Frontend/LocalAuthor:localinterest.html.twig',array(
                'interests' => $interests
            ));
    }

	public function featuredGuidesInProfileAction( LocalAuthor $localAuthor, $ownPage = false )
	{
		
        $guides = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:EGuide')
                          ->findFeaturedInProfile($localAuthor);
		
		$totalPublishedGuides = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countApprovedByLocalAuthor($localAuthor);
		
        return $this->render('BugglMainBundle:Frontend/LocalAuthor:featuredGuidesInProfile.html.twig',array(
                'guides' => $guides,
				'ownPage' => $ownPage,
				'localAuthor' => $localAuthor,
				'totalPublishedGuides' => $totalPublishedGuides
            ));
	}
}