<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketFormCollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Command;
use AppBundle\Form\CommandType;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commandAction(Request $request)
    {
        $command = new Command();
        $commandForm = $this->createForm(CommandType::class, $command);


        // Récupère les données du Form et hydrate $command
        $commandForm->handleRequest($request);

        // Si on a des données 'POST' et Valides, c'est que le formulaire a été envoyé donc on le traite
        if($commandForm->isSubmitted() && $commandForm->isValid())
        {
            // insertion des tickets vides
            for($i = 0 ; $i < $command->getNumberOfTickets(); $i++ )
            {
                $command->addTicket(new Ticket());
            }

            // sauvegarde $command en variable session
            $request->getSession()->set('command', $command);

            // Redirection vers le second formulaire auquel on passe l'objet $command
            return $this->redirectToRoute('app_tickets');
        }

        // Soit le visiteur arrive sur la page, soit des données du formulaire ne sont pas valides
        return $this->render('commandForm.html.twig', array('commandForm' => $commandForm->createView()));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketsAction(Request $request)
    {
        $command = $request->getSession()->get('command');
        $grammar = $command->getNumberOfTickets() > 1 ? ' billets' : ' billet';
        $halfDay = $command->getFullDay() ? ' demi-journée' : ' journée';
        $recap = 'Vous souhaitez réserver '.$command->getNumberOfTickets().$grammar.$halfDay.
        ' pour le '.$command->getVisitDate()->format('d-m-Y').'.';

        $ticketsCollection = $this->createForm(TicketFormCollectionType::class, $command);


        // Récupère les données des forms et hydrate $command
        $ticketsCollection->handleRequest($request);

        // Si on a des données 'POST' et Valides, c'est que le formulaire a été envoyé donc on le traite
        if($ticketsCollection->isSubmitted() && $ticketsCollection->isValid())
        {
            $totalPrice = 0;

            // On persist chaque $ticket dans la collection
            foreach ($command->getTickets() as $ticket)
            {
                $ticketPrice = $ticket->defineAndSetTicketPrice();
                $totalPrice = $totalPrice + $ticketPrice;
            }

            // on complète les champs vides de la commande
            $command->setTotalPrice($totalPrice);
            $command->setReservationDate(new \DateTime());

            $request->getSession()->set('command', $command);
            // Redirection vers la page de paiement
            return $this->redirectToRoute('app_command_prepare');
        }

        // Sinon le visiteur arrive sur la page, ou bien des données du formulaire ne sont pas valides
        return $this->render('tickets.html.twig',
            array(
                'ticketsCollection' => $ticketsCollection->createView(),
                'command' => $command,
                'recap' => $recap
        ));
    }

    public function prepareAction(Request $request)
    {
        $command = $request->getSession()->get('command');
        $request->getSession()->set('command', $command);
        dump($command);

        if(($command->getChargeId() == null) && ($request->isMethod('POST')))
        {
            // Get the credit card details submitted by the form
            \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
            $token = $request->get('stripeToken');

            // Create a charge: this will charge the user's card
            try
            {
                $charge = \Stripe\Charge::create(array(
                    "amount" => $command->getTotalPrice() * 100, // Amount in cents
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Commande"
                ));

                $command->setChargeId($charge->id);

                $this->addFlash("success", "Paiement éffectué !");

                $request->getSession()->set('command', $command);

                return $this->redirectToRoute("app_command_prepare");
            }
            catch(\Stripe\Error\Card $e)
            {
                $this->addFlash("error", "Erreur, paiement non éffectué !");

                return $this->redirectToRoute("app_command_prepare");
                // The card has been declined
            }
        }
        elseif($command->getChargeId() !== null)
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($command);

            foreach ($command->getTickets() as $ticket)
            {
                $em->persist($ticket);
            }

            $em->flush();

            $this->addFlash("success", "Entitées sauvegardées !");
        }

        return $this->render('prepare.html.twig',
            array('stripe_public_key' => $this->getParameter('stripe_public_key'), 'command' => $command));
    }
}