<?php

namespace Base\Services;

use Base\Contracts\File\FileInterface;

final class FileService implements FileInterface
{
    public function put(string $filename, mixed $data, int $flags = 0): int|false
    {
        return file_put_contents($filename, $data, $flags);
    }
}
