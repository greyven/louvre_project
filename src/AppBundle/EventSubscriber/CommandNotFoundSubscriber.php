<?php
// src/AppBundle/EventSubscriber/ExceptionSubscriber.php

namespace AppBundle\EventSubscriber;

use AppBundle\Exception\CommandNotFoundException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;


class CommandNotFoundSubscriber implements EventSubscriberInterface
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(Router $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(KernelEvents::EXCEPTION =>  array('redirectToHome', 1));
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function redirectToHome(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        if($e instanceof CommandNotFoundException)
        {
            $this->session->getFlashBag()->add('error', $e->getMessage());
            $response = new RedirectResponse($this->router->generate('homepage'));
            $event->setResponse($response);
        }

        return;
    }
}