<?php

namespace AppBundle\Controller;

use AppBundle\Form\TicketFormCollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Command;
use AppBundle\Form\CommandType;
use AppBundle\Form\TicketType;
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
                $grammar = $command->getNumberOfTickets() > 1 ? ' billets' : ' billet';
                $halfDay = $command->getTicketType() ? ' demi-journée' : ' journée';

                $request->getSession()->getFlashBag()
                    ->add('Récapitulatif', 'Vous souhaitez réserver '.$command->getNumberOfTickets().$grammar.$halfDay.
                        ' le '.$command->getVisitDate()->format('d-m-Y').'.')
                ;

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
//        $locale = $request->getLocale();
        $command = $request->getSession()->get('command');
        $nbTickets = $command->getNumberOfTickets();

        $ticketFormCollection = $this->createForm(TicketFormCollectionType::class);

        for ($i = 0; $i < $nbTickets; $i++)
        {
            $tForm = $this->createForm(TicketType::class);
            $ticketFormCollection->add($tForm);
        }

        return $this->render('tickets.html.twig',
            array('ticketFormCollection' => $ticketFormCollection->createView()));
    }
}