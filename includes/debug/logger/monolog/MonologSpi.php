<?php
/**
 * @section LICENSE
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

/**
 * MWLogger service provider that creates loggers implemented by Monolog.
 *
 * Configured using an array of configuration data with the keys 'loggers',
 * 'processors', 'handlers' and 'formatters'.
 *
 * The ['loggers']['@default'] configuration will be used to create loggers
 * for any channel that isn't explicitly named in the 'loggers' configuration
 * section.
 *
 * Example:
 * @code
 * array(
 *     'loggers' => array(
 *         '@default' => array(
 *             'processors' => array( 'psr', 'pid', 'uid', 'web' ),
 *             'handlers'   => array( 'stream' ),
 *         ),
 *     ),
 *     'processors' => array(
 *         'psr' => array(
 *             'class' => '\\Monolog\\Processor\\PsrLogMessageProcessor',
 *         ),
 *         'pid' => array(
 *             'class' => '\\Monolog\\Processor\\ProcessIdProcessor',
 *         ),
 *         'uid' => array(
 *             'class' => '\\Monolog\\Processor\\UidProcessor',
 *         ),
 *         'web' => array(
 *             'class' => '\\Monolog\\Processor\\WebProcessor',
 *         ),
 *     ),
 *     'handlers' => array(
 *         'stream' => array(
 *             'class'     => '\\Monolog\\Handler\\StreamHandler',
 *             'args'      => array( 'path/to/your.log' ),
 *             'formatter' => 'line',
 *         ),
 *         'redis' => array(
 *             'class'     => '\\Monolog\\Handler\\RedisHandler',
 *             'args'      => array( function() {
 *                     $redis = new Redis();
 *                     $redis->connect( '127.0.0.1', 6379 );
 *                     return $redis;
 *                 },
 *                 'logstash'
 *             ),
 *             'formatter' => 'logstash',
 *         ),
 *     ),
 *     'formatters' => array(
 *         'line' => array(
 *             'class' => '\\Monolog\\Formatter\\LineFormatter',
 *          ),
 *          'logstash' => array(
 *              'class' => '\\Monolog\\Formatter\\LogstashFormatter',
 *              'args'  => array( '', php_uname( 'n' ), null, '', 1 ),
 *          ),
 *     ),
 * );
 * @endcode
 *
 * Configuration can be specified using the $wgMWLoggerMonologSpiConfig global
 * variable.
 *
 * @see https://github.com/Seldaek/monolog
 * @since 1.24
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 */
class MWLoggerMonologSpi implements MWLoggerSpi {

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
	 * @param array $config Configuration data. Defaults to global
	 *     $wgMWLoggerMonologSpiConfig
	 */
	public function __construct( $config = null ) {
		if ( $config === null ) {
			global $wgMWLoggerMonologSpiConfig;
			$config = $wgMWLoggerMonologSpiConfig;
		}
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
	 * @return MWLogger Logger instance
	 */
	public function getLogger( $channel ) {
		if ( !isset( $this->singletons['loggers'][$channel] ) ) {
			// Fallback to using the '@default' configuration if an explict
			// configuration for the requested channel isn't found.
			$spec = isset( $this->config['loggers'][$channel] ) ?
				$this->config['loggers'][$channel] :
				$this->config['loggers']['@default'];

				$monolog = $this->createLogger( $channel, $spec );
				$this->singletons['loggers'][$channel] = new MWLogger( $monolog );
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
		$obj = new \Monolog\Logger( $channel );

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
	protected function getProcessor( $name ) {
		if ( !isset( $this->singletons['processors'][$name] ) ) {
			$spec = $this->config['processors'][$name];
			$this->singletons['processors'][$name] = $this->instantiate( $spec );
		}
		return $this->singletons['processors'][$name];
	}


	/**
	 * Create or return cached handler.
	 * @param string $name Processor name
	 * @return \Monolog\Handler\HandlerInterface
	 */
	protected function getHandler( $name ) {
		if ( !isset( $this->singletons['handlers'][$name] ) ) {
			$spec = $this->config['handlers'][$name];
			$handler = $this->instantiate( $spec );
			$handler->setFormatter( $this->getFormatter( $spec['formatter'] ) );
			$this->singletons['handlers'][$name] = $handler;
		}
		return $this->singletons['handlers'][$name];
	}


	/**
	 * Create or return cached formatter.
	 * @param string $name Formatter name
	 * @return \Monolog\Formatter\FormatterInterface
	 */
	protected function getFormatter( $name ) {
		if ( !isset( $this->singletons['formatters'][$name] ) ) {
			$spec = $this->config['formatters'][$name];
			$this->singletons['formatters'][$name] = $this->instantiate( $spec );
		}
		return $this->singletons['formatters'][$name];
	}


	/**
	 * Instantiate the requested object.
	 *
	 * The specification array must contain a 'class' key with string value that
	 * specifies the class name to instantiate. It can optionally contain an
	 * 'args' key that provides constructor arguments.
	 *
	 * @param array $spec Object specification
	 * @return object
	 */
	protected function instantiate( $spec ) {
		$clazz = $spec['class'];
		$args = isset( $spec['args'] ) ? $spec['args'] : array();
		// If an argument is a callable, call it.
		// This allows passing things such as a database connection to a logger.
		$args = array_map( function ( $value ) {
				if ( is_callable( $value ) ) {
					return $value();
				} else {
					return $value;
				}
			}, $args );

		if ( empty( $args ) ) {
			$obj = new $clazz();

		} else {
			$ref = new ReflectionClass( $clazz );
			$obj = $ref->newInstanceArgs( $args );
		}

		return $obj;
	}

}
