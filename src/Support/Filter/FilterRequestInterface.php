<?php
namespace Base\Support\Filter;

interface FilterRequestInterface
{
    public function filterInputPost(string $name): string;
}