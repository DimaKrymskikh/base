<?php

namespace Base\Support;

use Base\Foundation\Application;

final class Router
{
    private array $routers = [];

    public function __construct(
        private Application $app
    )
    {}

    /**
     * Добавляет в контейнер приложения контроллер и данные, необходимые этому контроллеру
     * 
     * @return void
     */
    public function setAction(): void
    {
        $appUri = $this->app->make('config')->app_url;
        
        $requestUri = explode('/', trim($this->app->make('request')->uri, '/'));
        
        // Перебираем все возможные маршруты до первого найденного
        foreach ($this->routers as $router) {
            // Методы запроса должны совпадать
            if (mb_strtolower($this->app->make('request')->method) !== mb_strtolower($router->method)) {
                continue;
            }
            // Разбитые на части полученный $uri и паттерн должны иметь равное число частей
            $patternUri = explode('/', trim($router->pattern, '/'));
            if (count($requestUri) !== count($patternUri)) {
                continue;
            }
            // Сравнивая части $uri и паттерна, находим аргументы экшена
            $arrArg = [];
            $nCoincidence = 0;
            foreach ($patternUri as $key => $part) {
                // Если часть паттерна заключена в фигурные скобки, то соответствующая часть $uri - аргумент экшена
                if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                    $arrArg[] = $requestUri[$key];
                    $nCoincidence++;
                    // Если часть паттерна без фигурных скобок, то она должна равняться соответствующей части $uri
                } elseif ($patternUri[$key] === $requestUri[$key]) {
                    $nCoincidence++;
                }
            }
            // Если по всем частям $uri и паттерна найдены совпадения, то добавляем в контейнер контроллер маршрута
            // и данные, необходимые этому контроллеру
            if ($nCoincidence === count($patternUri)) {
                $this->app->bind('action', fn (): object => (object) [
                        'controller' => $router->controller,
                        'action' => $router->action,
                        'arr_arg' => $arrArg,
                        'template' => $appUri.$this->app->make('request')->module->template,
                        'views_folder' => $appUri.$this->app->make('request')->module->views_folder,
                    ]);
                return;
            }
        }
        
        // Если по всем частям $uri и паттерна не найдены совпадения, то добавляем в контейнер контроллер для ошибок
        // и данные, необходимые этому контроллеру
        $this->app->bind('action', fn (): object => (object) [
                'controller' => $this->app->make('config')->error_router->controller,
                'action' => $this->app->make('config')->error_router->action,
                'arr_arg' => ['Страница не найдена'],
                'template' => $appUri.$this->app->make('config')->error_router->template,
                'views_folder' => $appUri.$this->app->make('config')->error_router->views_folder,
            ]);
    }

    /**
     * Добавляем маршрут с методом 'GET'
     * @param string $pattern
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function get(string $pattern, string $controller, string $action = 'index'): void
    {
        $this->routers[] = $this->getObject('GET', $pattern, $controller, $action);
    }

    /**
     * Добавляем маршрут с методом 'POST'
     * @param string $pattern
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function post(string $pattern, string $controller, string $action = 'index'): void
    {
        $this->routers[] = $this->getObject('POST', $pattern, $controller, $action);
    }

    /**
     * Добавляем маршрут с методом 'PUT'
     * @param string $pattern
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function put(string $pattern, string $controller, string $action = 'index'): void
    {
        $this->routers[] = $this->getObject('PUT', $pattern, $controller, $action);
    }

    /**
     * Добавляем маршрут с методом 'DELETE'
     * @param string $pattern
     * @param string $controller
     * @param string $action
     * @return void
     */
    public function delete(string $pattern, string $controller, string $action = 'index'): void
    {
        $this->routers[] = $this->getObject('DELETE', $pattern, $controller, $action);
    }

    /**
     * Возвращает объект для добавления в $this->routers
     * @param string $method
     * @param string $pattern
     * @param string $controller
     * @param string $action
     * @return object
     */
    private function getObject(string $method, string $pattern, string $controller, string $action = 'index'): object
    {
        return (object) [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action
        ];
    }
}
