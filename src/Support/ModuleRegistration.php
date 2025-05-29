<?php

namespace Base\Support;

final class ModuleRegistration
{
    public function __construct(
        // Обработанный $config (вместо отсутствующих параметров используются дефолтные)
        private object $config,
        private string $uri
    )
    {}
    
    /**
     * Находит модуль, соответствующий uri запроса, и возвращает параметры модуля.
     * Если модуль не найден, возвращаются общие параметры приложения.
     * 
     * @return object
     */
    public function getRequestModule(): object
    {
        $modules = $this->config->modules;
        $givenModul = null;

        // Если в конфигурации приложения заданы модули, находим модуль, соответствующий запросу
        if(is_array($modules)) {
            foreach ($modules as $module) {
                if(stripos($this->uri, trim($module->pattern, '/')) === 0) {
                    $givenModul = $module;
                    break;
                }
            }
        }

        // Если в модуле не определено какое-то поле, берём главное поле конфигурации
        if($givenModul) {
            $requestModule = (object) [
                'views_folder' => $givenModul->views_folder ?? $this->config->views_folder,
                'template' => $givenModul->template ?? $this->config->template,
            ];
        // Если uri запроса не содержит pattern какого-либо модуля, берём главные поля конфигурации
        } else {
            $requestModule = (object) [
                'views_folder' => $this->config->views_folder,
                'template' => $this->config->template,
            ];
        }
        
        return $requestModule;
    }
}
