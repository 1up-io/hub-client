<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Filesystem\FileReader;
use App\Model\DataCollectorInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class TargetServerCollector implements DataCollectorInterface
{
    public function __construct(
        private readonly FileReader $fileReader,
    ) {
    }

    public function collect(string $directory, string $environment): ?array
    {
        $contents = $this->fileReader->read($directory, '.mage.yml');

        if (null === $contents) {
            return null;
        }

        try {
            /** @var array $mage */
            $mage = Yaml::parse($contents);
        } catch (ParseException) {
            return null;
        }

        $server = $mage['magephp']['environments'][$environment]['hosts'][0] ?? null;

        if (null === $server) {
            return null;
        }

        return [
            'server' => $server,
        ];
    }
}
