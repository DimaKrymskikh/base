<?php

namespace Base\Router;

readonly class ActionOptions 
{
    public function __construct(
        public string $controller,
        public string $action,
        public array $actionArguments,
        public string $template,
        public string $viewsFolder,
    ) {
        //
    }
}
