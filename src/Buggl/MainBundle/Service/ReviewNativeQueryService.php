<?php

namespace Buggl\MainBundle\Service;
use Doctrine\DBAL\Connection;
use PDO;

class ReviewNativeQueryService
{

    private $connection;
    private $objects;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setObjects($objects = array())
    {
        $this->objects = $objects;

        return $this;
    }

    public function findReviewIdsRelatedToLocalAuthor($status, $offset = 0, $limit = 0)
    {
        $localAuthor = $this->objects['local_author'];
        $localAuthorId = $localAuthor->getId();

        $localAuthorReview = "SELECT r.id as arrange FROM `review` r INNER JOIN `local_author_review` lar ON lar.id = r.id LEFT JOIN `local_author` la ON la.id = lar.local_author_id WHERE la.id = $localAuthorId AND r.status = $status";
        $travelGuideReview = "SELECT r.id as arrange FROM `review` r INNER JOIN `travel_guide_review` t ON r.id = t.id LEFT JOIN `e_guide` e ON t.eguide_id = e.id LEFT JOIN `local_author` la ON la.id = e.local_author_id WHERE la.id = $localAuthorId AND r.status = $status";

        $sql = $localAuthorReview." UNION ".$travelGuideReview." ORDER BY arrange DESC";

        if($limit){
            $sql .= " LIMIT $offset,$limit";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $ids = array();

        while($data = $stmt->fetch(PDO::FETCH_NUM)){
            $ids[] = $data[0];
        }

        return $ids;
    }

    public function countReviewsByStatus($status)
    {
        $localAuthor = $this->objects['local_author'];
        $localAuthorId = $localAuthor->getId();

        $localAuthorReview = "SELECT r.id as arrange FROM `review` r INNER JOIN `local_author_review` lar ON lar.id = r.id LEFT JOIN `local_author` la ON la.id = lar.local_author_id WHERE la.id = $localAuthorId AND r.status = $status";
        $travelGuideReview = "SELECT r.id as arrange FROM `review` r INNER JOIN `travel_guide_review` t ON r.id = t.id LEFT JOIN `e_guide` e ON t.eguide_id = e.id LEFT JOIN `local_author` la ON la.id = e.local_author_id WHERE la.id = $localAuthorId AND r.status = $status";

        $sql = "SELECT count(a.arrange) FROM "."(".$localAuthorReview." UNION ".$travelGuideReview.") AS a";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_NUM);

        return $count[0];
    }

	// temporary
	public function getEguideToSpotsId($sql)
	{
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $ids = array();

        while($data = $stmt->fetch(PDO::FETCH_NUM)){
            $ids[] = $data[0];
        }

        return $ids;
	}
}