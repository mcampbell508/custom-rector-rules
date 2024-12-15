<?php

declare(strict_types=1);

use MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector;
use MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\ValueObject\MockeryIntersectionTypedPropertyFromStrictSetUp;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        MockeryIntersectionTypedPropertyFromStrictSetUpRector::class,
        [
            new MockeryIntersectionTypedPropertyFromStrictSetUp(
                false,
                false,
                true,
            ),
        ],
    );
};
