<?php
use MediaWiki\Services\ServiceWiring;
use Wikimedia\Assert\Assert;

/**
 * A factory for DatabaseBase objects based on a ServiceWiring helper.
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
 * @ingroup Database
 */

class DatabaseFactory {

	/**
	 * @var ServiceWiring
	 */
	private $wiring;

	public function __construct() {
		$this->wiring = new ServiceWiring();
	}

	/**
	 * @param array $wiringFiles A list of PGP files to load wiring information from.
	 * Each file is loaded using PHP's include mechanism. Each file is expected to
	 * return an associative array that maps service names to constructor callbacks.
	 */
	public function loadWiringFiles( array $wiringFiles ) {
		$this->wiring->loadWiringFiles( $wiringFiles );
	}

	/**
	 * Registers multiple services (aka a "wiring").
	 *
	 * @param array $serviceConstructors An associative array mapping service names to
	 *        constructor callbacks.
	 */
	public function applyWiring( array $serviceConstructors ) {
		$this->wiring->applyWiring( $serviceConstructors );
	}

	/**
	 * Returns true if construction of a DatabaseBase instance is supported for the given
	 * database type.
	 *
	 * @param string $dbType
	 *
	 * @return boolean
	 */
	public function hasSupport( $dbType ) {
		return $this->wiring->hasService( $dbType );
	}

	/**
	 * Returns all supported database types.
	 *
	 * @return string[]
	 */
	public function getDatabaseTypes() {
		return $this->wiring->getServiceNames();
	}

	/**
	 * Define a new database type. The type must not be known already.
	 *
	 * @see newDatabase().
	 *
	 * @param string $dbType The type name of the database to register, for use with newDatabase().
	 * @param callable $constructor Callback that returns a DatabaseBase instance.
	 *        Will be called with the $params array passed to newDatabase() as an argument.
	 *
	 * @throws RuntimeException if the database type was already registered.
	 */
	public function defineDatabaseType( $dbType, $constructor ) {
		$this->wiring->defineService( $dbType, $constructor );
	}

	/**
	 * Creates a new DatabaseBase instance for conencting to the given type of database.
	 *
	 * @see defineDatabase().
	 *
	 * @param string $dbType The database type
	 * @param array $params
	 *
	 * @return DatabaseBase if $name is not a known service.
	 */
	public function newDatabase( $dbType, array $params ) {
		$args = func_get_args();
		array_shift( $args );

		$db = $this->wiring->createService( $dbType, $params );

		Assert::postcondition(
			$db instanceof DatabaseBase,
			'Invalid wiring for database type $type: expected a DatabaseBase, got a '
				. get_class( $db )
		);
		return $db;
	}


}
