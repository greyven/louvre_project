<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $request->getSession()->set('mailed', true);
            return $this->redirectToRoute('app_mailed');
        }

        return $this->render('contact.html.twig', array('contactForm' => $contactForm->createView()));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailedAction(Request $request)
    {
        $mailed = $request->getSession()->get('mailed');
        $request->getSession()->remove('mailed');

        if($mailed)
        {
            $this->addFlash('success', 'Votre message a bien été envoyé.');
        }

        return $this->render('mailed.html.twig');
    }
}