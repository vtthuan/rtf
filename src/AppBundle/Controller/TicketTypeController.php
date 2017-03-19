<?php


namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\TicketType;
/**
 * @Route("/tickets")
*/
 
class TicketTypeController extends Controller {
   
    /**
     * Lists all Ticket entities.
     * 
     * @Route("/", name="tickets_index")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository(TicketType::getEntityName());
        $tickets = $repository->getActiveTicketTypes();
        
        return $this->render('ticket/index.html.twig', array('tickets' => $tickets));
    }
}