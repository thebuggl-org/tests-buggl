<?php

namespace Buggl\MainBundle\Twig;

class TravelInfoExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'renderByField' => new \Twig_Filter_Method($this, 'renderByField'),
        );
    }

    public function renderByField($travelInfo, $fieldId)
    {
		$content = "";
		
		if(is_null($travelInfo)){
			$content = "No Content Yet.";
		}
		else{
			if($fieldId == 0){
				$content = $travelInfo->getWhyAmITheBest();
			}
			else if($fieldId == 1){
				$content = $travelInfo->getHowILoveToTravel();
			}
			else if($fieldId == 2){
				$content = $travelInfo->getBestTravelExp();
			}
			else if($fieldId == 3){
				$content = $travelInfo->getWorstTravelExp();
			}
		}
		
        return $content;
    }

    public function getName()
    {
        return 'travel_info_extension';
    }
}