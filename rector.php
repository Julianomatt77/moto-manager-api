<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/config',
    ])
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
    // Active automatiquement les sets adaptés aux versions du composer.json
    ->withComposerBased(symfony: true, doctrine: true)
    // Annotations -> attributs (Route, ORM, Groups, Assert, etc.)
    ->withAttributesSets(symfony: true, doctrine: true, sensiolabs: true)
    ->withSets([
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::CONFIGS,
    ])
    ->withPhpSets(php85: true)
    ->withImportNames(removeUnusedImports: true);