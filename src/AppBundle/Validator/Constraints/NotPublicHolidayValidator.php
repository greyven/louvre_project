<?php
// src/AppBundle/Validator/Constraints/NotPublicHolidayValidator.php

namespace AppBundle\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotPublicHolidayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $holidaysList = self::getPublicHolidays($value);

        foreach ($holidaysList as $date)
        {
            if ($date == $value)
            {
                $this->context->addViolation($constraint->message);
                break;
            }
        }
    }

    /**
     * @param DateTime $date
     *
     * @return array
     */
    static public function getPublicHolidays(DateTime $date = null)
    {
        if ($date === null)
        {
            $date = new DateTime();
        }

        $easterDate = new DateTime('@' . easter_date($date->format('Y')));

        $publicHolidaysList = array(
            // Fixed date list
            new DateTime($date->format('Y-1-1')), // 1st january
            new DateTime($date->format('Y-5-1')), // May day
            new DateTime($date->format('Y-5-8')), // 1945
            new DateTime($date->format('Y-7-14')), // Nation Party day
            new DateTime($date->format('Y-8-15')), // Assumption
            new DateTime($date->format('Y-11-1')), // All Saints Day
            new DateTime($date->format('Y-11-11')), // Armistice
            new DateTime($date->format('Y-12-25')), // Christmas

            // Variable date from easter date
            (new DateTime($easterDate->format('Y-m-d')))->modify('+ 1 day'),
            (new DateTime($easterDate->format('Y-m-d')))->modify('+ 39 day'), // Don't count the current easter day
            (new DateTime($easterDate->format('Y-m-d')))->modify('+ 50 day'),
        );

        // Sort DateTime
        usort($publicHolidaysList, function ($a, $b) {
            $interval = $a->diff($b);
            return $interval->invert ? 1 : -1;
        });
        return $publicHolidaysList;
    }
}