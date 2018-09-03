<?php
// src/AppBundle/Validator/Constraints/NotSunday.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotSunday extends Constraint
{
    public $message = "Aucune réservation possible les dimanches.";
}