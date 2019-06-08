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
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @author Addshore
 * @since 1.31
 */
class NameTableStore {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var WANObjectCache */
	private $cache;

	/** @var LoggerInterface */
	private $logger;

	/** @var string[] */
	private $tableCache = null;

	/** @var bool|string */
	private $domain = false;

	/** @var int */
	private $cacheTTL;

	/** @var string */
	private $table;
	/** @var string */
	private $idField;
	/** @var string */
	private $nameField;
	/** @var null|callable */
	private $normalizationCallback = null;
	/** @var null|callable */
	private $insertCallback = null;

	/**
	 * @param ILoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param WANObjectCache $cache A cache manager for caching data. This can be the local
	 *        wiki's default instance even if $wikiId refers to a different wiki, since
	 *        makeGlobalKey() is used to constructed a key that allows cached names from
	 *        the same database to be re-used between wikis. For example, enwiki and frwiki will
	 *        use the same cache keys for names from the wikidatawiki database, regardless
	 *        of the cache's default key space.
	 * @param LoggerInterface $logger
	 * @param string $table
	 * @param string $idField
	 * @param string $nameField
	 * @param callable|null $normalizationCallback Normalization to be applied to names before being
	 * saved or queried. This should be a callback that accepts and returns a single string.
	 * @param bool|string $dbDomain Database domain ID. Use false for the local database domain.
	 * @param callable|null $insertCallback Callback to change insert fields accordingly.
	 * This parameter was introduced in 1.32
	 */
	public function __construct(
		ILoadBalancer $dbLoadBalancer,
		WANObjectCache $cache,
		LoggerInterface $logger,
		$table,
		$idField,
		$nameField,
		callable $normalizationCallback = null,
		$dbDomain = false,
		callable $insertCallback = null
	) {
		$this->loadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->logger = $logger;
		$this->table = $table;
		$this->idField = $idField;
		$this->nameField = $nameField;
		$this->normalizationCallback = $normalizationCallback;
		$this->domain = $dbDomain;
		$this->cacheTTL = IExpiringStore::TTL_MONTH;
		$this->insertCallback = $insertCallback;
	}

	/**
	 * @param int $index A database index, like DB_MASTER or DB_REPLICA
	 * @param int $flags Database connection flags
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $index, $flags = 0 ) {
		return $this->loadBalancer->getConnection( $index, [], $this->domain, $flags );
	}

	/**
	 * Gets the cache key for names.
	 *
	 * The cache key is constructed based on the wiki ID passed to the constructor, and allows
	 * sharing of name tables cached for a specific database between wikis.
	 *
	 * @return string
	 */
	private function getCacheKey() {
		return $this->cache->makeGlobalKey(
			'NameTableSqlStore',
			$this->table,
			$this->loadBalancer->resolveDomainID( $this->domain )
		);
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

		$table = $this->getTableFromCachesOrReplica();
		$searchResult = array_search( $name, $table, true );
		if ( $searchResult === false ) {
			$id = $this->store( $name );
			if ( $id === null ) {
				// RACE: $name was already in the db, probably just inserted, so load from master.
				// Use DBO_TRX to avoid missing inserts due to other threads or REPEATABLE-READs.
				// ...but not during unit tests, because we need the fake DB tables of the default
				// connection.
				$connFlags = defined( 'MW_PHPUNIT_TEST' ) ? 0 : ILoadBalancer::CONN_TRX_AUTOCOMMIT;
				$table = $this->reloadMap( $connFlags );

				$searchResult = array_search( $name, $table, true );
				if ( $searchResult === false ) {
					// Insert failed due to IGNORE flag, but DB_MASTER didn't give us the data
					$m = "No insert possible but master didn't give us a record for " .
						"'{$name}' in '{$this->table}'";
					$this->logger->error( $m );
					throw new NameTableAccessException( $m );
				}
			} elseif ( isset( $table[$id] ) ) {
				throw new NameTableAccessException(
					"Expected unused ID from database insert for '$name' "
					. " into '{$this->table}', but ID $id is already associated with"
					. " the name '{$table[$id]}'! This may indicate database corruption!" );
			} else {
				$table[$id] = $name;
				$searchResult = $id;

				// As store returned an ID we know we inserted so delete from WAN cache
				$this->purgeWANCache(
					function () {
						$this->cache->delete( $this->getCacheKey() );
					}
				);
			}
			$this->tableCache = $table;
		}

		return $searchResult;
	}

	/**
	 * Reloads the name table from the master database, and purges the WAN cache entry.
	 *
	 * @note This should only be called in situations where the local cache has been detected
	 * to be out of sync with the database. There should be no reason to call this method
	 * from outside the NameTabelStore during normal operation. This method may however be
	 * useful in unit tests.
	 *
	 * @param int $connFlags ILoadBalancer::CONN_XXX flags. Optional.
	 *
	 * @return string[] The freshly reloaded name map
	 */
	public function reloadMap( $connFlags = 0 ) {
		$this->tableCache = $this->loadTable(
			$this->getDBConnection( DB_MASTER, $connFlags )
		);
		$this->purgeWANCache(
			function () {
				$this->cache->reap( $this->getCacheKey(), INF );
			}
		);

		return $this->tableCache;
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

		$table = $this->getTableFromCachesOrReplica();
		$searchResult = array_search( $name, $table, true );

		if ( $searchResult !== false ) {
			return $searchResult;
		}

		throw NameTableAccessException::newFromDetails( $this->table, 'name', $name );
	}

	/**
	 * Get the name of the given id.
	 * If the id doesn't exist this will throw.
	 * This should be used in cases where we believe the id already exists.
	 *
	 * Note: Calls to this method will result in a master select for non existing IDs.
	 *
	 * @param int $id
	 * @throws NameTableAccessException The id does not exist
	 * @return string name
	 */
	public function getName( $id ) {
		Assert::parameterType( 'integer', $id, '$id' );

		$table = $this->getTableFromCachesOrReplica();
		if ( array_key_exists( $id, $table ) ) {
			return $table[$id];
		}
		$fname = __METHOD__;

		$table = $this->cache->getWithSetCallback(
			$this->getCacheKey(),
			$this->cacheTTL,
			function ( $oldValue, &$ttl, &$setOpts ) use ( $id, $fname ) {
				// Check if cached value is up-to-date enough to have $id
				if ( is_array( $oldValue ) && array_key_exists( $id, $oldValue ) ) {
					// Completely leave the cache key alone
					$ttl = WANObjectCache::TTL_UNCACHEABLE;
					// Use the old value
					return $oldValue;
				}
				// Regenerate from replica DB, and master DB if needed
				foreach ( [ DB_REPLICA, DB_MASTER ] as $source ) {
					// Log a fallback to master
					if ( $source === DB_MASTER ) {
						$this->logger->info(
							$fname . ' falling back to master select from ' .
							$this->table . ' with id ' . $id
						);
					}
					$db = $this->getDBConnection( $source );
					$cacheSetOpts = Database::getCacheSetOptions( $db );
					$table = $this->loadTable( $db );
					if ( array_key_exists( $id, $table ) ) {
						break; // found it
					}
				}
				// Use the value from last source checked
				$setOpts += $cacheSetOpts;

				return $table;
			},
			[ 'minAsOf' => INF ] // force callback run
		);

		$this->tableCache = $table;

		if ( array_key_exists( $id, $table ) ) {
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
		return $this->getTableFromCachesOrReplica();
	}

	/**
	 * @return string[]
	 */
	private function getTableFromCachesOrReplica() {
		if ( $this->tableCache !== null ) {
			return $this->tableCache;
		}

		$table = $this->cache->getWithSetCallback(
			$this->getCacheKey(),
			$this->cacheTTL,
			function ( $oldValue, &$ttl, &$setOpts ) {
				$dbr = $this->getDBConnection( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );
				return $this->loadTable( $dbr );
			}
		);

		$this->tableCache = $table;

		return $table;
	}

	/**
	 * Reap the WANCache entry for this table.
	 *
	 * @param callable $purgeCallback Callback to 'purge' the WAN cache
	 */
	private function purgeWANCache( $purgeCallback ) {
		// If the LB has no DB changes don't bother with onTransactionPreCommitOrIdle
		if ( !$this->loadBalancer->hasOrMadeRecentMasterChanges() ) {
			$purgeCallback();
			return;
		}

		$this->getDBConnection( DB_MASTER )
			->onTransactionPreCommitOrIdle( $purgeCallback, __METHOD__ );
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
			__METHOD__,
			[ 'ORDER BY' => 'id' ]
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
			$this->getFieldsToStore( $name ),
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

	/**
	 * @param string $name
	 * @return array
	 */
	private function getFieldsToStore( $name ) {
		$fields = [ $this->nameField => $name ];
		if ( $this->insertCallback !== null ) {
			$fields = call_user_func( $this->insertCallback, $fields );
		}
		return $fields;
	}

}
