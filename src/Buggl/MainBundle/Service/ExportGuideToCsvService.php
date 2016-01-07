<?php

namespace Buggl\MainBundle\Service;

class ExportGuideToCsvService
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
		$csvFile = $this->buildCsv();

		$zipName = $csvFile['filename'] . ".zip";
		$docRoot = $this->rootDir.'/../web/uploads/csv';
		if(!is_writable( $docRoot )){
            mkdir("$docRoot", 0755);
        }
        $zipFile = $docRoot . "/" . $zipName;

		$zip = new \ZipArchive();
		$zip->open($zipFile, \ZipArchive::CREATE);
		
		$zip->addFromString( $csvFile['filename'] . ".csv",  file_get_contents($csvFile['file']) );
		$zip->close();

		return array(
			"filename" => $zipName,
			"file" => $zipFile
			);
	}

	private function buildCsv()
	{
		$guides = $this->entityManager->getRepository('BugglMainBundle:EGuide')->findBy( array('status' => $this->constants->get('published') ) );
		$docRoot = $this->rootDir.'/../web/uploads/csv';
		
		if(!is_writable( $docRoot )){
            mkdir("$docRoot", 0755);
        }
		

		$filename = "guides-list-" . date('Ymd-His');
		$file = $docRoot . "/" . $filename . ".csv";
		
		$FileHandle = fopen($file, 'w') or die("can't open file");
		fclose($FileHandle);
		
		$fp = fopen($file, 'w');

		fputcsv( $fp, array("Title", "Author", "Date Created", "Last Updated", "Price", "Total Purchases", "Total Revenue (Author)", "Total Revenue (Buggl)" ) );

		foreach( $guides as $guide )
		{
			$author = $guide->getLocalAuthor();
			$sellerNetAmount = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->sumNetAmountForSeller( $author, $guide );
			$bugglNetAmount = $this->entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->sumNetAmountForBuggl( $guide );
			$price = ($guide->getPrice() == 0) ? "FREE" : '$'.number_format($guide->getPrice(),2,'.',',');

			$guideInfo = array(
				$guide->getPlainTitle(),
				$author->getName(),
				$guide->getDateCreated()->format('M d, Y'),
				$guide->getDateUpdated()->format('M d, Y'),
				$price,
				$guide->getPurchaseCount(),
				'$'.number_format($sellerNetAmount,2,'.',','),
				'$'.number_format($bugglNetAmount,2,'.',',')
			);

			fputcsv( $fp, $guideInfo );
		}

		fclose($fp);
		
		return array(
			"filename" => $filename,
			"file" => $file
		);

	}
}