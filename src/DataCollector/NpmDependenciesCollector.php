<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Filesystem\FileReader;
use App\Model\DataCollectorInterface;

class NpmDependenciesCollector implements DataCollectorInterface
{
    public function __construct(
        private readonly FileReader $fileReader,
    ) {
    }

    public function collect(string $directory): ?array
    {
        $contents = $this->fileReader->getJson($directory, 'package-lock.json');

        if (null === $contents) {
            return null;
        }

        $deps = [];

        foreach ($contents['packages'] as $name => $details) {
            if ('' === $name) {
                continue;
            }

            if (str_starts_with($name, 'node_modules/')) {
                $name = str_replace('node_modules/', '', $name);
            }

            $deps[] = [
                'name' => $name,
                'version' => $details['version'],
            ];
        }

        return [
            'npm' => $deps,
        ];
    }
}
