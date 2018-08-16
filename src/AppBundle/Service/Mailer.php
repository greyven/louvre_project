<?php

namespace AppBundle\Service;


use AppBundle\Entity\Command;
use Swift_Message;

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
    private $mailSender;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $swift_Mailer
     * @param \Twig_Environment $twig
     * @param $mailSender
     */
    public function __construct(\Swift_Mailer $swift_Mailer, \Twig_Environment $twig, $mailSender)
    {
        $this->twig = $twig;
        $this->mailSender = $mailSender;
        $this->swift_Mailer = $swift_Mailer;
    }

    /**
     * @param Command $command
     * @return Swift_Message
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendConfirmationMail(Command $command)
    {
        $body = $this->twig->render('mail.command.html.twig',['command'=>$command]);

        // Creer le message
        $message = (new Swift_Message('Commande n°'.$command->getId()))
            ->setFrom($this->mailSender)
            ->setTo([$command->getVisitorEmail()])
            ->setBody($body)
        ;

        // Envoyer le message
        return $this->swift_Mailer->send($message);
    }

}