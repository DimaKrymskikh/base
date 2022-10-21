<?php

namespace Base;

/**
 * @property private array $routers
 * @property private string $namespaceControllers Пространство имён, в котором хранятся контроллеры
 * @property private object $error Содержит контроллер и экшен, которые используются, когда указан неверный путь и/или метод
 */
class Router 
{
    private array $routers = [];

    public function __construct(
            private string $namespaceControllers,
            private object $error
    ) 
    {
    }

    /**
     * По полученному методу и $uri выполняется соответствующий экшен
     * @param string $method
     * @param string $uri
     * @return void
     */
    public function run(string $method, string $uri): void
    {
        $arrUri = explode('/', $uri);
        $isFind = false;
        
        // Перебираем все возможные маршруты до первого найденного
        foreach ($this->routers as $router) {
            // Методы запроса должны совпадать
            if (mb_strtolower($method) !== mb_strtolower($router->method)) {
                continue;
            }
            // Разбытые на части полученный $uri и паттерн должны иметь равное число частей
            $arrPattern = explode('/', $router->pattern);
            if (count($arrUri) !== count($arrPattern)) {
                continue;
            }
            // Сравнивая части $uri и патернам, находим аргументы экшена
            $arrArg = [];
            $nCoincidence = 0;
            foreach ($arrPattern as $key => $part) {
                // Если часть паттерна заключена в фигурные скобки, то соответствующая часть $uri - аргумент экшена
                if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                    $arrArg[] = $arrUri[$key];
                    $nCoincidence++;
                // Если часть паттерна без фигурных скобок, то она должна равняться соответствующей части $uri
                } elseif ($arrPattern[$key] === $arrUri[$key]) {
                    $nCoincidence++;
                }
            }
            // Если по всем частям $uri и патерна найдены совпадения, выполняем экшен
            if ($nCoincidence === count($arrPattern)) {
                $isFind = true;
                echo [new $router->controller, $router->action](...$arrArg);
                break;
            }
        }
        
        // Если полученног $uri нет в массиве маршрутов, то выполняем дефолтный экшен (задаётся в приложении)
        if (!$isFind) {
            $errorController = $this->namespaceControllers . '\\' . $this->error->controller;
            echo [new $errorController, $this->error->action]();
        }
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
            'controller' => $this->namespaceControllers . '\\' . $controller,
            'action' => $action
        ];
    }
}
