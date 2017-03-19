<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use AppBundle\Entity\Subtitle;
use Symfony\Component\Filesystem\Filesystem;

class SubtitleAdmin extends Admin{
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('file', 'file', array('required' => false))
            ->add('lang', 'sonata_type_model_list')
            ->add('post', 'sonata_type_model_list')
        ;
    }
    
    public function prePersist($subtitle) {
        $this->manageFileUpload($subtitle);
    }

    public function preUpdate($subtitle) {
        $this->manageFileUpload($subtitle);
    }
    
    public function manageFileUpload($subtitle) {
        if ($subtitle->getFile()) {             
            $fs = new Filesystem();
            $directory = Subtitle::getUploadRootDir() . $subtitle->getPost()->getId();
            $existed = $fs->exists($directory);
            $newName = $subtitle->getPost()->getId()
                    . "/"
                    . sha1(uniqid(mt_rand(), true))
                    . "-"
                    . $subtitle->getLanguage()->getCode() 
                    . "."
                    . $subtitle->getFile()->getClientOriginalExtension();
            $subtitle->setFileName($newName);
            if(!$existed)
            {
                $fs->mkdir($directory, 0700);
            }                
            $subtitle->getFile()->move(
                    $directory,
                    $newName
            );
            
            $subtitle->refreshUpdated();  
        }
    }
    
    public function toString($object)
    {
        return $object instanceof Subtitle
            ? $object->getFileName()
            : 'Subtitle'; // shown in the breadcrumb on the create view
    }
}