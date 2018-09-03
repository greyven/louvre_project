<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase
{
    public function testSetGetId()
    {
        $ticket = new Ticket();
        $ticket->setId(79);
        $id = $ticket->getId();
        $this->assertSame(79, $id);
    }

    public function testSetGetPriceType()
    {
        $ticket = new Ticket();
        $ticket->setPriceType(10);
        $priceType = $ticket->getPriceType();
        $this->assertSame(10, $priceType);
    }

    public function testSetGetLastName()
    {
        $ticket = new Ticket();
        $ticket->setLastName('testeur');
        $lastName = $ticket->getLastName();
        $this->assertSame('testeur', $lastName);
    }

    public function testSetGetFirstName()
    {
        $ticket = new Ticket();
        $ticket->setFirstName('testeur');
        $firstName = $ticket->getFirstName();
        $this->assertSame('testeur', $firstName);
    }

    public function testSetGetCountry()
    {
        $ticket = new Ticket();
        $ticket->setCountry('france');
        $country = $ticket->getCountry();
        $this->assertSame('france', $country);
    }

    public function testSetGetBirthDate()
    {
        $ticket = new Ticket();
        $ticket->setBirthDate(date('Y-m-d'));
        $birthDate = $ticket->getBirthDate();
        $this->assertSame(date('Y-m-d'), $birthDate);
    }

    public function testSetGetReducedPrice()
    {
        $ticket = new Ticket();
        $ticket->setReducedPrice(true);
        $reducedPrice = $ticket->getReducedPrice();
        $this->assertSame(true, $reducedPrice);
    }

    public function testSetGetTicketPrice()
    {
        $ticket = new Ticket();
        $ticket->setTicketPrice(16);
        $ticketPrice = $ticket->getTicketPrice();
        $this->assertSame(16, $ticketPrice);
    }

    public function testSetGetCommand()
    {
        $ticket = new Ticket();
        $command = new Command();
        $command->setId(7);
        $ticket->setCommand($command);
        $commandId = $ticket->getCommand()->getId();
        $this->assertSame(7, $commandId);
    }

    public function testDefineAndSetTicketPriceFullDayBaby()
    {
        $command = new Command();
        $command->setFullDay(true);            //journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('2017-01-01')); //bébé
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(0.0, $result);
    }

    public function testDefineAndSetTicketPriceFullDayChild()
    {
        $command = new Command();
        $command->setFullDay(true);            //journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('2010-01-01')); //enfant
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(8.0, $result);
    }

    public function testDefineAndSetTicketPriceFullDayReducedPrice()
    {
        $command = new Command();
        $command->setFullDay(true);            //journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('2000-01-01')); //tarif réduit
        $ticket->setReducedPrice(true);
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(10.0, $result);
    }

    public function testDefineAndSetTicketPriceFullDaySenior()
    {
        $command = new Command();
        $command->setFullDay(true);            //journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('1950-01-01')); //senior
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(12.0, $result);
    }

    public function testDefineAndSetTicketPriceFullDayNormal()
    {
        $command = new Command();
        $command->setFullDay(true);            //journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('1980-01-01')); //normal
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(16.0, $result);
    }

    public function testDefineAndSetTicketPriceHalfDayBaby()
    {
        $command = new Command();
        $command->setFullDay(false);            //demi-journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('2017-01-01')); //bébé
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(0.0, $result);
    }

    public function testDefineAndSetTicketPriceHalfDayChild()
    {
        $command = new Command();
        $command->setFullDay(false);            //demi-journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('2010-01-01')); //enfant
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(4.0, $result);
    }

    public function testDefineAndSetTicketPriceHalfDayReducedPrice()
    {
        $command = new Command();
        $command->setFullDay(false);            //demi-journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('2000-01-01')); //tarif réduit
        $ticket->setReducedPrice(true);
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(5.0, $result);
    }

    public function testDefineAndSetTicketPriceHalfDaySenior()
    {
        $command = new Command();
        $command->setFullDay(false);            //demi-journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('1950-01-01')); //senior
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(6.0, $result);
    }

    public function testDefineAndSetTicketPriceHalfDayNormal()
    {
        $command = new Command();
        $command->setFullDay(false);            //demi-journée
        $ticket = new Ticket();
        $ticket->setCommand($command);

        $ticket->setBirthDate(new \DateTime('1980-01-01')); //normal
        $result = $ticket->defineAndSetTicketPrice();
        $this->assertSame(8.0, $result);
    }

    public function testCalculateAge()
    {
        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime('2000-12-31'));
        $age = $ticket->calculateAge();
        $this->assertSame(17, $age);
    }
}