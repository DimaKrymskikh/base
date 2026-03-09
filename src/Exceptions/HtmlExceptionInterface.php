<?php

namespace Base\Exceptions;

interface HtmlExceptionInterface
{
    /**
     * Служит для записи сообщений об ошибках в сессию, чтобы затем отрисовать их на html-странице.
     * 
     * @return void
     */
    public function render(): void;
}
