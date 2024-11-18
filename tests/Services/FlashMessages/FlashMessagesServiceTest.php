<?php

use Base\Services\FlashMessages\FlashMessagesService;
use PHPUnit\Framework\TestCase;

class FlashMessagesServiceTest extends TestCase
{
    private const FLASH_MESSAGE = 'flash_message';

    private FlashMessagesService $flashMessages;

    public function test_createFlashMessage_method_is_recording_in_session(): void
    {
        $this->flashMessages->createFlashMessage('name', 'message');
        
        $this->assertEquals('message', $_SESSION[self::FLASH_MESSAGE]['name']);
    }

    public function test_createFlashMessage_method_rewrite_session(): void
    {
        $_SESSION[self::FLASH_MESSAGE]['name'] = 'Дима';
        $this->flashMessages->createFlashMessage('name', 'message');
        
        $this->assertEquals('message', $_SESSION[self::FLASH_MESSAGE]['name']);
    }

    public function test_getFlashMessage_method_return_flashMessage(): void
    {
        $_SESSION[self::FLASH_MESSAGE]['test'] = 'Проверка';
        $_SESSION[self::FLASH_MESSAGE]['name'] = 'Дима';
        
        $this->assertEquals('Проверка', $this->flashMessages->getFlashMessage('test'));
        $this->assertEquals(['name' => 'Дима'], $_SESSION[self::FLASH_MESSAGE]);
        $this->assertFalse(isset($_SESSION[self::FLASH_MESSAGE]['test']));
    }

    public function test_getFlashMessage_method_return_empty_string_if_key_not_set(): void
    {
        $this->assertSame('', $this->flashMessages->getFlashMessage('test'));
    }

    public function test_destroy_method_unset_flashMessage(): void
    {
        $_SESSION[self::FLASH_MESSAGE]['test'] = 'Проверка';
        $_SESSION[self::FLASH_MESSAGE]['name'] = 'Дима';
        
        $this->flashMessages->destroy();
        
        $this->assertFalse(isset($_SESSION[self::FLASH_MESSAGE]));
    }

    protected function setUp(): void
    {
        $this->flashMessages = FlashMessagesService::getInstance();
    }
    
    protected function tearDown(): void
    {
        $this->flashMessages->destroy();
    }
}
