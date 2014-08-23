<?php

/**
 * Copyright 2014
 *
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
 * Factory class to create Config objects
 *
 * @since 1.23
 */
class ConfigFactory {

	/**
	 * Map of config name => callback
	 * @var array
	 */
	protected $factoryFunctions = array();

	/**
	 * Config objects that have already been created
	 * name => Config object
	 * @var array
	 */
	protected $configs = array();

	/**
	 * @var ConfigFactory
	 */
	private static $self;

	public static function getDefaultInstance() {
		if ( !self::$self ) {
			self::$self = new self;
			global $wgConfigRegistry;
			foreach ( $wgConfigRegistry as $name => $callback ) {
				self::$self->register( $name, $callback );
			}
		}
		return self::$self;
	}

	/**
	 * Destroy the default instance
	 * Should only be called inside unit tests
	 * @throws MWException
	 * @codeCoverageIgnore
	 */
	public static function destroyDefaultInstance() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( __METHOD__ . ' was called outside of unit tests' );
		}

		self::$self = null;
	}

	/**
	 * Register a new config factory function
	 * Will override if it's already registered
	 * @param string $name
	 * @param callable $callback That takes this ConfigFactory as an argument
	 * @throws InvalidArgumentException If an invalid callback is provided
	 */
	public function register( $name, $callback ) {
		if ( !is_callable( $callback ) ) {
			throw new InvalidArgumentException( 'Invalid callback provided' );
		}
		$this->factoryFunctions[$name] = $callback;
	}

	/**
	 * Create a given Config using the registered callback for $name.
	 * If an object was already created, the same Config object is returned.
	 * @param string $name Name of the extension/component you want a Config object for
	 *                     'main' is used for core
	 * @throws ConfigException If a factory function isn't registered for $name
	 * @throws UnexpectedValueException If the factory function returns a non-Config object
	 * @return Config
	 */
	public function makeConfig( $name ) {
		if ( !isset( $this->configs[$name] ) ) {
			if ( !isset( $this->factoryFunctions[$name] ) ) {
				throw new ConfigException( "No registered builder available for $name." );
			}
			$conf = call_user_func( $this->factoryFunctions[$name], $this );
			if ( $conf instanceof Config ) {
				$this->configs[$name] = $conf;
			} else {
				throw new UnexpectedValueException( "The builder for $name returned a non-Config object." );
			}
		}

		return $this->configs[$name];
	}
}
