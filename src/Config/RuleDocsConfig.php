<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Config;

final readonly class RuleDocsConfig
{
    /** @param array<array-key, string> $tags */
    public function __construct(
        public string $description,
        public string $exportPath,
        public array $tags = [],
    ) {
    }
}
