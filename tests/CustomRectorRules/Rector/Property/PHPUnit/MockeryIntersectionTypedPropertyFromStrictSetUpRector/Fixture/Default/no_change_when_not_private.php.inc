<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\Default;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClass extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>
-----
<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\Default;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClass extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>