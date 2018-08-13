<?php
/**
 * Created by PhpStorm.
 * User: Windows
 * Date: 13/08/2018
 * Time: 10:51
 */

namespace AppBundle\Service;


use AppBundle\Entity\Command;

class Mailer
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }


    public function sendCommandConfirmation(Command $command)
    {
        $body = $this->twig->render('kop',['command'=>$command]);
    }

}