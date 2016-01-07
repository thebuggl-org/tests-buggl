<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Buggl\MainBundle\Entity\Wishlist;
use Buggle\MainBundle\Entity\StripInfo;

class WishlistController extends Controller
{
    public function displayWishlistAction(Request $request)
    {
        $localAuthor = $this->get('security.context')->getToken()->getUser();

        $e_guides = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Wishlist')->findAllMyWish($localAuthor);

        $count = count($e_guides);

        $data = array(
            'myid'=> $localAuthor,
            'count'=> $count,
            'list'=>$e_guides,
            'active' => 'wish'
        );

         return $this->render('BugglMainBundle:Frontend\Wishlist:wishlist.html.twig',$data);
    }

    public function addWishlistButtonAction(Request $request)
    {
        $securityContext = $this->get('security.context');
        $currentLocalAuthor = $securityContext->getToken()->getUser();

        $eguideId = $request->get('eguide');
        $eguideOwner = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Eguide')->findOneById($eguideId);
        $exists=1;
        $loggedIn=false;
        $isAdmin = false;
        $isOwned = false;
        $isPurchased = false;
        $sign = "Remove from Wishlist";

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){

            if(!is_null($currentLocalAuthor)){
                $roles = $currentLocalAuthor->getRoles();
                foreach( $roles as $role ){
                    if( $role === 'ROLE_SUPER_ADMIN' || $role === 'ROLE_ADMIN' ){
                        $isAdmin = ($isAdmin OR true);
                    }
                }
            }

            if(!$isAdmin){
                $loggedIn = true;
                $eguideWish = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Wishlist')->findIfAlreadyExist($eguideId,$currentLocalAuthor);

                if( count($eguideWish)==0 ){
                    $exists=0;
                    $sign = "Add to Wishlist";
                }
				/*
				NOTE: for stripe
                $purchased = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($currentLocalAuthor,$eguideOwner);
				*/
				$purchased = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($currentLocalAuthor,$eguideOwner);
                $isPurchased = !is_null($purchased);
                $isOwned = $currentLocalAuthor->getId() == $eguideOwner->getLocalAuthor()->getId();
            }
        }
		
		$isPurchased = $isPurchased || $this->getRequest()->getSession()->has('has_admin_access');

        $data = array(
            'eguideId' => $eguideId,
            'currentLocalAuthor' => $currentLocalAuthor,
            'isOwned' => $isOwned,
            'isPurchased' => $isPurchased,
            'exists' => $exists,
            'sign'=> $sign,
            'loggedIn' => $loggedIn
            );

        return $this->render('BugglMainBundle:Frontend\Wishlist:wishlistbutton.html.twig',$data);
    }

    public function addToWishlistAction(Request $request)
    {

        $params= $request->request->all();
        $id = $params['eguide'];
        $localAuthor = $params['localAuthor'];

        $eguides = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findOneById($id);
        $localAuthor= $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($localAuthor);

        $wishlist = new Wishlist();
        $wishlist->setEGuide($eguides);
        $wishlist->setLocalAuthor($localAuthor);
        $wishlist->setDateAdded(new \DateTime(date('Y-m-d H:i:s',time())));

        $em = $this->getDoctrine()->getEntityManager();
        $em-> persist($wishlist);
        $em->flush();

        return new JsonResponse(array(),200);
    }

    public function removeFromWishlistAction(Request $request)
    {

        $params= $request->request->all();
        $eguideId = $params['eguide'];
        $localAuthor = $params['localAuthor'];

        $eguides = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findOneById($eguideId);
        $localAuthor= $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($localAuthor);

        $em = $this->getDoctrine()->getEntityManager();
        $eguide = $em->getRepository('BugglMainBundle:Wishlist')->findIfAlreadyExist($eguideId,$localAuthor);
        $eguide = $em->getRepository('BugglMainBundle:Wishlist')->findOneById($eguide);

        $em->remove($eguide);
        $em->flush();

        return new JsonResponse(array(),200);
    }
	
	public function removeWishAction(Request $request)
	{
		$wish = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Wishlist')->findOneById($request->get('id',0));
		
		if(!is_null($wish)){
	        $this->getDoctrine()->getEntityManager()->remove($wish);
	        $this->getDoctrine()->getEntityManager()->flush();
		}
		
		return new RedirectResponse($this->generateUrl('buggl_display_wishlist'));
	}
}