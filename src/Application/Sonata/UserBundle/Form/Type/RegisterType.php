<?php

namespace Application\Sonata\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Repository\LanguageRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterType extends RegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('firstname', null, array(
                'label' => 'form.firstname',
                'translation_domain' => 'ApplicationSonataUserBundle',
                'required'=>true
            ))
            ->add('lastname', null, array(
                'label' => 'form.lastname',
                'translation_domain' => 'ApplicationSonataUserBundle',
                'required'=>true
            ))
                ;

//        $builder
//            ->add('nativeLanguage', 
//                    'entity',
//                       array(
//                             'class'=>'AppBundle\Entity\Language',
//                             'property'=>'code',
//                             'query_builder' => function (LanguageRepository $repository)
//                             {
//                                 return $repository->createQueryBuilder('s')
//                                        ->where('s.code != ?1')
//                                        ->setParameter(1, 'fr');
//                             }
//                            )                                    
//                      );
    }
    
    public function getName()
    {
        return 'front_user_registration';
    }
}