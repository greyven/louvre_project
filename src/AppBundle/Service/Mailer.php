<?php

namespace AppBundle\Service;


use AppBundle\Entity\Command;
use AppBundle\Form\ContactType;
use Swift_Image;
use Swift_Message;
use Symfony\Component\Form\FormInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $swift_Mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    private $internalMail;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $swift_Mailer
     * @param \Twig_Environment $twig
     * @param $internalMail
     */
    public function __construct(\Swift_Mailer $swift_Mailer, \Twig_Environment $twig, $internalMail)
    {
        $this->twig = $twig;
        $this->swift_Mailer = $swift_Mailer;
        $this->internalMail = $internalMail;
    }

    /**
     * @param Command $command
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendToVisitor(Command $command)
    {
        // Creer le message
        $message = new Swift_Message('Commande '.$command->getChargeId());
        $imgSrc = $message->embed(Swift_Image::fromPath('assets/images/mail_header.jpg'));
        $body = $this->twig->render('mail.command.html.twig',['command'=>$command, 'imgSrc' => $imgSrc]);
        $message
            ->setFrom($this->internalMail)
            ->setTo($command->getVisitorEmail())
            ->setBody($body)
            ->setContentType('text/html')
        ;

        // Envoyer le message
        return $this->swift_Mailer->send($message);
    }

    public function sendToMuseum(FormInterface $contactForm)
    {
        // Creer le message
        $message = new Swift_Message($contactForm->getData()['subject']);
        $body = $this->twig->render('mail.contact.html.twig',['contactForm'=>$contactForm]);
        $message
            ->setFrom($contactForm->getData()['visitorEmail'])
            ->setTo($this->internalMail)
            ->setBody($body)
            ->setContentType('text/html')
        ;

        // Envoyer le message
        return $this->swift_Mailer->send($message);
    }
}