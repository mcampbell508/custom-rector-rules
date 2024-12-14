<?php

declare(strict_types=1);

namespace MCampbell508\CustomRectorRules\Rector\Property\PHPUnit;

use PhpParser\Node\IntersectionType;
use MCampbell508\CustomRectorRules\Rector\Property\PHPUnit\ValueObject\MockeryIntersectionTypedPropertyFromStrictSetUp;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\Type;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeManipulator\ClassMethodPropertyFetchManipulator;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\Exception\ShouldNotHappenException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \MCampbell508\CustomRectorRules\Tests\Rector\Property\PHPUnit\MockeryIntersectionTypedPropertyFromStrictSetUpRector
 */
final class MockeryIntersectionTypedPropertyFromStrictSetUpRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface
{
    private MockeryIntersectionTypedPropertyFromStrictSetUp $mockeryIntersectionTypedPropertyFromStrictSetUp;

    public function __construct(
        private readonly ClassMethodPropertyFetchManipulator $classMethodPropertyFetchManipulator,
        private readonly NameImporter $nameImporter,
        private readonly CurrentFileProvider $currentFileProvider,
    ) {
    }

    /**
     * @throws PoorDocumentationException
     * @throws ShouldNotHappenException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        $description = <<<'DESCRIPTION'
The `MockeryIntersectionTypedPropertyFromStrictSetUpRector` is a custom Rector rule designed to enhance the type definitions of Mockery-based properties in PHPUnit
`TestCase` classes. It ensures that properties initialized in the `setUp()` method with Mockery are assigned intersection types, improving type safety and code clarity.

## Key Features

- **Intersection Type Support**: Adds intersection types (e.g., `\App\User&MockInterface`) to properties assigned Mockery mocks in the `setUp()` method.
- **Modern Type Handling**: Ensures that `MockInterface` is used instead of `LegacyMockInterface`.
- **PHP 8.1+ Required**: This rule leverages PHP 8.1's intersection types and will not work on earlier PHP versions.
- **Customizable Behavior**: Offers configuration options to tailor its behavior to specific coding styles and preferences.

## How this rule works

1. **Scans for `setUp()` Methods**: The rule processes classes that define a `setUp()` method, which is commonly used in PHPUnit to initialize properties.
2. **Identifies Mockery Assignments**: It identifies properties assigned using `Mockery::mock()` and determines their types based on the arguments passed to `mock()`.
3. **Adds Intersection Types**: For eligible properties, it assigns an intersection type that combines the class being mocked and `MockInterface`.
4. **Restricts to Test Classes**: This rule only applies to classes that extend from the PHPUnit `TestCase` class.
5. **Respects Property Visibility**: Only non-private properties are modified to avoid unexpected behavior with external usage.

## Situations when this rule would not be applied

- **Not a Test Class**: The rule skips classes that do not extend from the PHPUnit `TestCase` class via inheritance.
- **No `setUp()` Method**: The rule skips property assignments not within a `setUp()` method.
- **Existing Type Definitions**: If a property already has a type defined, it will not be modified. (You can override this via configuration)
- **Private Properties**: Private properties are excluded, as their usage outside the class cannot be guaranteed. (You can override this via configuration)
- **Non-Mockery Assignments**: Properties not assigned using `Mockery::mock()` are ignored.

## PHP Version Compatibility

This rule requires **PHP 8.1 or higher** due to its reliance on intersection types. It will not run on projects using earlier PHP versions.

## Benefits of Using This Rule

- **Improved Type Safety**: Intersection types ensure that properties are explicitly typed as both the mocked class and `MockInterface`.
- **Modern Mockery Support**: Replaces legacy types with the recommended `MockInterface`.
- **Cleaner Code**: Enhances code readability and maintainability by ensuring consistent and precise type annotations.

## Configuration Options

The behavior of this Rector rule can be customized through the [`MockeryIntersectionTypedPropertyFromStrictSetUp`](/src/Rector/Property/PHPUnit/ValueObject/MockeryIntersectionTypedPropertyFromStrictSetUp.php) configuration object:

### `useShortImports`
- **Type**: `bool`
- **Default**: `false`
- **Description**: Determines whether short imports should be used when generating or modifying code.
  - When set to `true`, the class will prefer short import statements.
  - When set to `false`, fully qualified names will be used.

### `replaceExistingType`
- **Type**: `bool`
- **Default**: `false`
- **Description**: Controls whether existing types on properties should be replaced.
  - When set to `true`, any existing type annotations on the property will be replaced.
  - When set to `false`, existing type annotations will remain untouched.

### `includeNonPrivateProperties`
- **Type**: `bool`
- **Default**: `false`
- **Description**: Specifies whether non-private properties should be included in the processing.
  - When set to `true`, properties with visibility other than `private` will be included.
  - When set to `false`, only `private` properties will be considered.

DESCRIPTION;

        return new RuleDefinition($description, [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;
use Mockery\MockInterface;
use App\User;

final class SomeClass extends TestCase
{
    private $user;
    private $anotherUser;

    public function setUp()
    {
        $this->user = Mockery::mock(User::class);
        $this->anotherUser = Mockery::mock('App\User');
    }
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;
use Mockery\MockInterface;
use App\User;

final class SomeClass extends TestCase
{
    private \App\User&MockInterface $user;
    private \App\User&MockInterface $anotherUser;

    public function setUp()
    {
        $this->user = Mockery::mock(User::class);
        $this->anotherUser = Mockery::mock('App\User');
    }
}
CODE_SAMPLE,
            [new MockeryIntersectionTypedPropertyFromStrictSetUp(true)],
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function configure(array $configuration): void
    {
        if ($configuration !== []) {
            Assert::count($configuration, 1);
            Assert::isInstanceOf($configuration[0], MockeryIntersectionTypedPropertyFromStrictSetUp::class);
            $config = $configuration[0];
        } else {
            $config = new MockeryIntersectionTypedPropertyFromStrictSetUp();
        }

        $this->mockeryIntersectionTypedPropertyFromStrictSetUp = $config;
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $setUpClassMethod = $node->getMethod(MethodName::SET_UP);

        if (! $setUpClassMethod instanceof ClassMethod) {
            return null;
        }
        $hasChanged = \false;

        foreach ($node->getProperties() as $property) {
            // type is already set
            if (!$this->mockeryIntersectionTypedPropertyFromStrictSetUp->replaceExistingType && $property->type !== null) {
                continue;
            }
            // is not private? we cannot be sure about other usage
            if (!$this->mockeryIntersectionTypedPropertyFromStrictSetUp->includeNonPrivateProperties && ! $property->isPrivate()) {
                continue;
            }

            $assignment = $this->getAssignment($property, $setUpClassMethod);

            if (! $assignment instanceof Name) {
                continue;
            }

            $assignmentName = new FullyQualified($assignment->toString());
            $mockInterfaceName = new FullyQualified('Mockery\\MockInterface');
            $file = $this->currentFileProvider->getFile();

            if ($this->mockeryIntersectionTypedPropertyFromStrictSetUp->useShortImports && $file instanceof File) {
                $assignmentName = $this->nameImporter->importName($assignmentName, $file);
                $mockInterfaceName = $this->nameImporter->importName($mockInterfaceName, $file);
            }

            if (!$assignmentName instanceof Name || !$mockInterfaceName instanceof Name) {
                continue;
            }

            $property->type = new IntersectionType([
                $assignmentName,
                $mockInterfaceName,
            ]);
            $hasChanged = \true;
        }
        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::INTERSECTION_TYPES;
    }

    private function getAssignment(Property $property, ClassMethod $classMethod): ?Name
    {
        $propertyName = $this->nodeNameResolver->getName($property);
        $assignedExprs = $this->classMethodPropertyFetchManipulator->findAssignsToPropertyName(
            $classMethod,
            $propertyName,
        );
        $assigned = null;
        foreach ($assignedExprs as $assignedExpr) {
            $type = $this->nodeTypeResolver->getType($assignedExpr);

            if ($this->shouldSkipExpression($assignedExpr, $type)) {
                continue;
            }

            if (!isset($assignedExpr->args[0])) {
                continue;
            }

            /** @var Arg $firstArg */
            $firstArg = $assignedExpr->args[0];

            $name = $this->getNameFromClassConstFetch($firstArg->value);
            if (! $name instanceof Name) {
                continue;
            }

            $assigned = $name;
        }

        return $assigned;
    }

    private function shouldSkipExpression(Expr $expr, Type $type): bool
    {
        if (! $expr instanceof StaticCall) {
            return true;
        }

        if ($expr->name instanceof Identifier && $expr->name->name !== 'mock') {
            return true;
        }
        if (! $type instanceof FullyQualifiedObjectType) {
            return true;
        }

        if ($type->getClassName() !== 'Mockery\LegacyMockInterface') {
            return true;
        }

        if (! isset($expr->args[0]) || ! $expr->args[0] instanceof Arg) {
            return true;
        }

        $value = $expr->args[0]->value;

        return ! $value instanceof ClassConstFetch && ! $value instanceof String_;
    }

    private function getNameFromClassConstFetch(Expr $expr): ?Name
    {
        if ($expr instanceof String_) {
            return new Name($expr->value);
        }

        if (! $expr instanceof ClassConstFetch) {
            return null;
        }

        if ($expr->class instanceof Name) {
            return $expr->class;
        }

        return null;
    }
}
