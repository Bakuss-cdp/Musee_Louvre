<?php

namespace MUSEE\LouvreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class closedDays extends Constraint
{
    public $message1 = "Le musée est fermé les mardis";

    public $message2 = "Le musée Louvre n'ouvre pas les dimanches";

    public $message3 = " Le {{day}} le musée sera fermé.";

    public function validatedBy()
    {
        return closedDaysValidator::class;
    }
}


