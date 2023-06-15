<?php

namespace Tests\Sources\Controllers;

class FooController
{
    public function index(): string
    {
        return 'FooController->index';
    }

    public function get(int $a): string
    {
        return "FooController->get: $a";
    }

    public function post(int $a): string
    {
        return "FooController->post: $a";
    }

    public function put(int $a, int $b): string
    {
        return "FooController->put: $a и $b";
    }

    public function delete(int $a, string $b): string
    {
        return "FooController->delete: $a и $b";
    }
}
