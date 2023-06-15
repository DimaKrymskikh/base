<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;

class ContainerTest extends TestCase
{
    public function test_container_can_receiving_and_giving_elements(): void
    {
        $ob = (object) [
            'a' => 1,
            'b' => 2
        ];

        $container = new Container();
        $container->register('a', fn (): int => 777);
        $container->register('b', fn (): object => $ob);

        $this->assertEquals(777, $container->get('a'));
        $this->assertSame($ob, $container->get('b'));
        $this->assertNull($container->get('c'));
    }
}
