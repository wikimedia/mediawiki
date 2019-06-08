<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Logger;

use MediaWiki\Logger\Monolog\BufferHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Wikimedia\ObjectFactory;

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
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class MonologSpi implements Spi {

	/**
	 * @var array $singletons
	 */
	protected $singletons;

	/**
	 * Configuration for creating new loggers.
	 * @var array $config
	 */
	protected $config;

	/**
	 * @param array $config Configuration data.
	 */
	public function __construct( array $config ) {
		$this->config = [];
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
			if ( !isset( $this->config['handlers']['@default'] ) ) {
				$this->config['handlers']['@default'] = [
					'class' => StreamHandler::class,
					'args' => [ 'php://stderr', Logger::ERROR ],
				];
			}
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
	 * @return \Psr\Log\LoggerInterface Logger instance
	 */
	public function getLogger( $channel ) {
		if ( !isset( $this->singletons['loggers'][$channel] ) ) {
			// Fallback to using the '@default' configuration if an explict
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
	 * @return \Monolog\Logger
	 */
	protected function createLogger( $channel, $spec ) {
		$obj = new Logger( $channel );

		if ( isset( $spec['calls'] ) ) {
			foreach ( $spec['calls'] as $method => $margs ) {
				$obj->$method( ...$margs );
			}
		}

		if ( isset( $spec['processors'] ) ) {
			foreach ( $spec['processors'] as $processor ) {
				$obj->pushProcessor( $this->getProcessor( $processor ) );
			}
		}

		if ( isset( $spec['handlers'] ) ) {
			foreach ( $spec['handlers'] as $handler ) {
				$obj->pushHandler( $this->getHandler( $handler ) );
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
			$processor = ObjectFactory::getObjectFromSpec( $spec );
			$this->singletons['processors'][$name] = $processor;
		}
		return $this->singletons['processors'][$name];
	}

	/**
	 * Create or return cached handler.
	 * @param string $name Processor name
	 * @return \Monolog\Handler\HandlerInterface
	 */
	public function getHandler( $name ) {
		if ( !isset( $this->singletons['handlers'][$name] ) ) {
			$spec = $this->config['handlers'][$name];
			$handler = ObjectFactory::getObjectFromSpec( $spec );
			if ( isset( $spec['formatter'] ) ) {
				$handler->setFormatter(
					$this->getFormatter( $spec['formatter'] )
				);
			}
			if ( isset( $spec['buffer'] ) && $spec['buffer'] ) {
				$handler = new BufferHandler( $handler );
			}
			$this->singletons['handlers'][$name] = $handler;
		}
		return $this->singletons['handlers'][$name];
	}

	/**
	 * Create or return cached formatter.
	 * @param string $name Formatter name
	 * @return \Monolog\Formatter\FormatterInterface
	 */
	public function getFormatter( $name ) {
		if ( !isset( $this->singletons['formatters'][$name] ) ) {
			$spec = $this->config['formatters'][$name];
			$formatter = ObjectFactory::getObjectFromSpec( $spec );
			$this->singletons['formatters'][$name] = $formatter;
		}
		return $this->singletons['formatters'][$name];
	}
}
