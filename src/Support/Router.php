<?php

namespace Base\Support;

final class Router
{
    private array $routers = [];

    public function __construct(
        private array $storage
    )
    {}

    /**
     * По полученному методу и $uri возвращается контроллер, экшен и его параметры
     * 
     * @return object
     */
    public function getAction(): object
    {
        $requestUri = explode('/', trim($this->storage['request']->uri, '/'));
        
        // Перебираем все возможные маршруты до первого найденного
        foreach ($this->routers as $router) {
            // Методы запроса должны совпадать
            if (mb_strtolower($this->storage['request']->method) !== mb_strtolower($router->method)) {
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
            // Если по всем частям $uri и паттерна найдены совпадения, то возвращается контроллер, экшен и параметры экшена
            if ($nCoincidence === count($patternUri)) {
                return (object) [
                    'controller' => $router->controller,
                    'action' => $router->action,
                    'arr_arg' => $arrArg
                ];
            }
        }
        
        // Если по всем частям $uri и паттерна не найдены совпадения, то возвращается error-контроллер, экшен с параметром 'Страница не найдена'
        return (object) [
            'controller' => $this->storage['config']->error_router->controller,
            'action' => $this->storage['config']->error_router->action,
            'arr_arg' => ['Страница не найдена']
        ];
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
