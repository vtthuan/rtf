<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Sonata\UserBundle\Form\Type\ProfileType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Repository\LanguageRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\UserBundle\Model\UserInterface;

class EditProfileType extends ProfileType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', 'sonata_user_gender', array(
                'label'    => 'form.label_gender',
                'required' => true,
                'translation_domain' => 'SonataUserBundle',
                'choices' => array(
                    UserInterface::GENDER_FEMALE => 'gender_female',
                    UserInterface::GENDER_MALE   => 'gender_male',
                )
            ))
            ->add('firstname', null, array(
                'label'    => 'form.label_firstname',
                'required' => false
            ))
            ->add('lastname', null, array(
                'label'    => 'form.label_lastname',
                'required' => false
            ))
            ->add('locale', 'locale', array(
                'label'    => 'form.label_locale',
                'required' => false
            ))
            ->add('nativeLanguage', 
                    'entity',
                       array(
                             'class'=>'AppBundle\Entity\Language',
                             'property'=>'code',
                             'query_builder' => function (LanguageRepository $repository)
                             {
                                 return $repository->createQueryBuilder('s')
                                        ->where('s.code != ?1')
                                        ->setParameter(1, 'fr');
                             }
            ))
        ;
    }
    
    public function getName()
    {
        return 'front_user_profile';
    }
}