<?php

namespace Base\Services\CsrfProtection;

final class CsrfProtectionService implements CsrfProtectionServiceInterface
{
    public function checkAndCreateToken(): void
    {
        $method = strtoupper(filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        if ($method === 'POST') {
            $token = filter_input(INPUT_POST, 'csrf_token');
            
            if (!$token || $token !== $_SESSION['csrf_token']) {
                header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL').' 405 Method Not Allowed');
                exit;
            }
        }
        
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
