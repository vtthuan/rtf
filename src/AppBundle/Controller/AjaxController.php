<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Service\MotorSearch;
use Application\Sonata\NewsBundle\Entity\Post;
use AppBundle\Entity\Word;
use AppBundle\Entity\Favorite;

class AjaxController extends Controller
{
    /**
     * @Route("/feedback/addContact/", name="addContactMessage", options={"expose"=true})
     */
    public function addContactMessageAction()
    {
        //if ($request->isXMLHttpRequest()) { 
        return $this->forward('AppBundle:Feedback:addContactMessage', array());
        // }
        //return new Response('This is not ajax!', 400);
    }
    
    
    /**
     * @Route("/translate/{source}/{target}/{word}", name="translate_word", options={"expose"=true})
     */
    public function translateWordAction(Request $request,$word)
    {
        //if ($request->isXMLHttpRequest()) { 
            
            if($word != '')
            {
				$word = self::formatWord($word);
				
                $em = $this->getDoctrine()->getManager();
                $words = $em->getRepository(Word::getEntityName())->findPossibleWords($word);            

                foreach($words as $text) {
                    //Danh tu : translate $w->getType()
                    //Gioi tinh : translate $w->getGender()
                    //Number : translate $w->getNumber()
                    //$div .= "<ul class='translation'><li>";
                    //$div .= "<li>" + "Mode indicatif, ngoi Je, o thi hien tai" . "</li></ul>";                        
                }

                return $this->render('post/translations/index.html.twig', array('translations' => $words));
            }            
       // }
    //return new Response('This is not ajax!', 400);
    }

    /**
     * @Route("/addFavorite", name="add_favorite", options={"expose"=true})
     * @Method("POST")
     */
    public function addFavoriteAction(Request $request){

        echo "<script>javascript: alert('2')></script>";
            $postId = $request->get('post');
            $from = $request->get('from');
            $to = $request->get('to');
            $content = $request->get('content');

            $post = $this->getDoctrine()
                ->getRepository(Post::getEntityName())
                ->findOneBy(array('id' => $postId));

            $favorite = new Favorite();
            $favorite->setPost($post);
            $favorite->setEnd($to);
            $favorite->setStart($from);
            $favorite->setPhrase($content);

            $em = $this->getDoctrine()->getManager();
            $em->persist($favorite);
            $em->flush();

            $this->addFlash('success', 'video.updated_successfully');

            return $this->render('post/translations/index.html.twig', array('translations' => ''));

    }

	function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}
	
	function formatWord($word)
	{	
		$rs = rtrim($word, " ,.'`|()[]!" );	
		if(self::startsWith($word, "j'") 
			|| self::startsWith($word, "qu'")
			|| self::startsWith($word, "l'")
			|| self::startsWith($word, "t'")
			|| self::startsWith($word, "m'")
			|| self::startsWith($word, "c'")
			|| self::startsWith($word, "ç'")
			)
		{
			$rs = substr($word, 2, strlen($rs));
		}
		return $rs;
	}
    
    /**
    * @Route("/view/{slug}", name="inscrease_view", options={"expose"=true})
    */
    public function inscreaseViewAction(Post $post)
    {
        $post->setViewCount($post->getViewCount() + 1 );
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
    }    
    
    /**
     * @Route("/search", name="search")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function rechercheAction(Request $request)
    {
        $motor = $this->get('app.motor_search');
        $search = $request->query->get('word', '');
        $categories = [];

//        if ($request->isXmlHttpRequest()) {
            if (strlen($search) >= MotorSearch::MIN_CHAR_MDR_POST) {
                
                $results = $motor->recherchePosts($search);
	
                $posts[] = [];

                foreach ($results as $post) {
                    $posts[] = [
		        'result' => $post->getTitle(),
                        'url' => ""
                    ];
                }
            } else {
                $posts = [];
            }            

//        } else {
//            $categories = [];
//        }
		
        return new JsonResponse($posts);
    }
}
