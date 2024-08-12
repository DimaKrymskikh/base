<?php

namespace Base\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundContainerException extends Exception implements NotFoundExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct("Не существует контейнер с ключом '$id'.");
    }
}
