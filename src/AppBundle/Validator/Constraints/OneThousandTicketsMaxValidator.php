<?php
// src/AppBundle/Validator/Constraints/OneThousandTicketsMaxValidator.php

namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OneThousandTicketsMaxValidator extends ConstraintValidator
{
    const ONE_THOUSAND = 1000;

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $command
     * @param Constraint $constraint
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function validate($command, Constraint $constraint)
    {
        $nbTickets = $this->howManyTickets($command->getVisitDate());

        if(($nbTickets + $command->getNumberOfTickets()) > self::ONE_THOUSAND)
        {
            $this->context->addViolation($constraint->message);
            return false;
        }

        return true;
    }

    /**
     * @param \DateTime $dateTime
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function howManyTickets(\DateTime $dateTime)
    {
        $repo = $this->em->getRepository('AppBundle:Command');

        $qb = $repo->createQueryBuilder('c');
        $qb->select('COUNT(c)');
        $qb->where('c.visitDate = :visitDate');
        $qb->setParameter('visitDate', $dateTime);

        $count = $qb->getQuery()->getSingleScalarResult();
        return $count;
    }
}