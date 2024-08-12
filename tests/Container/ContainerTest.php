<?php

use Base\Container\Container;
use Base\Container\NotFoundContainerException;
use PHPUnit\Framework\TestCase;
use Tests\Sources\Container\A;

class ContainerTest extends TestCase
{
    private Container $container;
    private object $ob;
    private array $arr;

    protected function setUp(): void
    {
        $this->container = new Container();
        
        $this->ob = (object) [
            'key1' => 'a',
            'key2' => 7
        ];
        
        $this->arr = ['a', 'b', 'c'];
    }

    public function test_checking_the_set_method(): void
    {
        // Добавляется примитив
        $this->container->set('number', 77);
        // Добавляется объект
        $this->container->set('object', $this->ob);
        // Добавляется массив
        $this->container->set('array', $this->arr);
        // Добавляется экземпляр класса
        $this->container->set('class', new A());
        
        $this->assertEquals(77, $this->container->get('number'));
        $this->assertEquals($this->ob, $this->container->get('object'));
        $this->assertEquals($this->arr, $this->container->get('array'));
        $this->assertInstanceOf(A::class, $this->container->get('class'));
    }
    
    public function test_checking_the_bind_method(): void
    {
        // Добавляется примитив
        $this->container->bind('number', fn (): int => 77);
        // Добавляется объект
        $this->container->bind('object', function(): object {
            return $this->ob;
        });
        // Добавляется массив
        $this->container->bind('array', fn (): array => $this->arr);
        // Добавляется экземпляр класса
        $this->container->bind('class', function(): A {
            return new A();
        });
        
        $this->assertEquals(77, $this->container->get('number'));
        $this->assertEquals($this->ob, $this->container->get('object'));
        $this->assertEquals($this->arr, $this->container->get('array'));
        $this->assertInstanceOf(A::class, $this->container->get('class'));
    }
    
    public function test_checking_the_has_method(): void
    {
        // Добавляется массив
        $this->container->set('array', $this->arr);
        // Добавляется экземпляр класса
        $this->container->set('class', new A());
        
        $this->assertFalse($this->container->has('number'));
        $this->assertFalse($this->container->has('object'));
        $this->assertTrue($this->container->has('array'));
        $this->assertTrue($this->container->has('class'));
    }
    
    public function test_checking_the_get_method_without_exception(): void
    {
        // Добавляется массив
        $this->container->set('array', $this->arr);
        // Добавляется экземпляр класса
        $this->container->set('class', new A());
        
        $this->assertEquals($this->arr, $this->container->get('array'));
        $this->assertInstanceOf(A::class, $this->container->get('class'));
    }
    
    public function test_checking_the_get_method_with_NotFoundContainerException(): void
    {
        $this->expectException(NotFoundContainerException::class);
        $this->expectExceptionMessage("Не существует контейнер с ключом 'class'.");
        
        // Добавляется массив
        $this->container->set('array', $this->arr);
        
        $this->assertEquals($this->arr, $this->container->get('class'));
    }
}
