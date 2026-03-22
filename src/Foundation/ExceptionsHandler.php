<?php

namespace Base\Foundation;

use Base\Exceptions\HtmlExceptionInterface;
use Base\Server\ServerRequestInterface;
use Base\Support\DB\DB;
use Psr\Log\LoggerInterface;

final class ExceptionsHandler
{
    public function __construct(
            private LoggerInterface $loggerService,
            private ServerRequestInterface $request,
            private DB $db,
    ) {
    }
    
    public function handle(\Throwable $e): void
    {
        if($this->db->inTransaction()) {
            $this->db->rollBack();
        }
        
        if($e instanceof HtmlExceptionInterface) {
            $e->render($this->request);
            return;
        }
        
        if(config('app_debug')) {
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
