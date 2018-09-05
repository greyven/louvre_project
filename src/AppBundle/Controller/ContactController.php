<?php

namespace AppBundle\Controller;

use AppBundle\Form\Handler\ContactTypeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     *
     * @param ContactTypeHandler $contactTypeHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(ContactTypeHandler $contactTypeHandler)
    {
        $result = $contactTypeHandler->handle();

        if (true === $result)
        {
            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig', array('contactForm' => $result->createView()));
    }
}