<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\CommandNotFoundException;
use AppBundle\Service\Pay;
use Swift_Mailer;
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
    private $swift_Mailer;

    public function __construct(SessionInterface $session, EntityManagerInterface $em, Pay $pay, Swift_Mailer $swift_Mailer)
    {
        $this->session = $session;
        $this->em = $em;
        $this->pay = $pay;
        $this->swift_Mailer = $swift_Mailer;
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
     * @throws CommandNotFoundException
     */
    public function getCurrentCommand()
    {
        $command = $this->session->get('command');
        if($command instanceof Command)
        {
            return $command;
        }
        else
        {
            throw new CommandNotFoundException("La commande n'existe pas.");
        }
    }

    /**
     * @return int
     * @throws CommandNotFoundException
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
     * @throws CommandNotFoundException
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
            return true;
        }

        return false;
    }
}