<?php

use Base\Support\ModuleRegistration;
use Base\Support\Options;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class ModuleRegistrationTest extends TestCase
{
    public function test_default_config(): void
    {
        $config = (new Options(Config::getDefaultConfig()))->config;
        
        $this->assertEquals((object) [
            'template' => '/Views/template.php',
            'views_folder' => '/Views/',
        ], (new ModuleRegistration($config, 'test'))->getRequestModule());
    }
    
    public function test_config_without_modules(): void
    {
        $config = (new Options( Config::getConfigWithoutModules() ))->config;
        
        $this->assertEquals((object) [
            'template' => '/Views/Layout/template.php',
            'views_folder' => '/Views/',
        ], (new ModuleRegistration($config, 'test'))->getRequestModule());
    }
    
    public function test_config_with_modules(): void
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        
        // Возвращаются общие параметры, т.к. ни один 'pattern' модулей не содержится в uri запроса (uri = 'test')
        $this->assertEquals((object) [
            'template' => '/Views/template.php',
            'views_folder' => '/Http/Views/',
        ], (new ModuleRegistration($config, 'test'))->getRequestModule());
        
        // Возвращаются параметры модуля
        $this->assertEquals((object) [
            'template' => '/app/ModuleTwo/Views/template.php',
            'views_folder' => '/app/ModuleTwo/Views/',
        ], (new ModuleRegistration($config, 'two/test'))->getRequestModule());
    }
}
