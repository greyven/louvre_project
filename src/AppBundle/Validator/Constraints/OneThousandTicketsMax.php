<?php
// src/AppBundle/Validator/Constraints/OneThousandTicketsMax.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OneThousandTicketsMax extends Constraint
{
    public $message = "Votre commande dépasse le quota des 1000 billets journaliers. Veuillez sélectionner une autre ".
                      "date de visite ou acheter moins de billets. Nous sommes désolés pour ce désagrément.";

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}