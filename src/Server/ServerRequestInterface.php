<?php

namespace Base\Server;

interface ServerRequestInterface
{
    public function getMethod(): string;
    
    public function getUri(): string;
    
    public function getProtocol(): string;
    
    /**
     * Редирект на исходную страницу.
     * 
     * @return void
     */
    public function back(): void;
}
