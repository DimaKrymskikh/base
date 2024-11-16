<?php

namespace Base\Services\Politics;

use Base\Services\User\BaseUserServiceInterface;

final class PoliticsService implements PoliticsServiceInterface
{
    private static ?PoliticsService $instance = null;
    
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    private function __construct()
    {}

    public function create(BaseUserServiceInterface $user): void
    {
        $_SESSION['user'] = $user->getUser();
    }
    
    public function destroy(): void
    {
        unset($_SESSION['user']);
    }
    
    public function isGuest(): bool
    {
        return !isset($_SESSION['user']) || !isset($_SESSION['user']->is_admin);
    }
    
    public function isAuth(): bool
    {
        return isset($_SESSION['user']) && isset($_SESSION['user']->is_admin);
    }
    
    public function isAdmin(): bool
    {
        return isset($_SESSION['user']) && isset($_SESSION['user']->is_admin) && $_SESSION['user']->is_admin;
    }
}
