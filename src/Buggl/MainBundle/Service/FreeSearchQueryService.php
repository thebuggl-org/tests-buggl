<?php

namespace Buggl\MainBundle\Service;
use Doctrine\DBAL\Connection;
use PDO;

class FreeSearchQueryService
{

    private $connection;
    private $objects;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function search($keywords, $status)
    {
        $sql = "SELECT eguide.id FROM `e_guide` as eguide WHERE eguide.free_search REGEXP :keywords AND eguide.status = :status";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam('keywords',$keywords);
        $stmt->bindParam('status',$status);
        $stmt->execute();

        $ids = array();

        while($data = $stmt->fetch(PDO::FETCH_NUM)){
            $ids[] = $data[0];
        }

        return $ids;
    }

    public function count($keywords, $status)
    {
        $sql = "SELECT COUNT(eguide.id) FROM `e_guide` as eguide WHERE eguide.free_search REGEXP :keywords AND eguide.status = :status";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam('keywords',$keywords);
        $stmt->bindParam('status',$status);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_NUM);

        return $result[0];
    }
}