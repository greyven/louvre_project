<?php
// src/AppBundle/Validator/Constraints/NotTuesday.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTuesday extends Constraint
{
    public $message = "Aucune réservation possible les mardis car le musée est fermé.";
}