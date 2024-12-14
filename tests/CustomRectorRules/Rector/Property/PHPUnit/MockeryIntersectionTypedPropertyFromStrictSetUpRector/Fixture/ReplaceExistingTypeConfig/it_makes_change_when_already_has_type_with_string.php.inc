<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\ReplaceExistingTypeConfig;

use Mockery;
use PHPUnit\Framework\TestCase;

final class ItMakesChangeWhenAlreadyHasTypeWithString extends TestCase
{
    private \App\User $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock('App\User');
    }
}

?>
-----
<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\ReplaceExistingTypeConfig;

use Mockery;
use PHPUnit\Framework\TestCase;

final class ItMakesChangeWhenAlreadyHasTypeWithString extends TestCase
{
    private \App\User&\Mockery\MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock('App\User');
    }
}

?>
