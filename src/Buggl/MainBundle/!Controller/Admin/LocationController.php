<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;

use Buggl\MainBundle\Form\Type\CategoryType;
use Buggl\MainBundle\Form\Type\CountryType;
use Buggl\MainBundle\Form\Type\CityType;

/**
 * controller related to location/country
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 *
 * @copyright 2013 April (c) Buggl.com
 */
class LocationController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    /**
     * @var integer limit of countries per page
     */
    const LIMIT = 5;


    /**
     * default page for countries
     *
     * @return Reposne html page
     */
    public function indexAction()
    {
        $data = array(
            'tab' => 'main'
        );

        $template = 'BugglMainBundle:Admin/Location:locations.html.twig';

        return $this->render($template, $data);
    }

    /**
     * get countries
     * @return Response html page
     */
    public function countryAction()
    {
        $status = $this->get('buggl_main.constants')->get('APPROVED_COUNTRY');

        $countries = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:Country')
                          ->findAllByStatus($status);

        $featured = array();

        $source = array_map(
            function($object){
                return array('id' => $object->getId(), 'name' => $object->getName());
            }, $countries
        );

        $featured = array();
        foreach ($source as $each) {
            $status = $this->get('buggl_main.constants')->get('featured_guide_in_country');
            $guides = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:EGuide')
                       ->findFeaturedInCountry($each['id'], $status);

            $featured[$each['id']] = $guides;
        }

        $data = array(
                'tab' => 'country',
                'lists' => $countries,
                'source' => $source,
                'limit' =>  self::LIMIT,
                'featured' => $featured
            );

        return $this->render('BugglMainBundle:Admin/Location:country.html.twig', $data);
    }

    /**
     * get country hmtl form for adding/editing
     * @param Request $request []
     *
     * @return JsonResponse html form for adding/editing country
     */
    public function getCountryFormAction(Request $request)
    {
        $id = $request->get('id');

        if ($id > 0) {
            $country = $this->getDoctrine()
                        ->getEntityManager()
                        ->find('BugglMainBundle:Country', $id);
        } else {
            $country = null;
        }

        $form = $this->createForm(new CountryType(), $country);
        $template = 'BugglMainBundle:Admin/Location:countryForm.html.twig';
        $params = array('form'=> $form->createView(), 'id'=>$id);

        $data = array(
            'html' => $this->renderView($template, $params)
        );

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }


    /**
     * save country
     *
     * @param Request $request []
     *
     * @return JsonResponse     returns info about country
     */
    public function saveCountryAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $form = $this->createForm(new CountryType());

            $form->bind($request);

            $id = $request->get('form_is_new');
            if ($form->isValid()) {
                $service = $this->get('buggl_main.location')->initCountry($form->getData(), $request->get('form_is_new'));
                $html = $service->saveCountry();

                // $html = $this->renderView('BugglMainBundle:Admin\Location:countryForm.html.twig',array('form'=>$form->createView()));
                $html = null;
                $success = true;
            } else {
                $html = $this->renderView('BugglMainBundle:Admin\Location:countryForm.html.twig', array('form'=>$form->createView(), 'id'=>$id));
                $success = false;
            }
            $data = array('html' => $html,'success'=>$success);
        } else {
            $data = array('success' => false);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }


    /**
     * save cateogory
     *
     * @param Request $request []
     *
     * @return JsonResponse     response in json format with html form as value of key
     */
    public function getCategoryFormAction(Request $request)
    {
        $id = $request->get('id');
        $createNew = $request->get('createNew');

        if (!$createNew) {
            $categoryToCountry = $this->getDoctrine()
                        ->getEntityManager()
                        ->find('BugglMainBundle:CategoryToCountry', $id);

            $file = $categoryToCountry->getImageWebPath();
        } else {
            $categoryToCountry = new \Buggl\MainBundle\Entity\CategoryToCountry();

            $country = $this->getDoctrine()
                            ->getEntityManager()
                            ->find('BugglMainBundle:Country', $id);

            $categoryToCountry->setCountry($country);

            $file = '';
        }

        $repository =  $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Category');
        $status = $this->get('buggl_main.constants')->get('published_category');
        $form = $this->createForm(new CategoryType(array('status' => $status, 'repository'=>$repository)), $categoryToCountry);

        $html = $this->renderView('BugglMainBundle:Admin/Location:categoryForm.html.twig',
            array(
                'form'=> $form->createView(),
                'createNew'=>$createNew,
                'id'=>$id,
                'file' => $file
        ));

        return new \Symfony\Component\HttpFoundation\JsonResponse(array('html' => $html), 200);
    }

    /**
     * saves category
     * @param Request $request []
     *
     * @return JsonResponse info about the category in json format
     */
    public function saveCategoryAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();

        if ($request->getMethod() == 'POST') {
            $status = $this->get('buggl_main.constants')->get('published_category');
            $repository =  $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Category');
            $form = $this->createForm(new CategoryType(array('status'=>$status, 'repository' => $repository)), new \Buggl\MainBundle\Entity\CategoryToCountry());

            $form->bind($request);

            $createNew = $request->get('form_is_new');
            $id = $request->get('country_if_new');

            $file = $request->files->get('category_to_country');
            $file = $file['picFilename'];

            $valid = $form->isValid();

            if (is_null($file) && $createNew) {
                $valid = (false && $valid);
            }

            if ($valid) {
                // uploads via submit
                // thus the files will be at $_FILES;

                if (!is_null($file)) {
                    $service = $this->get('buggl_main.photo_uploader');
                    $photoUploader = $service->setOptions($isAjax, $file)->upload();

                    $status = $photoUploader->getStatus();

                    if ($status['error']) {
                        $html = $this->renderView('BugglMainBundle:Admin\Location:categoryForm.html.twig',
                            array(
                                'form'=>$form->createView(),
                                'createNew' => $createNew,
                                'id' => $id,
                        ));

                        $success = false;

                        $data = array(
                                'html' => $html,
                                'success' => $success
                            );

                        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
                    }

                    $filename = $status['filename'];

                    $photo = $this->get('buggl_main.category_photo')
                                  ->saveCategoryPhoto($filename, $service->getPath());
                } else {
                    $photo = null;
                }

                $service = $this->get('buggl_main.location')
                                ->initCategory(
                                    $form->getData(),
                                    $request->get('form_is_new'),
                                    $id,
                                    $photo
                                );
                $html = $service->saveCategory();

                $success = true;
            } else {
                if (!$createNew) {
                    $categoryToCountry = $this->getDoctrine()->getEntityManager()->find('BugglMainBundle:CategoryToCountry', $id);
                    $file = $categoryToCountry->getImageWebPath();
                }

                $html = $this->renderView('BugglMainBundle:Admin\Location:categoryForm.html.twig',
                    array(
                        'form'=>$form->createView(),
                        'createNew' => $createNew,
                        'id' => $id,
                        'file' => $file
                ));
                $success = false;
            }
            $data = array('html' => $html, 'success'=>$success, 'isNew'=>$createNew);
        } else {
            $data = array('success' => false);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }

    /**
     * shows cities
     *
     * @return Resposne html page
     */
    public function cityAction()
    {
        $city = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:City')
                          ->findAllOrderedByCountry();
        $data = array(
                'tab' => 'city',
                'lists' => $city
            );

        return $this->render('BugglMainBundle:Admin/Location:city.html.twig', $data);
    }

    /**
     * get city form
     * @param Request $request
     *
     * @return JsonResponse  html form as value in json key.
     */
    public function getCityFormAction(Request $request)
    {

        $id = $request->get('id', 0);

        if ($id) {
            $city = $this->getDoctrine()
                         ->getEntityManager()
                         ->find('BugglMainBundle:City', $id);
        } else {
            $city = null;
        }

        $form = $this->createForm(new CityType(), $city);

        $html = $this->renderView('BugglMainBundle:Admin/Location:cityForm.html.twig', array('form'=> $form->createView(), 'id'=>$id));

        $data = array(
            'html' => $html,
            'success' => true
        );

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }

    /**
     * saves city info
     * @param Request $request []
     *
     * @return JsonResponse     information of city in json format
     */
    public function saveCityAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $form = $this->createForm(new CityType());

            $form->bind($request);

            $id = $request->get('form_is_new');
            if ($form->isValid()) {
                $service = $this->get('buggl_main.location')->initCity($form->getData(), $request->get('form_is_new'));
                $html = $service->saveCity();

                // $html = $this->renderView('BugglMainBundle:Admin\Location:countryForm.html.twig',array('form'=>$form->createView()));
                $html = null;
                $success = true;
            } else {
                $html = $this->renderView('BugglMainBundle:Admin\Location:cityForm.html.twig', array('form'=>$form->createView(),'id'=>$id));
                $success = false;
            }
            $data = array('html' => $html, 'success'=>$success);
        } else {
            $data = array('success'=>false);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }

    /**
     * delete category
     * @param Request $request []
     *
     * @return JsonResponse     returns status
     */
    public function deleteCategoryAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getEntityManager();

        $object = $em->find('BugglMainBundle:CategoryToCountry', $id);

        if (is_null($object)) {
            $data = array('success' => false);
        } else {
            $em->remove($object);
            $em->flush();

            $data = array('success' => true);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }

    /**
     * get guide by country
     * @param Request $request []
     *
     * @return JsonResponse     countries in json format
     */
    public function fetchGuideByCountryAction(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = self::LIMIT;
        $country = $request->get('country', 0);
        $key = $request->get('key', '');

        if (!$country) {
            $data = array('data' => null,'count'=>0);
        } else {
            $objects = $this->get('buggl_main.entity_repository')
                            ->getRepository('BugglMainBundle:EGuide')
                            ->findByCountryFilteredByKey($country, $key, $limit, $page);

            $values = array();
            foreach ($objects as $object) {
                $values[] = array(
                    'title' => $object->getPlainTitle(),
                    'price' => $object->getPrice(),
                    'author' => $object->getLocalAuthor()->getName(),
                    'id' => $object->getId()
                );
            }

            $data = array('data'=>$values, 'count'=>count($objects));
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }

    /**
     * feature a selected guide
     * @param Request $request []
     *
     * @return JsonResponse     status of the featured guide
     */
    public function featureGuideAction(Request $request)
    {
        $id = $request->get('id');

        $eguide = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:EGuide', $id);


        if (!is_null($eguide)) {
            $eguide->setIsFeaturedInCountry($eguide->getIsFeaturedInCountry() ? 0 : 1);

            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();
            $data = array('success'=>true);
        } else {
            $data = array('succes'=>false);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }
}