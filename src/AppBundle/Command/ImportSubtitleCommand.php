<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Filesystem\Filesystem;

use AppBundle\Entity\Subtitle;
use AppBundle\Entity\Language;
use Application\Sonata\NewsBundle\Entity\Post;

class ImportSubtitleCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:subtitle')
        ->setDescription('Import subtitles from CSV file');
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

            $post = $em->getRepository(Post::getEntityName())
                       ->findOneBy(array('slug' => $row['post']));

            if($post == null)
                 continue;
            
            $language = $em->getRepository(Language::getEntityName())
                       ->findOneBy(array('code' => $row['language']));
            
            $subtitle = $em->getRepository(Subtitle::getEntityName())
                       ->findOneBy(array('post' => $post->getId() , 'language' => $language->getId()));
            // If the subtitle doest not exist we create one
            if(!is_object($subtitle)){
                $subtitle = new Subtitle();
            }
			
            // Updating info
            $subtitle->setPost($post);
            $subtitle->setLanguage($language);
            $subtitle->setEnabled(boolval($row['enabled']));
            $fs = new Filesystem();
            $filePath = Subtitle::getPreUpSubtitle() .  $row['filePath'];
            $existed = file_exists($filePath);
            if($existed)
            {
                $directory = Subtitle::getUploadRootDir();
                $existed = $fs->exists($directory . $post->getId());
				if(!$existed)
				{
					$fs->mkdir($directory . $post->getId(), 0755);
				}				
                
                $newName = $post->getId()
                    . "/"
                    . sha1(uniqid(mt_rand(), true))
                    . "-"
                    . $language->getCode()
                    . "."
                    . pathinfo($filePath, PATHINFO_EXTENSION);
                
                $subtitle->setFileName($newName);
                copy($filePath, $directory . "/" . $newName);
                $subtitle->setModifiedAt(new \DateTime());

                // Persisting the current user
                $em->persist($subtitle);
            
            }
            
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
    }

    protected function get(InputInterface $input, OutputInterface $output) 
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/import/subtitle.csv';
        
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ';');
        return $data;
    }
    
}