<?php

namespace Base\Exceptions;

use Base\Server\ServerRequestInterface;

interface HtmlExceptionInterface
{
    /**
     * Записывает сообщения об ошибках в сессию и делает редирект на исходную html-страницу.
     * 
     * @param ServerRequestInterface $request
     * @return void
     */
    public function render(ServerRequestInterface $request): void;
}
