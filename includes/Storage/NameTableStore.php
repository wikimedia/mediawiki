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
use Psr\Log\LoggerInterface;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @author Addshore
 * @since 1.31
 */
class NameTableStore {

	/** @var LoadBalancer */
	private $loadBalancer;

	/** @var WANObjectCache */
	private $cache;

	/** @var LoggerInterface */
	private $logger;

	/** @var string[] */
	private $tableCache = null;

	/** @var bool|string */
	private $wikiId = false;

	/** @var string */
	private $table;
	/** @var string */
	private $idField;
	/** @var string */
	private $nameField;
	/** @var null|callable */
	private $normalizationCallback = null;

	/**
	 * @param LoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param WANObjectCache $cache A cache manager for caching data
	 * @param LoggerInterface $logger
	 * @param string $table
	 * @param string $idField
	 * @param string $nameField
	 * @param callable $normalizationCallback Normalization to be applied to names before being
	 * saved or queried. This should be a callback that accepts and returns a single string.
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		WANObjectCache $cache,
		LoggerInterface $logger,
		$table,
		$idField,
		$nameField,
		callable $normalizationCallback = null,
		$wikiId = false
	) {
		$this->loadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->logger = $logger;
		$this->table = $table;
		$this->idField = $idField;
		$this->nameField = $nameField;
		$this->normalizationCallback = $normalizationCallback;
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
		return $this->cache->makeKey( 'NameTableSqlStore', $this->table, $this->wikiId );
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function normalizeName( $name ) {
		if ( $this->normalizationCallback === null ) {
			return $name;
		}
		return call_user_func( $this->normalizationCallback, $name );
	}

	/**
	 * Acquire the id of the given name.
	 * This creates a row in the table if it doesn't already exist.
	 *
	 * @param string $name
	 * @throws NameTableAccessException
	 * @return int
	 */
	public function acquireId( $name ) {
		Assert::parameterType( 'string', $name, '$name' );
		$name = $this->normalizeName( $name );

		$table = $this->getCachedTable();
		$searchResult = array_search( $name, $table, true );
		if ( $searchResult === false ) {
			$id = $this->store( $name );
			if ( $id === null ) {
				// RACE: $name was already in the db, probably just inserted, so load from master
				$table = $this->loadTable( $this->getDBConnection( DB_MASTER ) );
				$searchResult = array_search( $name, $table, true );
				if ( $searchResult === false ) {
					// Insert failed due to IGNORE flag, but DB_MASTER didn't give us the data
					$m = "No insert possible but master didn't give us a record for '{$name}' in '{$this->table}'";
					$this->logger->error( $m );
					throw new NameTableAccessException( $m );
				}
			} else {
				$table[$id] = $name;
				$searchResult = $id;
			}
			$this->purgeWANCache();
			$this->tableCache = $table;
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
	 * @throws NameTableAccessException The name does not exist
	 * @return int Id
	 */
	public function getId( $name ) {
		Assert::parameterType( 'string', $name, '$name' );
		$name = $this->normalizeName( $name );

		$table = $this->getCachedTable();
		$searchResult = array_search( $name, $table, true );

		if ( $searchResult !== false ) {
			return $searchResult;
		}

		throw NameTableAccessException::newFromDetails( $this->table, 'name', $name );
	}

	/**
	 * Get the name of the given id.
	 * If the id doesn't exist this will throw.
	 * This should be used in cases where we believe the id already exists or want to check for
	 * existence.
	 *
	 * @param int $id
	 * @throws NameTableAccessException The id does not exist
	 * @return string name
	 */
	public function getName( $id ) {
		Assert::parameterType( 'integer', $id, '$id' );

		$table = $this->getCachedTable();
		if ( array_key_exists( $id, $table ) ) {
			return $table[$id];
		} elseif ( $this->tableCache !== null ) {
			// We used the process cache, clear and fallback to WAN cache
			$this->tableCache = null;
			$table = $this->getCachedTable();
			if ( array_key_exists( $id, $table ) ) {
				return $table[$id];
			}
		}

		// Fallback to loading from master
		// A caller that has an ID probably got that ID from the database
		$table = $this->loadTable( $this->getDBConnection( DB_MASTER ) );
		$this->logger->info(
			__METHOD__ . 'falling back to master select from ' . $this->table . ' with id ' . $id
		);
		if ( array_key_exists( $id, $table ) ) {
			$this->purgeWANCache();
			return $table[$id];
		}

		throw NameTableAccessException::newFromDetails( $this->table, 'id', $id );
	}

	/**
	 * Get the whole table, in no particular order as a map of ids to names.
	 * This method could be subject to DB or cache lag.
	 *
	 * @return string[] keys are the name ids, values are the names themselves
	 *  Example: [ 1 => 'foo', 3 => 'bar' ]
	 */
	public function getMap() {
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
	 * Remove the WANCache entry for this table.
	 */
	private function purgeWANCache() {
		$cacheClear = function () {
			$this->cache->delete(
				$this->getCacheKey(),
				WANObjectCache::HOLDOFF_TTL
			);
		};

		// If the LB has no DB changes don't event get a connection object.
		if ( !$this->loadBalancer->hasOrMadeRecentMasterChanges() ) {
			$cacheClear();
		}

		$dbMaster = $this->getDBConnection( DB_MASTER );

		/**
		 * This method (purgeWANCache) can be called when no master DB connection is open and or no
		 * transactions exists, for example in read only requests when the cache is out of date.
		 * In these cases avoid doing anything with the DB and just call the clear callback
		 * immediately.
		 */
		if ( $dbMaster->trxLevel() === 0 || !$dbMaster->isOpen() ) {
			$cacheClear();
		} else {
			// Clear the cache on commit or rollback
			$dbMaster->onTransactionPreCommitOrIdle( $cacheClear, __METHOD__ );
		}
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

		$assocArray = [];
		foreach ( $result as $row ) {
			$assocArray[$row->id] = $row->name;
		}

		return $assocArray;
	}

	/**
	 * Stores the given name in the DB, returning the ID when an insert occurs.
	 *
	 * @param string $name
	 * @return int|null int if we know the ID, null if we don't
	 */
	private function store( $name ) {
		Assert::parameterType( 'string', $name, '$name' );
		Assert::parameter( $name !== '', '$name', 'should not be an empty string' );
		// Note: this is only called internally so normalization of $name has already occurred.

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
