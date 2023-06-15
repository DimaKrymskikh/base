<?php

use PHPUnit\Framework\TestCase;
use Tests\Sources\Controllers\HtmlController;

class BaseHtmlControllerTest extends TestCase
{
    public function test_render_can_draw(): void
    {
        $str = (new HtmlController(__DIR__ . '/../Sources/Views/template.php'))->action();

        $this->assertEquals('begin a=5 b=x end', $str);
    }
}
