<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Language;
use Application\Sonata\NewsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArticleController extends Controller {

    /**
     * @Route("/article/{language}/{slug}", name="article_detail", defaults={"language" = "%defaultNativeLanguage%"})
     * @ParamConverter("article", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("language", options={"mapping": {"language": "code"}})
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Post $article, Language $language) {

        $form = $this->createFormBuilder()->getForm();
        
        $form->handleRequest($request);
        $user = $this->getUser();
        
        
//        $buying = $this->getDoctrine()
//                ->getRepository(Buying::getEntityName())
//                ->findOneBy(array('user' => $user, 'post' => $article));
//
//        if ($form->isSubmitted())
//        {            
//            if ($this->getUser() != null && $this->getUser()->getBalance() > $article->getPrice()) {
                
//                if($buying == null)
//                {
//                    $buying = new Buying();
//                    $buying->setPost($article);
//                    $buying->setUser($this->getUser());
//                    $buying->setQuantity($article->getPrice());
//                    $cstrong = false;
//                    $bytes = openssl_random_pseudo_bytes(10, $cstrong);
//                    if($cstrong)
//                    {
//                        $hex = bin2hex($bytes);
//                        $buying->setToken($hex);
//
//                        $em = $this->getDoctrine()->getManager();
//                        $em->persist($buying);
//                        $em->flush();
//
//                        $newBalance = $user->getBalance() - $article->getPrice();                    
//                        $user->setBalance($newBalance);
//
//                        $userManager = $this->get('fos_user.user_manager');
//                        $userManager->updateUser($user);
//                        $this->getDoctrine()->getManager()->flush();
//                        
//                        $this->addFlash('success', 'video.updated_successfully');
//                    }
//                    else
//                    {
//                        //log
//                    }
//                }
//                return $this->redirectToRoute('post_detail_full', array('token' => $buying->getToken(),
//                    'slug' => $article->getSlug(),
//                    'nativeLanguage' => $language));
//                
//            } else {
//                return $this->forward('AppBundle:TicketTypeController:index');
//            }            
//        }
        
//        if ($buying) {
//            return $this->redirectToRoute('post_detail_full', array('token' => $buying->getToken(),
//                'slug' => $article->getSlug(),
//                'nativeLanguage' => $language));
//        }

        $balance = 0;
        if ($user != null) {
            $balance = $user->getBalance();
        }
        
        if($this->get('session')->get($article->getSlug()) == null)
        {
            $this->get('session')->set($article->getSlug(), true);
            $article->setViewCount($article->getViewCount() + 1 );
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
        }
        
        $relativesPosts = $this->getDoctrine()
                ->getRepository(Post::getEntityName())
                ->getRelativePosts($article);

        return $this->render('post/trail.html.twig', array('post' => $article,
            'form'=> $form->createView(),
            'trail' => true,
            'relativesPosts' => $relativesPosts,
            'nativeLanguage' => $language));
    }

}
