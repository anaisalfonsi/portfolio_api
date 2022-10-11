<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class PasswordBlackList extends Constraint
{
    public string $message = "This password => *{{ string }}* is not allowed to be used";

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return get_class($this).'Validator';
    }
}