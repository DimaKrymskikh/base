<?php

use Base\Server\ServerRequestInterface;
use Base\Support\Options;
use Base\Support\RequestModule;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class RequestModuleTest extends TestCase
{
    private object $config;
    private ServerRequestInterface $serverRequest;
    
    public function test_default_config(): void
    {
        $this->config = (new Options(Config::getDefaultConfig()))->config;

        $this->serverRequest->method('getMethod')->willReturn('get');
        $this->serverRequest->method('getUri')->willReturn('test');
        
        $this->assertEquals((object) [
            'method' => 'get',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/Views/'
            ]
        ], (new RequestModule($this->config, $this->serverRequest))->request);
    }
    
    public function test_config_without_modules(): void
    {
        $this->config = (new Options( Config::getConfigWithoutModules() ))->config;
        
        $this->serverRequest->method('getMethod')->willReturn('post');
        $this->serverRequest->method('getUri')->willReturn('test');
        
        $this->assertEquals((object) [
            'method' => 'post',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/Layout/template.php',
                'views_folder' => '/Views/',
            ]
        ], (new RequestModule($this->config, $this->serverRequest))->request);
    }
    
    public function test_config_with_modules_case_requestmodule_has_general_options(): void
    {
        $this->config = (new Options( Config::getConfigWithModules() ))->config;
        
        $this->serverRequest->method('getMethod')->willReturn('put');
        $this->serverRequest->method('getUri')->willReturn('test');
        
        // Модуль имеет общие параметры, т.к. ни один 'pattern' модулей не содержится в uri запроса (uri = 'test')
        $this->assertEquals((object) [
            'method' => 'put',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/Http/Views/',
            ]
        ], (new RequestModule($this->config, $this->serverRequest))->request);
    }
    
    public function test_config_with_modules_case_requestmodule_has_module_options(): void
    {
        $this->config = (new Options( Config::getConfigWithModules() ))->config;
        
        $this->serverRequest->method('getMethod')->willReturn('delete');
        $this->serverRequest->method('getUri')->willReturn('one/test');
        
        // Модуль имеет параметры модуля
        $this->assertEquals((object) [
            'method' => 'delete',
            'uri' => 'one/test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/app/ModuleOne/Views/',
            ]
        ], (new RequestModule($this->config, $this->serverRequest))->request);
    }

    protected function setUp(): void
    {
        $this->serverRequest = $this->createStub(ServerRequestInterface::class);
    }
}
