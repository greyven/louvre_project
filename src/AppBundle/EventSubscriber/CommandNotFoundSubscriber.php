<?php
// src/AppBundle/EventSubscriber/ExceptionSubscriber.php

namespace AppBundle\EventSubscriber;

use AppBundle\Exception\CommandNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;


class CommandNotFoundSubscriber implements EventSubscriberInterface
{
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
        $response = new RedirectResponse('/louvre_project/web/app_dev.php/home');
        $event->setResponse($response);
    }
}