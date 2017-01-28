<?php
/**
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
 * A Config instance which stores all provided settings as a member variable, merges
 * them with a corresponding global if set, then updates or removes the global.
 *
 * @since 1.29
 */
class LocalConfig implements Config, MutableConfig {

	/**
	 * Array of config settings
	 *
	 * @var array
	 */
	private $defaultSettings;

	/**
	 * Array of merge strategies
	 *
	 * @var array
	 */
	private $mergeStrategies;

	/** @var string */
	private $defaultMergeStrategy;

	/** @var string */
	private $globalPrefix;

	/** @var bool */
	private $removeGlobals;

	/**
	 * Array of setup callbacks
	 *
	 * @var Closure[]
	 */
	private $setupCallbacks = [];

	/**
	 * @param array $defaultSettings The default settings to use
	 * @param array $mergeStrategies The merge strategies for arrays
	 * @param string $mergeStrategies The default merge strategy for arrays
	 * @param string $prefix Prefix to lookup globals
	 * @param bool $removeGlobals Whether to remove the globals, if not they are updated
	 */
	public function __construct( array $defaultSettings = [], array $mergeStrategies = [],
		$defaultMergeStrategy = 'array_merge', $globalPrefix = 'wg', $removeGlobals = false
	) {
		$this->defaultSettings = $defaultSettings;
		$this->mergeStrategies = $mergeStrategies;
		$this->defaultMergeStrategy = $defaultMergeStrategy;
		$this->globalPrefix = $globalPrefix;
		$this->removeGlobals = $removeGlobals;
	}

	/**
	 * @see Config::get
	 */
	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
		}

		if ( isset( $this->localSettings[$name] ) ) {
			return $this->localSettings[$name];
		}

		if ( isset( $GLOBALS[$this->globalPrefix . $name] ) ) {
			$defaultVal = $this->defaultSettings[$name];
			$customVal = $GLOBALS[$this->globalPrefix . $name];

			if ( is_array( $defaultVal ) && $defaultVal && is_array( $customVal ) && $customVal ) {
				if ( isset( $this->mergeStrategies[$name] ) ) {
					$mergeStrategy = $this->mergeStrategies[$name];
				} else {
					$mergeStrategy = $this->defaultMergeStrategy;
				}
			} else {
				$mergeStrategy = 'override';
			}

			switch ( $mergeStrategy ) {
				case 'override':
					$val = $customVal;
					break;
				case 'array_merge':
					$val = array_merge( $defaultVal, $customVal );
					break;
				case 'array_plus':
					$val = $customVal + $defaultVal;
					break;
				case 'array_plus_2d':
					$val = wfArrayPlus2d( $customVal, $defaultVal );
					break;
				case 'array_merge_recursive':
					$val = array_merge_recursive( $customVal, $defaultVal );
					break;
				case 'array_replace_recursive':
					$val = array_replace_recursive( $customVal, $defaultVal );
					break;
				default:
					throw new UnexpectedValueException( "Unknown merge strategy '$mergeStrategy'" );
			}
			if ( $this->removeGlobals ) {
				unset( $GLOBALS[$this->globalPrefix . $name] );
			} else {
				$GLOBALS[$this->globalPrefix . $name] = $val;
			}
		} else {
			$val = $this->defaultSettings[$name];
		}
		if ( isset( $this->setupCallbacks[$name] ) ) {
			$val = call_user_func( $this->setupCallback[$name], $val );
		}
		$this->localSettings[$name] = $val;
		return $this->localSettings[$name];
	}

	/**
	 * @see Config::has
	 */
	public function has( $name ) {
		return array_key_exists( $name, $this->defaultSettings );
	}

	/**
	 * @see MutableConfig::set
	 */
	public function set( $name, $value ) {
		global $wgFullyInitialised;
		if ( $wgFullyInitialised ) {
			throw new ConfigException( 'Setup is finished, this local config cannot be modified.' );
		}
		$this->localSettings[$name] = $value;
	}

	/**
	 * Allow to modify with a callback the returned value upon first get
	 * @param string $name
	 * @param Closure $callback
	 */
	public function setupCallback( $name, Closure $callback ) {
		global $wgFullyInitialised;
		if ( $wgFullyInitialised ) {
			throw new ConfigException( 'Setup is finished, this local config cannot be modified.' );
		}
		$this->setupCallbacks[$name] = $callback;
	}

}
