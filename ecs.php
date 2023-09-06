<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE, SetList::PSR_12]);

    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/config',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ]);

    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, [
        'allow_mixed' => true,
    ]);

    $ecsConfig->skip([

        // breaking and handled better by Rector PHPUnit code quality set, removed in symplify dev-main
        PhpUnitStrictFixer::class,

    ]);
};
