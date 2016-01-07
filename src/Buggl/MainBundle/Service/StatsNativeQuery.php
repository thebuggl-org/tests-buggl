<?php

namespace Buggl\MainBundle\Service;
use Doctrine\DBAL\Connection;
use PDO;
use Buggl\MainBundle\Helper\BugglConstant;

/**
 * StatsNativeQuery uses the raw sql statements
 *
 * @author    Vincent Farly Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 * @version   Release: v 1.0.0 April 2013
 * @todo      please improve this class.
 */
class StatsNativeQuery
{

    /**
     * @var Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @var Buggl\MainBundle\Helper\BugglConstant
     */
    private $constants;


    /**
     * Constructor
     * @param Connection    $connection [description]
     * @param BugglConstant $constants  [description]
     */
    public function __construct(Connection $connection,BugglConstant $constants)
    {
        $this->connection = $connection;
        $this->constants = $constants;
    }

    /**
     * count the monthly published eguide
     * @param Integer $month month number
     * @param Integer $year  year
     *
     * @return Integer        []
     */
    public function countMonthlyPublishedEguide($month,$year)
    {

        $featured = $this->constants->get('featured');
        $published = $this->constants->get('published');

        $sql = "SELECT count(eguide.id) FROM e_guide eguide WHERE MONTH(eguide.date_created) = $month AND YEAR(eguide.date_created) = $year AND ( eguide.status BETWEEN $published AND $featured )";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_NUM);

        return $count[0];
    }

    /**
     * count the number of local author that joins monthly
     * @param Integer $month month number
     * @param Integer $year  year
     *
     * @return Integer
     */
    public function countMonthlyJoinedLocalAuthor($month,$year)
    {

        $isLocalAuthor = $this->constants->get('LOCAL_AUTHOR');

        $sql = "SELECT count(author.id) FROM local_author author WHERE MONTH(author.date_joined) = $month AND YEAR(author.date_joined) = $year AND ( author.is_local_author = 1 )";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_NUM);

        return $count[0];
    }

    /**
     * totals the month revenue of buggl
     * @param Integer $month month number
     * @param Integer $year  year
     *
     * @return Decimal
     */
    public function countMonthlyBugglRevenue($month,$year)
    {
        // $sql = "SELECT sum(info.buggl_fee) FROM purchase_info info WHERE MONTH(info.date_of_transaction) = $month AND YEAR(info.date_of_transaction) = $year";
        $sql = "SELECT sum(info.buggl_fee) FROM paypal_purchase_info info WHERE MONTH(info.date_of_transaction) = $month AND YEAR(info.date_of_transaction) = $year";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_NUM);

        return is_null($count[0]) ? 0 : $count[0];
    }

}