<?php

namespace Base\Services;

use Psr\Clock\ClockInterface;

final class ClockService implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
