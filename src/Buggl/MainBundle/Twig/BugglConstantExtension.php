<?php

namespace Buggl\MainBundle\Twig;

use Buggl\MainBundle\Helper\BugglConstant;

class BugglConstantExtension extends \Twig_Extension
{
    private $constants;

    public function __construct(BugglConstant $constants)
    {
        $this->constants = $constants;
    }

    public function getFilters()
    {
        return array(
            'constant' => new \Twig_Filter_Method($this, 'constant')
        );
    }

    public function constant($key)
    {
        return $this->constants->get($key);
    }

    public function getName()
    {
        return 'buggl_constant_extension';
    }
}