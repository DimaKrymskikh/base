<?php

namespace Tests\Sources\Controllers\Html;

use Base\Controller\BaseHtmlController;

class ErrorController extends BaseHtmlController
{
    public function action(): string
    {
        return $this->render('error.php');
    }
}
