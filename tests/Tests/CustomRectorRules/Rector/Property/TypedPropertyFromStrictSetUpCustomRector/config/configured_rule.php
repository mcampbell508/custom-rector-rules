<?php

declare(strict_types=1);

use CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypedPropertyFromStrictSetUpCustomRector::class);
};
