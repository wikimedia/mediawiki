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

use Exception;
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
	 *        wiki's default instance even if $dbDomain refers to a different wiki, since
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
		return $this->loadBalancer->getConnectionRef( $index, [], $this->domain, $flags );
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
	 * @note If called within an atomic section, there is a chance for the acquired ID
	 * to be lost on rollback. A best effort is made to re-insert the mapping
	 * in this case, and consistency of the cache with the database table is ensured
	 * by re-loading the map after a failed atomic section. However, there is no guarantee
	 * that an ID returned by this method is valid outside the transaction in which it
	 * was produced. This means that calling code should not retain the return value beyond
	 * the scope of a transaction, but rather call acquireId() again after the transaction
	 * is complete. In some rare cases, this may produce an ID different from the first call.
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
				$table = $this->reloadMap( ILoadBalancer::CONN_TRX_AUTOCOMMIT );

				$searchResult = array_search( $name, $table, true );
				if ( $searchResult === false ) {
					// Insert failed due to IGNORE flag, but DB_MASTER didn't give us the data
					$m = "No insert possible but master didn't give us a record for " .
						"'{$name}' in '{$this->table}'";
					$this->logger->error( $m );
					throw new NameTableAccessException( $m );
				}
			} else {
				if ( isset( $table[$id] ) ) {
					// This can happen when a transaction is rolled back and acquireId is called in
					// an onTransactionResolution() callback, which gets executed before retryStore()
					// has a chance to run. The right thing to do in this case is to discard the old
					// value. According to the contract of acquireId, the caller should not have
					// used it outside the transaction, so it should not be persisted anywhere after
					// the rollback.
					$m = "Got ID $id for '$name' from insert"
						. " into '{$this->table}', but ID $id was previously associated with"
						. " the name '{$table[$id]}'. Overriding the old value, which presumably"
						. " has been removed from the database due to a transaction rollback.";

					$this->logger->warning( $m );
				}

				$table[$id] = $name;
				$searchResult = $id;

				// As store returned an ID we know we inserted so delete from WAN cache
				$dbw = $this->getDBConnection( DB_MASTER );
				$dbw->onTransactionPreCommitOrIdle( function () {
					$this->cache->delete( $this->getCacheKey() );
				} );
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
		if ( $connFlags !== 0 && defined( 'MW_PHPUNIT_TEST' ) ) {
			// HACK: We can't use $connFlags while doing PHPUnit tests, because the
			// fake database tables are bound to a single connection.
			$connFlags = 0;
		}

		$dbw = $this->getDBConnection( DB_MASTER, $connFlags );
		$this->tableCache = $this->loadTable( $dbw );
		$dbw->onTransactionPreCommitOrIdle( function () {
			$this->cache->reap( $this->getCacheKey(), INF );
		} );

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

		$id = null;
		$dbw->doAtomicSection(
			__METHOD__,
			function ( IDatabase $unused, $fname )
			use ( $name, &$id, $dbw ) {
				// NOTE: use IDatabase from the parent scope here, not the function parameter.
				// If $dbw is a wrapper around the actual DB, we need to call the wrapper here,
				// not the inner instance.
				$dbw->insert(
					$this->table,
					$this->getFieldsToStore( $name ),
					$fname,
					[ 'IGNORE' ]
				);

				if ( $dbw->affectedRows() === 0 ) {
					$this->logger->info(
						'Tried to insert name into table ' . $this->table . ', but value already existed.'
					);

					return;
				}

				$id = $dbw->insertId();

				// Any open transaction may still be rolled back. If that happens, we have to re-try the
				// insertion and restore a consistent state of the cached table.
				$dbw->onAtomicSectionCancel(
					function ( $trigger, IDatabase $unused ) use ( $name, $id, $dbw ) {
						$this->retryStore( $dbw, $name, $id );
					},
				$fname );
			},
			IDatabase::ATOMIC_CANCELABLE
		);

		return $id;
	}

	/**
	 * After the initial insertion got rolled back, this can be used to try the insertion again,
	 * and ensure a consistent state of the cache.
	 *
	 * @param IDatabase $dbw
	 * @param string $name
	 * @param int $id
	 */
	private function retryStore( IDatabase $dbw, $name, $id ) {
		// NOTE: in the closure below, use the IDatabase from the original method call,
		// not the one passed to the closure as a parameter.
		// If $dbw is a wrapper around the actual DB, we need to call the wrapper,
		// not the inner instance.

		try {
			$dbw->doAtomicSection(
				__METHOD__,
				function ( IDatabase $unused, $fname ) use ( $name, $id, &$ok, $dbw ) {
					// Try to insert a row with the ID we originally got.
					// If that fails (because of a key conflict), we will just try to get another ID again later.
					$dbw->insert(
						$this->table,
						$this->getFieldsToStore( $name, $id ),
						$fname
					);

					// Make sure we re-load the map in case this gets rolled back again.
					// We could re-try once more, but that bears the risk of an infinite loop.
					// So let's just give up on the ID.
					$dbw->onAtomicSectionCancel(
						function ( $trigger, IDatabase $unused ) use ( $name, $id, $dbw ) {
							$this->logger->warning(
								'Re-insertion of name into table ' . $this->table
								. ' was rolled back. Giving up and reloading the cache.'
							);
							$this->reloadMap( ILoadBalancer::CONN_TRX_AUTOCOMMIT );
						},
						$fname
					);

					$this->logger->info(
						'Re-insert name into table ' . $this->table . ' after failed transaction.'
					);
				},
				IDatabase::ATOMIC_CANCELABLE
			);
		} catch ( Exception $ex ) {
			$this->logger->error(
				'Re-insertion of name into table ' . $this->table . ' failed: ' . $ex->getMessage()
			);
		} finally {
			// NOTE: we reload regardless of whether the above insert succeeded. There is
			// only three possibilities: the insert succeeded, so the new map will have
			// the desired $id/$name mapping. Or the insert failed because another
			// process already inserted that same $id/$name mapping, in which case the
			// new map will also have it. Or another process grabbed the desired ID for
			// another name, or the database refuses to insert the given ID into the
			// auto increment field - in that case, the new map will not have a mapping
			// for $name (or has a different mapping for $name). In that last case, we can
			// only hope that the ID produced within the failed transaction has not been
			// used outside that transaction.

			$this->reloadMap( ILoadBalancer::CONN_TRX_AUTOCOMMIT );
		}
	}

	/**
	 * @param string $name
	 * @param int|null $id
	 * @return array
	 */
	private function getFieldsToStore( $name, $id = null ) {
		$fields = [];

		$fields[$this->nameField] = $name;

		if ( $id !== null ) {
			$fields[$this->idField] = $id;
		}

		if ( $this->insertCallback !== null ) {
			$fields = call_user_func( $this->insertCallback, $fields );
		}
		return $fields;
	}

}
