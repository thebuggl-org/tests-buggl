<?php

/** 
 * @author Nash Lesigon <nashlesigon@gmail.com>
 * @version 1.0
 * @created Dec. 05, 2013
 * Dependency: KnpSnappy Bundle
 */

namespace Buggl\MainBundle\Helper;

class BugglPdfCreator {

	private $knpSnappyPdf;
	private $rootDir;
	private $pdfFilename;
	private $htmlFilename;

    private $removeChars = array('#','`','"',"'",'!');

    private $s3 = null;
    private $constants = null;
    public function __construct($knpSnappyPdf, $rootDir, $s3, $constants)
    {
        $this->knpSnappyPdf = $knpSnappyPdf;
        $this->rootDir = $rootDir;
        $this->s3 = $s3;
        $this->constants = $constants;
    }

	public function generate(\Buggl\MainBundle\Entity\EGuide $eguide, $domain = null)
	{
		$pdfDocRoot = $this->rootDir.'/../web/uploads/eguide_pdf';

        $slug = str_replace($this->removeChars,'',$eguide->getSlug());

        $filename = $slug . ".pdf";
        $pdfFile = $pdfDocRoot . "/" . $filename;

        $i = 1;


        while ( file_exists($pdfFile) ) {
            $filename = $slug . "-V$i" . ".pdf";
            $pdfFile = $pdfDocRoot . "/" . $filename;
            $i++;
        }

        $this->createPdf($eguide, $filename, $domain);
        $paceCount = $this->getPdfPageCount();
        return array('pdf' => $this->pdfFilename, 'html' => $this->htmlFilename, 'pageCount' => $paceCount);
	}

	private function getPdfPageCount()
	{
		$filepath = $this->rootDir . "/../web/uploads/eguide_pdf/" . $this->pdfFilename;
		$fp = @fopen(preg_replace("/\[(.*?)\]/i", "",$filepath),"r");
	    $max=0;
	    while(!feof($fp)) {
	            $line = fgets($fp,255);
	            if (preg_match('/\/Count [0-9]+/', $line, $matches)){
	                    preg_match('/[0-9]+/',$matches[0], $matches2);
	                    if ($max<$matches2[0]) $max=$matches2[0];
	            }
	    }

	    return $max;
	}

	private function createPdf(\Buggl\MainBundle\Entity\EGuide $eguide, $pdfFilename = null, $domain = null)
	{
		$htmlDocRoot = $this->rootDir.'/../web/uploads/eguide_html';
        $slug = str_replace($this->removeChars,'',$eguide->getSlug());
        $htmlFilename = $slug . ".html";
        $htmlFile = $htmlDocRoot . "/" . $htmlFilename;
        
        $i = 1;
        while ( file_exists($htmlFile) ) {
            $htmlFilename = $slug . "-V$i" . ".html";
            $htmlFile = $htmlDocRoot . "/" . $htmlFilename;
            $i++;
        }
        
        $this->createHtml($eguide, $htmlFilename, $domain);

        $pdfDocRoot = $this->rootDir.'/../web/uploads/eguide_pdf';
        $pdfFilename = (is_null($pdfFilename)) ? $slug . ".pdf" : $pdfFilename;
        $pdfFile = $pdfDocRoot . "/" . $pdfFilename;

        // set the global pdf and html filenames
        $this->pdfFilename = $pdfFilename;
        $this->htmlFilename = $htmlFilename;

        if(is_null($domain))
            $html = "http://".$_SERVER['HTTP_HOST']."/uploads/eguide_html/" . $htmlFilename;
        else
            $html = "http://" . $domain . "/uploads/eguide_html/" . $htmlFilename;
        
        $options = array(
        		// 'page-size'		=> 'A5',
        		// 'orientation'	=> 'Portrait',
        		// 'dpi'			=> 600,
                'page-height'   => '770px',
                'page-width'    => '515px',
                'margin-bottom' => 0,
                'margin-left'   => 0,
                'margin-right'  => 0,
                'margin-top'    => 0,
                // 'zoom'			=> 1,
                // 'disable-dotted-lines' => true,
                // 'no-footer-line'		=> true,
                'disable-smart-shrinking' => true,
                'ignore-load-errors' => true,
                'encoding' => 'UTF-8'
            );

        $this->knpSnappyPdf->generate($html,$pdfFile ,$options);

        // amazon s3
        $sourceFile = $pdfFile;
        $key = $this->constants->get('EGUIDE_PDF') . $pdfFilename;
        $this->s3->upload($sourceFile, $key);
        
	}

	private function createHtml(\Buggl\MainBundle\Entity\EGuide $eguide, $filename = null, $domain = null)
	{
        if(is_null($domain))
    		$url = "http://".$_SERVER['HTTP_HOST']."/guide-preview/".$eguide->getID();
        else
            $url = "http://".$domain."/guide-preview/".$eguide->getID();


        // echo "<br/>html preview : ".$url."<br/>";
        $htmlDocRoot = $this->rootDir.'/../web/uploads/eguide_html';
        if(!is_writable($htmlDocRoot)){
            mkdir("$htmlDocRoot",0755);
        }

        $slug = str_replace($this->removeChars,'',$eguide->getSlug());

        $filename = (is_null($filename)) ? $slug . ".html" : $filename;
        
        $htmlFile = $htmlDocRoot . "/" . $filename;

        if(!file_exists($htmlFile))
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $data = curl_exec($ch);

            curl_close($ch);

            file_put_contents($htmlFile, $data);
        }
	}	
}