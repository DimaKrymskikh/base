<?php

namespace Base\Container;

use Psr\Container\ContainerInterface;

/**
 * Сервисный контейнер.
 */
final class Container implements ContainerInterface
{
    private static Container|null $instance = null;
    
    private array $instances = [];
    
    private function __construct() {}
    
    /**
     * Возвращает экземпляр контейнера.
     * 
     * @return static
     */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
    
    /**
     * Добавляет в контейнер объект зависимости.
     * 
     * @param string $id
     * @param mixed $instance
     * @return void
     */
    public function set(string $id, mixed $instance): void
    {
        $this->instances[$id] = $instance;
    }
    
    /**
     * Добавляет в контейнер объект зависимости через замыкание.
     * 
     * @param string $id
     * @param \Closure $callback
     * @return void
     */
    public function bind(string $id, \Closure $callback): void
    {
        $this->instances[$id] = $callback();
    }

    /**
     * {@inheritdoc}
     * 
     * @param string $id
     * @return mixed
     */
    #[\Override]
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new NotFoundContainerException($id);
        }
        
        return $this->instances[$id];
    }
    
    /**
     * {@inheritdoc}
     * 
     * @param string $id
     * @return bool
     */
    #[\Override]
    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }
    
    /**
     * Очищает контейнер.
     * 
     * @return void
     */
    public function flush(): void
    {
        $this->instances = [];
    }
}
