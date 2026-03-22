<?php

use Base\Container\Container;
use Base\Controller\BaseHtmlController;
use Base\Router\ActionOptions;
use PHPUnit\Framework\TestCase;

class BaseHtmlControllerTest extends TestCase
{
    private BaseHtmlController $ctrl;

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

    #[\Override]
    protected function setUp(): void
    {
        Container::getInstance()->set('action', new ActionOptions('', '', [], 'tests/Sources/Views/Html/template.php', 'tests/Sources/Views/Html/'));
        
        $this->ctrl = new BaseHtmlController();
    }
    
    #[\Override]
    protected function tearDown(): void
    {
        Container::getInstance()->flush();
    }
}
