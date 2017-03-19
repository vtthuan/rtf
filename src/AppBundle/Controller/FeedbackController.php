<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ContactMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\Type\ContactType;

class FeedbackController extends Controller {

    public function addContactMessageAction(Request $request) {
        //This is optional. Do not do this check 
        //if you want to call the same action using a regular request.
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $contactMessage = new ContactMessage();
        $form = $this->getContactMessageForm($contactMessage);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactMessage);
            $em->flush();

            return new JsonResponse(array('message' => 'Success!'), 200);
        }

        $response = new JsonResponse(
                array(
            'message' => 'Error',
            'form' => $this->renderView('feedback/contactForm.html.twig', array(
                'entity' => $contactMessage,
                'form' => $form->createView(),
            ))), 400);

        return $response;
    }

    public function contactAction(Request $request) {
        $contactMessage = new ContactMessage();
        $form = $this->getContactMessageForm($contactMessage);

        return $this->render('feedback/contact.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    protected function getContactMessageForm(ContactMessage $contactMessage) {
        
        $user = $this->getUser();
        if ($user != null) {
            $contactMessage->setEmail($user->getEmail());
            $contactMessage->setName($user->getFullname());
        }

        $form = $this->get('form.factory')
                ->createNamed(
                '', 'contact_type', $contactMessage);
        return $form;
    }

}
