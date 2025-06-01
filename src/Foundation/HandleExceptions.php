<?php

namespace Base\Foundation;

use Base\Services\LoggerService;

final class HandleExceptions
{
    public function __construct(
            private LoggerService $loggerService,
    ) {
    }
    
    public function render(\Throwable $e): void
    {
        if(APP_DEBUG) {
            echo $this->getHtmlMessage($e);
        } else {
            $this->loggerService->error($this->getLogMessage($e));
        }
    }
    
    private function getLogMessage(\Throwable $e): string
    {
        $str = "Код: {$e->getCode()} \n";
        $str .= "Ошибка: {$e->getMessage()} \n";
        $str .= "Ошибка произошла в файле {$e->getFile()} в строке {$e->getLine()} \n";
        
        $str .= "Трассировка стека: \n";

        foreach ($e->getTrace() as $value) {
            $str .= "{$value['file']}: стр. {$value['line']}, функ. {$value['function']} \n";
        }
        
        return $str;
    }
    
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

        foreach ($e->getTrace() as $value) {
            $str .= "<div>{$value['file']}: стр. {$value['line']}, функ. {$value['function']}</div>";
        }
        
        $str .= '</div>';
        
        return $str;
    }
}
