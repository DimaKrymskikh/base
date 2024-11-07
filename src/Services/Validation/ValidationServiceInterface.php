<?php

namespace Base\Services\Validation;

interface ValidationServiceInterface
{
    public function validate(string $field, string $options, array $messages): array;
}
