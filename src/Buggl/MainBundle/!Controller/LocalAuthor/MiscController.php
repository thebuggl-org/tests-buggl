<?php


namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MiscController extends Controller
{
    public function miscAction($localAuthor)
    {
        $follow =  $this->get('buggl_main.entity_repository')
                        ->getRepository('BugglMainBundle:Follower')
                        ->findOneBy(array('localAuthor' => $localAuthor));

        $followers = 0;

        if(!is_null($follow)){
            $followers = json_decode($follow->getFollower());

            $followers = count($followers);
        }

        $isLocalAuthor = $localAuthor->getIsLocalAuthor()? true : false;

        $data = array(
            'followers' => $followers,
            'isLocalAuthor' => $isLocalAuthor
        );



        if($isLocalAuthor){
            $status = $this->get('buggl_main.constants')->get('LOCAL_REF_LIST');

            $referenceCount = $this->getDoctrine()
                         ->getEntityManager()
                         ->getRepository('BugglMainBundle:LocalAuthorToLocalReference')
                         ->countRequestsByStatus($status,$localAuthor);

            $soldCount = $this->getDoctrine()
                            ->getEntityManager()
                            ->getRepository('BugglMainBundle:PaypalPurchaseInfo')
                            ->countDownloadsForSeller($localAuthor);

            $data['referenceCount'] = $referenceCount;
            $data['soldCount'] = $soldCount;
        }

        return $this->render('BugglMainBundle:LocalAuthor\Misc:misc.html.twig',$data);
    }
}