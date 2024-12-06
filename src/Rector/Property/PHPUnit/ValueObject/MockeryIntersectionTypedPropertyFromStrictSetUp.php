<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\ValueObject;

final readonly class MockeryIntersectionTypedPropertyFromStrictSetUp
{
    public function __construct(
        public bool $useShortImports = false,
    ) {
    }
}
