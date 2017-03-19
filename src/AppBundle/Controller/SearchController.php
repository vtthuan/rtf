<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Model\PostSearch;
use AppBundle\Form\PostSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class SearchController extends Controller {
    /**
     * @Route("/search/{page}", name="search", defaults={"page"=1})
     * @Method({"GET"})
     */
    public function listAction(Request $request, $page) {
        $postSearch = new PostSearch();
        $postSearch->handleRequest($request);

        $postSearchForm = $this->get('form.factory')
                ->createNamed(
                '', 'post_search_type', $postSearch, array(
                'action' => $this->generateUrl('search'),
                'method' => 'GET'
                )
        );
        $postSearchForm->handleRequest($request);
        $postSearch = $postSearchForm->getData();

        if ($postSearch->getTitle() != null) {
            $motor = $this->get('app.motor_search');
            $results = $motor->searchByName($postSearch);

            $adapter = new ArrayAdapter($results);
            $pager = new Pagerfanta($adapter);
            $pager->setMaxPerPage($this->getParameter('MaxPerPage'));
            $pager->setCurrentPage($page);
            return $this->render('search/searchBar.html.twig', array(
                        'results' => $pager->getCurrentPageResults(),
                        'pager' => $pager,
                        'postSearchForm' => $postSearchForm->createView(),
            ));
        }

        return $this->render('search/searchBar.html.twig', array(
                    'results' => [],
                    'pager' => null,
                    'postSearchForm' => $postSearchForm->createView()));
    }

}
