<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/**
 * Description of newPHPClass
 *
 * @author asus khadija
 */
class MessageType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $builder
            ->add('name',null,array(
                'label'=> 'label.name',
                'required' => true
            ))
            ->add('email',null,array(
                'label'=> 'label.email',
                'required' => true,
            ))
            ->add('body',null,array(
                'label'=> 'label.message',
                'required' => true,
            ))
        ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            // avoid to pass the csrf token in the url (but it's not protected anymore)
            'csrf_protection' => false,
            'data_class' => 'AppBundle\Entity\Message'
        ));
    }
    
    public function getName()
    {
        return 'message_type';
    }
}
