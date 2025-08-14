<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Filesystem\FileReader;
use App\Model\DataCollectorInterface;

class ComposerDependenciesCollector implements DataCollectorInterface
{
    public function __construct(
        private readonly FileReader $fileReader,
    ) {
    }

    public function collect(string $directory): ?array
    {
        $contents = $this->fileReader->getJson($directory, 'composer.lock');

        if (null === $contents) {
            return null;
        }

        $deps = [];

        foreach ($contents['packages'] as $package) {
            $deps[] = [
                'name' => $package['name'],
                'version' => $package['version'],
                'description' => $package['description'] ?? '',
                'homepage' => $package['homepage'] ?? '',
                'abandoned' => (bool) ($package['abandoned'] ?? false),
            ];
        }

        return [
            'packagist' => $deps,
        ];
    }
}
