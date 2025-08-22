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
use MediaWiki\Logger\LoggerFactory;

/**
 * Class to handle file lock manager registration
 *
 * @ingroup LockManager
 * @since 1.19
 */
class LockManagerGroup {
	/** @var string domain (usually wiki ID) */
	protected $domain;

	/** @var array Array of (name => ('class' => ..., 'config' => ..., 'instance' => ...)) */
	protected $managers = [];

	/**
	 * Do not call this directly. Use LockManagerGroupFactory.
	 *
	 * @param string $domain Domain (usually wiki ID)
	 * @param array[] $lockManagerConfigs In format of $wgLockManagers
	 */
	public function __construct( $domain, array $lockManagerConfigs ) {
		$this->domain = $domain;

		foreach ( $lockManagerConfigs as $config ) {
			$config['domain'] = $this->domain;
			if ( !isset( $config['name'] ) ) {
				throw new InvalidArgumentException( "Cannot register a lock manager with no name." );
			}
			$name = $config['name'];
			if ( !isset( $config['class'] ) ) {
				throw new InvalidArgumentException( "Cannot register lock manager `{$name}` with no class." );
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
			throw new InvalidArgumentException( "No lock manager defined with the name `$name`." );
		}
		// Lazy-load the actual lock manager instance
		if ( !isset( $this->managers[$name]['instance'] ) ) {
			$class = $this->managers[$name]['class'];
			'@phan-var class-string<LockManager> $class';
			$config = $this->managers[$name]['config'];
			$config['logger'] = LoggerFactory::getInstance( 'LockManager' );

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
			throw new InvalidArgumentException( "No lock manager defined with the name `$name`." );
		}
		$class = $this->managers[$name]['class'];

		return [ 'class' => $class ] + $this->managers[$name]['config'];
	}
}
