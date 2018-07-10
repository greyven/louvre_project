<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 */
class Command
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="ticketType", type="boolean")
     */
    private $ticketType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetime")
     */
    private $visitDate;

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfTickets", type="integer")
     */
    private $numberOfTickets;

    /**
     * @var float
     *
     * @ORM\Column(name="totalPrice", type="float")
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorsNames", type="string", length=255)
     */
    private $visitorsNames;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservationDate", type="datetime")
     */
    private $reservationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="visitorEmail", type="string", length=255)
     */
    private $visitorEmail;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ticketType.
     *
     * @param bool $ticketType
     *
     * @return Command
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;

        return $this;
    }

    /**
     * Get ticketType.
     *
     * @return bool
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * Set visitDate.
     *
     * @param \DateTime $visitDate
     *
     * @return Command
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate.
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set numberOfTickets.
     *
     * @param int $numberOfTickets
     *
     * @return Command
     */
    public function setNumberOfTickets($numberOfTickets)
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    /**
     * Get numberOfTickets.
     *
     * @return int
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * Set totalPrice.
     *
     * @param float $totalPrice
     *
     * @return Command
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice.
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set visitorsNames.
     *
     * @param string $visitorsNames
     *
     * @return Command
     */
    public function setVisitorsNames($visitorsNames)
    {
        $this->visitorsNames = $visitorsNames;

        return $this;
    }

    /**
     * Get visitorsNames.
     *
     * @return string
     */
    public function getVisitorsNames()
    {
        return $this->visitorsNames;
    }

    /**
     * Set reservationDate.
     *
     * @param \DateTime $reservationDate
     *
     * @return Command
     */
    public function setReservationDate($reservationDate)
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    /**
     * Get reservationDate.
     *
     * @return \DateTime
     */
    public function getReservationDate()
    {
        return $this->reservationDate;
    }

    /**
     * Set visitorEmail.
     *
     * @param string $visitorEmail
     *
     * @return Command
     */
    public function setVisitorEmail($visitorEmail)
    {
        $this->visitorEmail = $visitorEmail;

        return $this;
    }

    /**
     * Get visitorEmail.
     *
     * @return string
     */
    public function getVisitorEmail()
    {
        return $this->visitorEmail;
    }
}
