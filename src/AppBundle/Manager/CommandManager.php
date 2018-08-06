<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandManager
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return Command
     */
    public function initCommand()
    {
        $command = new Command();
        $this->session->set('command', $command);
        return $command;
    }

    /**
     * @return Command
     */
    public function getCurrentCommand()
    {
        return $this->session->get('command');
    }

    /**
     * @return string
     */
    public function getCommandRecap()
    {
        $command = $this->getCurrentCommand();
        $grammar = $command->getNumberOfTickets() > 1 ? ' billets' : ' billet';
        $halfDay = $command->getFullDay() ? ' demi-journée' : ' journée';
        $recap = 'Vous souhaitez réserver ' . $command->getNumberOfTickets() . $grammar . $halfDay .
            ' pour le ' . $command->getVisitDate()->format('d-m-Y') . '.';
        return $recap;
    }

    /**
     * @return int
     */
    public function getTotalPrice()
    {
        $command = $this->getCurrentCommand();
        $totalPrice = 0;

        foreach ($command->getTickets() as $ticket) {
            $ticketPrice = $ticket->defineAndSetTicketPrice();
            $totalPrice = $totalPrice + $ticketPrice;
        }
        return $totalPrice;
    }

    /**
     *
     */
    public function completeCommand()
    {
        $command = $this->getCurrentCommand();
        $command->setTotalPrice($this->getTotalPrice());
        $command->setReservationDate(new \DateTime());
    }

    /**
     * @param Command $command
     */
    public function generateTickets(Command $command)
    {
        // ajout de tickets vides
        for ($i = 0; $i < $command->getNumberOfTickets(); $i++)
        {
            $command->addTicket(new Ticket());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCardToken(Request $request)
    {
        // Get the credit card details submitted by the form
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        return $request->get('stripeToken');
    }

    /**
     * @param Request $request
     */
    public function createCharge(Request $request)
    {
        $command = $this->getCurrentCommand();
        $charge = \Stripe\Charge::create(array(
            "amount" => $command->getTotalPrice() * 100, // Amount in cents
            "currency" => "eur",
            "source" => $this->getCardToken($request),
            "description" => "Commande"
        ));

        $command->setChargeId($charge['id']);
    }

    /**
     *
     */
    public function persistAndFlushCommand()
    {
        $command = $this->getCurrentCommand();
        $em = $this->getDoctrine()->getManager();
        $em->persist($command);
        $em->flush();
    }

    /**
     * @param Request $request
     */
    public function payAndSaveCommand(Request $request)
    {
        $this->createCharge($request);
        $this->persistAndFlushCommand();
    }
}