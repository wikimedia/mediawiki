<?php
/**
 * Lock manager registration handling.
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
 * @ingroup LockManager
 */

/**
 * Class to handle file lock manager registration
 *
 * @ingroup LockManager
 * @author Aaron Schulz
 * @since 1.19
 */
class LockManagerGroup {
	/** @var array (domain => LockManager) */
	protected static $instances = [];

	protected $domain; // string; domain (usually wiki ID)

	/** @var array Array of (name => ('class' => ..., 'config' => ..., 'instance' => ...)) */
	protected $managers = [];

	/**
	 * @param string $domain Domain (usually wiki ID)
	 */
	protected function __construct( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * @param bool|string $domain Domain (usually wiki ID). Default: false.
	 * @return LockManagerGroup
	 */
	public static function singleton( $domain = false ) {
		$domain = ( $domain === false ) ? wfWikiID() : $domain;
		if ( !isset( self::$instances[$domain] ) ) {
			self::$instances[$domain] = new self( $domain );
			self::$instances[$domain]->initFromGlobals();
		}

		return self::$instances[$domain];
	}

	/**
	 * Destroy the singleton instances
	 */
	public static function destroySingletons() {
		self::$instances = [];
	}

	/**
	 * Register lock managers from the global variables
	 */
	protected function initFromGlobals() {
		global $wgLockManagers;

		$this->register( $wgLockManagers );
	}

	/**
	 * Register an array of file lock manager configurations
	 *
	 * @param array $configs
	 * @throws Exception
	 */
	protected function register( array $configs ) {
		foreach ( $configs as $config ) {
			$config['domain'] = $this->domain;
			if ( !isset( $config['name'] ) ) {
				throw new Exception( "Cannot register a lock manager with no name." );
			}
			$name = $config['name'];
			if ( !isset( $config['class'] ) ) {
				throw new Exception( "Cannot register lock manager `{$name}` with no class." );
			}
			$class = $config['class'];
			unset( $config['class'] ); // lock manager won't need this
			$this->managers[$name] = [
				'class' => $class,
				'config' => $config,
				'instance' => null
			];
		}
	}

	/**
	 * Get the lock manager object with a given name
	 *
	 * @param string $name
	 * @return LockManager
	 * @throws Exception
	 */
	public function get( $name ) {
		if ( !isset( $this->managers[$name] ) ) {
			throw new Exception( "No lock manager defined with the name `$name`." );
		}
		// Lazy-load the actual lock manager instance
		if ( !isset( $this->managers[$name]['instance'] ) ) {
			$class = $this->managers[$name]['class'];
			$config = $this->managers[$name]['config'];
			$this->managers[$name]['instance'] = new $class( $config );
		}

		return $this->managers[$name]['instance'];
	}

	/**
	 * Get the config array for a lock manager object with a given name
	 *
	 * @param string $name
	 * @return array
	 * @throws Exception
	 */
	public function config( $name ) {
		if ( !isset( $this->managers[$name] ) ) {
			throw new Exception( "No lock manager defined with the name `$name`." );
		}
		$class = $this->managers[$name]['class'];

		return [ 'class' => $class ] + $this->managers[$name]['config'];
	}

	/**
	 * Get the default lock manager configured for the site.
	 * Returns NullLockManager if no lock manager could be found.
	 *
	 * @return LockManager
	 */
	public function getDefault() {
		return isset( $this->managers['default'] )
			? $this->get( 'default' )
			: new NullLockManager( [] );
	}

	/**
	 * Get the default lock manager configured for the site
	 * or at least some other effective configured lock manager.
	 * Throws an exception if no lock manager could be found.
	 *
	 * @return LockManager
	 * @throws Exception
	 */
	public function getAny() {
		return isset( $this->managers['default'] )
			? $this->get( 'default' )
			: $this->get( 'fsLockManager' );
	}
}
