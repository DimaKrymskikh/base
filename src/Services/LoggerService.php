<?php

namespace Base\Services;

use Base\Contracts\File\FileInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

final class LoggerService extends AbstractLogger
{
    private object $logs;
    private \DateTimeImmutable $now;
    private FileInterface $fileService;

    public function __construct(object $logs, ClockInterface $clock, FileInterface $fileService)
    {
        $this->logs = $logs;
        $this->now = $clock->now();
        $this->fileService = $fileService;
    }
    
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $file = $this->getLogFile($level).'-'.$this->now->format('Y-m-d').'.log';
        
        $text = sprintf(
                "[%s] %s: %s \n\n",
                $this->now->format('Y-m-d H:i:s'),
                $level,
                $this->interpolate($message, $context)
            );
        
        $this->fileService->put($file, $text, FILE_APPEND);
    }
    
    /**
     * По уровню ошибки определяет файл журнала.
     * 
     * @param string $level
     * @return string
     */
    private function getLogFile(string $level): string
    {
        return match ($level) {
            LogLevel::INFO => $this->logs->assets->folder.'/'.$this->logs->assets->file,
            default => $this->logs->errors->folder.'/'.$this->logs->errors->file,
        };
    }
    
    /**
     * В шаблон сообщения вставляет нужные значения.
     * 
     * @param type $message
     * @param array $context
     * @return string
     */
    private function interpolate($message, array $context = []): string
    {
        $replace = [];
        
        array_walk($context, function($val, $key) use (&$replace) {
            if(is_string($val)) {
                $replace['{{ '.$key.' }}'] = $val;
            }
        });

        return wrapper_strtr($message, $replace);
    }
}
