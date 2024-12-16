<?php

declare(strict_types=1);

use MCampbell508\CustomRectorRules\Config\FixtureConfig;
use MCampbell508\CustomRectorRules\Config\RuleConfig;
use MCampbell508\CustomRectorRules\Config\RuleDocsConfig;
use MCampbell508\CustomRectorRules\Config\RuleType;
use MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector;

return [
    new RuleConfig(
        className: MockeryIntersectionTypedPropertyFromStrictSetUpRector::class,
        ruleDocsConfig: new RuleDocsConfig(
            description: MockeryIntersectionTypedPropertyFromStrictSetUpRector::DOCS_DESCRIPTION,
            exportPath: 'mockery_property_types.md',
            tags: [
                'typed-properties',
                'mockery',
                'phpunit',
                'configurable',
                'php8.1',
            ],
        ),
        ruleType: RuleType::WITH_CONFIG,
        fixtures: [
            new FixtureConfig(
                path: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/Fixture/Default/',
                exampleName: 'Default',
                configPath: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/config/configured_rule.php',
            ),
            new FixtureConfig(
                path: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/Fixture/UseShortImports/',
                exampleName: 'Use Short Imports',
                configPath: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/config/use_short_imports_config.php',
            ),
            new FixtureConfig(
                path: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/Fixture/ReplaceExistingTypeConfig/',
                exampleName: 'Replace Existing Type',
                configPath: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/config/replace_existing_type_config.php',
            ),
            new FixtureConfig(
                path: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/Fixture/IncludeNonPrivateProperties/',
                exampleName: 'Include Non Private Properties',
                configPath: __DIR__ . '/../tests/CustomRectorRules/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector/config/include_non_private_properties_config.php',
            ),
        ],
    ),
];
