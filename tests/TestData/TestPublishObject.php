<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\OpenGraph\Test\TestData;

use Fusonic\OpenGraph\Objects\ObjectBase;
use Fusonic\OpenGraph\Property;

final class TestPublishObject extends ObjectBase
{
    public const KEY = 'og:title';

    public function __construct(
        private readonly mixed $value,
    ) {
    }

    public function getProperties(): array
    {
        return [
            new Property(self::KEY, $this->value),
        ];
    }
}
