<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TicketType;
use AppBundle\Entity\Order;

use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\DiExtraBundle\Annotation as DI;

use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/payments")
 * @Security("has_role('ROLE_USER')")
 */
class PaymentController extends Controller
{
    /** @DI\Inject */
    private $request;

    /** @DI\Inject */
    private $router;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("payment.plugin_controller") */
    private $ppc;

    /**
     * @Route("/{id}", name="payment")
     * @Template()
     */
    public function paymentAction(TicketType $ticket)
    {
        $form = $this->getFormFactory()->create('jms_choose_payment_method', null, array(
            'amount'   => $ticket->getQuantity(),
            'currency' => 'EUR',
            'default_method' => 'payment_paypal', // Optional
            'predefined_data' => array()
        ));
        
        if ('POST' === $this->request->getMethod()) {
            $form->submit($this->request);
            
            $this->getUser()->setOrderNumber($this->getUser()->getOrderNumber() + 1);
            $this->em->persist( $this->getUser());
            $this->em->flush( $this->getUser());
            
            $order = new Order();
            $order->setTicket($ticket);
            $order->setOrderNumber(Order::PREFIX . $this->getUser()->getId() . $this->getUser()->getOrderNumber());
            $this->em->persist( $order);
            $this->em->flush( $order);
 
            $form = $this->getFormFactory()->create('jms_choose_payment_method', null, array(
                'amount'   => $order->getTicket()->getPrice(),
                'currency' => 'EUR',
                'default_method' => 'payment_paypal', // Optional
                'predefined_data' => array(
                    'paypal_express_checkout' => array(
                        'return_url' => $this->router->generate('payment_complete', array(
                            'id' =>$order->getId()
                        ), true),
                        'cancel_url' => $this->router->generate('payment_cancel', array(
                            'id' => $order->getId()
                        ), true)
                    ),
                ),
            ));
 
            $form->submit($this->request);
 
            // Once the Form is validate, you update the order with payment instruction
            if ($form->isValid()) {
                $instruction = $form->getData();
                $this->ppc->createPaymentInstruction($instruction);
                $order->setPaymentInstruction($instruction);
                $this->em->persist($order);
                $this->em->flush($order);
                // now, let's redirect to payment_complete with the order id
                return new RedirectResponse($this->router->generate('payment_complete', array('id' => $order->getId() )));
            }
        }
        return $this->render('payment/paymentChooseTemplate.html.twig',array('form' => $form->createView() , 'ticket' => $ticket));
    }
    
    /**
     * @Route("/complete/{id}", name = "payment_complete")
     */
    public function completeAction(Order $order) // id of the order
    {
        if ($order == null ){
            return;
        }
 
        $instruction = $order->getPaymentInstruction();
        if (null === $pendingTransaction = $instruction->getPendingTransaction()) {            
            $payment = $this->ppc->createPayment($instruction->getId(), $instruction->getAmount() - $instruction->getDepositedAmount());
        } else {            
            $payment = $pendingTransaction->getPayment();
        }
 
        $result = $this->ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());
        if (Result::STATUS_PENDING === $result->getStatus()) {
            $ex = $result->getPluginException();
            
            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();
                
                if ($action instanceof VisitUrl) 
                {
                    return new RedirectResponse($action->getUrl() . "&useraction=commit");
                }
                throw $ex;
            }
        } 
        else
        {
            if (Result::STATUS_SUCCESS !== $result->getStatus()) 
            {
            throw new \RuntimeException('Transaction was not successful: '.$result->getReasonCode());
            }
            else
            {
                if(Result::STATUS_SUCCESS === $result->getStatus()) 
                {
                    $newBalance = $this->getUser()->getBalance() . $order->getTicket()->getQuantity();
                    $this->getUser()->setBalance($newBalance);
                    $this->em->persist( $this->getUser());
                    $this->em->flush( $this->getUser());
                    
                    $order->setCompleted(true);
                    $this->em->persist( $order);
                    $this->em->flush( $order);
                    
                }
            }
        }
        // if successfull, i render my order validation template
        return $this->render('payment/validation.html.twig',array('order'=>$order )); 
        
    }
 
    /** @DI\LookupMethod("form.factory") */
    protected function getFormFactory() { 
        
    }
 
    /**
     * @Route("/cancel", name = "payment_cancel")
     */
    public function CancelAction( )
    {
        $this->get('session')->getFlashBag()->add('info', 'Transaction annulée.');
        return $this->redirect($this->generateUrl('yourTemplate'));
    }
}