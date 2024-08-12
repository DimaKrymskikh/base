<?php

namespace Base\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ContainerException extends Exception implements ContainerExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct("Контейнер с ключом '$id' неправильно настроен.");
    }
}
