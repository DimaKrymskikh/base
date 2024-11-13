<?php

namespace Base\Services\Politics;

use Base\Services\User\UserServiceInterface;

interface PoliticsServiceInterface
{
    public function create(UserServiceInterface $user): void;
    
    public function destroy(): void;
    
    public function isGuest(): bool;
    
    public function isAuth(): bool;
    
    public function isAdmin(): bool;
    
    public function getLogin(): string;
    
    public function getEmail(): string;
}
