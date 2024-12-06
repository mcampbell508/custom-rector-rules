# 1 Rules Overview

## MockeryIntersectionTypedPropertyFromStrictSetUpRector

Add strict typed property based on `setUp()` strict typed assigns in TestCase.

This differs from the built-in Rector rule "TypedPropertyFromStrictSetUpRector" slightly to suit my preferred style.

The benefits:

* Use `MockInterface` instead of `LegacyMockInterface`
* Type is an intersection rather than union

Requires PHP8.1!

- class: [`MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector`](../src/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector.php)

<br>
