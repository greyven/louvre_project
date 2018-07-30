<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Date()
     *
     * @ORM\Column(name="visitDate", type="datetime")
     */

    private $visitDate;

    /**
     * @var int
     *
     * @Assert\Type(type="int")
     * @Assert\Range(min=1, minMessage="Vous devez prendre au moins 1 billet.", max=10, maxMessage="Le maximum d'achat est de 10 billets")
     *
     * @ORM\Column(name="numberOfTickets", type="integer")
     */

    private $numberOfTickets = 1;

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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket.
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Command
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
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
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
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
