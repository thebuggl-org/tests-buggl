<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\NotNull;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Form\FormError;

use  Buggl\MainBundle\Entity\RotatingFeature;

class AdController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    const FEATURED_IN_HOME = 1;

    public function indexAction(Request $request)
    {
		$rotatingFeatures = $this->getDoctrine()->getRepository('BugglMainBundle:RotatingFeature')->findAll();

		$data = array(
			'tab' => 'rotating',
			'rotatingFeatures' => $rotatingFeatures
		);

        return $this->render('BugglMainBundle:Admin/Ad:rotating.html.twig',$data);
    }

	public function getRotatingFeatureFormAction(Request $request)
	{
		$success = false;
		$edit = false;
		$builder = $this->createFormBuilder();
		$id = $request->get('id',0);
		if($id == 0){
			$rotatingFeature = new RotatingFeature();
			$photoPath = '/bundles/bugglmain/images/custom/940_500.jpg';
			$rsm = new ResultSetMapping();
			$rsm->addEntityResult('Buggl\MainBundle\Entity\EGuide', 'eguide');
			$rsm->addFieldResult('eguide', 'id', 'id');
			$rsm->addFieldResult('eguide', 'plain_title', 'plainTitle');

			$query = $this->getDoctrine()
						  ->getEntityManager()
						  ->createNativeQuery('SELECT `e_guide`.`id`,`plain_title` FROM `e_guide` LEFT JOIN `rotating_feature` ON `e_guide`.`id` = `rotating_feature`.`eguide_id`  WHERE 1 AND `rotating_feature`.`eguide_id` is null AND `e_guide`.`is_featured_in_home` = ? AND `e_guide`.`status` = ?', $rsm);
			$query->setParameter(1, 0);
			$query->setParameter(2, 2);
			$guides = $query->getResult();
			$choices = array();
			foreach($guides as $guide)
				$choices[$guide->getId()] = $guide->getPlainTitle();
			$builder->add('guide', 'choice', array('choices' => $choices, 'empty_value' => '', 'attr' => array('data-placeholder' => 'Guide','data-url' => $this->generateUrl('admin_ajax_get_guide_info')),'constraints' => array(new NotNull(array('message'=>'Please select a guide.')))));
			$builder->add('photo', 'file', array('constraints' => array(new NotNull(array('message'=>'Please upload a photo.')))));
		}
		else{
			$edit = true;
			$rotatingFeature = $this->getDoctrine()->getRepository('BugglMainBundle:RotatingFeature')->findOneById($id);
			$photoPath = '/'.$rotatingFeature->getImageWebPath();
			$builder->add('guide', 'hidden', array('attr' => array('value' => $rotatingFeature->getId())));
			$builder->add('photo', 'file', array('required' => false) );
		}

		$form = $builder->getForm();

		if($request->isMethod('POST')){
			if(empty($_FILES))
				exit;
				
			$form->bindRequest($request);
			if($form->isValid()){
				$formData = $form->getData();
				$file = $formData['photo'];
				if(!is_null($file)){
					$extension = $file->guessExtension();
					 // extension cannot be guessed
					if (!$extension)
					    $extension = 'bin';

					$uploadPath = 'uploads/rotating_feature';
			        if(!file_exists($uploadPath))
			            mkdir($uploadPath,0777,true);

					$path = sha1(uniqid(mt_rand(), true)).'.'.$extension;
					$file->move($uploadPath, $path);

					$rotatingFeature->setEguide($this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($formData['guide']));
					$rotatingFeature->setPhoto($path);
					$this->getDoctrine()->getEntityManager()->persist($rotatingFeature);
					$this->getDoctrine()->getEntityManager()->flush();
				}
				if($edit){
					$this->get('session')->getFlashBag()->add('success', "\"".$rotatingFeature->getEguide()->getPlainTitle()."'s\" photo updated.");
				}
				else{
					$this->get('session')->getFlashBag()->add('success', "You have successfully set \"".$rotatingFeature->getEguide()->getPlainTitle()."\" as a rotating feature.");
				}

				$success = true;
			}
		}

		if($success){
			$data = array(
				'status' => 'SUCCESS',
			);
		}
		else{
			$data = array(
				'status' => 'ERROR',
				'html' => $this->renderView('BugglMainBundle:Admin/Ad:rotatingFeatureForm.html.twig',array('form'=>$form->createView(),'photoPath' => $photoPath,'id'=>$id))
			);
		}

		return new JsonResponse($data,200);
	}

	public function deleteRotatingFeatureAction(Request $request)
	{
		$id = $request->get('id',0);
		$rotatingFeature = $this->getDoctrine()->getRepository('BugglMainBundle:RotatingFeature')->findOneById($id);

		if(!is_null($rotatingFeature)){
			$this->getDoctrine()->getEntityManager()->remove($rotatingFeature);
			$this->getDoctrine()->getEntityManager()->flush();
			$this->get('session')->getFlashBag()->add('success', "Rotating feature removed.");
		}
		else{
			$this->get('session')->getFlashBag()->add('error', "The rotating feature does not exist.");
		}

		return new RedirectResponse($this->generateUrl('admin_ad'));
	}

    public function featuredAction()
    {

        $status = $this->get('buggl_main.constants')->get('published');

        $lists = $this->get('buggl_main.entity_repository')
                      ->getRepository('BugglMainBundle:EGuide')
                      ->findFeaturedGuidesInHome(1,$status);

        $data = array(
            'tab' => 'featured',
            'url' => 'admin_unfeatured_guide',
            'lists' => $lists
        );

        return $this->render('BugglMainBundle:Admin/Ad:featured.html.twig',$data);
    }

    public function spotLightAction(Request $request)
    {
		$spotlight = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneByIsSpotlight(1);
		$default = is_null($spotlight) ? '' : $spotlight->getId();
        $status = $this->get('buggl_main.constants')->get('published');
        $repo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide');
        $lists = $repo->findFeaturedGuidesInHome(1,$status);
		$choices = array();
		foreach($lists as $list)
			$choices[$list->getId()] = $list->getPlainTitle();

		$builder = $this->createFormBuilder();
		$builder->add('guide', 'choice', array('choices' => $choices, 'data' => $default,'empty_value' => '', 'attr' => array('data-placeholder' => 'Guide','data-url' => $this->generateUrl('admin_ajax_get_guide_info')),'constraints' => array(new NotNull(array('message'=>'Please select a guide.')))));
		$builder->add('photo', 'file');
		$form = $builder->getForm();

		if($request->isMethod('POST')){
			if(empty($_FILES)){
				$form->get('photo')->addError(new FormError('File size too large.'));
				$builder->add('guide', 'choice', array('choices' => $choices, 'data' => '','empty_value' => '', 'attr' => array('data-placeholder' => 'Guide','data-url' => $this->generateUrl('admin_ajax_get_guide_info')),'constraints' => array(new NotNull(array('message'=>'Please select a guide.')))));
				$form = $builder->getForm();
			}
			else{
				$form->bind($request);
				$formData = $form->getData();
				$spotlight = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneById($formData['guide']);
			
				if($form->isValid()){
					$file = $form['photo']->getData();
					if(!is_null($file)){
						$extension = $file->guessExtension();
						 // extension cannot be guessed
						if (!$extension)
						    $extension = 'bin';

						$uploadPath = 'uploads/spotlight';
				        if(!file_exists($uploadPath))
				            mkdir($uploadPath,0777,true);

						$path = sha1(uniqid(mt_rand(), true)).'.'.$extension;
						$file->move($uploadPath, $path);

						$prevSpotlight = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneByIsSpotlight(1);
						if(!is_null($prevSpotlight)){
							$prevSpotlight->setIsSpotlight(0);
							$this->getDoctrine()->getEntityManager()->persist($prevSpotlight);
						}

						//$guide = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneById($form['guide']->getData());
						$prevPhoto = $spotlight->getSpotlightPhotoAbsolutePath();
						@unlink($prevPhoto);

						$spotlight->setSpotlightPhoto($path);
						$spotlight->setIsSpotlight(1);
						$this->getDoctrine()->getEntityManager()->persist($spotlight);
						$this->getDoctrine()->getEntityManager()->flush();

						$this->get('session')->getFlashBag()->add('success', "You have successfully set \"".$spotlight->getPlainTitle()."\" in spotlight.");
						return new RedirectResponse($this->generateUrl('admin_spotlight'));
					}
					else{
						if(!is_null($spotlight->getSpotlightPhotoWebPath())){
							$prevSpotlight = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneByIsSpotlight(1);
							if(!is_null($prevSpotlight)){
								$prevSpotlight->setIsSpotlight(0);
								$this->getDoctrine()->getEntityManager()->persist($prevSpotlight);
							}
						
							$spotlight->setIsSpotlight(1);
							$this->getDoctrine()->getEntityManager()->persist($spotlight);
							$this->getDoctrine()->getEntityManager()->flush();

							$this->get('session')->getFlashBag()->add('success', "You have successfully set \"".$spotlight->getPlainTitle()."\" in spotlight.");
							return new RedirectResponse($this->generateUrl('admin_spotlight'));
						}
						else{
							$form->get('photo')->addError(new FormError('Please upload a photo!'));	
						}
					}
				}
			}
		}

        $data = array(
            'tab' => 'spotlight',
            'spotlight' => $spotlight,
			'form' => $form->createView(),
			'default' => $default
        );

        return $this->render('BugglMainBundle:Admin/Ad:spotlight.html.twig',$data);
    }
	
	public function removeSpotLightAction(Request $request)
	{
		$spotlight = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneByIsSpotlight(1);
		if(!is_null($spotlight)){
			$spotlight->setIsSpotlight(0);
			$this->getDoctrine()->getEntityManager()->persist($spotlight);
			$this->getDoctrine()->getEntityManager()->flush();
			
			$this->get('session')->getFlashBag()->add('success', "You have removed the spotlight guide.");
		}
		else{
			$this->get('session')->getFlashBag()->add('error', "The spotlight guide does not exist.");
		}
		
		return new RedirectResponse($this->generateUrl('admin_spotlight'));
	}

    public function featureAdAction(Request $request)
    {
        $id = $request->get('id',0);

        $entityManager = $this->get('doctrine.orm.entity_manager');

        $repo = $entityManager->getRepository('BugglMainBundle:EGuide');

        $status = $this->get('buggl_main.constants')->get('published');
        $guides = $repo->findFeaturedGuidesInHome(1,$status);

        $limit = $this->get('buggl_main.constants')->get('featured_in_home_limit');
        $count = count($guides);

        if( $count < $limit ){
            $eguide = $entityManager->find('BugglMainBundle:Eguide',$id);

            if(!is_null($eguide)){
                $feature = $eguide->getIsFeaturedInHome() ? 0 : 1;

                $eguide->setIsFeaturedInHome($feature);

                $entityManager->flush();

                $html = $this->renderView('BugglMainBundle:Admin/Ad:list.html.twig',array('guide' => $eguide));

                $success = true;
                $message = $html;
            }
            else{
                $success = false;
                $message = 'Guide does not exist';
            }
        }
        else{
            $success = false;
            $message = 'LIMIT REACHED - '.$limit;
        }

        $response = array('success' => $success, 'message' => $message );

        return new JsonResponse($response,200);
    }

	public function unfeatureAction(Request $request)
	{
		$guideId = $request->get('guideId',0);
		$guide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($guideId);

		if(!is_null($guide))
		{
			$title = $guide->getPlainTitle();
			$guide->setIsFeaturedInHome(0);
			$guide->setIsSpotlight(0);
			$this->getDoctrine()->getEntityManager()->persist($guide);
			$this->getDoctrine()->getEntityManager()->flush();

			$this->get('session')->getFlashBag()->add('success', "You have unfeatured \"".$title."\" out of homepage.");
			return new RedirectResponse($this->generateUrl('admin_featured'));
		}

		$this->get('session')->getFlashBag()->add('error', "The guide does not exist.");
		return new RedirectResponse($this->generateUrl('admin_featured'));
	}

	public function getGuideInfoAction(Request $request)
	{
		$guideId = $request->get('guideId',0);
		$guide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($guideId);

		$data = array(
			'guideTitle' => is_null($guide) ? 'Guide Title' : $guide->getPlainTitle(),
			'spotlightPhoto' => is_null($guide) ? '' : '/'.$guide->getSpotlightPhotoWebPath(),
			'authorName' => is_null($guide) ? 'Guide Author' : $guide->getLocalAuthor()->getName(),
			'authorPic' => is_null($guide) || is_null($guide->getLocalAuthor()->getProfile()) || is_null($guide->getLocalAuthor()->getProfile()->getImageWebPath()) ? '/bundles/bugglmain/images/profile-big.jpg' : '/'.$guide->getLocalAuthor()->getProfile()->getImageWebPath(),
		);

		return new JsonResponse($data,200);
	}

	private function getFeaturedGuideChoices()
	{
        $status = $this->get('buggl_main.constants')->get('published');
        $repo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide');
        $lists = $repo->findFeaturedGuidesInHome(1,$status);
		$choices = array();
		foreach($lists as $list)
			$choices[$list->getId()] = $list->getPlainTitle();

		return $choices;
	}
}