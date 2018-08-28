<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\CommandNotFoundException;
use AppBundle\Service\Pay;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SessionInterface $session, EntityManagerInterface $em, Pay $pay,
                                Swift_Mailer $swift_Mailer, ValidatorInterface $validator)
    {
        $this->session = $session;
        $this->em = $em;
        $this->pay = $pay;
        $this->swift_Mailer = $swift_Mailer;
        $this->validator = $validator;
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
     * @param null $validator_group
     * @return Command
     * @throws CommandNotFoundException
     */
    public function getCurrentCommand($validator_group = null)
    {
        $command = $this->session->get('command');
        if($command instanceof Command)
        {
            switch ($validator_group)
            {
                case 'step1':
                    $errors = $this->validator->validate($command, null, ['step1']);
                    break;
                case 'step2':
                    $errors = $this->validator->validate($command, null, ['step2']);
                    break;
                case 'step3':
                    $errors = $this->validator->validate($command, null, ['step3']);
                    break;
                default :
                    $errors = [];
            }

            if(count($errors) > 0)
            {
                //return $errors;
                throw new CommandNotFoundException("Commande invalide.");
            }

            return $command;
        }
        else
        {
            throw new CommandNotFoundException("Pas de commande en mémoire.");
        }
    }

    /**
     * @param Command $command
     * @return int
     */
    public function getTotalPrice(Command $command)
    {
        $totalPrice = 0;

        /** @var Ticket $ticket */
        foreach ($command->getTickets() as $ticket)
        {
            $ticketPrice = $ticket->defineAndSetTicketPrice();
            $totalPrice = $totalPrice + $ticketPrice;
        }
        return $totalPrice;
    }

    /**
     * @param Command $command
     */
    public function completeCommand(Command $command)
    {
        $command->setTotalPrice($this->getTotalPrice($command));
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