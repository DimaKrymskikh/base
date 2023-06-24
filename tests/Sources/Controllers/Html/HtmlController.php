<?php

namespace Tests\Sources\Controllers\Html;

use Base\Controller\BaseHtmlController;
use Base\Container\Container;

class HtmlController extends BaseHtmlController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->pushTemplateParameters('key', 'value');
    }

    public function action(): string
    {
        return $this->render('test.php', [
            'a' => 5,
            'b' => 'x'
        ]);
    }
}
