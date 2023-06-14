<?php

namespace Tests\Controllers;

class BarController
{
    public function index(): string
    {
        return 'BarController->index';
    }

    public function get(int $a): string
    {
        return "BarController->get: $a";
    }

    public function post(int $a, string $b, string $c): string
    {
        return "BarController->post: $a, $b и $c";
    }

    public function put(string $a): string
    {
        return "BarController->put: $a";
    }

    public function delete(string $a, string $b): string
    {
        return "BarController->delete: $a и $b";
    }
}
