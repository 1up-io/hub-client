<?php

declare(strict_types=1);

namespace App\DataCollector;

use App\Model\DataCollectorInterface;
use Symfony\Component\Process\Process;

class GitRemoteCollector implements DataCollectorInterface
{
    public function collect(string $directory): ?array
    {
        $process = new Process(['git', 'config', '--get', 'remote.origin.url'], cwd: $directory);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $output = $process->getOutput();
        $output = trim($output);

        return [
            'git' => $output,
        ];
    }
}
