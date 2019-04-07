<?php

namespace App\Validator\Allegro;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Authorization extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The value "{{ value }}" is not valid.';
}
