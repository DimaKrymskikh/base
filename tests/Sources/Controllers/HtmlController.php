<?php

namespace Tests\Sources\Controllers;

use Base\Controller\BaseHtmlController;

class HtmlController extends BaseHtmlController
{
    protected const BASE_URL = __DIR__ . '/../Views/';

    public function action(): string
    {
        return $this->render('test.php', [
            'a' => 5,
            'b' => 'x'
        ]);
    }
}
