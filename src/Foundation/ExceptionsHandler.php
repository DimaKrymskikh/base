<?php

namespace Base\Foundation;

use Base\Exceptions\HtmlExceptionInterface;
use Base\Server\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Глобальный обработчик исключений.
 */
final class ExceptionsHandler
{
    public function __construct(
            private LoggerInterface $logger,
            private ServerRequestInterface $request,
    ) {
    }
    
    /**
     * Перехватывает исключения.
     * 
     * @param \Throwable $e
     * @return void
     */
    public function handle(\Throwable $e): void
    {
        if($e instanceof HtmlExceptionInterface) {
            $e->render($this->request);
            return;
        }
        
        if(config('app_debug')) {
            echo $this->getHtmlMessage($e);
        } else {
            $this->logger->error($this->getLogMessage($e));
        }
    }
    
    /**
     * Запись трассировки ошибки в журнал (production).
     * 
     * @param \Throwable $e
     * @return string
     */
    private function getLogMessage(\Throwable $e): string
    {
        $str = "Код: {$e->getCode()} \n";
        $str .= "Ошибка: {$e->getMessage()} \n";
        $str .= "Ошибка произошла в файле {$e->getFile()} в строке {$e->getLine()} \n";
        
        $str .= "Трассировка стека: \n";
        
        array_map(function($value) use (&$str) {
            $file = $value['file'] ?? '';
            $line = $value['line'] ?? '';
            $str .= "$file: стр. $line, функ. {$value['function']} \n";
        }, $e->getTrace());
        
        return $str;
    }
    
    /**
     * Вывод трассировки ошибки в монитор (development).
     * 
     * @param \Throwable $e
     * @return string
     */
    private function getHtmlMessage(\Throwable $e): string
    {
        $str = <<<HTML
            <div>
                Код: <b>{$e->getCode()}</b>
            </div>
        
            <div>
                Ошибка: <b>{$e->getMessage()}</b>
            </div>
                
            <div>
                Ошибка произошла в файле <b>{$e->getFile()}</b> в строке <b>{$e->getLine()}</b>
            </div>
        HTML;
                
        $str .= '<div>Трассировка стека:';

        array_map(function($value) use (&$str) {
            $file = $value['file'] ?? '';
            $line = $value['line'] ?? '';
            $str .= "<div>$file: стр. $line, функ. {$value['function']}</div>";
        }, $e->getTrace());
        
        $str .= '</div>';
        
        return $str;
    }
}
