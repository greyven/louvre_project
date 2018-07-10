<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Command;
use AppBundle\Form\CommandType;

class CommandController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCommandFormAction()
    {

        $commandForm = $this->createForm(CommandType::class, new Command());

        return $this->render('commandForm.html.twig', array('commandForm' => $commandForm->createView()));
    }
}