<?php

declare(strict_types=1);

namespace App\Filesystem;

class FileReader
{
    public function read(string $directory, string $path): ?string
    {
        $file = rtrim($directory, '/') . '/' . ltrim($path, '/');

        if (!file_exists($file)) {
            return null;
        }

        return trim((string) file_get_contents($file));
    }

    public function getJson(string $directory, string $path): ?array
    {
        $contents = $this->read($directory, $path);

        if (null === $contents) {
            return null;
        }

        return (array) json_decode($contents, true);
    }
}
