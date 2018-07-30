<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    const AGE_CHILD = 4;
    const AGE_ADULT = 12;
    const AGE_SENIOR = 60;

    const PRICE_CHILD = 8;
    const PRICE_NORMAL = 16;
    const PRICE_SENIOR = 12;
    const PRICE_REDUCED = 10;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="costType", type="string", length=255)
     */
    private $costType;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="datetime")
     */
    private $birthDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="reducedCost", type="boolean")
     */
    private $reducedCost;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Command", inversedBy="tickets")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @param $birthDate
     * @param null $reducedPrice
     * @param null $halfDay
     * @return int
     */
    public function defineTicketCost($birthDate, $reducedPrice = null, $halfDay = null)
    {
        $age = $this->calculateAge($birthDate);

        if($halfDay)
        {
            $coef = 0.5;
        }
        else
        {
            $coef = 1;
        }

        if($age < self::AGE_CHILD)
        {
            $this->setCostType('Gratuit - bébé');
            return 0;
        }
        elseif ($age >= self::AGE_CHILD && $age < self::AGE_ADULT)
        {
            $this->setCostType('Tarif - enfant');
            return (self::PRICE_CHILD * $coef);
        }

        if($reducedPrice)
        {
            $this->setCostType('Tarif - réduit (présenter justificatif)');
            return (self::PRICE_REDUCED * $coef);
        }
        else
        {
            $age >= self::AGE_SENIOR ? $this->setCostType('Tarif - senior') : $this->setCostType('Tarif - normal');
            return $age >= self::AGE_SENIOR ? (self::PRICE_SENIOR * $coef) : (self::PRICE_NORMAL * $coef);
        }
    }

    /**
     * @param $birthDate
     * @return int
     */
    public function calculateAge($birthDate)
    {
        $strBDate = $birthDate->format('d/m/Y');
        $bDate = explode('/', $strBDate);
        $today = explode('/', date('d/m/Y'));

        if(($bDate[1] < $today[1]) || (($bDate[1] == $today[1]) && ($bDate[0] <= $today[0])))
        { return ($today[2] - $bDate[2]); }

        return ($today[2] - $bDate[2] - 1);
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

    /**
     * Set costType.
     *
     * @param string $costType
     *
     * @return Ticket
     */
    public function setCostType($costType)
    {
        $this->costType = $costType;

        return $this;
    }

    /**
     * Get costType.
     *
     * @return string
     */
    public function getCostType()
    {
        return $this->costType;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Ticket
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set birthDate.
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate.
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set reducedCost.
     *
     * @param bool $reducedCost
     *
     * @return Ticket
     */
    public function setReducedCost($reducedCost)
    {
        $this->reducedCost = $reducedCost;

        return $this;
    }

    /**
     * Get reducedCost.
     *
     * @return bool
     */
    public function getReducedCost()
    {
        return $this->reducedCost;
    }

    /**
     * Set command.
     *
     * @param \AppBundle\Entity\Command $command
     *
     * @return Ticket
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command.
     *
     * @return \AppBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
    }
}
