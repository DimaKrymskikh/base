<?php

namespace Base\Server;

final class CsrfProtection
{
    public function __construct(
            private FilterRequestInterface & ServerRequestInterface $request
    ) {
    }
    
    public function check(): void
    {
        if ($this->request->getMethod() === 'POST') {
            $token = $this->request->filterInputPost('csrf_token');
            
            if ($token !== $_SESSION['csrf_token']) {
                header($this->request->getProtocol().' 405 Method Not Allowed');
                throw new \Exception('405 Method Not Allowed');
            }
        }
        
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
