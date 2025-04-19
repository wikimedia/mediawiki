Domain Events {#events}
=====

## Introduction

Domain Events are a way for MediaWiki core components and extensions to be
informed about events that have occurred in another component or extension by
registering listeners. Domain Events in MediaWiki are similar to hooks, but
offer more flexibility in data modeling and stronger guarantees around the
invocation of listener code. They are introduced with MediaWiki 1.44 to improve
the developer experience by making use of an established design pattern that is
easy to use and helps reduce coupling, making the codebase more sustainable.

The idea of domain event in MediaWiki is inspired by domain driven design where
the term is used to describe something that happened in the past that users care
about, typically something that changes the visible state of the system. Martin
Fowler writes: "the essence of a Domain Event is that you use it to capture
things that can trigger a change to the state of the application you are
developing".

For more information see the documentation on mediawiki.org:
https://www.mediawiki.org/wiki/Manual:Domain_events

## Motivation

The Domain Event System is intended to enable event oriented processing within
and eventually around MediaWiki. Events become first-class concepts for
connecting MediaWiki core components and extensions as well as integrating
MediaWiki with other services in a distributed system.

Domain Events are designed to replace a certain type of hook as an extension
interface, and to provide a mechanism for decoupling core components using the
observer pattern. Eventually, MediaWiki should become able to broadcast events
to and receive events from other wikis and other services.

The overall motivation for introducing domain events is to make MediaWiki and
extension development more sustainable:

* Improve component boundaries between core components by applying the observer
  pattern (aka listener pattern). Listeners remove the need for code that
  affects a change to know about all code that needs to be informed about it.
* Clarify the semantics of extension callbacks invoked as a result of a change,
  particularly with respect to transactional context. Standardize deferred
  update behavior, which will reduce boilerplate code and risk of
  misimplementation.
* Make the extension interface more future proof by avoiding the rigidity
  imposed by using PHP interfaces to define hook parameters. Due to limitations
  of PHP, method signatures defined by extensions can’t be modified in a
  backwards-compatible way.
* Prepare for the creation of a generic relay mechanism for broadcasting events
  over an event bus. Broadcasting itself is not in scope for the initial phase,
  but accommodating that use case is a design goal.

The design follows the idea of domain events as defined in domain driven design:
events represent changes maintained by a given component (or bounded
context).

