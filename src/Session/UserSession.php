<?php

namespace Base\Session;

use Base\Services\User\BaseUserServiceInterface;

final class UserSession extends BaseSession
{
    protected static UserSession|null $instance = null;

    public function create(BaseUserServiceInterface $user): void
    {
        $_SESSION['user'] = $user->getUser();
    }
    
    public function getId(): int
    {
        return $_SESSION['user']?->id ?? 0;
    }
    
    public function getLogin(): string
    {
        return $_SESSION['user']?->login ?? '';
    }
    
    public function getEmail(): string
    {
        return $_SESSION['user']?->email ?? '';
    }
    
    public function isGuest(): bool
    {
        return !isset($_SESSION['user']) || !isset($_SESSION['user']->id);
    }
    
    public function isAuth(): bool
    {
        return !$this->isGuest();
    }
    
    public function isAdmin(): bool
    {
        return $this->isAuth() && $this->getFieldIsAdmin();
    }
    
    #[\Override]
    public function destroy(): void
    {
        unset($_SESSION['user']);
    }
    
    private function getFieldIsAdmin(): bool
    {
        return $_SESSION['user']?->is_admin ?? false;
    }
}
