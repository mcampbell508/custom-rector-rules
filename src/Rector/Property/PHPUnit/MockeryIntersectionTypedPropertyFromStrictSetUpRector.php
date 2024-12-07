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
Add Mockery intersection typed property based on setUp() Mockery typed assigns in TestCase.

This differs from the built-in Rector rule "TypedPropertyFromStrictSetUpRector" slightly to suit my preferred style.

The benefits:

* Use `MockInterface` instead of `LegacyMockInterface`
* Type is an intersection rather than union

Requires PHP8.1!
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
            if ($property->type !== null) {
                continue;
            }
            // is not private? we cannot be sure about other usage
            if ($property->isPrivate()) {
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

        if (property_exists($expr, 'class') && $expr->class !== null && $expr->class instanceof Name) {
            return $expr->class;
        }

        return null;
    }
}
