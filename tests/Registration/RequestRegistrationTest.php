<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;
use Base\Registration\RequestRegistration;

class RequestRegistrationTest extends TestCase
{
    public function test_registration_can_request(): void
    {
        $container = new Container();
        $config = (object) [
            'method' => 'get',
            'uri' => 'a/b'
        ];

        new RequestRegistration($container, $config);

        $this->assertEquals('get', $container->get('request')->method);
        $this->assertEquals('a/b', $container->get('request')->uri);
    }
}
