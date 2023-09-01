<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypedPropertyFromStrictSetUpCustomRector::class);
};