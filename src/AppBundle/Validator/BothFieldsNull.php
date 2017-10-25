<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

const VALIDATION_MESSAGE = 'Populate at least one input field.';
const VALIDATOR_CONST    = 'Validator';

/**
 * Class BothFieldsNull
 *
 * Custom validator annotation. Both fields cannot be null.
 *
 * @package AppBundle\Validator
 * @Annotation
 */
class BothFieldsNull extends Constraint
{
    public $message = VALIDATION_MESSAGE;

    /**
     * Validator used for validating
     *
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).VALIDATOR_CONST;
    }
}
