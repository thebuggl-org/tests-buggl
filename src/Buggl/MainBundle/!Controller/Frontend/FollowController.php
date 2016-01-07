<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FollowController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    public function followAction()
    {
        $request = $this->getRequest();

        if(!$request->isXmlHttpRequest()){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

        $slug = $request->get('follow');
        $action = $request->get('action');

        $fail = array('success' => false, 'text' => 'Follow');


        $localAuthor = $this->get('buggl_main.entity_repository')
                            ->getRepository('BugglMainBundle:LocalAuthor')
                            ->findOneBySlug($slug);


        if(is_null($localAuthor)){
            return new JsonResponse($fail);
        }

        $follower = $this->get('security.context')->getToken()->getUser(); // current user

        $isFollowing = $this->get('buggl_main.follow')->isFollowing($follower,$slug);

        if($isFollowing && !$action){
            return new JsonResponse($fail);
        }

        if($follower->getId() != $localAuthor->getId()){
            $event = new \Buggl\MainBundle\Event\FollowEvent($follower,$localAuthor,$action);

            $this->get('event_dispatcher')->dispatch('buggl.follow_user',$event);

			$constants = $this->get('buggl_main.constants');
			$activityEvent = new \Buggl\MainBundle\Event\ActivityEvent(null,$follower,$localAuthor,$constants->get('ACTIVITY_FOLLOW_AUTHOR'));
			$this->get('event_dispatcher')->dispatch('buggl.activity',$activityEvent);

            $value = $action ? 0 : 1;
            $text = $action ? 'Follow' : 'Following';

            return new JsonResponse(array('success'=>true,'text'=> "<span class=ico></span>".$text,'value' => $value));
        }

        return new JsonResponse($fail);
    }
	public function followedAction(Request $request)
	{
		$page = $request->get('page',1);
		$limit = 10;
		$offset = ($page - 1) * $limit;

		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$followed = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Follower')->findOneByLocalAuthor($localAuthor);

		$slug=$localAuthor->getSlug();
		
		if(!is_null($followed))
		{
			$countFollowing=$following=json_decode($followed->getFollowing(),true);

			$following=array_slice($following,$offset,$limit);

			$authors = array();
			foreach($following as $followed){
		 		$followedLocalAuthor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($followed);
				$authors[] = $followedLocalAuthor;
			}
			$countedFollowing =count($countFollowing);
			$hasNext = ($offset + $limit) < $countedFollowing;

			$data = array(
				'content' => $this->renderView('BugglMainBundle:LocalAuthor\Dashboard:followedModal.html.twig', array('authors' => $authors, 'isFollowing' => 1,'hasNext' => $hasNext,'text' => 1,'countedFollowing' => $countedFollowing ))
			);

			 return new JsonResponse($data,200);
		}
		else
		{
			$data = array(
				'content' => $this->renderView('BugglMainBundle:LocalAuthor\Dashboard:followedModal.html.twig', array('text' => 0 ))
			);
			return new JsonResponse($data,200);
		}
	}
	public function followerAction(Request $request)
	{
		$page = $request->get('page',1);
		$limit = 10;
		$offset = ($page - 1) * $limit;

		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$follower = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Follower')->findOneByLocalAuthor($localAuthor);

		if(!is_null($follower))
		{
			$countFollowers=$followers=json_decode($follower->getFollower(),true);

			$followers=array_slice($followers,$offset,$limit);

			$authors = array();
			$localAuthorfollower = array();
			foreach($followers as $id )
			{
				$followerLocalAuthor=$this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($id);
				$authors[]=$followerLocalAuthor;
				$slug=$followerLocalAuthor->getSlug();
				$isFollowing = $this->get('buggl_main.follow')->isFollowing($localAuthor,$slug);

				$localAuthorfollower[$id] =array(
					'isFollowing' => $isFollowing ? 1:0,
					'text' => $isFollowing ? "Unfollow":"Follow"
				);

			}
			$countedFollowers=count($countFollowers);
			$hasNext = ($offset + $limit) < $countedFollowers;


				$data=array(
					'content' =>$this->renderView('BugglMainBundle:LocalAuthor\Dashboard:followerModal.html.twig', array('authors' => $authors,'localAuthorfollower' => $localAuthorfollower,'hasNext'=>$hasNext,'text' =>1,'countedFollowers'=>$countedFollowers))
				);
				return new JsonResponse($data,200);
		}
		else
		{
			$data=array(
				'content' =>$this->renderView('BugglMainBundle:LocalAuthor\Dashboard:followerModal.html.twig', array('text' => 0))
			);
			return new JsonResponse($data,200);
		}

	}

}