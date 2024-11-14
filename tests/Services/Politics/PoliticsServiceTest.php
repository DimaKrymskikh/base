<?php

use Base\Services\Politics\PoliticsService;
use Base\Services\User\UserServiceInterface;
use PHPUnit\Framework\TestCase;

class PoliticsServiceTest extends TestCase
{
    private PoliticsService $politics;
    
    private const NOT_ADMIN_USER = [
                    'login' => 'A1',
                    'email' => 'q@m.com',
                    'is_admin' => false
                ];


    public function test_getInstance_method_create_a_single_object(): void
    {
        $politics = PoliticsService::getInstance();
        
        $this->assertInstanceOf(PoliticsService::class, $politics);
        $this->assertSame($this->politics, $politics);
    }
   

    public function test_user_is_not_in_session(): void
    {
        $this->assertTrue($this->politics->isGuest());
        $this->assertFalse($this->politics->isAuth());
        $this->assertFalse($this->politics->isAdmin());
    }

    public function test_user_session_is_null(): void
    {
        $_SESSION['user'] = null;
        
        $this->assertTrue($this->politics->isGuest());
        $this->assertFalse($this->politics->isAuth());
        $this->assertFalse($this->politics->isAdmin());
    }

    public function test_user_session_is_empty_string(): void
    {
        $_SESSION['user'] = '';
        
        $this->assertTrue($this->politics->isGuest());
        $this->assertFalse($this->politics->isAuth());
        $this->assertFalse($this->politics->isAdmin());
    }

    public function test_user_session_is_empty_object(): void
    {
        $_SESSION['user'] = (object) [];
        
        $this->assertTrue($this->politics->isGuest());
        $this->assertFalse($this->politics->isAuth());
        $this->assertFalse($this->politics->isAdmin());
    }

    public function test_user_session_is_auth(): void
    {
        $_SESSION['user'] = (object) [
            'is_admin' => false
        ];
        
        $this->assertFalse($this->politics->isGuest());
        $this->assertTrue($this->politics->isAuth());
        $this->assertFalse($this->politics->isAdmin());
    }

    public function test_user_session_is_admin(): void
    {
        $_SESSION['user'] = (object) [
            'is_admin' => true
        ];
        
        $this->assertFalse($this->politics->isGuest());
        $this->assertTrue($this->politics->isAuth());
        $this->assertTrue($this->politics->isAdmin());
    }

    public function test_create_method_save_user_in_session(): void
    {
        $user = $this->createStub(UserServiceInterface::class);
        $user->method('getUser')
                ->willReturn((object) self::NOT_ADMIN_USER);
        
        $this->politics->create($user);
        
        $this->assertSame(self::NOT_ADMIN_USER['login'], $_SESSION['user']->login);
        $this->assertSame(self::NOT_ADMIN_USER['email'], $_SESSION['user']->email);
        $this->assertSame($this->politics->isAdmin(), $_SESSION['user']->is_admin);
    }

    public function test_destroy_method_unset_user_from_session(): void
    {
        $_SESSION['user'] = (object) self::NOT_ADMIN_USER;
        
        $this->politics->destroy();
        
        $this->assertFalse(isset($_SESSION['user']));
    }
    
    protected function setUp(): void
    {
        $this->politics = PoliticsService::getInstance();
    }
    
    protected function tearDown(): void
    {
        unset($this->politics);
        unset($_SESSION['user']);
    }
}
