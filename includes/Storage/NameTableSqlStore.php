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

namespace MediaWiki\Storage;

use IExpiringStore;
use LogicException;
use Psr\Log\LoggerInterface;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @author Addshore
 * @since 1.31
 */
class NameTableSqlStore {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var string[]
	 */
	private $tableCache = null;

	/**
	 * @var bool|string
	 */
	private $wikiId = false;

	/** @var string */
	private $table;
	/** @var string */
	private $idField;
	/** @var string */
	private $nameField;

	/**
	 * @param LoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param WANObjectCache $cache A cache manager for caching data
	 * @param LoggerInterface $logger
	 * @param string $table
	 * @param string $idField
	 * @param string $nameField
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		WANObjectCache $cache,
		LoggerInterface $logger,
		$table, $idField, $nameField,
		$wikiId = false
	) {
		$this->loadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->logger = $logger;
		$this->table = $table;
		$this->idField = $idField;
		$this->nameField = $nameField;
		$this->wikiId = $wikiId;
	}

	/**
	 * @param int $index A database index, like DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $index ) {
		return $this->loadBalancer->getConnection( $index, [], $this->wikiId );
	}

	private function getCacheKey() {
		return $this->cache->makeKey( 'NameTableSqlStore', $this->table );
	}

	/**
	 * Acquire the id of the given name.
	 * This creates a row in the table if it doesn't already exist.
	 *
	 * @param string $name
	 * @return int
	 */
	public function acquireId( $name ) {
		$table = $this->getCachedTable();
		$searchResult = array_search( $name, $table, true );
		if ( $searchResult === false ) {
			$id = $this->store( $name );
			if ( $id === null ) {
				// RACE: $name was already in the db, probably just inserted, so load from master
				$dbMaster = $this->getDBConnection( DB_MASTER );
				$table = $this->loadTable( $dbMaster );
				$searchResult = array_search( $name, $table, true );
				if ( $searchResult === false ) {
					// Insert failed due to IGNORE flag, but DB_MASTER didn't give us the data
					throw new LogicException( 'Failed to get the value' );
				}
				$this->updateCachedTable( $table, $dbMaster );
			} else {
				$table[$id] = $name;
				$searchResult = $id;
				$this->updateCachedTable( $table );
			}
		}

		return $searchResult;
	}

	/**
	 * Get the id of the given name.
	 * If the name doesn't exist this will throw.
	 * This should be used in cases where we believe the name already exists or want to check for
	 * existence.
	 *
	 * @param string $name
	 * @return int Id
	 * @throws NameTableAccessException The name does not exist
	 */
	public function getId( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		$table = $this->getCachedTable();
		$searchResult = array_search( $name, $table, true );

		if ( $searchResult !== false ) {
			return $searchResult;
		}

		throw new NameTableAccessException( $this->table, 'name', $name );
	}

	/**
	 * Get the name of the given id.
	 * If the id doesn't exist this will throw.
	 * This should be used in cases where we believe the id already exists or want to check for
	 * existence.
	 *
	 * @param int $id
	 * @return string name
	 * @throws NameTableAccessException The id does not exist
	 */
	public function getName( $id ) {
		Assert::parameterType( 'integer', $id, '$id' );

		$table = $this->getCachedTable();
		if ( array_key_exists( $id, $table ) ) {
			return $table[$id];
		}

		throw new NameTableAccessException( $this->table, 'id', $id );
	}

	/**
	 * Get the whole table, in no particular order.
	 * This method could be subject to DB or cache lag.
	 *
	 * @return string[] keys are the name ids, values are the names themselves
	 *  Example: [ 1 => 'foo', 3 => 'bar' ]
	 */
	public function getTable() {
		return $this->getCachedTable();
	}

	/**
	 * @return string[]
	 */
	private function getCachedTable() {
		if ( $this->tableCache !== null ) {
			return $this->tableCache;
		}
		$table = $this->cache->getWithSetCallback(
			$this->getCacheKey(),
			IExpiringStore::TTL_MONTH,
			function ( $unused, &$ttl, &$setOpts ) {
				$dbr = $this->getDBConnection( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );
				return $this->loadTable( $dbr );
			}
		);

		$this->tableCache = $table;

		return $table;
	}

	/**
	 * Stores the table in the WAN cache and tableCache property
	 *
	 * @param string[] $table
	 * @param IDatabase $db Database that the data came from (optional)
	 */
	private function updateCachedTable( $table, IDatabase $db = null ) {
		$this->cache->set(
			$this->getCacheKey(),
			$table,
			IExpiringStore::TTL_MONTH,
			( $db !== null ? Database::getCacheSetOptions( $db ) : [] )
		);
		$this->tableCache = $table;
	}

	/**
	 * Gets the table from the db
	 *
	 * @param IDatabase $db
	 *
	 * @return string[]
	 */
	private function loadTable( IDatabase $db ) {
		$result = $db->select(
			$this->table,
			[
				'id' => $this->idField,
				'name' => $this->nameField
			],
			[],
			__METHOD__
		);
		return $this->dbResultToAssocArray( $result );
	}

	/**
	 * Converts a DB result to an assoc array used throughout this class.
	 *
	 * @param IResultWrapper $result
	 * @return string[] id => name
	 */
	private function dbResultToAssocArray( IResultWrapper $result ) {
		$array = [];
		foreach ( $result as $row ) {
			$array[$row->id] = $row->name;
		}
		return $array;
	}

	/**
	 * Stores the given name in the DB, returning the ID when an insert occours.
	 *
	 * @param string $name
	 * @return int|null int if we know the ID, null if we don't
	 */
	private function store( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		$dbw = $this->getDBConnection( DB_MASTER );

		$dbw->insert(
			$this->table,
			[ $this->nameField => $name ],
			__METHOD__,
			[ 'IGNORE' ]
		);

		if ( $dbw->affectedRows() === 0 ) {
			$this->logger->info(
				'Tried to insert name into table ' . $this->table . ', but value already existed.'
			);
			return null;
		}

		return $dbw->insertId();
	}

}
