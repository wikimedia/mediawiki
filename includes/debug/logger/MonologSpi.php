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

use Monolog\Logger;
use ObjectFactory;

/**
 * LoggerFactory service provider that creates loggers implemented by
 * Monolog.
 *
 * Configured using an array of configuration data with the keys 'loggers',
 * 'processors', 'handlers' and 'formatters'.
 *
 * The ['loggers']['@default'] configuration will be used to create loggers
 * for any channel that isn't explicitly named in the 'loggers' configuration
 * section.
 *
 * Configuration will most typically be provided in the $wgMWLoggerDefaultSpi
 * global configuration variable used by LoggerFactory to construct its
 * default SPI provider:
 * @code
 * $wgMWLoggerDefaultSpi = array(
 *   'class' => '\\MediaWiki\\Logger\\MonologSpi',
 *   'args' => array( array(
 *       'loggers' => array(
 *           '@default' => array(
 *               'processors' => array( 'wiki', 'psr', 'pid', 'uid', 'web' ),
 *               'handlers'   => array( 'stream' ),
 *           ),
 *           'runJobs' => array(
 *               'processors' => array( 'wiki', 'psr', 'pid' ),
 *               'handlers'   => array( 'stream' ),
 *           )
 *       ),
 *       'processors' => array(
 *           'wiki' => array(
 *               'class' => '\\MediaWiki\\Logger\\Monolog\\WikiProcessor',
 *           ),
 *           'psr' => array(
 *               'class' => '\\Monolog\\Processor\\PsrLogMessageProcessor',
 *           ),
 *           'pid' => array(
 *               'class' => '\\Monolog\\Processor\\ProcessIdProcessor',
 *           ),
 *           'uid' => array(
 *               'class' => '\\Monolog\\Processor\\UidProcessor',
 *           ),
 *           'web' => array(
 *               'class' => '\\Monolog\\Processor\\WebProcessor',
 *           ),
 *       ),
 *       'handlers' => array(
 *           'stream' => array(
 *               'class'     => '\\Monolog\\Handler\\StreamHandler',
 *               'args'      => array( 'path/to/your.log' ),
 *               'formatter' => 'line',
 *           ),
 *           'redis' => array(
 *               'class'     => '\\Monolog\\Handler\\RedisHandler',
 *               'args'      => array( function() {
 *                       $redis = new Redis();
 *                       $redis->connect( '127.0.0.1', 6379 );
 *                       return $redis;
 *                   },
 *                   'logstash'
 *               ),
 *               'formatter' => 'logstash',
 *           ),
 *           'udp2log' => array(
 *               'class' => '\\MediaWiki\\Logger\\Monolog\\LegacyHandler',
 *               'args' => array(
 *                   'udp://127.0.0.1:8420/mediawiki
 *               ),
 *               'formatter' => 'line',
 *           ),
 *       ),
 *       'formatters' => array(
 *           'line' => array(
 *               'class' => '\\Monolog\\Formatter\\LineFormatter',
 *            ),
 *            'logstash' => array(
 *                'class' => '\\Monolog\\Formatter\\LogstashFormatter',
 *                'args'  => array( 'mediawiki', php_uname( 'n' ), null, '', 1 ),
 *            ),
 *       ),
 *   ) ),
 * );
 * @endcode
 *
 * @see https://github.com/Seldaek/monolog
 * @since 1.25
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
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
		$this->config = $config;
		$this->reset();
	}


	/**
	 * Reset internal caches.
	 *
	 * This is public for use in unit tests. Under normal operation there should
	 * be no need to flush the caches.
	 */
	public function reset() {
		$this->singletons = array(
			'loggers'    => array(),
			'handlers'   => array(),
			'formatters' => array(),
			'processors' => array(),
		);
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
			$spec = isset( $this->config['loggers'][$channel] ) ?
				$this->config['loggers'][$channel] :
				$this->config['loggers']['@default'];

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
