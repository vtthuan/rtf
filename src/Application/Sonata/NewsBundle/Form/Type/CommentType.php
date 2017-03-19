<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Application\Sonata\NewsBundle\Form\Type;

use Sonata\NewsBundle\Form\Type\CommentType;

class AppBundleCommentType extends CommentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', null, array('label' => 'form.comment.message'))
        ;
    }

    public function getName()
    {
        return 'app_bundle_post_comment';
    }
    

}