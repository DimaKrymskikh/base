<?php

namespace Base\Router;

use Base\Container\Container;

final class Router
{
    private array $routers = [];

    public function __construct(
        private Container $container
    ) {
        //
    }

    /**
     * Добавляет в контейнер приложения контроллер и данные, необходимые этому контроллеру
     * 
     * @return void
     */
    public function setAction(): void
    {
        $config = $this->container->get('config');
        $requestModule = $this->container->get('requestModule');
        
        $appUri = $config->app_url;
        
        $requestUri = explode('/', trim($requestModule->uri, '/'));
        
        // Перебираем все возможные маршруты до первого найденного
        foreach ($this->routers as $router) {
            // Методы запроса должны совпадать
            if (mb_strtolower($requestModule->method) !== mb_strtolower($router->method)) {
                continue;
            }
            // Разбитые на части полученный $uri и паттерн должны иметь равное число частей
            if (count($requestUri) !== count($router->patternChunks)) {
                continue;
            }
            // Сравнивая части $uri и паттерна, находим аргументы экшена
            $nCoincidence = 0;
            foreach ($router->patternChunks as $key => $part) {
                // Если часть паттерна заключена в фигурные скобки, то соответствующая часть $uri - аргумент экшена
                if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                    $router->pushActionArguments($requestUri[$key]);
                    $nCoincidence++;
                    // Если часть паттерна без фигурных скобок, то она должна равняться соответствующей части $uri
                } elseif ($router->patternChunks[$key] === $requestUri[$key]) {
                    $nCoincidence++;
                }
            }
            // Если по всем частям $uri и паттерна найдены совпадения, то добавляем в контейнер контроллер маршрута
            // и данные, необходимые этому контроллеру
            if ($nCoincidence === count($router->patternChunks)) {
                $this->container->set(
                        'action',
                        new ActionOptions(
                            $router->controller,
                            $router->action,
                            $router->getActionArguments(),
                            $appUri.$requestModule->module->template,
                            $appUri.$requestModule->module->views_folder,
                        )
                    );
                return;
            }
        }
        
        // Если по всем частям $uri и паттерна не найдены совпадения, то добавляем в контейнер контроллер для ошибок
        // и данные, необходимые этому контроллеру
        $this->container->set(
                'action',
                new ActionOptions(
                    $config->error_router->controller,
                    $config->error_router->action,
                    ['Страница не найдена'],
                    $appUri.$config->error_router->template,
                    $appUri.$config->error_router->views_folder,
                )
            );
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
     * 
     * @param string $method
     * @param string $pattern
     * @param string $controller
     * @param string $action
     * @return object
     */
    private function getObject(string $method, string $pattern, string $controller, string $action = 'index'): RouterOptions
    {
        return new RouterOptions($method, $pattern, $controller, $action);
    }
}
