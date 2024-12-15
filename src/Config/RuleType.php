<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Config;

enum RuleType: string
{
    case WITH_CONFIG = 'with_config';
    case WITHOUT_CONFIG = 'without_config';

    public function isConfigurable(): bool
    {
        return $this === self::WITH_CONFIG;
    }
}
