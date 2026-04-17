<?php

namespace Base\Server;

use Base\Utils\ArrayUtils;

final class ServerRequest implements FilterRequestInterface, ServerRequestInterface
{
    private string $method;
    private string $uri;
    private string $protocol;
    private string $host;
    private string $httpRefere;

    public function __construct()
    {
        $this->method = strtoupper($this->filterInputPost('request_method') ?: filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        $this->uri = mb_trim(parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH), '/');
        $this->protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
        $this->host = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $this->httpRefere = filter_input(INPUT_SERVER, 'HTTP_REFERER') ?? '';
    }
    
    #[\Override]
    public function getMethod(): string
    {
        return $this->method;
    }
    
    #[\Override]
    public function getUri(): string
    {
        return $this->uri;
    }
    
    #[\Override]
    public function getProtocol(): string
    {
        return $this->protocol;
    }
    
    #[\Override]
    public function filterInputGet(string $name): string
    {
        return filter_input(INPUT_GET, $name) ?: '';
    }

    #[\Override]
    public function filterInputPost(string $name): string
    {
        return filter_input(INPUT_POST, $name) ?: '';
    }
    
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function back(): void
    {
        header('Location: '.$this->httpRefere, true, 303);
    }
    
    public function getGlobalArraysAsString(): string
    {
        $str = "\nПеременные HTTP GET:\n";
        $str .= count($_GET) ? ArrayUtils::getArrayAsString($_GET) : "Отсутствуют.\n";
        
        $str .= "Переменные HTTP POST:\n";
        $str .= count($_POST) ? ArrayUtils::getArrayAsString($_POST) : "Отсутствуют.\n";
        
        $str .= "HTTP Cookies:\n";
        $str .= count($_COOKIE) ? ArrayUtils::getArrayAsString($_COOKIE) : "Отсутствуют.\n";
        
        $str .= "Информация о сервере и среде исполнения:\n";
        $str .= count($_SERVER) ? ArrayUtils::getArrayAsString($_SERVER) : "Отсутствует.\n";
            
        return $str; 
    }
}
