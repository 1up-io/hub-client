<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Filesystem\FileReader;
use App\Model\DataCollectorInterface;

class PhpVersionCollector implements DataCollectorInterface
{
    public function __construct(
        private readonly FileReader $fileReader,
    ) {
    }

    public function collect(string $directory): ?array
    {
        $version = $this->readFromDotFile($directory) ?? $this->readFromPlatform($directory);

        return array_filter([
            'php' => $version,
        ]);
    }

    private function readFromDotFile(string $directory): ?string
    {
        return $this->fileReader->read($directory, '.php-version');
    }

    private function readFromPlatform(string $directory): ?string
    {
        $contents = $this->fileReader->getJson($directory, 'composer.json');

        if (null === $contents) {
            return null;
        }

        return $contents['config']['platform']['php'] ?? null;
    }
}
