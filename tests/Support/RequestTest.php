<?php

use Base\Support\Options;
use Base\Support\Request;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class RequestTest extends TestCase
{
    public function test_default_config(): void
    {
        $config = (new Options(Config::getDefaultConfig()))->config;
        
        $this->assertEquals((object) [
            'method' => 'get',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/Views/'
            ]
        ], (new Request($config, 'get', 'test'))->request);
    }
    
    public function test_config_without_modules(): void
    {
        $config = (new Options( Config::getConfigWithoutModules() ))->config;
        
        $this->assertEquals((object) [
            'method' => 'post',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/Layout/template.php',
                'views_folder' => '/Views/',
            ]
        ], (new Request($config, 'post', 'test'))->request);
    }
    
    public function test_config_with_modules(): void
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        
        // Модуль имеет общие параметры, т.к. ни один 'pattern' модулей не содержится в uri запроса (uri = 'test')
        $this->assertEquals((object) [
            'method' => 'put',
            'uri' => 'test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/Http/Views/',
            ]
        ], (new Request($config, 'put', 'test'))->request);
        
        // Модуль имеет параметры модуля
        $this->assertEquals((object) [
            'method' => 'delete',
            'uri' => 'one/test',
            'module' => (object) [
                'template' => '/Views/template.php',
                'views_folder' => '/app/ModuleOne/Views/',
            ]
        ], (new Request($config, 'delete', 'one/test'))->request);
    }
}
