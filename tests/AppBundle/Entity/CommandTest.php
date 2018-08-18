<?php

namespace Tests\AppBundle\Entity;


use AppBundle\Entity\Ticket;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    private $command;

    public function setUp()
    {
        $this->command = $this->getMockBuilder('AppBundle\Entity\Command')
                              ->disableOriginalConstructor()
                              ->setMethods(['setId', 'getId', 'setFullDay', 'getFullDay', 'setVisitDate', 'getVisitDate',
                                  'setTotalPrice', 'getTotalPrice', 'setReservationDate', 'getReservationDate',
                                  'setVisitorEmail', 'getVisitorEmail', 'setNumberOfTickets', 'getNumberOfTickets',
                                  'setChargeId', 'getChargeId', 'addTicket', 'removeTicket', 'getTickets'])
                              ->getMock();
    }
    
    public function testSetGetId()
    {
        $this->command->setId(79);
        $id = $this->command->getId();
        $this->assertSame(79, $id);
    }

    public function testSetGetFullDay()
    {
        $this->command->setFullDay(true);
        $fullDay = $this->command->getFullDay();
        $this->assertSame(true, $fullDay);
    }

    public function testSetGetVisitDate()
    {
        $this->command->setVisitDate(date('d/m/Y'));
        $visitDate = $this->command->getVisitDate();
        $this->assertSame(date('d/m/Y'), $visitDate);
    }

    public function testSetGetTotalPrice()
    {
        $this->command->setTotalPrice(25);
        $totalPrice = $this->command->getTotalPrice();
        $this->assertSame(25, $totalPrice);
    }

    public function testSetGetReservationDate()
    {
        $this->command->setReservationDate(date('d/m/Y'));
        $reservationDate = $this->command->getReservationDate();
        $this->assertSame(date('d/m/Y'), $reservationDate);
    }

    public function testSetGetVisitorEmail()
    {
        $this->command->setVisitorEmail('test@test.com');
        $visitorEmail = $this->command->getVisitorEmail();
        $this->assertSame('test@test.com', $visitorEmail);
    }

    public function testSetGetNumberOfTickets()
    {
        $this->command->setNumberOfTickets(5);
        $numberOfTickets = $this->command->getNumberOfTickets();
        $this->assertSame(5, $numberOfTickets);
    }

    public function testSetGetChargeId()
    {
        $this->command->setChargeId('TeSTCHaRGeiD');
        $chargeId = $this->command->getChargeId();
        $this->assertSame('TeSTCHaRGeiD', $chargeId);
    }

    public function testAddGetRemoveTicket()
    {
        $ticket = new Ticket();
        $ticket->setId(45);
        $this->command->addTicket($ticket);
        foreach ($this->command->getTickets() as $tick)
        {
            $ticketId = $tick->getId();
        }
        $this->command->removeTicket($ticket);
        $this->assertSame(45, $ticketId);
    }
}