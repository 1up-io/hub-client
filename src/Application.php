<?php

declare(strict_types=1);

namespace App;

use App\Console\PushToHubCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct(PushToHubCommand $command)
    {
        parent::__construct('hub-client', '1.0');

        $this->add($command);
        $this->setDefaultCommand((string) $command->getName(), true);
    }
}
