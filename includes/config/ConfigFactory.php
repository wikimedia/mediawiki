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
use MediaWiki\Services\SalvageableService;
use Wikimedia\Assert\Assert;

/**
 * Factory class to create Config objects
 *
 * @since 1.23
 */
class ConfigFactory implements SalvageableService {

	/**
	 * Map of config name => callback
	 * @var array
	 */
	protected $factoryFunctions = [];

	/**
	 * Config objects that have already been created
	 * name => Config object
	 * @var array
	 */
	protected $configs = [];

	/**
	 * @deprecated since 1.27, use MediaWikiServices::getConfigFactory() instead.
	 *
	 * @return ConfigFactory
	 */
	public static function getDefaultInstance() {
		return \MediaWiki\MediaWikiServices::getInstance()->getConfigFactory();
	}

	/**
	 * Re-uses existing Cache objects from $other. Cache objects are only re-used if the
	 * registered factory function for both is the same. Cache config is not copied,
	 * and only instances of caches defined on this instance with the same config
	 * are copied.
	 *
	 * @see SalvageableService::salvage()
	 *
	 * @param SalvageableService $other The object to salvage state from. $other must have the
	 * exact same type as $this.
	 */
	public function salvage( SalvageableService $other ) {
		Assert::parameterType( self::class, $other, '$other' );

		/** @var ConfigFactory $other */
		foreach ( $other->factoryFunctions as $name => $otherFunc ) {
			if ( !isset( $this->factoryFunctions[$name] ) ) {
				continue;
			}

			// if the callback function is the same, salvage the Cache object
			// XXX: Closures are never equal!
			if ( isset( $other->configs[$name] )
				&& $this->factoryFunctions[$name] == $otherFunc
			) {
				$this->configs[$name] = $other->configs[$name];
				unset( $other->configs[$name] );
			}
		}

		// disable $other
		$other->factoryFunctions = [];
		$other->configs = [];
	}

	/**
	 * @return string[]
	 */
	public function getConfigNames() {
		return array_keys( $this->factoryFunctions );
	}

	/**
	 * Register a new config factory function.
	 * Will override if it's already registered.
	 * Use "*" for $name to provide a fallback config for all unknown names.
	 * @param string $name
	 * @param callable|Config $callback A factory callback that takes this ConfigFactory
	 *        as an argument and returns a Config instance, or an existing Config instance.
	 * @throws InvalidArgumentException If an invalid callback is provided
	 */
	public function register( $name, $callback ) {
		if ( !is_callable( $callback ) && !( $callback instanceof Config ) ) {
			if ( is_array( $callback ) ) {
				$callback = '[ ' . implode( ', ', $callback ) . ' ]';
			} elseif ( is_object( $callback ) ) {
				$callback = 'instanceof ' . get_class( $callback );
			}
			throw new InvalidArgumentException( 'Invalid callback \'' . $callback . '\' provided' );
		}

		unset( $this->configs[$name] );
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
			$key = $name;
			if ( !isset( $this->factoryFunctions[$key] ) ) {
				$key = '*';
			}
			if ( !isset( $this->factoryFunctions[$key] ) ) {
				throw new ConfigException( "No registered builder available for $name." );
			}

			if ( $this->factoryFunctions[$key] instanceof Config ) {
				$conf = $this->factoryFunctions[$key];
			} else {
				$conf = call_user_func( $this->factoryFunctions[$key], $this );
			}

			if ( $conf instanceof Config ) {
				$this->configs[$name] = $conf;
			} else {
				throw new UnexpectedValueException( "The builder for $name returned a non-Config object." );
			}
		}

		return $this->configs[$name];
	}

}
