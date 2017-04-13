<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\Sonata\ClassificationBundle\Entity\Collection;
use Application\Sonata\NewsBundle\Entity\Post;
use AppBundle\Entity\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/category")
 */
class CategoryController extends Controller {
    
     /**
     * @Route("/", name="category_index")
     */
    public function indexAction()
    {
        $collectionRepository= $this->getDoctrine()->getRepository(Collection::getEntityName());
        $postRepository = $this->getDoctrine()->getRepository(Post::getEntityName());
        $categories = $collectionRepository->getActiveCollections();
        foreach($categories as $category)
        {
            $posts = $postRepository->getActivePostsByCollection($category, Post::NUM_ITEMS);
            $category->setPosts($posts);
        }
        
        $helper = $this->get('app.user_helper');
        
        return $this->render('category/index.html.twig', array('categories' => $categories, 'language'=> $helper->findLanguage($this->getUser())));
    }
    
     /**
     * @Route("/{language}/{id}", name="category_detail", defaults={"language" = "%defaultNativeLanguage%"}))
     * @ParamConverter("language", options={"mapping": {"language": "code"}})
     */
    public function detailAction($id, Language $language)
    {
        $repository= $this->getDoctrine()->getRepository(Collection::getEntityName());
        $postRepository = $this->getDoctrine()->getRepository(Post::getEntityName());
        $collection = $repository->find($id);
        if (!$collection) {
            throw $this->createNotFoundException(
            'No category found for id '.$id );        
        }
        
        $collection->setPosts($postRepository->getActivePostsByCollection($collection));
        
        return $this->render('category/category_detail.html.twig', array('category' => $collection, 'language'=> $language));
    }

    /**
     * @Route("/", name="category_recent")
     */
    public function recentAction()
    {

        $postRepository = $this->getDoctrine()->getRepository(Post::getEntityName());

        $posts = $postRepository->findBy(array(), array('createdAt'=>'desc'), 6, 0);

        $helper = $this->get('app.user_helper');

        return $this->render('category/recent_post.html.twig', array('posts'=> $posts,  'language'=> $helper->findLanguage($this->getUser())));


    }
}