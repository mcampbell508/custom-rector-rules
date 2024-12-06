<?php

declare(strict_types=1);

use CustomRectorRules\Rector\Property\MockeryIntersectionTypedPropertyFromStrictSetUpRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MockeryIntersectionTypedPropertyFromStrictSetUpRector::class);
};
