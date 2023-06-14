<?php

use PHPUnit\Framework\TestCase;
use Base\Jwt\JwtHelper;

class JwtHelperTest extends TestCase
{
    public function testGetToken()
    {
        $secretKey = base64_encode('qqqqqq');

        // Тестовые данные для двух токенов
        $jti1 = 'qqqqq';
        $uid1 = 1;
        $nbf1 = '+1 minute';
        $jti2 = null;
        $uid2 = 2;

        // Создаём два токена
        $token1 = JwtHelper::generateToken($secretKey, 'http://server.com', 'http://client.com', $jti1, $uid1, $nbf1, '+1 hour');
        $token2 = JwtHelper::generateToken($secretKey, 'http://server.com', 'http://client.com', $jti2, $uid2);

        // Получаем распарсенные токены
        $resultToken1 = JwtHelper::getResultToken($token1->toString());
        $resultToken2 = JwtHelper::getResultToken($token2->toString());

        // Распарсенные токены действительны
        $this->assertTrue(JwtHelper::isValidToken($resultToken1, $secretKey));
        $this->assertTrue(JwtHelper::isValidToken($resultToken2, $secretKey));

        // Проверка данных распарсенного токена
        $this->assertEquals($jti1, $resultToken1->claims()->get('jti'));
        $this->assertNull($resultToken2->claims()->get('jti'));
        $this->assertEquals($uid1, $resultToken1->claims()->get('uid'));
        $this->assertEquals($uid2, $resultToken2->claims()->get('uid'));
        $this->assertNull($resultToken2->claims()->get('nbf'));
    }
}
