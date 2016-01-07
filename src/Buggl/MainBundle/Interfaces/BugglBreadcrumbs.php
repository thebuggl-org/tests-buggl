<?php

namespace Buggl\MainBundle\Interfaces;

interface BugglBreadcrumbs
{
    public function set($parameters);
    public function getBreadcrumbs();
}