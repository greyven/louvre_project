<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     *
     * @param Request $request
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request, Mailer $mailer)
    {
        $request->getSession()->remove('command');
        
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $mailer->sendToMuseum($contactForm);
            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig', array('contactForm' => $contactForm->createView()));
    }
}