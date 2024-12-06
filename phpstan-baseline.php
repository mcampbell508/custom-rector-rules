<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	// identifier: method.childParameterType
	'message' => '#^Parameter \\#1 \\$node \\(PhpParser\\\\Node\\\\Stmt\\\\Class_\\) of method MCampbell508\\\\CustomRectorRules\\\\Rector\\\\Property\\\\PHPUnit\\\\MockeryIntersectionTypedPropertyFromStrictSetUpRector\\:\\:refactor\\(\\) should be contravariant with parameter \\$node \\(PhpParser\\\\Node\\) of method Rector\\\\Contract\\\\Rector\\\\RectorInterface\\:\\:refactor\\(\\)$#',
	'count' => 2,
	'path' => __DIR__ . '/src/Rector/Property/PHPUnit/MockeryIntersectionTypedPropertyFromStrictSetUpRector.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
