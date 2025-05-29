<?php

use Base\Controller\BaseHtmlController;
use Base\Router\ActionOptions;
use PHPUnit\Framework\TestCase;

class BaseHtmlControllerTest extends TestCase
{
    private BaseHtmlController $ctrl;
    private ActionOptions $options;

    public function test_renderContent(): void
    {
        $baseCtrl = new ReflectionClass(BaseHtmlController::class);
        $renderContent = $baseCtrl->getMethod('renderContent');
        
        $page = $renderContent->invoke($this->ctrl, 'tests/Sources/Views/Html/test.php', [
            'a' => 5,
            'b' => 'x'
        ]);
        
        $this->assertEquals('a=5 b=x', $page);
    }

    public function test_render(): void
    {
        $baseCtrl = new ReflectionClass(BaseHtmlController::class);
        $render = $baseCtrl->getMethod('render');
        
        $templateParameters = $baseCtrl->getProperty('templateParameters');
        $parameters = $templateParameters->getValue($this->ctrl);
        
        $parameters['key'] = 'value';
        $templateParameters->setValue($this->ctrl, $parameters);
        
        $page = $render->invoke($this->ctrl, 'test.php', [
            'a' => 5,
            'b' => 'x'
        ]);
        
        $this->assertEquals('begin value a=5 b=x end', $page);
    }

    protected function setUp(): void
    {
        $this->options = new ActionOptions('', '', [], 'tests/Sources/Views/Html/template.php', 'tests/Sources/Views/Html/');
        
        $this->ctrl = new BaseHtmlController($this->options);
    }
}
