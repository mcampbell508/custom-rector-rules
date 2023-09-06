<?php

namespace CustomRectorRules\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClass extends TestCase
{
    public $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>
-----
<?php

namespace CustomRectorRules\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClass extends TestCase
{
    public $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>