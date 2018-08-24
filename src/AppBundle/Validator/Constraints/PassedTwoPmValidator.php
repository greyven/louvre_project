<?php
// src/AppBundle/Validator/Constraints/PassedTwoPmValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PassedTwoPmValidator extends ConstraintValidator
{
    const HALF_DAY = 14;

    /**
     * @param mixed $command
     * @param Constraint $constraint
     * @return bool
     */
    public function validate($command, Constraint $constraint)
    {
        $hour = date('H', time());
        $today = date('Y-m-d');

        if(($hour >= self::HALF_DAY) && ($command->getVisitDate()->format('Y-m-d') == $today) && $command->getFullDay())
        {
            $this->context->addViolation($constraint->message);
            return false;
        }

        return true;
    }
}