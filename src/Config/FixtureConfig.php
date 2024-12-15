<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Config;

final readonly class FixtureConfig
{
    public function __construct(
        public string $path,
        public ?string $configPath, // Nullable for cases without config
    ) {
    }
}
