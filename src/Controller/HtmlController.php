<?php

namespace Base\Controller;

abstract class HtmlController
{
    abstract protected function render(string $file, array $params = []): string;

    abstract protected function renderContent(string $file, array $params = []): string;

    abstract protected function conditionalRender(string $file, array $data): string;

    abstract protected function redirect(string $uri, int $code = 303): void;
}
