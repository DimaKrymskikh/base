<?php

use Base\DataTransferObjects\InputServerDto;
use Base\Support\Options;
use Base\Support\Request;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class RequestTest extends TestCase
{
    public function test_default_config(): void
    {
        $config = (new Options(Config::getDefaultConfig()))->config;
        $inputServer = new InputServerDto('get', 'test');
        
        $this->assertEquals((object) [
            'method' => 'get',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/Views/'
            ]
        ], (new Request($config, $inputServer))->request);
    }
    
    public function test_config_without_modules(): void
    {
        $config = (new Options( Config::getConfigWithoutModules() ))->config;
        $inputServer = new InputServerDto('post', 'test');
        
        $this->assertEquals((object) [
            'method' => 'post',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/Layout/template.php',
                'views_folder' => '/Views/',
            ]
        ], (new Request($config, $inputServer))->request);
    }
    
    public function test_config_with_modules(): void
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $inputServer = new InputServerDto('put', 'test');
        $inputServerModule = new InputServerDto('delete', 'one/test');
        
        // Модуль имеет общие параметры, т.к. ни один 'pattern' модулей не содержится в uri запроса (uri = 'test')
        $this->assertEquals((object) [
            'method' => 'put',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/Http/Views/',
            ]
        ], (new Request($config, $inputServer))->request);
        
        // Модуль имеет параметры модуля
        $this->assertEquals((object) [
            'method' => 'delete',
            'uri' => 'one/test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/app/ModuleOne/Views/',
            ]
        ], (new Request($config, $inputServerModule))->request);
    }
}
