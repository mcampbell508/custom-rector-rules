<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Exception\ShouldNotHappenException;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class IncludeNonPrivatePropertiesTest extends AbstractRectorTestCase
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
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture/IncludeNonPrivateProperties');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/include_non_private_properties_config.php';
    }
}
