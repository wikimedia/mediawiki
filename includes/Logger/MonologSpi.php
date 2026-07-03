<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger;

use Closure;
use DateTimeZone;
use MediaWiki\Logger\Monolog\BufferHandler;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\PsrHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Psr\Log\LoggerInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * LoggerFactory service provider that creates loggers implemented by
 * Monolog.
 *
 * Configured using an array of configuration data with the keys 'loggers',
 * 'processors', 'handlers' and 'formatters'.
 *
 * The ['loggers']['\@default'] configuration will be used to create loggers
 * for any channel that isn't explicitly named in the 'loggers' configuration
 * section.
 *
 * Configuration will most typically be provided in the $wgMWLoggerDefaultSpi
 * global configuration variable used by LoggerFactory to construct its
 * default SPI provider:
 * @code
 * $wgMWLoggerDefaultSpi = [
 *   'class' => \MediaWiki\Logger\MonologSpi::class,
 *   'args' => [ [
 *       'loggers' => [
 *           '@default' => [
 *               'processors' => [ 'wiki', 'psr', 'pid', 'uid', 'web' ],
 *               'handlers'   => [ 'stream' ],
 *           ],
 *           'runJobs' => [
 *               'processors' => [ 'wiki', 'psr', 'pid' ],
 *               'handlers'   => [ 'stream' ],
 *           ]
 *       ],
 *       'processors' => [
 *           'wiki' => [
 *               'class' => \MediaWiki\Logger\Monolog\WikiProcessor::class,
 *           ],
 *           'psr' => [
 *               'class' => \Monolog\Processor\PsrLogMessageProcessor::class,
 *           ],
 *           'pid' => [
 *               'class' => \Monolog\Processor\ProcessIdProcessor::class,
 *           ],
 *           'uid' => [
 *               'class' => \Monolog\Processor\UidProcessor::class,
 *           ],
 *           'web' => [
 *               'class' => \Monolog\Processor\WebProcessor::class,
 *           ],
 *       ],
 *       'handlers' => [
 *           'stream' => [
 *               'class'     => \Monolog\Handler\StreamHandler::class,
 *               'args'      => [ 'path/to/your.log' ],
 *               'formatter' => 'line',
 *           ],
 *           'redis' => [
 *               'class'     => \Monolog\Handler\RedisHandler::class,
 *               'args'      => [ function() {
 *                       $redis = new Redis();
 *                       $redis->connect( '127.0.0.1', 6379 );
 *                       return $redis;
 *                   },
 *                   'logstash'
 *               ],
 *               'formatter' => 'logstash',
 *               'buffer' => true,
 *           ],
 *           'udp2log' => [
 *               'class' => \MediaWiki\Logger\Monolog\LegacyHandler::class,
 *               'args' => [
 *                   'udp://127.0.0.1:8420/mediawiki
 *               ],
 *               'formatter' => 'line',
 *           ],
 *       ],
 *       'formatters' => [
 *           'line' => [
 *               'class' => \Monolog\Formatter\LineFormatter::class,
 *            ],
 *            'logstash' => [
 *                'class' => \Monolog\Formatter\LogstashFormatter::class,
 *                'args'  => [ 'mediawiki', php_uname( 'n' ), null, '', 1 ],
 *            ],
 *       ],
 *   ] ],
 * ];
 * @endcode
 *
 * @see https://github.com/Seldaek/monolog
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class MonologSpi implements Spi {

	/**
	 * @var array{loggers:LoggerInterface[],handlers:HandlerInterface[],formatters:FormatterInterface[],processors:callable[]}
	 */
	protected $singletons;

	/**
	 * Configuration for creating new loggers.
	 * @var array<string,array<string,array>>
	 */
	protected array $config = [];

	/**
	 * @param array $config Configuration data.
	 */
	public function __construct( array $config ) {
		$this->mergeConfig( $config );
	}

	/**
	 * Merge additional configuration data into the configuration.
	 *
	 * @since 1.26
	 * @param array $config Configuration data.
	 */
	public function mergeConfig( array $config ) {
		foreach ( $config as $key => $value ) {
			if ( isset( $this->config[$key] ) ) {
				$this->config[$key] = array_merge( $this->config[$key], $value );
			} else {
				$this->config[$key] = $value;
			}
		}
		if ( !isset( $this->config['loggers']['@default'] ) ) {
			$this->config['loggers']['@default'] = [
				'handlers' => [ '@default' ],
			];
			$this->config['handlers']['@default'] ??= [
				'class' => StreamHandler::class,
				'args' => [ 'php://stderr', Logger::ERROR ],
			];
		}
		$this->reset();
	}

	/**
	 * Reset internal caches.
	 *
	 * This is public for use in unit tests. Under normal operation there should
	 * be no need to flush the caches.
	 */
	public function reset() {
		$this->singletons = [
			'loggers'    => [],
			'handlers'   => [],
			'formatters' => [],
			'processors' => [],
		];
	}

	/**
	 * Get a logger instance.
	 *
	 * Creates and caches a logger instance based on configuration found in the
	 * $wgMWLoggerMonologSpiConfig global. Subsequent request for the same channel
	 * name will return the cached instance.
	 *
	 * @param string $channel Logging channel
	 * @return LoggerInterface
	 */
	public function getLogger( $channel ) {
		if ( !isset( $this->singletons['loggers'][$channel] ) ) {
			// Fallback to using the '@default' configuration if an explicit
			// configuration for the requested channel isn't found.
			$spec = $this->config['loggers'][$channel] ?? $this->config['loggers']['@default'];

			$monolog = $this->createLogger( $channel, $spec );
			$this->singletons['loggers'][$channel] = $monolog;
		}

		return $this->singletons['loggers'][$channel];
	}

	/**
	 * Create a logger.
	 * @param string $channel Logger channel
	 * @param array $spec Configuration
	 * @return LoggerInterface
	 */
	protected function createLogger( $channel, $spec ): LoggerInterface {
		global $wgShowDebug, $wgDebugToolbar;

		$handlers = [];
		if ( isset( $spec['handlers'] ) && $spec['handlers'] ) {
			foreach ( $spec['handlers'] as $handler ) {
				$handlers[] = $this->getHandler( $handler );
			}
		}

		$processors = [];
		if ( isset( $spec['processors'] ) ) {
			foreach ( $spec['processors'] as $processor ) {
				$processors[] = $this->getProcessor( $processor );
			}
		}

		// Use UTC for logs instead of Monolog's default, which asks the
		// PHP runtime, which MediaWiki sets to $wgLocaltimezone (T99581)
		$obj = new Logger( $channel, $handlers, $processors, new DateTimeZone( 'UTC' ) );

		if ( $wgShowDebug || $wgDebugToolbar ) {
			$legacyLogger = new LegacyLogger( $channel );
			$legacyPsrHandler = new PsrHandler( $legacyLogger );
			$obj->pushHandler( $legacyPsrHandler );
		}

		if ( isset( $spec['calls'] ) ) {
			foreach ( $spec['calls'] as $method => $margs ) {
				$obj->$method( ...$margs );
			}
		}

		return $obj;
	}

	/**
	 * Create or return cached processor.
	 * @param string $name Processor name
	 * @return callable
	 */
	public function getProcessor( $name ) {
		if ( !isset( $this->singletons['processors'][$name] ) ) {
			$spec = $this->config['processors'][$name];
			/** @var callable $processor */
			$processor = ObjectFactory::getObjectFromSpec( $spec );
			$this->singletons['processors'][$name] = self::adaptProcessor( $processor );
		}
		return $this->singletons['processors'][$name];
	}

	/**
	 * Bridge a Monolog 2-style processor onto the Monolog 3 calling convention.
	 *
	 * Monolog 3 passes a LogRecord object to every processor, but configuration
	 * that predates the upgrade may still register a processor declared as
	 * `function ( array $record )` — most notably closures defined in
	 * wmf-config. Such a processor raises a TypeError the moment Monolog hands
	 * it a LogRecord. Until every caller is migrated to accept a LogRecord
	 * (T397070), wrap an array-typed processor so it keeps receiving the array
	 * form, and fold the mutable parts of its result back into the record.
	 *
	 * A processor already declared with LogRecord (or an array|LogRecord union,
	 * or no type at all) is returned unchanged.
	 *
	 * @param callable $processor
	 * @return callable
	 */
	private static function adaptProcessor( callable $processor ): callable {
		if ( !self::processorExpectsArray( $processor ) ) {
			return $processor;
		}

		return static function ( LogRecord $record ) use ( $processor ): LogRecord {
			$result = $processor( $record->toArray() );
			if ( !is_array( $result ) ) {
				// A well-behaved processor returns the (array) record; if it
				// returned nothing useful, leave the record untouched.
				return $record;
			}
			// Only message, context and extra are realistically mutated by a
			// processor; level, channel and datetime are preserved by with().
			// (with() also expects a Level enum for 'level', which toArray()
			// would have flattened to an int, so it must not be round-tripped.)
			return $record->with(
				message: $result['message'] ?? $record->message,
				context: $result['context'] ?? $record->context,
				extra: $result['extra'] ?? $record->extra,
			);
		};
	}

	/**
	 * Whether a processor declares its record parameter as a plain array
	 * (Monolog 2 style) rather than a LogRecord (Monolog 3 style).
	 *
	 * @param callable $processor
	 * @return bool
	 */
	private static function processorExpectsArray( callable $processor ): bool {
		try {
			$params = ( new ReflectionFunction( Closure::fromCallable( $processor ) ) )
				->getParameters();
		} catch ( ReflectionException ) {
			return false;
		}
		if ( $params === [] ) {
			return false;
		}
		$type = $params[0]->getType();
		// Only bridge an unambiguous `array` hint. A union such as
		// array|LogRecord already accepts the object, so leave it alone.
		return $type instanceof ReflectionNamedType && $type->getName() === 'array';
	}

	/**
	 * Create or return cached handler.
	 * @param string $name Processor name
	 * @return HandlerInterface
	 */
	public function getHandler( $name ) {
		if ( !isset( $this->singletons['handlers'][$name] ) ) {
			$spec = $this->config['handlers'][$name];
			/** @var HandlerInterface $handler */
			$handler = ObjectFactory::getObjectFromSpec( $spec );
			if (
				isset( $spec['formatter'] ) &&
				$handler instanceof FormattableHandlerInterface
			) {
				$handler->setFormatter(
					$this->getFormatter( $spec['formatter'] )
				);
			}
			if ( isset( $spec['buffer'] ) && $spec['buffer'] ) {
				// @phan-suppress-next-line PhanTypeMismatchArgument Gets confused by FormattableHandlerInterface
				$handler = new BufferHandler( $handler );
			}
			$this->singletons['handlers'][$name] = $handler;
		}
		return $this->singletons['handlers'][$name];
	}

	/**
	 * Create or return cached formatter.
	 * @param string $name Formatter name
	 * @return FormatterInterface
	 */
	public function getFormatter( $name ) {
		if ( !isset( $this->singletons['formatters'][$name] ) ) {
			$spec = $this->config['formatters'][$name];
			/** @var FormatterInterface $formatter */
			$formatter = ObjectFactory::getObjectFromSpec( $spec );
			$this->singletons['formatters'][$name] = $formatter;
		}
		return $this->singletons['formatters'][$name];
	}
}
