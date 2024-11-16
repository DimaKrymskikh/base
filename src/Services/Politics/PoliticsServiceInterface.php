<?php

namespace Base\Services\Politics;

use Base\Services\User\BaseUserServiceInterface;

interface PoliticsServiceInterface
{
    public function create(BaseUserServiceInterface $user): void;
    
    public function destroy(): void;
    
    public function isGuest(): bool;
    
    public function isAuth(): bool;
    
    public function isAdmin(): bool;
}
