<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Service\StatsNativeQuery;

/**
 * AdminStatsService
 *
 * @author    Vincent Farly Tabaoda <farly.taboada@goabroad.com>
 *
 * @copyright 2013 (c) Buggl.com
 */
class AdminStatsService
{
    /**
     * @var StatsNativeQuery
     */
    private $statsNativeQuery;

    /**
     * constructor
     * @param StatsNativeQuery $statsQuery
     */
    public function __construct( StatsNativeQuery $statsQuery )
    {
        $this->statsNativeQuery = $statsQuery;
    }

    /**
     * returns the monthly guides built
     * @param String $format
     *
     * @return Array
     */
    public function getMontlyBuiltGuides( $format = "F" )
    {
        $month = date('n');
        $year = date('Y');

        //$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
        $information = array();
        for ($i = 1; $i <= $month; $i++) {
            $monthName = date($format, mktime(0, 0, 0, $i));
            $information[] = array(
                $monthName,
                $this->statsNativeQuery->countMonthlyPublishedEguide($i, $year)
            );
        }

        return $information;
    }

    /**
     * returns the monthly authors that joined
     * @param String $format
     *
     * @return Array
     */
    public function getMonthlyJoinedAuthors( $format = "F" )
    {
        $month = date('n');
        $year = date('Y');

        for ($i = 1; $i <= $month; $i++) {
            $monthName = date($format, mktime(0, 0, 0, $i));
            $information[] = array(
                $monthName,
                $this->statsNativeQuery->countMonthlyJoinedLocalAuthor($i, $year)
            );
        }

        return $information;
    }

    /**
     * retuns the monthly revenue of buggl
     * @param String $format
     *
     * @return Array
     */
    public function getMonthlyBugglRevenue( $format = "F" )
    {
        $month = date('n');
        $year = date('Y');

        for ($i = 1; $i <= $month; $i++) {
            $monthName = date($format, mktime(0, 0, 0, $i));
            $information[] = array(
                $monthName,
                number_format(($this->statsNativeQuery->countMonthlyBugglRevenue($i, $year)/100), 2)
            );
        }

        return $information;
    }
}