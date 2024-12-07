<?php

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\UseShortImports;

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

namespace MCampbell508\CustomRectorRules\Tests\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector\Fixture\UseShortImports;

use App\User;
use Mockery\MockInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

final class BasicTestClassWithString extends TestCase
{
    private User&MockInterface $user;

    public function setUp(): void
    {
        $this->user = Mockery::mock('App\User');
    }
}

?>