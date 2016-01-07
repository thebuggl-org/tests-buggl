<?php

namespace Buggl\MainBundle\Service;

class ExportLocalAuthorToCsvService
{
	private $mailer;
	private $entityManager;
	private $constants;
	private $router;
	private $rootDir;
	
	public function __construct($mailer, $entityManager, $constants, $router, $rootDir)
	{
		$this->mailer = $mailer;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;
		$this->rootDir = $rootDir;
	}
	
	public function execute()
	{
		// return $this->exportInsiders();
		// return $this->exportTravelers();
		$insiders = $this->exportInsiders();
		$travelers = $this->exportTravelers();

		$zipName = "insider-traveler-list-" . date('Ymd-His') . ".zip";
		$docRoot = $this->rootDir.'/../web/uploads/csv';
		if(!is_writable( $docRoot )){
            mkdir("$docRoot", 0755);
        }
        $zipFile = $docRoot . "/" . $zipName;

		$zip = new \ZipArchive();
		$zip->open($zipFile, \ZipArchive::CREATE);
		// $zip->addFile($insiders['file'],$insiders['file']);
		// $zip->addFile($travelers['file'],$travelers['file']);
		$zip->addFromString($insiders['filename'],  file_get_contents($insiders['file'])); 
		$zip->addFromString($travelers['filename'],  file_get_contents($travelers['file'])); 
		$zip->close();

		return array(
			"filename" => $zipName,
			"file" => $zipFile
			);
		// $localAuthors = $this->entityManager->getRepository('BugglMainBundle:LocalAuthor')->findBy( array('status' => $this->constants->get('allowed_user')) );
		
		// $docRoot = $this->rootDir.'/../web/uploads/csv';
		
		// if(!is_writable( $docRoot )){
  //           mkdir("$docRoot", 0755);
  //       }
		
		// $filename = "local-author-list-" . date('Ymd-His') . ".csv";
		// $file = $docRoot . "/" . $filename;
		
		// $FileHandle = fopen($file, 'w') or die("can't open file");
		// fclose($FileHandle);
		
		// $fp = fopen($file, 'w');
		
		// fputcsv( $fp, array("Name", "Email", "Link to Buggl Profile", "Registration Type") );
		// foreach( $localAuthors as $iObj )
		// {
		// 	$linkToProfile = $this->constants->get('site_url') . $this->router->generate( 'local_author_profile', array('slug' => $iObj->getSlug()) );
		// 	$regType = ($iObj->getIsLocalAuthor()) ? "Travel Insider" : "Traveller";
		// 	$authorInfo = array(
		// 		$iObj->getName(),
		// 		$iObj->getEmail(),
		// 		$linkToProfile,
		// 		$regType
		// 	);
		// 	fputcsv( $fp, $authorInfo );
		// }

		// fclose($fp);
		
		// return array(
		// 	"filename" => $filename,
		// 	"file" => $file
		// );
	}

	public function exportInsiders()
	{
		$localAuthors = $this->entityManager->getRepository('BugglMainBundle:LocalAuthor')->findBy( array('status' => $this->constants->get('allowed_user'), 'isLocalAuthor' => 1 ) );
		$docRoot = $this->rootDir.'/../web/uploads/csv';
		
		if(!is_writable( $docRoot )){
            mkdir("$docRoot", 0755);
        }
		
		$filename = "local-author-list-" . date('Ymd-His') . ".csv";
		$file = $docRoot . "/" . $filename;
		
		$FileHandle = fopen($file, 'w') or die("can't open file");
		fclose($FileHandle);
		
		$fp = fopen($file, 'w');

		fputcsv( $fp, array("Name", "Email", "Link to Buggl Profile", "Date Joined", "Guides Published", "Number of Free Guides", "Guides Sold", "Total Revenue" ) );

		$totalPublishedGuides = 0;
		$totalGuidesSold = 0;

		foreach( $localAuthors as $iObj )
		{
			$linkToProfile = $this->constants->get('site_url') . $this->router->generate( 'local_author_profile', array('slug' => $iObj->getSlug()) );
			$cntPublishedGuides = $this->entityManager->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($iObj, $this->constants->get('published_guide'));
			$cntFreeGuides = $this->entityManager->getRepository('BugglMainBundle:EGuide')->countFreeGuidesByAuthor($iObj);
			$cntGuidesSold = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->countSoldGuidesBySeller($iObj);
			$totalRevenue = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->sumNetAmountForSeller($iObj);

			$totalPublishedGuides = $totalPublishedGuides + $cntPublishedGuides;
			$totalGuidesSold = $totalGuidesSold + $cntGuidesSold;



			$authorInfo = array(
				$iObj->getName(),
				$iObj->getEmail(),
				$linkToProfile,
				$iObj->getDateJoined()->format('M d, Y'),
				$cntPublishedGuides,
				$cntFreeGuides,
				$cntGuidesSold,
				$totalRevenue
			);
			fputcsv( $fp, $authorInfo );
		}

		$publishedGuides = array('OVERALL # PUBLISHED GUIDES', $totalPublishedGuides );
		$guidesSold = array('OVERALL # GUIDES SOLD', $totalGuidesSold);

		fputcsv( $fp, array() );
		fputcsv( $fp, array() );
		fputcsv( $fp, $publishedGuides );
		fputcsv( $fp, $guidesSold );

		fclose($fp);
		
		return array(
			"filename" => $filename,
			"file" => $file
		);
	}

	public function exportTravelers()
	{
		$travellers = $this->entityManager->getRepository('BugglMainBundle:LocalAuthor')->findBy( array('status' => $this->constants->get('allowed_user'), 'isLocalAuthor' => 0 ) );
		$docRoot = $this->rootDir.'/../web/uploads/csv';
		
		if(!is_writable( $docRoot )){
            mkdir("$docRoot", 0755);
        }
		
		$filename = "traveller-list-" . date('Ymd-His') . ".csv";
		$file = $docRoot . "/" . $filename;
		
		$FileHandle = fopen($file, 'w') or die("can't open file");
		fclose($FileHandle);
		
		$fp = fopen($file, 'w');

		fputcsv( $fp, array("Name", "Email", "Link to Buggl Profile", "Date Joined", "Guides Purchased", "Amount spent") );
		
		foreach( $travellers as $iObj )
		{
			$linkToProfile = $this->constants->get('site_url') . $this->router->generate( 'local_author_profile', array('slug' => $iObj->getSlug()) );
			$cntGuidesBought = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->countBoughtGuidesByBuyer($iObj);
			$amountSpent = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->sumAmountSpent($iObj);

			$authorInfo = array(
				$iObj->getName(),
				$iObj->getEmail(),
				$linkToProfile,
				$iObj->getDateJoined()->format('M d, Y'),
				$cntGuidesBought,
				$amountSpent
			);
			fputcsv( $fp, $authorInfo );
		}

		fclose($fp);
		
		return array(
			"filename" => $filename,
			"file" => $file
		);
	}
	
}