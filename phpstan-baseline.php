<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	// identifier: property.notFound
	'message' => '#^Access to an undefined property PhpParser\\\\Node\\\\Expr\\:\\:\\$class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rector/Property/TypedPropertyFromStrictSetUpCustomRector.php',
];
$ignoreErrors[] = [
	// identifier: property.nonObject
	'message' => '#^Cannot access property \\$args on array\\<PhpParser\\\\Node\\\\Expr\\>\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rector/Property/TypedPropertyFromStrictSetUpCustomRector.php',
];
$ignoreErrors[] = [
	// identifier: method.childParameterType
	'message' => '#^Parameter \\#1 \\$node \\(PhpParser\\\\Node\\\\Stmt\\\\Class_\\) of method CustomRectorRules\\\\Rector\\\\Property\\\\TypedPropertyFromStrictSetUpCustomRector\\:\\:refactor\\(\\) should be contravariant with parameter \\$node \\(PhpParser\\\\Node\\) of method Rector\\\\Core\\\\Contract\\\\Rector\\\\RectorInterface\\:\\:refactor\\(\\)$#',
	'count' => 2,
	'path' => __DIR__ . '/src/Rector/Property/TypedPropertyFromStrictSetUpCustomRector.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method CustomRectorRules\\\\Rector\\\\Property\\\\TypedPropertyFromStrictSetUpCustomRector\\:\\:shouldSkipExpression\\(\\) expects PhpParser\\\\Node, array\\<PhpParser\\\\Node\\\\Expr\\> given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rector/Property/TypedPropertyFromStrictSetUpCustomRector.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Rector\\\\NodeTypeResolver\\\\NodeTypeResolver\\:\\:getType\\(\\) expects PhpParser\\\\Node, array\\<PhpParser\\\\Node\\\\Expr\\> given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rector/Property/TypedPropertyFromStrictSetUpCustomRector.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter 1 should use "array\\<PhpParser\\\\Node\\\\Expr\\>" type as the only type passed to this method$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Rector/Property/TypedPropertyFromStrictSetUpCustomRector.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
