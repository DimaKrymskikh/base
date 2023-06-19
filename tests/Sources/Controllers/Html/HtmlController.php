<?php

namespace Tests\Sources\Controllers\Html;

use Base\Controller\BaseHtmlController;

class HtmlController extends BaseHtmlController
{
    public function action(): string
    {
        return $this->render('test.php', [
            'a' => 5,
            'b' => 'x'
        ]);
    }
}
