<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Filesystem\FileReader;
use App\Model\DataCollectorInterface;
use Siketyan\YarnLock\YarnLock;

class YarnDependenciesCollector implements DataCollectorInterface
{
    public function __construct(
        private readonly FileReader $fileReader,
    ) {
    }

    public function collect(string $directory): ?array
    {
        $contents = $this->fileReader->read($directory, 'yarn.lock');

        if (null === $contents) {
            return null;
        }

        $deps = [];

        $contents = YarnLock::toArray($contents);
        $contents = YarnLock::packagesFromArray($contents);

        foreach ($contents as $package) {
            $deps[] = [
                'name' => $package->getName(),
                'version' => $package->getVersion(),
            ];
        }

        return [
            'yarn' => $deps,
        ];
    }
}
