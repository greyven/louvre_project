<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * This controller is used to simulate an order from a customer.
 * Class OrderController
 * @package AppBundle\Controller
 * @Route("/order", name="order_prepare")
 */
class OrderController extends Controller
{
    /**
     * @Route("/prepare", name="order_prepare")
     */
    public function prepareAction()
    {
        return $this->render('prepare.html.twig',
            array('stripe_public_key' => $this->getParameter('stripe_public_key')));
    }

    /**
     * @Route(
     *     "/checkout",
     *     name="order_checkout",
     *     methods="POST"
     * )
     */
    public function checkoutAction(Request $request)
    {
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        if($request->isMethod('POST'))
        {
            // Get the credit card details submitted by the form
            $token = $request->get('stripeToken');

            // Create a charge: this will charge the user's card
            try
            {
                $charge = \Stripe\Charge::create(array(
                    "amount" => 1500, // Amount in cents
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Paiement Stripe - Exemple"
                ));
                $this->addFlash("success", "Paiement éffectué !");
                return $this->redirectToRoute("order_prepare");
            }
            catch(\Stripe\Error\Card $e)
            {

                $this->addFlash("error", "Erreur, paiement non éffectué !");
                return $this->redirectToRoute("order_prepare");
                // The card has been declined
            }
        }
    }
}
