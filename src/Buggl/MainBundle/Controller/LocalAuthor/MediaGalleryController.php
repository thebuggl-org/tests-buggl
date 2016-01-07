<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Buggl\MainBundle\Entity\LocalAuthorPhoto;

/**
 * MediaGalleryController
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 */
class MediaGalleryController extends Controller
                             implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    /**
     * limit in fetching of photos per page.
     *
     * @var   int constant
     */
    const LIMIT = 12;

    /**
     * default page for gallery
     *
     * @return response response in a form of template|view
     */
    public function indexAction()
    {
        $page = 1;

        $user = $this->get('security.context')->getToken()->getUser();

        $objects = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:LocalAuthorPhoto')
                       ->findByLocalAuthor($user, self::LIMIT, $page);

        $images = array();
        foreach ($objects as $object) {
            $images[] = array( 'id' => $object->getId(), 'path' => $object->getWebImagePath() );
        }

        $data = array(
                'activeTab' => 'photos',
                'images' => json_encode($images),
                'totalCount' => count($objects),
                'limit' => self::LIMIT
            );

        return $this->render('BugglMainBundle:LocalAuthor\MediaGallery:mediaGalleryPhotos.html.twig', $data);
    }

    /**
     * controller called when fetching photos via ajax
     * @param Request $request request paramters are stored here
     *
     * @return response
     */
    public function fetchPictureAction(Request $request)
    {
        $page = $request->get('page', 1);

        $user = $this->get('security.context')->getToken()->getUser();
        $objects = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:LocalAuthorPhoto')
                       ->findByLocalAuthor($user, self::LIMIT, $page);

        $images = array();
        foreach ($objects as $object) {
            $images[] = array(
                    'id' => $object->getId(),
                    'path' => $object->getWebImagePath()
                );
        }

        return new JsonResponse($images, 200);
    }

    /**
     * controller for videos page
     * @return template
     *
     * @deprecated not used yet
     */
    public function videosAction()
    {
        return $this->render(
            'BugglMainBundle:LocalAuthor\MediaGallery:mediaGalleryVideos.html.twig',
            array('activeTab' => 'videos')
        );
    }

    /**
     * controller of spots page
     * @return template
     * @author Nash Lesigon <nash.lesigon@goabroad.com>
     */
    public function spotsAction()
    {
        // return $this->render('BugglMainBundle:LocalAuthor\MediaGallery:mediaGallerySpots.html.twig', array('activeTab' => 'spots'));
        $response = $this->forward('BugglMainBundle:LocalAuthor\Spot:index');

        return $response;
    }

    /**
     * controller for uploading photos via ajax
     * @param Request $request request params are stored here
     *
     * @return Response        format in json: info about the pic
     */
    public function uploadPicturesAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        $file = $request->files->get('file');
        $imageId = $request->get('imageId');

        $service = $this->get('buggl_main.photo_uploader');
        $photoUploader = $service->setOptions($isAjax, $file)->upload();

        $status = $photoUploader->getStatus();

        if (!$status['error']) {
            $user = $this->get('security.context')->getToken()->getUser();

            $photo = new LocalAuthorPhoto();
            $photo->setFilename($photoUploader->getFilename());
            $photo->setDateAdded(new \DateTime(date('Y-m-d H:i:s', time())));
            $photo->setTags(json_encode(array()));
            $photo->setCaption('');
            $photo->setLocalAuthor($user);
            $photo->setPath($service->getPath());

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($photo);
            $em->flush();

            $path = '/'.$photo->getWebImagePath();
            $objectId = $photo->getId();

            $success = true;
        } else {
            $path = '';
            $success = false;
            $objectId = 0;
        }


        $data = array(
            'success' => $success,
            'path' => $path,
            'imageId' => $imageId,
            'objectId' => $objectId
        );

        return new JsonResponse($data, 200);
    }

    /**
     * controller for deleting photos
     * @param Request $request
     *
     * @return Response           in JSON format
     */
    public function deletePicturesAction(Request $request)
    {
        $path = $this->get('buggl_main.constants')->get('web_path');
        $ids = $request->request->get('photos');

        $photoIDs = array_map(
            function($value){
                return $value;
            }, $ids);

        $photos = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:LocalAuthorPhoto')
                       ->findByPKs($photoIDs);

        $em = $this->getDoctrine()->getEntityManager();
        foreach ($photos as $photo) {
            $em->remove($photo);
            @unlink($path.$photo->getWebImagePath());
        }
        $em->flush();

        $data = array('success'=>true);

        return  new JsonResponse($data, 200);
    }
}