<?php

namespace AppBundle\Command;

use Sonata\MediaBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;

use Application\Sonata\MediaBundle\Entity\Media;

class ImportUserCommand extends BaseCommand
{

    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:user')
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
        $i = 0;
		$size = count($data);
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        
        
        // Processing on each row of data
        foreach($data as $row) {

            $command = $this->getApplication()->find("fos:user:create");
			$arguments = array(
				"username" => $row["username"],
				"email" => $row["email"],
				"password" => $row["password"],
				"--super-admin" => boolval($row["admin"])
			);
			$input = new ArrayInput($arguments);
			$returnCode = $command->run($input, $output);	
            $i++;

        }
		// Ending the progress bar process
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output) 
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/import/user.csv';
        
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ';');
        return $data;
    }
    
}