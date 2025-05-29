<?php
namespace Base\Server;

interface FilterRequestInterface
{
    public function filterInputPost(string $name): string;
    
    public function filterInputGet(string $name): string;
}
