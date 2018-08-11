<?php
// src/AppBundle/Validator/Constraints/PassedTwoPmValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PassedTwoPmValidator extends ConstraintValidator
{
    const HALF_DAY = 14;

    public function validate($value, Constraint $constraint)
    {
        $hour = date('H', time());

        if($hour >= self::HALF_DAY && $value)
        {
            $this->context->addViolation($constraint->message);
        }
    }
}