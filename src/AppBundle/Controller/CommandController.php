<?php

namespace AppBundle\Controller;

use AppBundle\Form\TicketFormCollectionType;
use AppBundle\Manager\CommandManager;
use AppBundle\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\CommandType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends Controller
{
    /**
     * @Route("/command", name="command")
     *
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

            return $this->redirectToRoute('tickets');
        }

        return $this->render('command.html.twig', array('commandForm' => $commandForm->createView()));
    }

    /**
     * @Route("/tickets", name="tickets")
     *
     * @param Request $request
     * @param CommandManager $commandManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function ticketsAction(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->getCurrentCommand('step1');

        $ticketsCollection = $this->createForm(TicketFormCollectionType::class, $command);

        $ticketsCollection->handleRequest($request);

        if ($ticketsCollection->isSubmitted() && $ticketsCollection->isValid())
        {
            $commandManager->completeCommand($command);
            return $this->redirectToRoute('payment');
        }

        return $this->render('tickets.html.twig',
            array('ticketsCollection' => $ticketsCollection->createView(), 'command' => $command));
    }

    /**
     * @Route("/command/payment", name="payment")
     *
     * @param Request $request
     * @param CommandManager $commandManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\CommandNotFoundException
     */
    public function paymentAction(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->getCurrentCommand('step2');

        if ($request->isMethod('POST'))
        {
            try
            {
                $commandManager->payAndSaveCommand($command);
                $this->addFlash("success", "Paiement éffectué !");
                return $this->redirectToRoute("confirm");
            }
            catch (\Stripe\Error\Card $e)
            {
                $this->addFlash("error", "Erreur, paiement non éffectué !");
                return $this->redirectToRoute("payment");
                // The card has been declined
            }
        }

        return $this->render('payment.html.twig',
            array('stripe_public_key' => $this->getParameter('stripe_public_key'), 'command' => $command));
    }

    /**
     * @Route("/command/confirm", name="confirm", methods={"GET"})
     *
     * @param Request $request
     * @param CommandManager $commandManager
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\CommandNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function confirmAction(Request $request, CommandManager $commandManager, Mailer $mailer)
    {
        $command = $commandManager->getCurrentCommand('step3');
        $request->getSession()->remove('command');

        /** @var int $mailResult (int The number of successful recipients. Can be 0 which indicates failure) */
        $mailResult = $mailer->sendToVisitor($command);

        return $this->render('confirm.html.twig', ['command' => $command, 'mailResult' => $mailResult]);
    }
}