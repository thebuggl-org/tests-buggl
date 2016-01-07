<?php

namespace Buggl\PhotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Buggl\MainBundle\Entity\CategoryPhoto;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BugglPhotoBundle:Default:index.html.twig');
    }

    public function uploadAction(Request $request)
    {

        $isAjax = $request->isXmlHttpRequest();

        $service = $this->get('buggl_photo.uploader_service')
             ->createUploadPath()
             ->setOptions(
                array(
                    'isAjax' => $isAjax,
                    'file' => $isAjax ? $request->get('file') : $_FILES['file'] 
                ))
             ->setPaths();

        $photoUploader = $service->upload()->getPhotoUploader();
        $status = $photoUploader->getStatus();

        $success = false;
        if(!$status['error']){
            $photo = new CategoryPhoto();
            $photo->setFilename($photoUploader->getFilename());
            $photo->setDateAdded( new \DateTime(date('Y-m-d H:i:s', time())));
            $photo->setTags(json_encode(array()));

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($photo);
            $em->flush();

            $sucess = true;
        }

        $result = array('e_id'=>$request->get('e_id'),'success'=>$success);
        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
