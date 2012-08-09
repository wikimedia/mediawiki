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
	/**
	 * @var LockManagerGroup
	 */
	protected static $instance = null;

	/** @var Array of (name => ('class' =>, 'config' =>, 'instance' =>)) */
	protected $managers = array();

	protected function __construct() {}

	/**
	 * @return LockManagerGroup
	 */
	public static function singleton() {
		if ( self::$instance == null ) {
			self::$instance = new self();
			self::$instance->initFromGlobals();
		}
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance, so that a new one will be created next
	 * time singleton() is called.
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Register lock managers from the global variables
	 *
	 * @return void
	 */
	protected function initFromGlobals() {
		global $wgLockManagers;

		$this->register( $wgLockManagers );
	}

	/**
	 * Register an array of file lock manager configurations
	 *
	 * @param $configs Array
	 * @return void
	 * @throws MWException
	 */
	protected function register( array $configs ) {
		foreach ( $configs as $config ) {
			if ( !isset( $config['name'] ) ) {
				throw new MWException( "Cannot register a lock manager with no name." );
			}
			$name = $config['name'];
			if ( !isset( $config['class'] ) ) {
				throw new MWException( "Cannot register lock manager `{$name}` with no class." );
			}
			$class = $config['class'];
			unset( $config['class'] ); // lock manager won't need this
			$this->managers[$name] = array(
				'class'    => $class,
				'config'   => $config,
				'instance' => null
			);
		}
	}

	/**
	 * Get the lock manager object with a given name
	 *
	 * @param $name string
	 * @return LockManager
	 * @throws MWException
	 */
	public function get( $name ) {
		if ( !isset( $this->managers[$name] ) ) {
			throw new MWException( "No lock manager defined with the name `$name`." );
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
	 * Get the default lock manager configured for the site.
	 * Returns NullLockManager if no lock manager could be found.
	 *
	 * @return LockManager
	 */
	public function getDefault() {
		return isset( $this->managers['default'] )
			? $this->get( 'default' )
			: new NullLockManager( array() );
	}

	/**
	 * Get the default lock manager configured for the site
	 * or at least some other effective configured lock manager.
	 * Throws an exception if no lock manager could be found.
	 *
	 * @return LockManager
	 * @throws MWException
	 */
	public function getAny() {
		return isset( $this->managers['default'] )
			? $this->get( 'default' )
			: $this->get( 'fsLockManager' );
	}
}
