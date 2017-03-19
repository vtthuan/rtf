<?php

namespace AppBundle\Command;

use Sonata\MediaBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;

use Application\Sonata\MediaBundle\Entity\Media;

class ImportMediaCommand extends BaseCommand
{

    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:media')
        ->setDescription('Import madias from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        // Importing CSV on DB via Doctrine ORM
        $this->import($input, $output);
        
        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }
    
    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get($input, $output);
        
        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 0;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        
        
        // Processing on each row of data
        foreach($data as $row) {

            $video = $em->getRepository(Media::getEntityName())
                       ->findOneBy(array('providerReference' => $row['provider_reference']));
		
			// If the user doest not exist we create one
            if(!is_object($video)){
                $video = new Media();
                $video->setProviderReference($row['provider_reference']);
            }
            // Updating info
            $video->setProviderReference($row['provider_reference']);
            $video->setName($row['name']);
			$video->setContext($row['context']);
            $video->setProviderName($row['provider_name']);
            $video->setProviderStatus(intval($row['provider_status']));
            $video->setEnabled(boolval($row['enabled']));
            $video->setAuthorName($row['author_name']);
            $video->setCreatedAt(new \DateTime());
            $video->setUpdatedAt(new \DateTime());
            
            // Persisting the current user
            $em->persist($video);
            
            // Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
		// Detaches all objects from Doctrine for memory save
                $em->clear();
                
		// Advancing for progress display on console
                $progress->advance($batchSize);
				
                $now = new \DateTime();
                $output->writeln(' of medias imported ... | ' . $now->format('d-m-Y G:i:s'));

            }

            $i++;

        }
		
	// Flushing and clear data on queue
        $em->flush();
        $em->clear();
		
	// Ending the progress bar process
        $progress->finish();
		
		$this->UpdateMetaData($input, $output);
		$this->SyncThumbnail($input, $output);
    }
	
	protected function UpdateMetaData(InputInterface $input, OutputInterface $output)
	{
		$command = $this->getApplication()->find("sonata:media:refresh-metadata");
		$arguments = array(
			"providerName" => "sonata.media.provider.youtube",
			"context" => "news"
		);
		$input = new ArrayInput($arguments);
		$returnCode = $command->run($input, $output);		
	}
	
	protected function SyncThumbnail(InputInterface $input, OutputInterface $output)
	{
		$command = $this->getApplication()->find("sonata:media:sync-thumbnails");
		$arguments = array(
			"providerName" => "sonata.media.provider.youtube",
			"context" => "news"
		);
		$input = new ArrayInput($arguments);
		$returnCode = $command->run($input, $output);	
	}

    protected function get(InputInterface $input, OutputInterface $output) 
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/import/media.csv';
        
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ';');
        return $data;
    }
    
}