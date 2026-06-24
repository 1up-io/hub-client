<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return (new Configuration())
    ->ignoreErrorsOnPackages(
        [
            'symfony/config',
            'symfony/dotenv',
        ],
        [ErrorType::UNUSED_DEPENDENCY]
    )
;
