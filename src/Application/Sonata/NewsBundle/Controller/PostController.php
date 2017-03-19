<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Sonata\NewsBundle\Controller;

use Sonata\NewsBundle\Controller\PostController as BaseController; 
use Symfony\Component\HttpFoundation\Request; 
use Sonata\NewsBundle\Model\CommentInterface;

class PostController extends BaseController
{
    public function archiveAction(Request $request = null)
    {
        $criteria = array('isArticle'=>false);
        $parameters = array();
        $response = parent::renderArchive($criteria, $parameters, $request);
        return $response;
    }
    
    public function commentsAction($postId)
    {
        $pager = $this->getCommentManager()
            ->getPager(array(
                'postId' => $postId,
                'status'  => CommentInterface::STATUS_VALID
            ), 1, 500); //no limit

        return $this->render('SonataNewsBundle:Post:comments.html.twig', array(
            'pager'  => $pager,
            'postId' => $postId,
        ));
    }
    
    public function addCommentAction($id)
    {
        $post = $this->getPostManager()->findOneBy(array(
            'id' => $id
        ));

        if (!$post) {
            throw new NotFoundHttpException(sprintf('Post (%d) not found', $id));
        }
        
        if(!$this->getUser())
        {
            throw new NotFoundHttpException(sprintf('User not connected but comment form is shown'));
        }
        
        $user = $this->getUser();
        $helper = $this->get('app.user_helper');
        $language = $helper->findLanguage($user);

        if (!$post->isCommentable()) {
            // todo add notice
            return  $this->redirectToRoute('article_detail', array(
                'slug' => $post->getSlug(),
                'language' => $language
                )); 
        }

        $form = $this->getCommentForm($post);
        $form->bind($this->get('request'));

        $comment = $form->getData();
        if ($comment->getMessage()) {
            $comment->setName($user->getFullname());
            $this->getCommentManager()->save($comment);
            $this->get('sonata.news.mailer')->sendCommentNotification($comment);             
        }

        return  $this->redirectToRoute('article_detail', array(
                'slug' => $post->getSlug(),
                'language' => $language
                )); 
    }
}