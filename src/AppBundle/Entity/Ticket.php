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

    const PRICE_BABY = 0;
    const PRICE_CHILD = 8;
    const PRICE_NORMAL = 16;
    const PRICE_SENIOR = 12;
    const PRICE_REDUCED = 10;

    const COEF_FULL = 1;
    const COEF_HALF = 0.5;


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
     * @ORM\Column(name="priceType", type="integer")
     */
    private $priceType;

    /**
     * @var int
     *
     * @ORM\Column(name="ticketPrice", type="integer")
     */
    private $ticketPrice;

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
     * @ORM\Column(name="reducedPrice", type="boolean")
     */
    private $reducedPrice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Command", inversedBy="tickets")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @return int
     */
    public function defineAndSetTicketPrice()
    {
        $age = $this->calculateAge();
        $coef = ($this->getCommand()->getFullDay() == Command::FULL_DAY)? self::COEF_FULL : self::COEF_HALF;


        if($age < self::AGE_CHILD)
        {
            $this->setPriceType(0); // 0 = Gratuit - bébé
            $price = self::PRICE_BABY;
        }
        elseif ($age < self::AGE_ADULT)
        {
            $this->setPriceType(1); // 1 = Tarif - enfant
            $price = self::PRICE_CHILD * $coef;
        }
        else
        {
            if($this->getReducedPrice())
            {
                $this->setPriceType(2); // 2 = Tarif - réduit (présenter justificatif)
                $price = self::PRICE_REDUCED * $coef;
            }
            else
            {
                // 3 = Tarif - senior  |  4 = Plein tarif
                $age >= self::AGE_SENIOR ? $this->setPriceType(3) : $this->setPriceType(4);
                $price = ($age >= self::AGE_SENIOR) ? (self::PRICE_SENIOR * $coef) : (self::PRICE_NORMAL * $coef);
            }
        }

        $this->setTicketPrice($price);

        return $price;
    }

    /**
     * @return int
     */
    public function calculateAge()
    {

        $birthDate = $this->getBirthDate()->format('d/m/Y');
        $bDate = explode('/', $birthDate);
        $today = explode('/', date('d/m/Y'));

        if(($bDate[1] < $today[1]) || (($bDate[1] == $today[1]) && ($bDate[0] <= $today[0])))
        {
            return ($today[2] - $bDate[2]);
        }
        else
        {
            return ($today[2] - $bDate[2] - 1);
        }
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
     * Set priceType.
     *
     * @param \int $priceType
     *
     * @return Ticket
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * Get priceType.
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
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
     * Set reducedPrice.
     *
     * @param bool $reducedPrice
     *
     * @return Ticket
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice.
     *
     * @return bool
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
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

    /**
     * Set ticketPrice.
     *
     * @param \int $ticketPrice
     *
     * @return Ticket
     */
    public function setTicketPrice($ticketPrice)
    {
        $this->ticketPrice = $ticketPrice;

        return $this;
    }

    /**
     * Get ticketPrice.
     *
     * @return \int
     */
    public function getTicketPrice()
    {
        return $this->ticketPrice;
    }
}
