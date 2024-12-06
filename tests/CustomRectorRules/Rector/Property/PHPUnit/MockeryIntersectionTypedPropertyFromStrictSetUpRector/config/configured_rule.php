<?php

declare(strict_types=1);

use MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MockeryIntersectionTypedPropertyFromStrictSetUpRector::class);
};
