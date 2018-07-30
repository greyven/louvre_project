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
    public function showCommandFormAction(Request $request)
    {
        $command = new Command();
        $commandForm = $this->createForm(CommandType::class, $command);

        // Si on a des données 'POST' c'est que le formulaire a été envoyé donc on le traite
        if($request->isMethod('POST'))
        {
            // Récupère les données du Form et hydrate $command
            $commandForm->handleRequest($request);

            // Si les données saisies sont valides
            if($commandForm->isValid())
            {
                // insertion des tickets vides
                for($i = 0 ; $i < $command->getNumberOfTickets(); $i++ ){
                    $command->addTicket(new Ticket());
                }

                // sauvegarde $command en variable session
                $request->getSession()->set('command', $command);

                // Redirection vers le second formulaire auquel on passe l'objet $command
                return $this->redirectToRoute('app_show_tickets_forms');
            }
        }

        // Soit le visiteur arrive sur la page, soit des données du formulaire ne sont pas valides
        return $this->render('commandForm.html.twig', array('commandForm' => $commandForm->createView()));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTicketsFormsAction(Request $request)
    {
        $command = $request->getSession()->get('command');
        $grammar = $command->getNumberOfTickets() > 1 ? ' billets' : ' billet';
        $halfDay = $command->getTicketType() ? ' demi-journée' : ' journée';
        $recap = 'Vous souhaitez réserver '.$command->getNumberOfTickets().$grammar.$halfDay.
        ' pour le '.$command->getVisitDate()->format('d-m-Y').'.';

        $ticketsCollection = $this->createForm(TicketFormCollectionType::class, $command);

        // Si on a des données 'POST' c'est que le formulaire a été envoyé donc on le traite
        if($request->isMethod('POST'))
        {
            dump($command);
            // Récupère les données des forms et hydrate $command
            $ticketsCollection->handleRequest($request);
            dump($command);

            // Si les forms sont valides
            if($ticketsCollection->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $totalPrice = 0;
                $visitorsNames = '';

                // On persist chaque $ticket dans la collection
                foreach ($command->getTickets() as $ticket)
                {
                    $em->persist($ticket);

                    $bdate = $ticket->getBirthDate();
                    $redu = $ticket->getReducedCost();
                    $half = $command->getTicketType();
                    $ticketPrice = $ticket->defineTicketCost($bdate, $redu, $half);
                    $totalPrice = $totalPrice + $ticketPrice;

                    $ticketName = $ticket->getFirstName()." ".$ticket->getLastName();
                    $visitorsNames = $visitorsNames."/".$ticketName;
                }

                // on complète les champs vides de la commande
                $command->setTotalPrice($totalPrice);
                $command->setVisitorsNames($visitorsNames);
                $command->setReservationDate(new \DateTime());

                // on persist la commande
                $em->persist($command);

                // et on sauvegarde en bdd
                $em->flush();

                $request->getSession()->set('command', $command);
                // Redirection vers la page de paiement
                return $this->redirectToRoute('app_bill');
            }
        }

        // Sinon le visiteur arrive sur la page, ou bien des données du formulaire ne sont pas valides
        return $this->render('tickets.html.twig',
            array(
                'ticketsCollection' => $ticketsCollection->createView(),
                'command' => $command,
                'recap' => $recap
        ));
    }

    public function billAction(Request $request)
    {
        $command = $request->get('command');

//        $commId = $request->get('commId');
//        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Command');
//        $comm = $repo->find($commId);

        return $this->render('bill.html.twig', array('command' => $command));
    }
}