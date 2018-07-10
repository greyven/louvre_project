<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Command;
use AppBundle\Form\CommandType;
use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;

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

    public function showTicketFormAction()
    {

        $ticketForm = $this->createForm(TicketType::class, new Ticket());

        return $this->render('ticketForm.html.twig', array('ticketForm' => $ticketForm->createView()));
    }
}