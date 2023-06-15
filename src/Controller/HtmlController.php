<?php

namespace Base\Controller;

abstract class HtmlController
{
    abstract protected function render(string $file, array $params = []): string;

    abstract protected function renderContent(string $file, array $params = []): string;
}
