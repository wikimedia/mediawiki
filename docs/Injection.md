Dependency Injection {#dependencyinjection}
=======

This is an overview of how MediaWiki uses of dependency injection.
The design originates from [RFC T384](https://phabricator.wikimedia.org/T384).

The term "dependency injection" (DI) refers to a pattern in object oriented
programming. DI tries to improve modularity by reducing strong coupling
between classes. In practical terms, this means that anything an object needs
to operate should be injected from the outside. The object itself should only
know narrow interfaces, no concrete implementation of the logic it relies on.

The requirement to inject everything typically results in an architecture based
on two main kinds of objects: simple "value" objects with no business logic
(and often immutable), and essentially stateless "service" objects that use
other service objects to operate on value objects.

As of 2022 (MediaWiki 1.39), MediaWiki has adopted dependency injection in much
of its code. However, some operations still require the use of singletons or
otherwise involve global state.

## Overview

The heart of the DI in MediaWiki is the central service locator,
MediaWikiServices, which acts as the top-level factory (or registry) for
services. MediaWikiServices represents the tree (or network) of service objects
that define MediaWiki's application logic. It acts as an entry point to all
dependency injection for MediaWiki core.

When `MediaWikiServices::getInstance()` is first called, it will create an
instance of MediaWikiServices and populate it with the services defined by
MediaWiki core in `includes/ServiceWiring.php`, as well as any additional
bootstrapping files specified in  `$wgServiceWiringFiles`. The service
wiring files define the (default) service implementations to use, and
specifies how they depend on each other ("wiring").

Extensions can add their own wiring files to `$wgServiceWiringFiles`, in order
to define their own service. Extensions may also use the `MediaWikiServices`
hook to replace ("redefine") a core service, by calling methods on the
MediaWikiServices instance.

It should be noted that the term "service locator" is often used to refer to a
top-level factory that is accessed directly, throughout the code, to avoid
explicit dependency injection. In contrast, the term "DI container" is often
used to describe a top-level factory that is only accessed only inside service
wiring code when instantiating service classes. We use the term "service locator"
because it is more descriptive than "DI container", even though application
logic is strongly discouraged from accessing MediaWikiServices directly.

`MediaWikiServices::getInstance()` should ideally be accessed only in "static
entry points" such as hook handler functions. See "Migration" below.

## Principles {#di-principles}

Service classes generally only vary on site configuration and are
deterministic and agnostic of global state. It is the responsibility of
callers to a service object to obtain and derive information from a
web request (such as title, user, language, WebRequest, RequestContext),
and pass this to specific methods of a service class as-needed. See
[T218555](https://phabricator.wikimedia.org/T218555) for related discussion.

Consider using the factory pattern if your service would otherwise be
unergonomic or slow, e.g. due to passing many parameters and/or recomputing
the same derived information. This keeps the global state out of the
service class, by having the service be a factory from which the caller
can obtain a (re-usable) object for its specific context.

This design ensures service classes are safe to use in both user-facing
contexts on the web (e.g. index.php page views and special pages), as
well as in an API, job, or maintenance script. It also ensures that
within a web-facing context the same service can be safely used
multiple times to perform different operations, without incorrectly
implying certain commonalities between these calls. Lastly, this
restriction allows services to be instantiated across wikis in the
future.

If a feature is not ready to meet these requirements, keep it outside
the service container. This avoids false confidence in the safety of an
injected service, and its ripple effect on other services.

### Principle exemption

There is a limited exemption to the above principles for "inconsequential
state". That is, global state may be used directly if and only if used
for diagnostics or to optimise performance, so long as they do not
change the observed functional outcome of a called method.

Examples of safe and inconsequential state:


* Use `$_SERVER['REQUEST_TIME_FLOAT']` or `ConvertibleTimestamp::now`
  to help compute a time measure that is sent to a metric service.

* Use `wfHostname()`, `PHP_SAPI`, or `WikiMap::getCurrentWikiId()`
  to describe where, how, or for which wiki the overall process was
  created and send it as message context to a logging service.

* Use `WebRequest::getRequestId()` to automatically inject a
  header into HTTP requests to other services. These are for tracking
  purposes only.

* Use `function_exists('apcu_fetch')` to automatically enable use
  of caching.

Examples of unsafe state in a service class:

* Do not use `WikiMap::getCurrentWikiId()` as the default value
  to obtain a database connection.

* Do not use `$_SERVER['SERVER_NAME']` to inject a header into
  HTTP requests to other services to control which wiki to operate on.

## Create a new service

To create a new service in MediaWiki core, write a function that will return
the appropriate class instantiation for that service in ServiceWiring.php. This
makes the service available through the generic `getService()` method on the
`MediaWikiServices` class. We then also add a wrapper method to
MediaWikiServices.php with a discoverable method named and strictly typed
return value to reduce mistakes and improve static analysis.

## Service Reset

Services get their configuration injected, and changes to global
configuration variables will not have any effect on services that were already
instantiated. This would typically be the case for low level services like
the ConfigFactory or the ObjectCacheManager, which are used during extension
registration. To address this issue, Setup.php resets the global service
locator instance by calling `MediaWikiServices::resetGlobalInstance()` once
configuration and extension registration is complete.

Note that "unmanaged" legacy services services that manage their own singleton
must not keep references to services managed by MediaWikiServices, to allow a
clean reset. After the global MediaWikiServices instance got reset, any such
references would be stale, and using a stale service will result in an error.

Services should either have all dependencies injected and be themselves managed
by MediaWikiServices, or they should use the Service Locator pattern, accessing
service instances via the global MediaWikiServices instance state when needed.
This ensures that no stale service references remain after a reset.

## Configuration

When the default MediaWikiServices instance is created, a Config object is
provided to the constructor. This Config object represents the "bootstrap"
configuration which will become available as the 'BootstrapConfig' service.
As of MW 1.27, the bootstrap config is a GlobalVarConfig object providing
access to the $wgXxx configuration variables.

The bootstrap config is then used to construct a 'ConfigFactory' service,
which in turn is used to construct the 'MainConfig' service. Application
logic should use the 'MainConfig' service (or a more specific configuration
object). 'BootstrapConfig' should only be used for bootstrapping basic
services that are needed to load the 'MainConfig'.

Note: Several well known services in MediaWiki core act as factories
themselves, e.g. ApiModuleManager, ObjectCache, SpecialPageFactory, etc.
The registries these factories are based on are currently managed as part of
the configuration. This may however change in the future.

## Migration

This section provides some recipes for improving code modularity by reducing
strong coupling. The dependency injection mechanism described above is an
essential tool in this effort.

### Migrate access to global service instances and config variables
Assume `Foo` is a class that uses the `$wgScriptPath` global and calls
`wfGetDB()` to get a database connection, in non-static methods.
* Add `$scriptPath` as a constructor parameter and use `$this->scriptPath`
  instead of `$wgScriptPath`.
* Add LoadBalancer `$dbLoadBalancer` as a constructor parameter. Use
  `$this->dbLoadBalancer->getConnection()` instead of `wfGetDB()`.
* Any code that calls `Foo`'s constructor would now need to provide the
  `$scriptPath` and `$dbLoadBalancer`. To avoid this, avoid direct instantiation
  of services all together - see below.

### Migrate services with multiple configuration variables
When a service needs multiple configuration globals injected, a ServiceOptions
object is commonly used with the service class defining a public constant
(usually `CONSTRUCTOR_OPTIONS`) with an array of settings that the class needs
access to.

```php
<?php

class DemoService {

	public const CONSTRUCTOR_OPTIONS = [
		'Foo',
		'Bar'
	];

	private $options;

	public function __construct( ServiceOptions $options ) {
		// ServiceOptions::assertRequiredOptions ensures that all of the
		// settings listed in CONSTRUCTOR_OPTIONS are available
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		// $wgFoo is now available with $this->options->get( 'Foo' )
		// $wgBar is now available with $this->options->get( 'Bar' )
	}

}
```

ServiceOptions objects are constructed within ServiceWiring.php and can also
be created in tests.
```php
'DemoService' => function ( MediaWikiServices $services ) : DemoService {
	return new DemoService(
		new ServiceOptions(
			DemoService::CONSTRUCTOR_OPTIONS,
			$services->getMainConfig()
		)
	);
},
```

### Migrate class-level singleton getters
Assume class `Foo` has mostly non-static methods, and provides a static
`getInstance()` method that returns a singleton (or default instance).
* Add an instantiator function for `Foo` into ServiceWiring.php. The
  instantiator would do exactly what `Foo::getInstance()` did. However, it
  should replace any access to global state with calls to `$services->getXxx()`
  to get a service, or `$services->getMainConfig()->get()` to get a
  configuration setting.
* Add a `getFoo()` method to MediaWikiServices. Don't forget to add the
  appropriate test cases in MediaWikiServicesTest.
* Turn `Foo::getInstance()` into a deprecated alias for
  `MediaWikiServices::getInstance()->getFoo()`. Change all calls to
  `Foo::getInstance()` to use injection (see above).

### Migrate direct service instantiation
Assume class `Bar` calls `new Foo()`.
* Add an instantiator function for `Foo` into ServiceWiring.php and add a
  `getFoo()` method to MediaWikiServices. Don't forget to add the appropriate
  test cases in MediaWikiServicesTest.
* In the instantiator, replace any access to global state with calls
  to `$services->getXxx()` to get a service, or
  `$services->getMainConfig()->get()` to get a configuration setting.
* The code in `Bar` that calls `Foo`'s constructor should be changed to have a
  `Foo` instance injected; Eventually, the only code that instantiates `Foo` is
  the instantiator in ServiceWiring.php.
* As an intermediate step, `Bar`'s constructor could initialize the `$foo`
  member variable by calling `MediaWikiServices::getInstance()->getFoo()`. This
  is acceptable as a stepping stone, but should be replaced by proper injection
  via a constructor argument. Do not however inject the MediaWikiServices
  object!

### Migrate parameterized helper instantiation
Assume class `Bar` creates some helper object by calling `new Foo( $x )`,
and `Foo` uses a global singleton of the `Xyzzy` service.
* Define a `FooFactory` class (or a `FooFactory` interface along with a
  `MyFooFactory` implementation). `FooFactory` defines the method
  `newFoo( $x )` or `getFoo( $x )`, depending on the desired semantics (`newFoo`
  would guarantee a fresh instance). When Foo gets refactored to have `Xyzzy`
  injected, `FooFactory` will need a `Xyzzy` instance, so `newFoo()` can pass it
  to `new Foo()`.
* Add an instantiator function for FooFactory into ServiceWiring.php and add a
  getFooFactory() method to MediaWikiServices. Don't forget to add the
  appropriate test cases in MediaWikiServicesTest.
* The code in Bar that calls Foo's constructor should be changed to have a
  FooFactory instance injected; Eventually, the only code that instantiates
  Foo are implementations of FooFactory, and the only code that instantiates
  FooFactory is the instantiator in ServiceWiring.php.
* As an intermediate step, Bar's constructor could initialize the $fooFactory
  member variable by calling `MediaWikiServices::getInstance()->getFooFactory()`.
  This is acceptable as a stepping stone, but should be replaced by proper
  injection via a constructor argument. Do not however inject the
  MediaWikiServices object!

### Migrate a handler registry
Assume class `Bar` calls `FooRegistry::getFoo( $x )` to get a specialized `Foo`
instance for handling `$x`.
* Turn `getFoo` into a non-static method.
* Add an instantiator function for `FooRegistry` into ServiceWiring.php and add
  a `getFooRegistry()` method to MediaWikiServices. Don't forget to add the
  appropriate test cases in MediaWikiServicesTest.
* Change all code that calls `FooRegistry::getFoo()` statically to call this
  method on a `FooRegistry` instance. That is, `Bar` would have a `$fooRegistry`
  member, initialized from a constructor parameter.
* As an intermediate step, Bar's constructor could initialize the `$fooRegistry`
  member variable by calling
  `MediaWikiServices::getInstance()->getFooRegistry()`. This is acceptable as a
  stepping stone, but should be replaced by proper injection via a constructor
  argument. Do not however inject the MediaWikiServices object!

### Migrate deferred service instantiation
Assume class `Bar` calls `new Foo()`, but only when needed, to avoid the cost of
instantiating Foo().
* Define a `FooFactory` interface and a `MyFooFactory` implementation of that
  interface. `FooFactory` defines the method `getFoo()` with no parameters.
* Precede as for the "parameterized helper instantiation" case described above.

### Migrate a class with only static methods
Assume `Foo` is a class with only static methods, such as `frob()`, which
interacts with global state or system resources.
* Introduce a `FooService` interface and a `DefaultFoo` implementation of that
  interface. `FooService` contains the public methods defined by Foo.
* Add an instantiator function for `FooService` into ServiceWiring.php and
  add a `getFooService()` method to MediaWikiServices. Don't forget to
  add the appropriate test cases in MediaWikiServicesTest.
* Add a private static `getFooService()` method to `Foo`. That method just
  calls `MediaWikiServices::getInstance()->getFooService()`.
* Make all methods in `Foo` delegate to the `FooService` returned by
  `getFooService()`. That is, `Foo::frob()` would do
  `self::getFooService()->frob()`.
* Deprecate `Foo`. Inject a `FooService` into all code that calls methods
  on `Foo`, and change any calls to static methods in foo to the methods
  provided by the `FooService` interface.

### Migrate static hook handler functions (to allow unit testing)
Assume `MyExtHooks::onFoo` is a static hook handler function that is called with
the parameter `$x`; Further assume `MyExt::onFoo` needs service `Bar`, which is
already known to MediaWikiServices (if not, see above).
* Create a non-static `doFoo( $x )` method in `MyExtHooks` that has the same
  signature as `onFoo( $x )`. Move the code from `onFoo()` into `doFoo()`,
  replacing any access to global or static variables with access to instance
  member variables.
* Add a constructor to `MyExtHooks` that takes a Bar service as a parameter.
* Add a static method called `newFromGlobalState()` with no parameters. It
  should just return
  `new MyExtHooks( MediaWikiServices::getInstance()->getBar() )`.
* The original static handler method `onFoo( $x )` is then implemented as
  `self::newFromGlobalState()->doFoo( $x )`.

### Migrate a "smart record"
Assume `Thingy` is a "smart record" that "knows" how to load and store itself.
For this purpose, `Thingy` uses wfGetDB().
* Create a "dumb" value class `ThingyRecord` that contains all the information
  that `Thingy` represents (e.g. the information from a database row). The value
  object should not know about any service.
* Create a DAO-style service for loading and storing `ThingyRecord`s, called
  `ThingyStore`. It may be useful to split the interfaces for reading and
  writing, with a single class implementing both interfaces, so we in the
  end have the `ThingyLookup` and `ThingyStore` interfaces, and a SqlThingyStore
  implementation.
* Add instantiator functions for `ThingyLookup` and `ThingyStore` in
  ServiceWiring.php. Since we want to use the same instance for both service
  interfaces, the instantiator for `ThingyLookup` would return
  `$services->getThingyStore()`.
* Add `getThingyLookup()` and `getThingyStore()` methods to MediaWikiServices.
  Don't forget to add the appropriate test cases in MediaWikiServicesTest.
* In the old `Thingy` class, replace all member variables that represent the
  record's data with a single `ThingyRecord` object.
* In the old Thingy class, replace all calls to static methods or functions,
  such as wfGetDB(), with calls to the appropriate services, such as
  `LoadBalancer::getConnection()`.
* In Thingy's constructor, pull in any services needed, such as the
  LoadBalancer, by using `MediaWikiServices::getInstance()`. These services
  cannot be injected without changing the constructor signature, which
  is often impractical for "smart records" that get instantiated directly
  in many places in the code base.
* Deprecate the old `Thingy` class. Replace all usages of it with one of the
  three new classes: loading needs a `ThingyLookup`, storing needs a
  `ThingyStore`, and reading data needs a `ThingyRecord`.

### Migrate lazy loading
Assume `Thingy` is a "smart record" as described above, but requires lazy
loading of some or all the data it represents.
* Instead of a plain object, define `ThingyRecord` to be an interface. Provide a
  "simple" and "lazy" implementations, called `SimpleThingyRecord` and
  `LazyThingyRecord`. `LazyThingyRecord` knows about some lower level storage
  interface, like a LoadBalancer, and uses it to load information on demand.
* Any direct instantiation of a `ThingyRecord` would use the
  `SimpleThingyRecord` implementation.
* `SqlThingyStore` however creates instances of `LazyThingyRecord`, and injects
  whatever storage layer service `LazyThingyRecord` needs to perform lazy
  loading.
