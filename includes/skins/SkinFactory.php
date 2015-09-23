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
 * Factory class to create Skin objects
 *
 * @since 1.24
 */
class SkinFactory {

	/**
	 * Map of name => callback
	 * @var array
	 */
	private $factoryFunctions = array();
	/**
	 * Map of name => fallback human-readable name, used when the 'skinname-<skin>' message is not
	 * available
	 *
	 * @var array
	 */
	private $displayNames = array();

	/**
	 * @var SkinFactory
	 */
	private static $self;

	public static function getDefaultInstance() {
		if ( !self::$self ) {
			self::$self = new self;
		}

		return self::$self;
	}

	/**
	 * Register a new Skin factory function.
	 *
	 * Will override if it's already registered.
	 *
	 * @param string $name Internal skin name. Should be all-lowercase (technically doesn't have
	 *     to be, but doing so would change the case of i18n message keys).
	 * @param string $displayName For backwards-compatibility with old skin loading system. This is
	 *     the text used as skin's human-readable name when the 'skinname-<skin>' message is not
	 *     available. It should be the same as the skin name provided in $wgExtensionCredits.
	 * @param callable $callback Callback that takes the skin name as an argument
	 * @throws InvalidArgumentException If an invalid callback is provided
	 */
	public function register( $name, $displayName, $callback ) {
		if ( !is_callable( $callback ) ) {
			throw new InvalidArgumentException( 'Invalid callback provided' );
		}
		$this->factoryFunctions[$name] = $callback;
		$this->displayNames[$name] = $displayName;
	}

	/**
	 * Returns an associative array of:
	 *  skin name => human readable name
	 *
	 * @return array
	 */
	public function getSkinNames() {
		return $this->displayNames;
	}

	/**
	 * Create a given Skin using the registered callback for $name.
	 * @param string $name Name of the skin you want
	 * @throws SkinException If a factory function isn't registered for $name
	 * @throws UnexpectedValueException If the factory function returns a non-Skin object
	 * @return Skin
	 */
	public function makeSkin( $name ) {
		if ( !isset( $this->factoryFunctions[$name] ) ) {
			throw new SkinException( "No registered builder available for $name." );
		}
		$skin = call_user_func( $this->factoryFunctions[$name], $name );
		if ( $skin instanceof Skin ) {
			return $skin;
		} else {
			throw new UnexpectedValueException( "The builder for $name returned a non-Skin object." );
		}
	}
}
