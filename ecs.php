<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withConfiguredRule(ConcatSpaceFixer::class, [
        'spacing' => 'one'
    ])
    ->withSkip([MethodArgumentSpaceFixer::class])
    ->withSets([
        SetList::PSR_12,
        SetList::CLEAN_CODE,
    ])
;
