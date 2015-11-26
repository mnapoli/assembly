# Assembly

*Assembly* provides an implementation for [definition-interop](https://github.com/container-interop/definition-interop) definitions as well as a compatible container.

[![Build Status](https://travis-ci.org/mnapoli/assembly.svg?branch=master)](https://travis-ci.org/mnapoli/assembly)

## Installation

```
composer require mnapoli/assembly@dev
```

## Usage

While you can implement `Interop\Container\Definition\DefinitionProviderInterface` and return an array of definition objects built manually, Assembly provides a natural API to create definitions more easily.

To take advantage of this, simply extend `Assembly\ArrayDefinitionProvider` and fill the `getArrayDefinitions` method:

```php
class MyModuleDefinitionProvider extend \Assembly\ArrayDefinitionProvider
{
    public function getArrayDefinitions()
    {
        return [
            'logger.destination' => '/var/log/myapp.log',

            'logger' => \Assembly\instance('MyLogger')
                ->setConstructorArguments('warning', \Assembly\get('logger.destination'))
                ->addMethodCall('setDebug', true),

            'super_mailer' => \Assembly\factory('mailer.factory', 'create'),
            'super_mailer.factory' => \Assembly\instance('MailerFactory'),

            'mailer' => \Assembly\alias('super_mailer'),
        ];
    }
}
```

If you are using PHP 5.6 or above, you can import namespaced functions:

```php
use function \Assembly\instance;
use function \Assembly\alias;

class MyModuleDefinitionProvider extend \Assembly\ArrayDefinitionProvider
{
    public function getArrayDefinitions()
    {
        return [
            'logger' => instance(MyLogger::class),
            'logger_alias' => alias('logger'),
        ];
    }
}
```

If you do not want to write a new class, you can also instantiate a new provider directly:

```php
$provider = new ArrayDefinitionProvider([
    // add definitions here
]);
```

## Definition classes

If you do not want to use the function helpers, you can also create definition instances directly.

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
