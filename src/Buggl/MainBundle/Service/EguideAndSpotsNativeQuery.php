<?php

namespace Buggl\MainBundle\Service;
use Doctrine\DBAL\Connection;
use PDO;

class EguideAndSpotsNativeQuery
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

	public function getSpotDetailsId($guide,$type=null,$offset=0,$limit=0)
	{
		$typeClause = '';
		if(!is_null($type))
			$typeClause = ' AND `spot_detail`.`spot_type_id` = '.$type->getId();

		$limitClause = '';
		if($limit > 0)
			$limitClause = " LIMIT ".$offset." , ".$limit;

		$sql = 'SELECT `spot_detail`.`id` FROM `e_guide_to_spot` LEFT JOIN `spot_detail` ON `e_guide_to_spot`.`spot_id` = `spot_detail`.`spot_id` WHERE 1 AND `e_guide_to_spot`.`e_guide_id` = '.$guide->getId().' AND `spot_detail`.`local_author_id` = '.$guide->getLocalAuthor()->getId().$typeClause.' ORDER BY `e_guide_to_spot`.`is_featured` DESC'.$limitClause;
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $ids = array();

        while($data = $stmt->fetch(PDO::FETCH_NUM)){
            $ids[] = $data[0];
        }

        return $ids;
	}
	
	public function getFeaturedSpotDetailsId($guide)
	{
		$sql = 'SELECT `spot_detail`.`id` FROM `e_guide_to_spot` LEFT JOIN `spot_detail` ON `e_guide_to_spot`.`spot_id` = `spot_detail`.`spot_id` WHERE 1 AND `e_guide_to_spot`.`e_guide_id` = '.$guide->getId().' AND `spot_detail`.`local_author_id` = '.$guide->getLocalAuthor()->getId().' AND `e_guide_to_spot`.`is_featured` = 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $ids = array();

        while($data = $stmt->fetch(PDO::FETCH_NUM)){
            $ids[] = $data[0];
        }

        return $ids;
	}
	
	public function getSpotTypesWithSpotsForGuide($guide)
	{
		$sql = 'SELECT `spot_type`.`id` FROM `e_guide_to_spot_detail` LEFT JOIN `spot_detail` ON `e_guide_to_spot_detail`.`spot_detail_id` = `spot_detail`.`id` LEFT JOIN `spot_type` ON `spot_detail`.`spot_type_id` = `spot_type`.`id` WHERE 1 AND `e_guide_to_spot_detail`.`e_guide_id` = '.$guide->getId();
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $ids = array();

        while($data = $stmt->fetch(PDO::FETCH_NUM)){
            $ids[] = $data[0];
        }

        return $ids;
	}
}