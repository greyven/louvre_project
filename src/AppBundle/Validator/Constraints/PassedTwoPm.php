<?php
// src/AppBundle/Validator/Constraints/PassedTwoPm.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PassedTwoPm extends Constraint
{
    public $message = "Après 14h, seuls les billets demi-journée peuvent être réservés.";
}