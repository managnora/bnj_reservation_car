<?php

namespace App\Domain\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations
    ) {
        parent::__construct("Erreur de validation");
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
