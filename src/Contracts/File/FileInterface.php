<?php

namespace Base\Contracts\File;

interface FileInterface
{
    public function put(string $filename, mixed $data, int $flags = 0): int|false;
}
