<?php

namespace App\Validator\Constraints;

use App\Repository\BlacklistedPasswordRepository;
use http\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/*
 * @Annotation
 */
class PasswordBlackListValidator extends ConstraintValidator
{
    private mixed $blackListed;

    public function __construct(BlacklistedPasswordRepository $blackListed)
    {
        $this->blackListed = $blackListed;
    }

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

        foreach ($this->blackListed->findAll() as $element) {
            if ($element->getPassword() === $value) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter("{{ string }}", $value)
                    ->addViolation();
                break;
            }
        }
    }
}