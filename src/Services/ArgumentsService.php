<?php

namespace Base\Services;

/**
 * Класс для внедрения зависимостей в контроллере и его экшенах.
 */
final class ArgumentsService
{
    private array $ctrlArgs;
    private array $actionArgs;

    public function __construct(string $controller, string $action)
    {
        $this->execute($controller, $action);
    }
    
    /**
     * Возвращает массив зависимостей контроллера.
     * 
     * @return array
     */
    public function getCtrlArgs(): array
    {
        return $this->ctrlArgs ?? [];
    }
    
    /**
     * Возвращает массив зависимостей экшена.
     * 
     * @return array
     */
    public function getActionArgs(): array
    {
        return $this->actionArgs ?? [];
    }
    
    /**
     * Задаёт массив зависимостей контроллера.
     * 
     * @param array $arguments
     * @return void
     */
    public function setCtrlArgs(array $arguments): void
    {
        $this->ctrlArgs = $arguments;
    }
    
    /**
     * Задаёт массив зависимостей экшена.
     * 
     * @param array $arguments
     * @return void
     */
    public function setActionArgs(array $arguments): void
    {
        $this->actionArgs = $arguments;
    }
    
    /**
     * Непосредственно задаёт зависимости контроллера и его экшенов.
     * 
     * @param string $controller
     * @param string $action
     * @return void
     */
    private function execute(string $controller, string $action): void
    {
        $parametersClass = $this->getParametersClassName($controller);
        
        if (class_exists($parametersClass)) {
            // Контроллер этого класса задаёт нужные зависимости.
            new $parametersClass($this, $action);
        }
    }
    
    /**
     * По полному имени класса контроллера находит полное имя класса Parameters.
     * Возвращает полное имя класса Parameters.
     * 
     * @param string $controller
     * @return string
     */
    private function getParametersClassName(string $controller): string
    {
        $str = mb_ereg_replace('Controllers', 'Parameters', $controller);
        
        return mb_ereg_replace('Controller', 'Parameters', $str);
    }
}
