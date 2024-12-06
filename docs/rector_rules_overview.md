# 1 Rules Overview

## TypedPropertyFromStrictSetUpCustomRector

Add strict typed property based on `setUp()` strict typed assigns in TestCase.

This differs from the built-in Rector rule "TypedPropertyFromStrictSetUpRector" slightly to suit my preferred style.

The benefits:

* Use `MockInterface` instead of `LegacyMockInterface`
* Type is an intersection rather than union

Requires PHP8.1!

- class: [`CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector`](../src/Rector/Property/MockeryIntersectionTypedPropertyFromStrictSetUpRector.php)

```diff
 use PHPUnit\Framework\TestCase;
 use Mockery\MockInterface;
 use App\User;

 final class SomeClass extends TestCase
 {
-    private $user;
-    private $anotherUser;
+    private \App\User&MockInterface $user;
+    private \App\User&MockInterface $anotherUser;

     public function setUp()
     {
         $this->user = Mockery::mock(User::class);
         $this->anotherUser = Mockery::mock('App\User');
     }
 }
```

<br>
