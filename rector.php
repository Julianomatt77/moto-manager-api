<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
        ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
        ->withComposerBased(symfony: true, doctrine: true)
        ->withAttributesSets(symfony: true, doctrine: true, sensiolabs: true)
        ->withSets([
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::CONFIGS,
            ])
        ->withPhpSets(php85: true)
        ->withImportNames(removeUnusedImports: true);