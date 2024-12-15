<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Config;

final readonly class RuleConfig
{
    /**
     * @param class-string $className
     * @param array<array-key, FixtureConfig> $fixtures
     */
    public function __construct(
        public string $className,
        public RuleDocsConfig $ruleDocsConfig,
        public RuleType $ruleType,
        public array $fixtures,
    ) {
    }
}
