<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @Annotation
 */
class EmailDomain extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The value "{{ value }}" is not valid.';

    public $blocked = [];
    
    public function __construct($options = [])
    {
        parent::__construct($options);
        if (!is_array( $options['blocked'])) {
            throw new ConstraintDefinitionException('the "blocked" option must be an array of blocked domains');
        }
    }
    public function getRequiredOptions()
    {
        return ['blocked'];
    }
}
