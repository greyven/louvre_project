<?php

namespace AppBundle\Controller;

use AppBundle\Form\TicketFormCollectionType;
use AppBundle\Manager\CommandManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\CommandType;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{
    /**
     * @param Request $request
     * @param CommandManager $commandManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandAction(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->initCommand();
        $commandForm = $this->createForm(CommandType::class, $command);

        $commandForm->handleRequest($request);

        if ($commandForm->isSubmitted() && $commandForm->isValid())
        {
            $commandManager->generateTickets($command);
            return $this->redirectToRoute('app_tickets');
        }

        return $this->render('command.html.twig', array('commandForm' => $commandForm->createView()));
    }

    /**
     * @param Request $request
     * @param CommandManager $commandManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketsAction(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->getCurrentCommand();
        $ticketsCollection = $this->createForm(TicketFormCollectionType::class, $command);

        $ticketsCollection->handleRequest($request);

        if ($ticketsCollection->isSubmitted() && $ticketsCollection->isValid())
        {
            $commandManager->completeCommand();
            return $this->redirectToRoute('app_command_payment');
        }

        return $this->render('tickets.html.twig',
            array('ticketsCollection' => $ticketsCollection->createView(), 'recap' => $commandManager->getCommandRecap()
        ));
    }

    /**
     * @param Request $request
     * @param CommandManager $commandManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function paymentAction(Request $request, CommandManager $commandManager)
    {
        if ($request->isMethod('POST'))
        {
            try
            {
                $commandManager->payAndSaveCommand($request);
                $this->addFlash("success", "Paiement éffectué !");
                return $this->redirectToRoute("app_command_confirm");
            }
            catch (\Stripe\Error\Card $e)
            {
                $this->addFlash("error", "Erreur, paiement non éffectué !");
                return $this->redirectToRoute("app_command_payment");
                // The card has been declined
            }
        }

        $command = $commandManager->getCurrentCommand();
        return $this->render('payment.html.twig',
            array('stripe_public_key' => $this->getParameter('stripe_public_key'), 'command' => $command));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(Request $request)
    {
        $command = $request->getSession()->get('command');
        $request->getSession()->remove('command');

        return $this->render('confirm.html.twig', ['command' => $command]);
    }
}