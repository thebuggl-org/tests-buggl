<?php

namespace Buggl\MainBundle\Twig;

class BugglCssSelectedExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'selected' => new \Twig_Filter_Method($this, 'cssClassSelected'),
            'decimal' => new \Twig_Filter_Method($this,'decimal'),
			'eguideEventClass' => new \Twig_Filter_Method($this,'eguideEventClass'),
        );
    }

    public function cssClassSelected($selected, $current, $class = "selected")
    {
        if($selected == $current){
            return $class;
        }

        return "";
    }

	public function eguideEventClass($eventType)
	{
		$classes = array(
			'0' => 'activity-added',
			'1' => 'activity-purchased',
			'2' => 'activity-edited',
			'3' => 'activity-deleted',
			'4' => 'activity-featured',
		);
		
		return $classes[$eventType];
	}

    public function decimal($number)
    {
        $numbers = explode('.', $number);

        return $numbers[1];
    }

    public function getName()
    {
        return 'buggl_css_selected_extension';
    }
}