<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Config;

final readonly class RuleDocsConfig
{
    public function __construct(
        public string $description,
        public string $exportPath,
    ) {
    }
}
