<?php
// src/AppBundle/Validator/Constraints/NotTuesdayValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotTuesdayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if($value->format("l") == "Tuesday")
        {
            $this->context->addViolation($constraint->message);
        }
    }
}