<?php

namespace Buggl\MainBundle\Twig;

class DateHelperExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'convertDateTime' => new \Twig_Filter_Method($this, 'formatDateTime'),
			'showTimeElapsed' => new \Twig_Filter_Method($this, 'showTimeElapsed'),
			'showTimeElapsedForEguideUpdate' => new \Twig_Filter_Method($this, 'showTimeElapsedForEguideUpdate'),
			'showTimeLeft' => new \Twig_Filter_Method($this, 'showTimeLeft'),
			'isExpired' => new \Twig_Filter_Method($this, 'isExpired'),
        );
    }

	public function formatDateTime($dateTime,$format='Y-m-d H:i:s')
	{
		return $dateTime->format($format);
	}

	public function showTimeElapsed($dateTime, $longAgoLimit='month')
	{
		$longAgoButThisYearFormat = 'M j g:i a';
		$longAgoFormat = 'n/j/y';
		$thisWeekFormat = 'D g:i a';

		$timeElapsed = 'a few seconds ago';
		$diff = $dateTime->diff(new \DateTime(date('Y-m-d H:i:s')));

		if($diff->y > 0){
			$timeElapsed = $dateTime->format($longAgoFormat);
		}
		else if($diff->m > 0){
			$thisYear = date('Y');
			if($thisYear > $dateTime->format('Y'))
				$timeElapsed = $dateTime->format($longAgoFormat);
			else
				$timeElapsed = $dateTime->format($longAgoButThisYearFormat);
		}
		else if($diff->d > 0){
			if($diff->d > 7 || $longAgoLimit == 'day')
				$timeElapsed = ($longAgoLimit == 'day' ? $dateTime->format($longAgoButThisYearFormat) : $diff->d.' days ago');
			else
				$timeElapsed = $dateTime->format($thisWeekFormat);
		}
		else if($diff->h > 0){
			$today = date('d');
			if($today > $dateTime->format('d'))
				$timeElapsed = 'yesterday';
			else
				$timeElapsed = $diff->h.($diff->h > 1 ? ' hours ago' : ' hour ago');
		}
		else if($diff->i > 0){
			$timeElapsed = $diff->i.($diff->i > 1 ? ' minutes ago' : ' minute ago');
		}
		else if($diff->s > 0){
			$timeElapsed = ($diff->s > 5 ? $diff->s.' seconds ago' : 'a few seconds ago');
		}

		return $timeElapsed;
	}

	public function showTimeElapsedForEguideUpdate($dateTime)
	{
		$dataFormat = 'M j Y';

		$timeElapsed = '';
		$diff = $dateTime->diff(new \DateTime(date('Y-m-d H:i:s')));

		if($diff->y > 0){
			$timeElapsed = $dateTime->format($dataFormat);
		}
		else if($diff->m > 0){
			$timeElapsed = $diff->m.($diff->m > 1 ? ' months ago' : ' month ago');
		}
		else if($diff->d > 0){
			$timeElapsed = $diff->d.($diff->d > 1 ? ' days ago' : ' day ago');
		}
		else if($diff->h > 0){
			$today = date('d');
			if($today > $dateTime->format('d'))
				$timeElapsed = 'yesterday';
			else
				$timeElapsed = $diff->h.($diff->h > 1 ? ' hours ago' : ' hour ago');
		}
		else if($diff->i > 0){
			$timeElapsed = $diff->i.($diff->i > 1 ? ' minutes ago' : ' minute ago');
		}
		else if($diff->s > 0){
			$timeElapsed = ($diff->s > 5 ? $diff->s.' seconds ago' : 'a few seconds ago');
		}

		return $timeElapsed;
	}

	public function showTimeLeft($dateTime, $preferedTimeMeasure = null)
	{
		$today = new \DateTime(date('Y-m-d H:i:s'));

		$diff = strtotime($dateTime->format('Y-m-d H:i:s')) - strtotime(date('Y-m-d H:i:s'));

		$timeMeasures = array(
			'second' => 1,
			'minute' => 60,
			'hour' => 60,
			'day' => 24,
			'month' => 30,
			'year' => 12
		);

		$timeDifference = $diff.' second'.($diff > 1 ? 's' : '');;
		foreach($timeMeasures as $key => $val){
			if($diff >= $val){
				$diff /= $val;;
				$timeDifference = round($diff,0,PHP_ROUND_HALF_UP).' '.$key.($diff > 1 ? 's' : '');
				if(!is_null($preferedTimeMeasure) && $preferedTimeMeasure == $key )
					break;
			}
			else{
				break;
			}
		}

		return $timeDifference;
	}

	public function isExpired($date)
	{
		$result = $date->format('Y-m-d H:i:s');
		return strtotime($result) < strtotime(date('Y-m-d H:i:s'));
	}

    public function getName()
    {
        return 'buggl_date_helper_extension';
    }
}