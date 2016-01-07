<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExportToCsvController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
	/**
     * export all local author to csv
     * @param Request $request []
     * @author Nash Lesigon <nash.lesigon@goabroad.com>
     * @return Response
     */
	public function authorToCsvAction(Request $request)
	{
		$exportToCsvService = $this->get("buggl_mail.export_local_author_to_csv");
		return $this->downloadResponse( $exportToCsvService );
	}

	/**
     * export all local author to csv
     * @param Request $request []
     * @author Nash Lesigon <nash.lesigon@goabroad.com>
     * @return Response
     */
	public function guideToCsvAction(Request $request)
	{
		$exportToCsvService = $this->get("buggl_mail.export_guides_to_csv");
		return $this->downloadResponse( $exportToCsvService );
	}

	private function downloadResponse($service)
	{
		$result = $service->execute();
		$csvFile = $result['file'];
		$filename = $result['filename'];

		$response = new \Symfony\Component\HttpFoundation\Response(); 
        $response->setStatusCode(200); 
        $response->setContent(file_get_contents($csvFile));
        $response->headers->set('Content-Type', 'application/stream'); 
        $response->headers->set('Content-length', filesize($csvFile)); 
        $response->headers->set('Content-Disposition',sprintf('attachment;filename="%s"', $filename)); 
        $response->send(); 
        return $response;
	}
}