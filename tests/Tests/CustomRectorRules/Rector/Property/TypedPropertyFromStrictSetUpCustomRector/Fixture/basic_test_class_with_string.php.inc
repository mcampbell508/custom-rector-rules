<?php

namespace CustomRectorRulels\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClassWithString extends TestCase
{
    private $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock('App\User');
    }
}

?>
-----
<?php

namespace CustomRectorRulels\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClassWithString extends TestCase
{
    private App\User&Mockery\MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock('App\User');
    }
}

?>