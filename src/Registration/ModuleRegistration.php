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
            $container->register('modul', fn (): object => (object) [
                'views_uri' => isset($givenModul->views_uri) ? $givenModul->views_uri : $container->get('views_uri'),
                'template' => isset($givenModul->template) ? $givenModul->template : $container->get('template'),
            ]);
            // Если uri запроса не содержит pattern какого-либо модуля, берём главные поля конфигурации
        } else {
            $container->register('modul', fn (): object => (object) [
                'views_uri' => $container->get('views_uri'),
                'template' => $container->get('template'),
            ]);
        }
    }
}
