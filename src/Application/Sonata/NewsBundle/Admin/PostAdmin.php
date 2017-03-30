<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Admin;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class PostAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('group_post', array(
                    'class' => 'col-md-8',
                ))
                ->add('author', 'sonata_type_model_list')
                ->add('title')
                ->add('abstract', null, array('attr' => array('rows' => 5)))
                ->add('price')                
                ->add('content', 'sonata_formatter_type', array(
                    'event_dispatcher'          => $formMapper->getFormBuilder()->getEventDispatcher(),
                    'format_field'              => 'contentFormatter',
                    'source_field'              => 'rawContent',
                    'source_field_options'      => array(
                        'horizontal_input_wrapper_class' => $this->getConfigurationPool()->getOption('form_type') == 'horizontal' ? 'col-lg-12' : '',
                        'attr'                           => array('class' => $this->getConfigurationPool()->getOption('form_type') == 'horizontal' ? 'span10 col-sm-10 col-md-10' : '', 'rows' => 20),
                    ),
                    'ckeditor_context'     => 'news',
                    'target_field'         => 'content',
                    'listener'             => true,
                ))
            ->end()
            ->with('group_status', array(
                    'class' => 'col-md-4',
                ))
                ->add('enabled', null, array('required' => false))
                ->add('isArticle', null, array('required' => false))
                ->add('image', 'sonata_type_model_list', array('required' => false), array(
                    'link_parameters' => array(
                        'context'      => 'news',
                        'hide_context' => true,
                    ),
                ))

                ->add('publicationDateStart', 'sonata_type_datetime_picker', array('dp_side_by_side' => true))
                ->add('commentsCloseAt', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'required'        => false,
                ))
                ->add('commentsEnabled', null, array('required' => false))
                ->add('commentsDefaultStatus', 'sonata_news_comment_status', array('expanded' => true))
            ->end()

            ->with('group_classification', array(
                'class' => 'col-md-4',
                ))
                ->add('collection', 'sonata_type_model_list', array('required' => false))
            ->end()
        ;
    }
    
        /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('custom', 'string', array(
                'template' => 'SonataNewsBundle:Admin:list_post_custom.html.twig',
                'label'    => 'Post',
                'sortable' => 'title',
            ))
            ->add('commentsEnabled', null, array('editable' => true))
            ->add('publicationDateStart')
        ;
    }
}
