<?php

declare(strict_types=1);

namespace CustomRectorRules\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class TypedPropertyFromStrictSetUpCustomRectorTest extends AbstractRectorTestCase
{
    /**
     * @throws ShouldNotHappenException
     */
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
