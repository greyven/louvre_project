<?php
// src/AppBundle/Validator/Constraints/NotSundayValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotSundayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if($value->format("l") == "Sunday")
        {
            $this->context->addViolation($constraint->message);
        }
    }
}