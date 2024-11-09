<?php

namespace Base\Services\CsrfProtection;

interface CsrfProtectionServiceInterface
{
    public function checkAndCreateToken(): void;
}
