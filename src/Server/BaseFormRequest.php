<?php

namespace Base\Server;

use Base\Session\FlashMessagesSession;

abstract class BaseFormRequest
{
    protected array $data;
    protected ServerRequest $request;

    public function __construct()
    {
        $this->request = new ServerRequest();
        $this->putRequstData();
        
        $flashMessages = FlashMessagesSession::getInstance();
        foreach ($this->data as $key => $value) {
            $flashMessages->setFlashMessage($key, $value);
        }
    }
    
    public function getData(): array
    {
        return $this->data;
    }
    
    protected abstract function putRequstData(): void;
}
