MediaWiki.Logger.LoggerFactory implements a [PSR-3] compatible message logging
system.

Named Psr.Log.LoggerInterface instances can be obtained from the
MediaWiki.Logger.LoggerFactory::getInstance() static method.
MediaWiki.Logger.LoggerFactory expects a class implementing the
MediaWiki.Logger.Spi interface to act as a factory for new
Psr.Log.LoggerInterface instances.

The "Spi" in MediaWiki.Logger.Spi stands for "service provider interface". An
SPI is an API intended to be implemented or extended by a third party. This
software design pattern is intended to enable framework extension and
replaceable components. It is specifically used in the
MediaWiki.Logger.LoggerFactory service to allow alternate PSR-3 logging
implementations to be easily integrated with MediaWiki.

The service provider interface allows the backend logging library to be
implemented in multiple ways. The $wgMWLoggerDefaultSpi global provides the
classname of the default MediaWiki.Logger.Spi implementation to be loaded at
runtime. This can either be the name of a class implementing the
MediaWiki.Logger.Spi with a zero argument constructor or a callable that will
return an MediaWiki.Logger.Spi instance. Alternately the
MediaWiki.Logger.LoggerFactory::registerProvider() static method can be called
to inject an MediaWiki.Logger.Spi instance into the LoggerFactory and bypass the
use of the default configuration variable.

The MediaWiki.Logger.LegacySpi class implements a service provider to generate
MediaWiki.Logger.LegacyLogger instances. The MediaWiki.Logger.LegacyLogger class
implements the PSR-3 logger interface and provides output and configuration
equivalent to the historic logging output of wfDebug, wfDebugLog, wfLogDBError
and wfErrorLog. The MediaWiki.Logger.LegacySpi class is the default service
provider configured in DefaultSettings.php. It's usage should be transparent for
users who are not ready or do not wish to switch to a alternate logging
platform.

The MediaWiki.Logger.MonologSpi class implements a service provider to generate
Psr.Log.LoggerInterface instances that use the [Monolog] logging library. See
the PHP docs (or source) for MediaWiki.Logger.MonologSpi for details on the
configuration of this provider. The default configuration installs a null
handler that will silently discard all logging events. The documentation
provided by the class describes a more feature rich logging configuration.

# Classes
* MediaWiki.Logger.LoggerFactory: Factory for Psr.Log.LoggerInterface loggers
* MediaWiki.Logger.Spi: Service provider interface for
  MediaWiki.Logger.LoggerFactory
* MediaWiki.Logger.NullSpi: MediaWiki.Logger.Spi for creating instances that
  discard all log events
* MediaWiki.Logger.LegacySpi: Service provider for creating
  MediaWiki.Logger.LegacyLogger instances
* MediaWiki.Logger.LegacyLogger: PSR-3 logger that mimics the historical output
  and configuration of wfDebug, wfErrorLog and other global logging functions.
* MediaWiki.Logger.MonologSpi: MediaWiki.Logger.Spi for creating instances
  backed by the monolog logging library
* MediaWiki.Logger.Monolog.LegacyHandler: Monolog handler that replicates the
  udp2log and file logging functionality of wfErrorLog()
* MediaWiki.Logger.Monolog.WikiProcessor: Monolog log processer that adds host:
  wfHostname() and wiki: wfWikiID() to all records

# Globals
* $wgMWLoggerDefaultSpi: Specification for creating the default service provider
  interface to use with LoggerFactory

[PSR-3]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
[Monolog]: https://github.com/Seldaek/monolog
