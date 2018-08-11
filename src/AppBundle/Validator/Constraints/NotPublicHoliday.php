<?php
// src/AppBundle/Validator/Constraints/NotPublicHoliday.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotPublicHoliday extends Constraint
{
    public $message = "Aucune réservation possible les jours feriés.";

    public function targets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}