<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Buying;
use AppBundle\Entity\Language;
use Application\Sonata\NewsBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FullArticleController extends Controller
{
    /**
     * @Route("/article/{language}/{slug}/{token}", name="post_detail_full", defaults={"language" = "%defaultNativeLanguage%"})
     * @ParamConverter("language", options={"mapping": {"language": "code"}})
     * @ParamConverter("article", options={"mapping": {"slug": "slug"}})
     */
    public function indexAction(Request $request, Post $article, Language $language, $token)
    {
        $buying = $this->getDoctrine()
                ->getRepository(Buying::getEntityName())
                ->findOneBy(array('user' => $this->getUser(), 'post' => $article, 'token' => $token));
        
        if($buying == null)
        {
            return  $this->redirectToRoute('article_detail', array(
                'post' => $article->getSlug(),
                'language' => $language->getCode()
                )); 
        }
        else {
            $relativesPosts = $this->getDoctrine()
                ->getRepository(Post::getEntityName())
                ->getRelativePosts($article);
            
            return $this->render('post/full.html.twig', array(
                'post' => $article, 
                'trail' => false,
                'relativesPosts' => $relativesPosts,
                'nativeLanguage' => $language)
                );
        }
    }
}
