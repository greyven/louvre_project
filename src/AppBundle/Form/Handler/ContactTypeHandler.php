<?php

namespace AppBundle\Form\Handler;


use AppBundle\Form\ContactType;
use AppBundle\Service\Mailer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ContactTypeHandler
{
    /**
     * @var RequestStack
     */
    private $stack;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(RequestStack $stack, FormFactoryInterface $formFactory, Mailer $mailer)
    {
        $this->stack = $stack;
        $this->formFactory = $formFactory;
        $this->mailer = $mailer;
    }

    public function handle()
    {
        $request = $this->stack->getCurrentRequest();

        $contactForm = $this->formFactory->create(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $this->mailer->sendToMuseum($contactForm);
            return true;
        }

        return $contactForm;
    }
}