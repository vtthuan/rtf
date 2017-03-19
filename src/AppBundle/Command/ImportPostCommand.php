<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use Application\Sonata\NewsBundle\Entity\Post;
use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\ClassificationBundle\Entity\Collection;
use Application\Sonata\UserBundle\Entity\User;
class ImportPostCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:post')
        ->setDescription('Import posts from CSV file');
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
                       ->findOneBy(array('slug' => $row['slug']));
		
            $collection = $em->getRepository(Collection::getEntityName())
                       ->findOneBy(array('slug' => $row['collection']));
            // If the post doest not exist we create one
            if(!is_object($post)){
                $post = new Post();
                $post->setSlug($row['slug']);
                $post->setEnabled(false);
            }
            else
            {
                $post->setEnabled(boolval($row['enabled']));
            }
            
            $user = $em->getRepository(User::getEntityName())
                       ->findOneBy(array('username' => $row['author']));
            
            $video = $em->getRepository(Media::getEntityName())
                       ->findOneBy(array('providerReference' => $row['image']));
            $post->setAuthor($user);
            $post->setImage($video);
            $post->setCollection($collection);           
            
            // Updating info
            $post->setTitle($row['title']);
            $post->setAbstract($row['abstract']);
            $post->setCommentsDefaultStatus(intval($row['comments_default_status']));
            $post->setCommentsEnabled(boolval($row['comments_enabled']));
            $post->setContent($row['content']);
            $post->setContentFormatter($row['content_formatter']);            
            
            $post->setIsArticle(boolval($row['isArticle']));
            $post->setPrice(intval($row['price']));
            $post->setRawContent($row['raw_content']);
            $post->setViewCount(intval($row['viewCount']));
            $post->setCreatedAt(new \DateTime());
            $post->setUpdatedAt(new \DateTime());

            // Persisting the current user
            $em->persist($post);
            
            // Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
		// Detaches all objects from Doctrine for memory save
                $em->clear();
                
		// Advancing for progress display on console
                $progress->advance($batchSize);
				
                $now = new \DateTime();
                $output->writeln(' of postss imported ... | ' . $now->format('d-m-Y G:i:s'));

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
        $fileName = 'web/uploads/import/post.csv';
        
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ';');
        
        return $data;
    }
    
}
