<?php

namespace CustomRectorRulels\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

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

namespace CustomRectorRulels\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

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