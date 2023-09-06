<?php

namespace CustomRectorRules\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class NoChangeWhenAlreadyHasTypeTest extends TestCase
{
    public App\User&Mockery\MockInterface $user;

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

final class NoChangeWhenAlreadyHasTypeTest extends TestCase
{
    public App\User&Mockery\MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>