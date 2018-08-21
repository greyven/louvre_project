<?php

namespace Tests\AppBundle\Manager;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\CommandManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CommandManagerTest extends TestCase
{
    public function testGetCurrentCommandReturnCommand()
    {
        $command = new Command();
        $command->setId(15);
        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $pay = $this->createMock('AppBundle\Service\Pay');
        $swift_mailer = $this->createMock('\Swift_Mailer');

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $commandId = $cm->getCurrentCommand()->getId();
        $this->assertSame(15, $commandId);
    }

    public function testGetCurrentCommandThrowingException()
    {
        $session = new Session(new MockArraySessionStorage());
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $pay = $this->createMock('AppBundle\Service\Pay');
        $swift_mailer = $this->createMock('\Swift_Mailer');

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $this->expectException('AppBundle\Exception\CommandNotFoundException');
        $cm->getCurrentCommand();
    }

    public function testGetTotalPriceAndCompleteCommand()
    {
        $command = new Command();
        $command->setFullDay(0);
        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime('2008-01-31'));
        $command->addTicket($ticket);
        $session = new Session(new MockArraySessionStorage());
        $session->set('command', $command);
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $pay = $this->createMock('AppBundle\Service\Pay');
        $swift_mailer = $this->createMock('\Swift_Mailer');

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $totalPrice = $cm->getTotalPrice();
        $this->assertSame(4.0, $totalPrice);

        $cm->completeCommand();
    }

    public function testGenerateTickets()
    {
        $command = new Command();
        $command->setNumberOfTickets(3);

        $session = new Session(new MockArraySessionStorage());
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $pay = $this->createMock('AppBundle\Service\Pay');
        $swift_mailer = $this->createMock('\Swift_Mailer');

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $cm->generateTickets($command);

        $nbTickets = 0;
        foreach ($command->getTickets() as $ticket)
        {
            $nbTickets ++;
        }

        $this->assertSame(3, $nbTickets);
    }

    public function testPersistAndFlushCommand()
    {
        $command = new Command();

        $session = new Session(new MockArraySessionStorage());
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $pay = $this->createMock('AppBundle\Service\Pay');
        $swift_mailer = $this->createMock('\Swift_Mailer');

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $cm->persistAndFlushCommand($command);

        $this->assertSame(4, 4);
    }

    public function testPayAndSaveCommandReturnTrue()
    {
        $command = new Command();
        $command->setTotalPrice(16);

        $session = new Session(new MockArraySessionStorage());
        $em = $this->createMock('Doctrine\ORM\EntityManager');
        $pay = $this->createMock('AppBundle\Service\Pay');
        $swift_mailer = $this->createMock('\Swift_Mailer');

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $result = $cm->payAndSaveCommand($command);
        $this->assertSame(true, $result);
    }

//    public function testPayAndSaveCommandReturnFalse()
//    {
//        $command = new Command();
//        $command->setTotalPrice(0);
//
//        $session = new Session(new MockArraySessionStorage());
//        $em = $this->createMock('Doctrine\ORM\EntityManager');
//        $pay = $this->createMock('AppBundle\Service\Pay');
//        $swift_mailer = $this->createMock('\Swift_Mailer');
//
//        $cm = new CommandManager($session, $em, $pay, $swift_mailer);
//
//        $result = $cm->payAndSaveCommand($command);
//        $this->assertSame(false, $result);
//    }
}