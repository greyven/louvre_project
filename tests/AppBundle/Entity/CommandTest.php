<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Command;
use AppBundle\Entity\Ticket;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testSetGetId()
    {
        $command = new Command();
        $command->setId(79);
        $id = $command->getId();
        $this->assertSame(79, $id);
    }

    public function testSetGetFullDay()
    {
        $command = new Command();
        $command->setFullDay(true);
        $fullDay = $command->getFullDay();
        $this->assertSame(true, $fullDay);
    }

    public function testSetGetVisitDate()
    {
        $command = new Command();
        $command->setVisitDate(date('Y-m-d'));
        $visitDate = $command->getVisitDate();
        $this->assertSame(date('Y-m-d'), $visitDate);
    }

    public function testSetGetTotalPrice()
    {
        $command = new Command();
        $command->setTotalPrice(25);
        $totalPrice = $command->getTotalPrice();
        $this->assertSame(25, $totalPrice);
    }

    public function testSetGetReservationDate()
    {
        $command = new Command();
        $command->setReservationDate(date('Y-m-d'));
        $reservationDate = $command->getReservationDate();
        $this->assertSame(date('Y-m-d'), $reservationDate);
    }

    public function testSetGetVisitorEmail()
    {
        $command = new Command();
        $command->setVisitorEmail('test@test.com');
        $visitorEmail = $command->getVisitorEmail();
        $this->assertSame('test@test.com', $visitorEmail);
    }

    public function testSetGetNumberOfTickets()
    {
        $command = new Command();
        $command->setNumberOfTickets(5);
        $numberOfTickets = $command->getNumberOfTickets();
        $this->assertSame(5, $numberOfTickets);
    }

    public function testSetGetChargeId()
    {
        $command = new Command();
        $command->setChargeId('TeSTCHaRGeiD');
        $chargeId = $command->getChargeId();
        $this->assertSame('TeSTCHaRGeiD', $chargeId);
    }

    public function testAddGetRemoveTicket()
    {
        $ticket = new Ticket();
        $ticket->setId(45);
        $command = new Command();
        $command->addTicket($ticket);
        foreach ($command->getTickets() as $tick)
        {
            $ticketId = $tick->getId();
        }
        $command->removeTicket($ticket);
        $this->assertSame(45, $ticketId);
    }
}