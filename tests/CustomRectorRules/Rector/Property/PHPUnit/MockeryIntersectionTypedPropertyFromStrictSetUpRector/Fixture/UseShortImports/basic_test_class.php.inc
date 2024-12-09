<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\UseShortImports;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClass extends TestCase
{
    private $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>
-----
<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\UseShortImports;

use Mockery\MockInterface;
use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClass extends TestCase
{
    private User&MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>