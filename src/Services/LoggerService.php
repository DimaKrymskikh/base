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
    
    private function getLogFile(string $level): string
    {
        return match ($level) {
            LogLevel::INFO => $this->logs->assets->folder.'/'.$this->logs->assets->file,
            LogLevel::ERROR => $this->logs->errors->folder.'/'.$this->logs->errors->file,
        };
    }
    
    private function interpolate($message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{'.$key.'}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
