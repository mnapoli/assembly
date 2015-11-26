# Assembly

*Assembly* provides an implementation for [definition-interop](https://github.com/container-interop/definition-interop) definitions as well as a compatible container.

[![Build Status](https://travis-ci.org/mnapoli/assembly.svg?branch=master)](https://travis-ci.org/mnapoli/assembly)

## Definitions

Here are examples showing how to use each definition:

### ParameterDefinition

```php
$definition = new ParameterDefinition('db.port', 3306);
```

This definition will define a container entry `"db.port"`. That means `get('db.port')` will return `3306`.

### AliasDefinition

```php
$definition = new AliasDefinition('logger', 'monolog');
```

This definition will alias the entry "logger" to the entry "monolog". That means that `get('logger')` will return the result of `get('monolog')`.

### InstanceDefinition

```php
$definition = new InstanceDefinition('db', 'PDO');
$definition->addConstructorArgument('mysql:host=localhost;dbname=test');
$definition->addConstructorArgument('user');
$definition->addConstructorArgument('password');
```

The definition above will return the result of `new PDO('mysql:host=localhost;dbname=test', 'user', 'password')`.

References can also be used:

```php
$definition = new InstanceDefinition('db', 'PDO');
$definition->addConstructorArgument(new Reference('db.connection_string'));
$definition->addConstructorArgument('user');
$definition->addConstructorArgument('password');
```

The definition above will return the result of `new PDO($container->get('db.connection_string'), 'user', 'password')`.

### FactoryDefinition

```php
$definition = new FactoryDefinition('db', new Reference('db.factory'), 'create');
```

The definition above will call the `create()` method on the `db.factory` container entry and return its result.

## Definition providers

Here is an example on how to implement a *container-interop* definition provider:

```php
use Interop\Container\Definition\DefinitionProviderInterface;

class MyModuleDefinitionProvider implements DefinitionProviderInterface
{
    public function getDefinitions()
    {
        $definitions = [];

        $definitions[] = new ParameterDefinition(...);
        $definitions[] = new AliasDefinition(...);
        $definitions[] = new InstanceDefinition(...);
        $definitions[] = new FactoryDefinition(...);

        return $definitions;
    }
}
```

## Container

Assembly ships with a simple container that is compatible with the standard definitions. The goal of this container is to provide a very easy way to get started for those wanting to consume definitions.

Here is how to use it:

```php
// List the definition providers to load
$definitionProviders = [
    new Module1DefinitionProvider(),
    new Module2DefinitionProvider(),
];

// Define here container entries for the application
$entries = [
    'abc' => 'def',
    'router' => new Router(...),
];

$container = new Container($entries, $definitionProviders);
```

For simplicity's sake, the container is immutable and its API is very limited. You are encouraged to use any other compatible container if you are left unsatisfied.
