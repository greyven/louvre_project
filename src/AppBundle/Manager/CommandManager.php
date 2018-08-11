<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use AppBundle\Service\Pay;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class CommandManager
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Pay
     */
    private $pay;
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(SessionInterface $session, EntityManagerInterface $em, Pay $pay, Swift_Mailer $mailer)
    {
        $this->session = $session;
        $this->em = $em;
        $this->pay = $pay;
        $this->mailer = $mailer;
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

        /** @var Ticket $ticket */
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
     * @param Command $command
     */
    public function persistAndFlushCommand(Command $command)
    {
        $this->em->persist($command);
        $this->em->flush();
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function payAndSaveCommand(Command $command)
    {
        $transactionId = $this->pay->createCharge($command->getTotalPrice(), "Commande");
        if($transactionId !== false)
        {
            $command->setChargeId($transactionId);
            $this->persistAndFlushCommand($command);
            //TODO envoyer mail
            return true;
        }

        return false;
    }

    /**
     * @param Command $command
     * @param Swift_Mailer $mailer
     * @return int
     */
    public function sendMail(Command $command)
    {
        // Create a message
        $message = (new Swift_Message('Wonderful Subject'))
            ->setFrom(['stephen.sere@seyssinet-pariset.com' => 'Stef'])
            ->setTo([$command->getVisitorEmail()])
            ->setBody('Here is the message itself')
        ;

        // Send the message
        $result = $this->mailer->send($message);
        return $result;
    }
}