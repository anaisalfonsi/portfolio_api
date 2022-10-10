<?php

namespace App\Validator\Constraints;

use http\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/*
 * @Annotation
 */
class PasswordBlackListValidator extends ConstraintValidator
{
    const BLACKLISTED = [
        "admin",
        "user",
        "trump",
        "password",
        "pass"
    ];

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint) : void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, code: 'string');
        }

        if (in_array($value, self::BLACKLISTED)) {
            dd("cette valeur a été blacklistée");
            exit;
            /*$this->context->buildViolation($constraint->message)
                ->setParameter("{{ string }}", $value)
                ->addViolation();*/
        }
    }
}