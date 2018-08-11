<?php

namespace AppBundle\Service;

use Stripe\Stripe;
use Symfony\Component\HttpFoundation\RequestStack;

class Pay
{
    private $request;
    private $stripeSecretKey;

    public function __construct(RequestStack $requestStack, $stripeSecretKey)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->stripeSecretKey = $stripeSecretKey;
    }

    /**
     * @return mixed
     */
    public function getCardToken()
    {
        // Get the credit card details submitted by the form
        Stripe::setApiKey($this->stripeSecretKey);
        return $this->request->get('stripeToken');
    }

    /**
     * @param $totalPrice
     * @param $description
     * @return bool|mixed|null
     */
    public function createCharge($totalPrice, $description)
    {
        try
        {
            $charge = \Stripe\Charge::create(array(
                "amount" => $totalPrice * 100, // Amount in cents
                "currency" => "eur",
                "source" => $this->getCardToken(),
                "description" => $description
            ));
        }
        catch(\Exception $e)
        {
            return false;
        }

        return $charge['id'];
    }
}