<?php

namespace Base\Registration;

use Base\Container\Container;
use Base\Contracts\Registration\Registration;

final class ModuleRegistration extends Registration
{
    protected function register(Container $container, object $config): void
    {
        $modules = isset($config->modules) ? $config->modules : null;
        $givenModul = null;

        // Если в конфигурации приложения заданы модули, находим модуль, соответствующий запросу
        if(is_array($modules)) {
            foreach ($modules as $modul) {
                if(stripos($container->get('request')->uri, trim($modul->pattern, '/')) === 0) {
                    $givenModul = $modul;
                    break;
                }
            }
        }

        // Если в модуле не определено какое-то поле, берём главное поле конфигурации
        if($givenModul) {
            $container->register('module', fn (): object => (object) [
                'views_folder' => isset($givenModul->views_folder) ? $givenModul->views_folder : $container->get('views_folder'),
                'template' => isset($givenModul->template) ? $givenModul->template : $container->get('template'),
            ]);
            // Если uri запроса не содержит pattern какого-либо модуля, берём главные поля конфигурации
        } else {
            $container->register('module', fn (): object => (object) [
                'views_folder' => $container->get('views_folder'),
                'template' => $container->get('template'),
            ]);
        }
    }
}
