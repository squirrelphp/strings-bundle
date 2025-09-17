<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Argument of an invalid type mixed supplied for foreach, only iterables are supported\\.$#',
	'identifier' => 'foreach.nonIterable',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/RandomStringGeneratorPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset \'generator\' on mixed\\.$#',
	'identifier' => 'offsetAccess.nonOffsetAccessible',
	'count' => 2,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/RandomStringGeneratorPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\StringsBundle\\\\DependencyInjection\\\\Compiler\\\\RandomStringGeneratorPass\\:\\:createGeneratorDefinitionList\\(\\) has parameter \\$taggedServices with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/RandomStringGeneratorPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\StringsBundle\\\\DependencyInjection\\\\Compiler\\\\RandomStringGeneratorPass\\:\\:createGeneratorDefinitionList\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/RandomStringGeneratorPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$string of function strlen expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/RandomStringGeneratorPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly invalid array key type mixed\\.$#',
	'identifier' => 'offsetAccess.invalidOffset',
	'count' => 2,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/RandomStringGeneratorPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Argument of an invalid type mixed supplied for foreach, only iterables are supported\\.$#',
	'identifier' => 'foreach.nonIterable',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset \'filter\' on mixed\\.$#',
	'identifier' => 'offsetAccess.nonOffsetAccessible',
	'count' => 2,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\StringsBundle\\\\DependencyInjection\\\\Compiler\\\\StringFilterPass\\:\\:createFilterDefinitionList\\(\\) has parameter \\$taggedServices with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\StringsBundle\\\\DependencyInjection\\\\Compiler\\\\StringFilterPass\\:\\:createFilterDefinitionList\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$class of class Symfony\\\\Component\\\\DependencyInjection\\\\Definition constructor expects string\\|null, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$id of method Symfony\\\\Component\\\\DependencyInjection\\\\ContainerBuilder\\:\\:setDefinition\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$string of function strlen expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$subject of function str_replace expects array\\<string\\>\\|string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly invalid array key type mixed\\.$#',
	'identifier' => 'offsetAccess.invalidOffset',
	'count' => 2,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Property Squirrel\\\\StringsBundle\\\\DependencyInjection\\\\Compiler\\\\StringFilterPass\\:\\:\\$defaultFilters type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringFilterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$class of class Symfony\\\\Component\\\\DependencyInjection\\\\Definition constructor expects string\\|null, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringTesterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$id of method Symfony\\\\Component\\\\DependencyInjection\\\\ContainerBuilder\\:\\:setDefinition\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringTesterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$subject of function str_replace expects array\\<string\\>\\|string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringTesterPass.php',
];
$ignoreErrors[] = [
	'message' => '#^Property Squirrel\\\\StringsBundle\\\\DependencyInjection\\\\Compiler\\\\StringTesterPass\\:\\:\\$defaultTesters type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Compiler/StringTesterPass.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
