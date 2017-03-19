<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\Sonata\ClassificationBundle\Entity\Collection;
use Application\Sonata\NewsBundle\Entity\Post;
use AppBundle\Entity\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/buying")
 */
class BuyingController extends Controller {
     /**
     * @Route("/", name="buying_index")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository(Buying::getEntityName());
        $buyings = $repository->findBy(array('user' => $this->getUser()));
        
        return $this->render('buying/all_buyings.html.twig', array('buyings' => $buyings));
    }
}