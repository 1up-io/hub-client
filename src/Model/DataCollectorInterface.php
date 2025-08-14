<?php

declare(strict_types=1);

namespace App\Model;

interface DataCollectorInterface
{
    public function collect(string $directory): ?array;
}
