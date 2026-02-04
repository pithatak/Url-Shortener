<?php

namespace App\Validators;

use App\Dto\UrlCreateData;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UrlValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(UrlCreateData $url): array
    {
        $errors = $this->validator->validate($url);
        $errorMessages = [];

        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorMessages;
    }
}
