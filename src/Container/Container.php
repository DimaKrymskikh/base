<?php

namespace Base\Container;

use Base\Contracts\Container\Container as ContainerContract;

/**
 * Класс для хранения глобальных зависимостей
 */
final class Container implements ContainerContract
{
    // Массив для хранения глобальных зависимостей
    private array $storage = [];

    /**
     * Регистрирует глобальную зависимость (в приложении)
     * @param string $key
     * @param \Closure $callback
     * @return void
     */
    public function register(string $key, \Closure $callback): void
    {
        $this->storage[$key] = $callback();
    }

    /**
     * Возвращает глобальную зависимость
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }
}
