<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Lib\gapi;

class GoogleAnalyticsService
{
	private $apiService;
	private $account = 'sampler08@gmail.com';
	private $pwd = 'ga3143password';
	private $profileId = '72872245';

	private $methods = array(
		'pageviews' => 'PageViews',
		'visits' => 'Visits',
		'newVisits' => 'NewVisits'
	);

	protected $environmentVars;

	public function __construct($environmentVars)
	{
		$this->environmentVars = $environmentVars;

		$this->apiService = new gapi($this->account,$this->pwd);
	}

	public function getAnalyticsData($params)
	{
		$startDate = isset($params['start_date']) ? $params['start_date'] : null;
		$endDate = isset($params['end_date']) ? $params['end_date'] : null;
		$filter = isset($params['filter']) ? $params['filter'] : null;
		$aggregation = isset($params['aggregation']) ? $params['aggregation'] : 'date';
		$metrics = isset($params['metrics']) ? $params['metrics'] : array('visits');

		$dimensions = array($aggregation);

		$keyMethod = 'get'.ucwords($aggregation);

		$this->apiService->requestReportData($this->profileId,$dimensions,$metrics,null,$filter,$startDate,$endDate,1,1000);

		$results = array();
		foreach($this->apiService->getResults() as $data){
			$result = array();
			foreach($metrics as $metric){
				$method = 'get'.$this->methods[$metric];
				$result[$metric] = $data->$method();
			}
			$results[$data->$keyMethod()] = $result;
		}

		return $results;
	}

}