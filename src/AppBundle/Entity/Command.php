<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandRepository")
 *
 * @Constraints\OneThousandTicketsMax()
 * @Constraints\PassedTwoPm()
 */
class Command
{
    const FULL_DAY = true;
    const HALF_DAY = false;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @Assert\Type(type="int")
     * @Assert\Range(min=1, minMessage="Vous devez prendre au moins 1 billet.", max=10, maxMessage="Le maximum d'achat est de 10 billets")
     */
    private $numberOfTickets = 1;

    /**
     * @var bool
     *
     * @ORM\Column(name="fullDay", type="boolean")
     */
    private $fullDay;

    /**
     * @var \DateTime
     *
     * @Assert\Date()
     * @Assert\GreaterThanOrEqual("today", message="Vous ne pouvez pas réserver pour une date passée.")
     *
     * @ORM\Column(name="visitDate", type="datetime")
     *
     * @Constraints\NotSunday()
     * @Constraints\NotTuesday()
     * @Constraints\NotPublicHoliday()
     */
    private $visitDate;

    /**
     * @var float
     *
     * @ORM\Column(name="totalPrice", type="float")
     */
    private $totalPrice;

    /**
     * @var \DateTime
     *
     * @Assert\Date()
     *
     * @ORM\Column(name="reservationDate", type="datetime")
     */
    private $reservationDate;

    /**
     * @var string
     *
     * @Assert\Email()
     *
     * @ORM\Column(name="visitorEmail", type="string", length=255)
     */
    private $visitorEmail;

    /**
     * @var Ticket[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="command", cascade={"persist","remove"})
     */
    private $tickets;

    /**
     * @var string
     *
     * @ORM\Column(name="chargeId", type="string", length=255)
     */
    private $chargeId;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set ticketType.
     *
     * @param $fullDay
     * @return Command
     */
    public function setFullDay($fullDay)
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    /**
     * Get ticketType.
     *
     * @return bool
     */
    public function getFullDay()
    {
        return $this->fullDay;
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
     * Set chargeId.
     *
     * @param string $chargeId
     *
     * @return Command
     */
    public function setChargeId($chargeId)
    {
        $this->chargeId = $chargeId;

        return $this;
    }

    /**
     * Get chargeId.
     *
     * @return string
     */
    public function getChargeId()
    {
        return $this->chargeId;
    }

    /**
     * Add ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Command
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setCommand($this);

        return $this;
    }

    /**
     * Remove ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTicket(Ticket $ticket)
    {
        return $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
