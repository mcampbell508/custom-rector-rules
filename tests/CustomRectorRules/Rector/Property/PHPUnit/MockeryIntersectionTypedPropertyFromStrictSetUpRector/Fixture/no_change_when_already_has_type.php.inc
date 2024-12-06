<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class NoChangeWhenAlreadyHasTypeTest extends TestCase
{
    public User&Mockery\MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>
-----
<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture;

use App\User;
use Mockery;
use PHPUnit\Framework\TestCase;

final class NoChangeWhenAlreadyHasTypeTest extends TestCase
{
    public User&Mockery\MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock(User::class);
    }
}

?>