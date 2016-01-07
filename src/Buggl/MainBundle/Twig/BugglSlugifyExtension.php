<?php

namespace Buggl\MainBundle\Twig;

class BugglSlugifyExtension extends \Twig_Extension
{
    public function __construct($slugifier)
    {
        $this->slugifier = $slugifier;
    }


    public function getFilters()
    {
        return array(
            'slugify' => new \Twig_Filter_Method($this, 'slugify'),
        );
    }

    public function slugify($text, $append="")
    {
		$this->slugifier->format($text);
		if(!empty($append))
			$this->slugifier->append($append);

        return $this->slugifier->getSlug();
    }

    public function getName()
    {
        return 'buggl_slugify_extension';
    }
}