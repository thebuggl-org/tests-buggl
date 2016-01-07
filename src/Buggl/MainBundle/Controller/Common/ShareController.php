<?php

namespace Buggl\MainBundle\Controller\Common;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class for share related controllers
 */
class ShareController extends Controller
{
    /**
     * Controller for contact us page
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getGplusCountAction(Request $request)
    {
		$url = $request->get('url');
        
        if (strlen($url) == 0) {
            $data = array(
                'success' => false
            );
        } else {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            $curl_results = curl_exec ($curl);
            curl_close ($curl);

            $json = json_decode($curl_results, true);

            $data = array(
                'success' => true,
                'count' => (intval($json[0]['result']['metadata']['globalCounts']['count']))
            );
        }

        return new JsonResponse($data);
    }
	
	public function getPinCountAction(Request $request)
	{
		
		$url = $request->get('url','');
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.pinterest.com/v1/urls/count.json?url='.$request->headers->get('referer'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$httpResponse = curl_exec($ch);
		$response = json_decode(str_replace(')','',str_replace('receiveCount(','',$httpResponse)),true);
		curl_close($ch);
		
		if(isset($response['count'])){
			$count = $response['count'];
		}
		else{
			$count = 0;
		}
		
		$data = array(
			'count' => $count
		);
		
		return new JsonResponse($data,200);
	}
}