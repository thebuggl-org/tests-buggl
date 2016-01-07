<?php

namespace Buggl\MainBundle\Controller\Common;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Buggl\MainBundle\Form\Type\ContactUsType;

use Buggl\MainBundle\Event\Mail\ContactUsEvent;

/**
 * Class for contact us related controllers
 */
class ContactUsController extends Controller
{
    /**
     * Controller for contact us page
     * @param Request $request
     *
     * @return Response
     */
    public function contactUsAction(Request $request)
    {
		$contactUs = new \Buggl\MainBundle\Entity\ContactUs();
		$form = $this->createForm(new ContactUsType(), $contactUs);

        if ($request->isMethod('POST')) {
			$form->bind($request);
            if($form->isValid()){
            	$contactUs = $form->getData();
				$contactUs->setDateContacted(new \DateTime(date('Y-m-d H:i:s')));
				$contactUs->setStatus($this->get('buggl_main.constants')->get('CONTACT_US_UNREAD'));
				$this->getDoctrine()->getEntityManager()->persist($contactUs);
				$this->getDoctrine()->getEntityManager()->flush();

				$this->get('session')->getFlashBag()->add('success', "Your request has been submitted. We will get back to you shortly.");

                $event = new ContactUsEvent($this->get('buggl_main.mail_message_builder'),$contactUs);
                $this->get('event_dispatcher')->dispatch('buggl.contact_us',$event);

				return new RedirectResponse($this->generateUrl('buggl_static_contact_us'));
            }
        }

        $slug = substr( strrchr($request->getUri(), "/"), 1 );
        $metas = $this->get('buggl_seo.static_page')->buildMetaAttributes($slug);

        return $this->render('BugglMainBundle:Common/ContactUs:index.html.twig',array('form' => $form->createView(), 'metas' => $metas));
    }
}