<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__);

return (new PhpCsFixer\Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@PHP81Migration' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => 'Copyright (c) Fusonic GmbH. All rights reserved.'.\PHP_EOL.'Licensed under the MIT License. '.
                'See LICENSE file in the project root for license information.',
            'location' => 'after_open',
        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_strict' => true,
        'single_line_throw' => false,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
