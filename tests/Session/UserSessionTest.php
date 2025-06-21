<?php

use Base\Services\User\BaseUserServiceInterface;
use Base\Session\UserSession;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserSessionTest extends TestCase
{
    private UserSession $userSession;
    
    private const NOT_ADMIN_USER = [
                    'id' => 7,
                    'login' => 'A1',
                    'email' => 'q@m.com',
                    'is_admin' => false
                ];

    public function test_singleton(): void
    {
        $userSession = UserSession::getInstance();
        $this->assertTrue($userSession === $this->userSession);
    }

    public function test_user_is_not_in_session(): void
    {
        $this->assertTrue($this->userSession->isGuest());
        $this->assertFalse($this->userSession->isAuth());
        $this->assertFalse($this->userSession->isAdmin());
    }

    public static function emptyUserSessionProvider(): array
    {
        return [
            [null],
            [''],
            [array()],
            [(object) []],
        ];
    }
    
    #[DataProvider('emptyUserSessionProvider')]
    public function test_empty_user_session(mixed $user): void
    {
        $_SESSION['user'] = (object) $user;
        
        $this->assertTrue($this->userSession->isGuest());
        $this->assertFalse($this->userSession->isAuth());
        $this->assertFalse($this->userSession->isAdmin());
        $this->assertEquals(0, $this->userSession->getId());
        $this->assertEquals('', $this->userSession->getLogin());
        $this->assertEquals('', $this->userSession->getEmail());
    }

    public static function authUserSessionProvider(): array
    {
        return [
            [self::NOT_ADMIN_USER],
            // Отсутствует поле is_admin
            [array(
                    'id' => 7,
                    'login' => 'A1',
                    'email' => 'q@m.com',
                )],
        ];
    }
    
    #[DataProvider('authUserSessionProvider')]
    public function test_user_session_is_auth(array $user): void
    {
        $_SESSION['user'] = (object) $user;
        
        $this->assertFalse($this->userSession->isGuest());
        $this->assertTrue($this->userSession->isAuth());
        $this->assertFalse($this->userSession->isAdmin());
        $this->assertEquals(self::NOT_ADMIN_USER['id'], $this->userSession->getId());
        $this->assertEquals(self::NOT_ADMIN_USER['login'], $this->userSession->getLogin());
        $this->assertEquals(self::NOT_ADMIN_USER['email'], $this->userSession->getEmail());
    }

    public function test_user_session_is_admin(): void
    {
        $_SESSION['user'] = (object) self::NOT_ADMIN_USER;
        $_SESSION['user']->is_admin = true;
        
        $this->assertFalse($this->userSession->isGuest());
        $this->assertTrue($this->userSession->isAuth());
        $this->assertTrue($this->userSession->isAdmin());
        $this->assertEquals(self::NOT_ADMIN_USER['id'], $this->userSession->getId());
        $this->assertEquals(self::NOT_ADMIN_USER['login'], $this->userSession->getLogin());
        $this->assertEquals(self::NOT_ADMIN_USER['email'], $this->userSession->getEmail());
    }

    public function test_create_method_save_user_in_session(): void
    {
        $user = $this->createStub(BaseUserServiceInterface::class);
        $user->method('getUser')
                ->willReturn((object) self::NOT_ADMIN_USER);
        
        $this->userSession->create($user);
        
        $this->assertFalse($this->userSession->isGuest());
        $this->assertTrue($this->userSession->isAuth());
        $this->assertFalse($this->userSession->isAdmin());
        $this->assertEquals(self::NOT_ADMIN_USER['id'], $this->userSession->getId());
        $this->assertEquals(self::NOT_ADMIN_USER['login'], $this->userSession->getLogin());
        $this->assertEquals(self::NOT_ADMIN_USER['email'], $this->userSession->getEmail());
    }
    
    public function test_destroy_method_unset_user_from_session(): void
    {
        $_SESSION['user'] = (object) self::NOT_ADMIN_USER;
        
        $this->userSession->destroy();
        
        $this->assertFalse(isset($_SESSION['user']));
    }
    
    #[\Override]
    protected function setUp(): void
    {
        $this->userSession = UserSession::getInstance();
    }
    
    #[\Override]
    protected function tearDown(): void
    {
        $this->userSession->destroy();
    }
}
