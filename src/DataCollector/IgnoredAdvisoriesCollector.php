<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Filesystem\FileReader;
use App\Model\DataCollectorInterface;

class IgnoredAdvisoriesCollector implements DataCollectorInterface
{
    public function __construct(
        private readonly FileReader $fileReader,
    ) {
    }

    public function collect(string $directory, string $environment): ?array
    {
        $contents = $this->fileReader->getJson($directory, 'composer.json');

        if (null === $contents) {
            return null;
        }

        return [
            'ignoredAdvisories' => $contents['config']['audit']['ignore'] ?? [],
        ];
    }
}
