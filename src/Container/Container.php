<?php

namespace Base\Container;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    private array $instances = [];
    
    public function set(string $id, mixed $instance): void
    {
        $this->instances[$id] = $instance;
    }
    
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
    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }
}
