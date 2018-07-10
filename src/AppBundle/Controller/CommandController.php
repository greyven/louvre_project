<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCommandFormAction()
    {

        $commandForm = $this->createForm(CommandType::class, new Command());

        return $this->render('form.html.twig', array('commandForm' => $commandForm->createView()));
    }
}