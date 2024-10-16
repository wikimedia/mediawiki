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

use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * @since 1.31
 * @author Addshore
 */
class NameTableStore {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var WANObjectCache */
	private $cache;

	/** @var LoggerInterface */
	private $logger;

	/** @var array<int,string>|null */
	private $tableCache = null;

	/** @var bool|string */
	private $domain;

	/** @var int */
	private $cacheTTL;

	/** @var string */
	private $table;
	/** @var string */
	private $idField;
	/** @var string */
	private $nameField;
	/** @var null|callable */
	private $normalizationCallback;
	/** @var null|callable */
	private $insertCallback;

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
		?callable $normalizationCallback = null,
		$dbDomain = false,
		?callable $insertCallback = null
	) {
		$this->loadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->logger = $logger;
		$this->table = $table;
		$this->idField = $idField;
		$this->nameField = $nameField;
		$this->normalizationCallback = $normalizationCallback;
		$this->domain = $dbDomain;
		$this->cacheTTL = ExpirationAwareness::TTL_MONTH;
		$this->insertCallback = $insertCallback;
	}

	/**
	 * @param int $index A database index, like DB_PRIMARY or DB_REPLICA
	 * @param int $flags Database connection flags
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
	 * @note If called within an atomic section, there is a chance for the acquired ID to be
	 * lost on rollback. There is no guarantee that an ID returned by this method is valid
	 * outside the transaction in which it was produced. This means that calling code should
	 * not retain the return value beyond the scope of a transaction, but rather call acquireId()
	 * again after the transaction is complete. In some rare cases, this may produce an ID
	 * different from the first call.
	 *
	 * @param string $name
	 * @throws NameTableAccessException
	 * @return int
	 */
	public function acquireId( string $name ) {
		$name = $this->normalizeName( $name );

		$table = $this->getTableFromCachesOrReplica();
		$searchResult = array_search( $name, $table, true );
		if ( $searchResult === false ) {
			$id = $this->store( $name );

			if ( isset( $table[$id] ) ) {
				// This can happen when a name is assigned an ID within a transaction due to
				// CONN_TRX_AUTOCOMMIT being unable to use a separate connection (e.g. SQLite).
				// The right thing to do in this case is to discard the old value. According to
				// the contract of acquireId, the caller should not have used it outside the
				// transaction, so it should not be persisted anywhere after the rollback.
				$m = "Got ID $id for '$name' from insert"
					. " into '{$this->table}', but ID $id was previously associated with"
					. " the name '{$table[$id]}'. Overriding the old value, which presumably"
					. " has been removed from the database due to a transaction rollback.";
				$this->logger->warning( $m );
			}

			$table[$id] = $name;
			$searchResult = $id;

			$this->tableCache = $table;
		}

		return $searchResult;
	}

	/**
	 * Reloads the name table from the primary database, and purges the WAN cache entry.
	 *
	 * @note This should only be called in situations where the local cache has been detected
	 * to be out of sync with the database. There should be no reason to call this method
	 * from outside the NameTableStore during normal operation. This method may however be
	 * useful in unit tests.
	 *
	 * @param int $connFlags ILoadBalancer::CONN_XXX flags. Optional.
	 *
	 * @return string[] The freshly reloaded name map
	 */
	public function reloadMap( $connFlags = 0 ) {
		$dbw = $this->getDBConnection( DB_PRIMARY, $connFlags );
		$this->tableCache = $this->loadTable( $dbw );
		$dbw->onTransactionPreCommitOrIdle( function () {
			$this->cache->delete( $this->getCacheKey() );
		}, __METHOD__ );

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
	public function getId( string $name ) {
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
	 * Note: Calls to this method will result in a primary DB select for non existing IDs.
	 *
	 * @param int $id
	 * @throws NameTableAccessException The id does not exist
	 * @return string name
	 */
	public function getName( int $id ) {
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
				// Regenerate from replica DB, and primary DB if needed
				foreach ( [ DB_REPLICA, DB_PRIMARY ] as $source ) {
					// Log a fallback to primary
					if ( $source === DB_PRIMARY ) {
						$this->logger->info(
							$fname . ' falling back to primary select from ' .
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
	 * @return array<int,string>
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
	 * @param IReadableDatabase $db
	 * @return array<int,string>
	 */
	private function loadTable( IReadableDatabase $db ) {
		$result = $db->newSelectQueryBuilder()
			->select( [
				'id' => $this->idField,
				'name' => $this->nameField
			] )
			->from( $this->table )
			->orderBy( 'id' )
			->caller( __METHOD__ )->fetchResultSet();

		$assocArray = [];
		foreach ( $result as $row ) {
			$assocArray[(int)$row->id] = $row->name;
		}

		return $assocArray;
	}

	/**
	 * Stores the given name in the DB, returning the ID when an insert occurs.
	 *
	 * @param string $name
	 * @return int The new or colliding ID
	 */
	private function store( string $name ) {
		Assert::parameter( $name !== '', '$name', 'should not be an empty string' );
		// Note: this is only called internally so normalization of $name has already occurred.

		$dbw = $this->getDBConnection( DB_PRIMARY, ILoadBalancer::CONN_TRX_AUTOCOMMIT );

		$dbw->newInsertQueryBuilder()
			->insertInto( $this->table )
			->ignore()
			->row( $this->getFieldsToStore( $name ) )
			->caller( __METHOD__ )->execute();

		if ( $dbw->affectedRows() > 0 ) {
			$id = $dbw->insertId();
			// As store returned an ID we know we inserted so delete from WAN cache
			$dbw->onTransactionPreCommitOrIdle(
				function () {
					$this->cache->delete( $this->getCacheKey() );
				},
				__METHOD__
			);

			return $id;
		}

		$this->logger->info(
			'Tried to insert name into table ' . $this->table . ', but value already existed.'
		);

		// Note that in MySQL, even if this method somehow runs in a transaction, a plain
		// (non-locking) SELECT will see the new row created by the other transaction, even
		// with REPEATABLE-READ. This is due to how "consistent reads" works: the latest
		// version of rows become visible to the snapshot after the transaction sees those
		// rows as either matching an update query or conflicting with an insert query.
		$id = $dbw->newSelectQueryBuilder()
			->select( [ 'id' => $this->idField ] )
			->from( $this->table )
			->where( [ $this->nameField => $name ] )
			->caller( __METHOD__ )->fetchField();

		if ( $id === false ) {
			// Insert failed due to IGNORE flag, but DB_PRIMARY didn't give us the data
			$m = "No insert possible but primary DB didn't give us a record for " .
				"'{$name}' in '{$this->table}'";
			$this->logger->error( $m );
			throw new NameTableAccessException( $m );
		}

		return (int)$id;
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
