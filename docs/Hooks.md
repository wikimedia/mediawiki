Hooks
=====

## Introduction

Hooks allow MediaWiki Core to call extensions or allow one extension to call
another extension. For more information and a list of hooks, see
https://www.mediawiki.org/wiki/Manual:Hooks

Starting in MediaWiki 1.35, each hook called by MediaWiki Core has an
associated interface with a single method. To call the hook, obtain a "hook
runner" object, which implements the relevant interface, and call the relevant
method. To handle a hook event in an extension, create a handler object which
implements the interface.

The name of the interface is the name of the hook with "Hook" added to the end.
Interfaces are typically placed in the namespace of their primary caller.

The method name for the hook is the name of the hook, prefixed with "on".

Several hooks had colons in their name, which are invalid in an interface or
method name. These hooks have interfaces and method names in which the colons
are replaced with underscores.

For example, if the hook is called `Mash`, we might have the interface:

    interface MashHook {
        public function onMash( $banana );
    }

Hooks can be defined and called by extensions. The extension should define a
hook interface for each hook, as described above.

## HookContainer

HookContainer is a service which is responsible for maintaining a list of hook
handlers and calling those handlers when requested. HookContainer is not aware
of hook interfaces or parameter types.

HookContainer provides hook metadata. For example, `isRegistered()` tells us
whether there are any handlers for a given hook event.

A HookContainer instance can be obtained from the global service locator with
MediaWikiServices::getHookContainer(). When implementing a service that needs
to call a hook, a HookContainer object should be passed to the constructor of
the service.

## Hook runner classes

A hook runner is a class which implements hook interfaces, proxying the calls
to `HookContainer::run()`.

MediaWiki has two hook runner classes: HookRunner and ApiHookRunner.
ApiHookRunner has proxy methods for all hooks which are called by the Action
API. HookRunner has proxy methods for all hooks which are called by other parts
of Core. Some hooks are implemented in both classes.

Extensions which call hooks should create their own hook runner class, by
analogy with the ones in Core. Hook runner classes are effectively internal
to the module which calls the relevant hooks. Reorganisation of the hook
calling code may lead to methods being removed from hook runner classes. Thus,
it is safer for extensions to define their own hook runner classes even if
they are calling Core hooks.

New code should typically be written in a service which takes a HookContainer
as a constructor parameter. However, for the convenience of existing static
functions in MediaWiki Core, `Hooks::runner()` may be used to obtain a
HookRunner instance. This is equivalent to

    new HookRunner( MediaWikiServices::getInstance()->getHookContainer() )

For example, to call the hook `Mash`, as defined above, in static code:

    Hooks::runner()->onMash( $banana );

## How to handle a hook event in an extension

In extension.json, there is a new attribute called `HookHandlers`. This is
an object mapping the handler name to an ObjectFactory specification describing
how to create the handler object. The specification will typically have a
`class` member with the name of the handler class. For example, in an extension
called `FoodProcessor`, we may have:

    "HookHandlers": {
        "main": {
            "class": "MediaWiki\\Extension\\FoodProcessor\\HookHandler"
        }
    }

Then in the Hooks attribute, instead of a function name, the value will be the
handler name:

    "Hooks": {
        "Mash": "main"
    }

Or more explicitly, by using an object instead of a string for the handler:

    "Hooks": {
        "Mash": {
            "handler": "main"
        }
    }

Note that while your HookHandler class will implement an interface that ends
with the word "Hook", in `extension.json` you should omit the word "Hook"
from the key in the `Hooks` definition. For example, in the definitions above,
the key must be "Mash", not "MashHook".

Then the extension will define a handler class:

    namespace MediaWiki\Extension\FoodProcessor;

    class HookHandler implements MashHook {
        public function onMash( $banana ) {
            // Implementation goes here
        }
    }

## Service dependencies

The ObjectFactory specification in HookHandlers can contain a list of services
which should be instantiated and provided to the constructor or factory
function for the handler. For example:

    "HookHandlers": {
        "main": {
            "class": "MediaWiki\\Extension\\FoodProcessor\\HookHandler",
            "services": [ "ReadOnlyMode" ]
        }
    }

However, care should be taken with this feature. Some services have expensive
constructors, so requesting them when handling commonly-called hooks may damage
performance. Also, some services may not be safe to construct from within a hook
call.

The safest pattern for service injection is to use a separate handler for each
hook, and to inject only the services needed by that hook.

Calling a hook with the `noServices` option disables service injection. If a
handler for such a hook specifies services, an exception will be thrown when
the hook is called.

## Returning and aborting

If a hook handler returns false, HookContainer will stop iterating through the
list of handlers and will immediately return false.

If a hook handler returns true, or if there is no return value (causing it to
effectively return null), then HookContainer will continue to call any other
remaining handlers. Eventually HookContainer::run() will return true.

If there were no registered handlers, HookContainer::run() will return true.

Some hooks are declared to be "not abortable". If a handler for a non-abortable
hook returns false, an exception will be thrown. A hook is declared to be not
abortable by passing `[ "abortable" => false ]` in the $options parameter to
HookContainer::run().

Aborting is properly used to enforce a convention that only one extension
may handle a given hook call.

Aborting is sometimes used as a generic return value, to indicate that the
caller should stop performing some action.

Most hook callers do not check the return value from HookContainer::run() and
there is no real concept of aborting. The only effect of returning `false` from
a handler of these hooks is to break other extensions.

Theoretically, extensions which are registered first in LocalSettings.php will
be called first, and thus will have the first opportunity to abort a hook call.
This behaviour should not be relied upon. In the new hook system, handlers
registered in the legacy way are called first, before handlers registered in
the new way.

## Parameters passed by reference

The typical way for a handler to return data to the caller is by modifying a
parameter which was passed by reference. This is sometimes called "replacement".

Reference parameters were somewhat overused in early versions of MediaWiki. You
may find that some parameters passed by reference cannot reasonably be modified.
Replacement either has no effect on the caller or would cause unexpected or
inconsistent effects. Handlers should generally only replace a parameter when it
is clear from the documentation that replacement is expected.

## How to define a new hook

* Create a hook interface, typically in a subnamespace called `Hook` relative
  to the caller namespace. For example, if the caller is in a namespace called
  `MediaWiki\Foo`, the hook interface might be placed in `MediaWiki\Foo\Hook`.
* Add an implementation to the relevant HookRunner class.

## Hook deprecation

Core hooks are deprecated by adding them to an array in the DeprecatedHooks
class. Hooks declared in extensions may be deprecated by listing them in the
`DeprecatedHooks` attribute:

    "DeprecatedHooks": {
        "Mash": {
            "deprecatedVersion": "2.0",
            "component": "FoodProcessor"
        }
    }

If the `component` is not specified, it defaults to the name of the extension.

The hook interface should be marked as deprecated by adding @deprecated to the
interface doc comment. The interface doc comment is a better place for
@deprecated than the method doc comment, because this causes the interface to
be deprecated for implementation. Deprecating the method only causes calling
to be deprecated, not handling.

Deprecating a hook in this way activates a migration system called
**call filtering**. Extensions opt in to call filtering of deprecated hooks by
**acknowledging** deprecation. An extension acknowledges deprecation with the
`deprecated` parameter in the `Hooks` attribute:

    "Hooks": {
        "Mash": {
            "handler": "main",
            "deprecated": "true"
        }
    }

If deprecation is acknowledged by the extension:

* If MediaWiki knows that the hook is deprecated, the handler will not be
  called. The call to the handler is filtered.
* If MediaWiki does not have the hook in its list of deprecated hooks, the
  handler will be called anyway.

Deprecation acknowledgement is a way for the extension to say that it has made
some other arrangement for implementing the relevant functionality and does
not need the handler for the deprecated hook to be called.

### Call filtering example

Suppose the hook `Mash` is deprecated in MediaWiki 2.0, and is replaced by a
new one called `Slice`. In our example extension FoodProcessor 1.0, the
`Mash` hook is handled. In FoodProcessor 2.0, both `Mash` and `Slice` have
handlers, but deprecation of `Mash` is acknowledged. Thus:

* With MediaWiki 2.0 and FoodProcessor 1.0, `onMash` is called but raises a
  deprecation warning.
* With MediaWiki 2.0 and FoodProcessor 2.0, `onMash` is filtered, and `onSlice`
  is called.
* With MediaWiki 1.0 and FoodProcessor 2.0, `onMash` is called, since it is not
  yet deprecated in Core. `onSlice` is not called since it does not yet exist
  in Core.

So the call filtering system provides both forwards and backwards compatibility.

### Silent deprecation

Developers sometimes use two stages of deprecation: "soft" deprecation in which
the deprecated entity is merely discouraged in documentation, and "hard"
deprecation in which a warning is raised. When you soft-deprecate a hook, it is
important to register it as deprecated so that call filtering is activated.
Activating call filtering simplifies the task of migrating extensions to the
new hook.

To deprecate a hook without raising deprecation warnings, use the "silent" flag:

    "DeprecatedHooks": {
        "Mash": {
            "deprecatedVersion": "2.0",
            "component": "FoodProcessor",
            "silent": true
        }
    }

As with hard deprecation, @deprecated should be added to the interface.
