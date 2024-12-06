<?php

declare(strict_types=1);

namespace CustomRectorRules\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\Type;
use Rector\NodeManipulator\ClassMethodPropertyFetchManipulator;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \CustomRectorRules\Tests\Tests\CustomRectorRules\Rector\Property\TypedPropertyFromStrictSetUpCustomRector\TypedPropertyFromStrictSetUpCustomRectorTest
 */
final class TypedPropertyFromStrictSetUpCustomRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ClassMethodPropertyFetchManipulator $classMethodPropertyFetchManipulator,
    ) {
    }


    public function getRuleDefinition(): RuleDefinition
    {
        $description = <<<'DESCRIPTION'
Add strict typed property based on setUp() strict typed assigns in TestCase.

This differs from the built-in Rector rule "TypedPropertyFromStrictSetUpRector" slightly to suit my preferred style.

The benefits:

* Use `MockInterface` instead of `LegacyMockInterface`
* Type is an intersection rather than union

Requires PHP8.1!
DESCRIPTION;

        return new RuleDefinition($description, [new CodeSample(
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
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
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
            if (! $property->isPrivate()) {
                continue;
            }

            $assignment = $this->getAssignment($property, $setUpClassMethod);

            if (! $assignment instanceof Name) {
                continue;
            }

            $property->type = new Node\IntersectionType([
                new FullyQualified($assignment->toString()),
                new Name('Mockery\\MockInterface'),
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

    private function getAssignment($property, $classMethod): ?Name
    {
        $propertyName = $this->nodeNameResolver->getName($property);
        $assignedExprs = $this->classMethodPropertyFetchManipulator->findAssignsToPropertyName(
            $classMethod,
            $propertyName
        );
        $assigned = null;
        /** @var Expr[] $assignedExpr */
        foreach ($assignedExprs as $assignedExpr) {
            $type = $this->nodeTypeResolver->getType($assignedExpr);

            if ($this->shouldSkipExpression($assignedExpr, $type)) {
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

    private function shouldSkipExpression(Node $node, Type $type): bool
    {
        if (! $node instanceof StaticCall) {
            return true;
        }

        if ($node->name->name !== 'mock') {
            return true;
        }
        if (! $type instanceof FullyQualifiedObjectType) {
            return true;
        }

        if ($type->getClassName() !== 'Mockery\LegacyMockInterface') {
            return true;
        }

        if (! isset($node->args[0]) && ! $node->args[0] instanceof Arg) {
            return true;
        }

        $value = $node->args[0]->value;

        if (! $value instanceof ClassConstFetch && ! $value instanceof String_) {
            return true;
        }

        return false;
    }

    private function getNameFromClassConstFetch(Expr $expr): ?Name
    {
        if ($expr instanceof String_) {
            return new Name($expr->value);
        }

        return $expr->class;
    }
}
