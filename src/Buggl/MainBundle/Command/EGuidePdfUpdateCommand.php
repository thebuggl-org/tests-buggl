<?php 

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EGuidePdfUpdateCommand extends ContainerAwareCommand 
{
	public function configure()
    {
        $this
            ->setName('buggl:eguide:pdfupdate')
            ->setDescription('A script that would update all the published guides pdf copies.')
            ->addArgument('domain', InputArgument::REQUIRED, 'The domain that the html file would be located?')
            ->addArgument('id', InputArgument::OPTIONAL, 'Specific guide id.')
            ->addArgument('all', InputArgument::OPTIONAL, 'Optional to start from the beggining or just the guides without pdf yet.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	ini_set('max_execution_time', 0);
    	/* 
    		steps
			1. retrieve all published travel guide
			2. generate pdf
			3. update table e_guide
    	*/
		$output->writeln("STARTING SCRIPT . . . ");

    	$em = $this->getContainer()->get('doctrine.orm.entity_manager');
    	$constants = $this->getContainer()->get('buggl_main.constants'); 

        if($input->getArgument('id') > 0)
        {
            $guide = $em->getRepository('BugglMainBundle:EGuide')->findOneBy( array('id' => $input->getArgument('id'), 'status' => $constants->get('published') )  );
            if($guide)
                $this->generate($guide, $input, $output);
            else
                $output->writeln("Could not find published guide with id : " . $input->getArgument('id') );

        }
        else 
        {   

            if($input->getArgument('all'))
            {
                // RETRIEVE ALL PUBLISHED TRAVEL GUIDE
                $output->writeln("Retrieving published guides . . . ");
                $guides = $em->getRepository('BugglMainBundle:EGuide')->findBy(array('status' => $constants->get('published')));
            }
            else
            {
                // RETRIEVE ALL PUBLISHED TRAVEL GUIDE
                $output->writeln("Retrieving published guides without pdf . . . ");
                $guides = $em->getRepository('BugglMainBundle:EGuide')->findPublishedGuidesWithoutPdf();
            }
            
            foreach($guides as $guide)
            {
                /* problematic guide ids in beta */
                // 102
                // $problematicIds = array(102, 109);
                $problematicIds = array();
                if( !in_array( $guide->getID(), $problematicIds ) )
                {
                    $this->generate($guide, $input, $output);
                }
                
            }
        }



    	$output->writeln("DONE RUNNING SCRIPT!");
    	$output->writeln("Written by: Nash Lesigon <nash.lesigon@goabroad.com>");
    }

    private function generate($guide, $input, $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $output->writeln("Generating pdf for guide : ".$guide->getID());
        try {
            $result = $this->getContainer()->get('buggl_main.pdf_creator')->generate($guide, $input->getArgument('domain'));
        
            $output->writeln("Generated pdf : " . $result['pdf'] . " - page count : " . $result['pageCount']);
            $output->writeln("Generated html : " . $result['html']);
            $output->writeln("DONE! Will now update guide . . . ");
            // set eguide pdf and html filenames
            $guide->setPdfFilename($result['pdf']);
            $guide->setHtmlFilename($result['html']);
            $guide->setPdfPageCount($result['pageCount']);

            $em->persist($guide);
            $em->flush();

            $output->writeln("");
            $output->writeln("DONE Updating guide : " . $guide->getPlainTitle());
            $output->writeln("");
            $output->writeln("");
        }catch(Exception $e){
            $output->writeln("");
            $output->writeln("Could not create pdf for guide : " . $guide->getPlainTitle());
            $output->writeln("");
            $output->writeln("");
        }
    }
}