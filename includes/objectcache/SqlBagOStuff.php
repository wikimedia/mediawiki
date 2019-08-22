<?php
/**
 * Object caching using a SQL database.
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
 * @ingroup Cache
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\ScopedCallback;
use Wikimedia\WaitConditionLoop;

/**
 * Class to store objects in the database
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends MediumSpecificBagOStuff {
	/** @var array[] (server index => server config) */
	protected $serverInfos;
	/** @var string[] (server index => tag/host name) */
	protected $serverTags;
	/** @var int */
	protected $numServers;
	/** @var int UNIX timestamp */
	protected $lastGarbageCollect = 0;
	/** @var int */
	protected $purgePeriod = 10;
	/** @var int */
	protected $purgeLimit = 100;
	/** @var int */
	protected $shards = 1;
	/** @var string */
	protected $tableName = 'objectcache';
	/** @var bool */
	protected $replicaOnly = false;

	/** @var LoadBalancer|null */
	protected $separateMainLB;
	/** @var array */
	protected $conns;
	/** @var array UNIX timestamps */
	protected $connFailureTimes = [];
	/** @var array Exceptions */
	protected $connFailureErrors = [];

	/** @var int */
	private static $GC_DELAY_SEC = 1;

	/** @var string */
	private static $OP_SET = 'set';
	/** @var string */
	private static $OP_ADD = 'add';
	/** @var string */
	private static $OP_TOUCH = 'touch';
	/** @var string */
	private static $OP_DELETE = 'delete';

	/**
	 * Constructor. Parameters are:
	 *   - server:      A server info structure in the format required by each
	 *                  element in $wgDBServers.
	 *
	 *   - servers:     An array of server info structures describing a set of database servers
	 *                  to distribute keys to. If this is specified, the "server" option will be
	 *                  ignored. If string keys are used, then they will be used for consistent
	 *                  hashing *instead* of the host name (from the server config). This is useful
	 *                  when a cluster is replicated to another site (with different host names)
	 *                  but each server has a corresponding replica in the other cluster.
	 *
	 *   - purgePeriod: The average number of object cache writes in between
	 *                  garbage collection operations, where expired entries
	 *                  are removed from the database. Or in other words, the
	 *                  reciprocal of the probability of purging on any given
	 *                  write. If this is set to zero, purging will never be done.
	 *
	 *   - purgeLimit:  Maximum number of rows to purge at once.
	 *
	 *   - tableName:   The table name to use, default is "objectcache".
	 *
	 *   - shards:      The number of tables to use for data storage on each server.
	 *                  If this is more than 1, table names will be formed in the style
	 *                  objectcacheNNN where NNN is the shard index, between 0 and
	 *                  shards-1. The number of digits will be the minimum number
	 *                  required to hold the largest shard index. Data will be
	 *                  distributed across all tables by key hash. This is for
	 *                  MySQL bugs 61735 <https://bugs.mysql.com/bug.php?id=61735>
	 *                  and 61736 <https://bugs.mysql.com/bug.php?id=61736>.
	 *
	 *   - replicaOnly: Whether to only use replica DBs and avoid triggering
	 *                  garbage collection logic of expired items. This only
	 *                  makes sense if the primary DB is used and only if get()
	 *                  calls will be used. This is used by ReplicatedBagOStuff.
	 *   - syncTimeout: Max seconds to wait for replica DBs to catch up for WRITE_SYNC.
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->attrMap[self::ATTR_EMULATION] = self::QOS_EMULATION_SQL;
		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_NONE;

		if ( isset( $params['servers'] ) ) {
			$this->serverInfos = [];
			$this->serverTags = [];
			$this->numServers = count( $params['servers'] );
			$index = 0;
			foreach ( $params['servers'] as $tag => $info ) {
				$this->serverInfos[$index] = $info;
				if ( is_string( $tag ) ) {
					$this->serverTags[$index] = $tag;
				} else {
					$this->serverTags[$index] = $info['host'] ?? "#$index";
				}
				++$index;
			}
		} elseif ( isset( $params['server'] ) ) {
			$this->serverInfos = [ $params['server'] ];
			$this->numServers = count( $this->serverInfos );
		} else {
			// Default to using the main wiki's database servers
			$this->serverInfos = false;
			$this->numServers = 1;
			$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_BE;
		}
		if ( isset( $params['purgePeriod'] ) ) {
			$this->purgePeriod = intval( $params['purgePeriod'] );
		}
		if ( isset( $params['purgeLimit'] ) ) {
			$this->purgeLimit = intval( $params['purgeLimit'] );
		}
		if ( isset( $params['tableName'] ) ) {
			$this->tableName = $params['tableName'];
		}
		if ( isset( $params['shards'] ) ) {
			$this->shards = intval( $params['shards'] );
		}
		// Backwards-compatibility for < 1.34
		$this->replicaOnly = $params['replicaOnly'] ?? ( $params['slaveOnly'] ?? false );
	}

	/**
	 * Get a connection to the specified database
	 *
	 * @param int $serverIndex
	 * @return IMaintainableDatabase
	 * @throws MWException
	 */
	protected function getDB( $serverIndex ) {
		if ( $serverIndex >= $this->numServers ) {
			throw new MWException( __METHOD__ . ": Invalid server index \"$serverIndex\"" );
		}

		# Don't keep timing out trying to connect for each call if the DB is down
		if (
			isset( $this->connFailureErrors[$serverIndex] ) &&
			( $this->getCurrentTime() - $this->connFailureTimes[$serverIndex] ) < 60
		) {
			throw $this->connFailureErrors[$serverIndex];
		}

		if ( $this->serverInfos ) {
			if ( !isset( $this->conns[$serverIndex] ) ) {
				// Use custom database defined by server connection info
				$info = $this->serverInfos[$serverIndex];
				$type = $info['type'] ?? 'mysql';
				$host = $info['host'] ?? '[unknown]';
				$this->logger->debug( __CLASS__ . ": connecting to $host" );
				$db = Database::factory( $type, $info );
				$db->clearFlag( DBO_TRX ); // auto-commit mode
				$this->conns[$serverIndex] = $db;
			}
			$db = $this->conns[$serverIndex];
		} else {
			// Use the main LB database
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$index = $this->replicaOnly ? DB_REPLICA : DB_MASTER;
			if ( $lb->getServerType( $lb->getWriterIndex() ) !== 'sqlite' ) {
				// Keep a separate connection to avoid contention and deadlocks
				$db = $lb->getConnectionRef( $index, [], false, $lb::CONN_TRX_AUTOCOMMIT );
			} else {
				// However, SQLite has the opposite behavior due to DB-level locking.
				// Stock sqlite MediaWiki installs use a separate sqlite cache DB instead.
				$db = $lb->getConnectionRef( $index );
			}
		}

		$this->logger->debug( sprintf( "Connection %s will be used for SqlBagOStuff", $db ) );

		return $db;
	}

	/**
	 * Get the server index and table name for a given key
	 * @param string $key
	 * @return array Server index and table name
	 */
	protected function getTableByKey( $key ) {
		if ( $this->shards > 1 ) {
			$hash = hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
			$tableIndex = $hash % $this->shards;
		} else {
			$tableIndex = 0;
		}
		if ( $this->numServers > 1 ) {
			$sortedServers = $this->serverTags;
			ArrayUtils::consistentHashSort( $sortedServers, $key );
			reset( $sortedServers );
			$serverIndex = key( $sortedServers );
		} else {
			$serverIndex = 0;
		}
		return [ $serverIndex, $this->getTableNameByShard( $tableIndex ) ];
	}

	/**
	 * Get the table name for a given shard index
	 * @param int $index
	 * @return string
	 */
	protected function getTableNameByShard( $index ) {
		if ( $this->shards > 1 ) {
			$decimals = strlen( $this->shards - 1 );
			return $this->tableName .
				sprintf( "%0{$decimals}d", $index );
		} else {
			return $this->tableName;
		}
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		$blobs = $this->fetchBlobMulti( [ $key ] );
		if ( array_key_exists( $key, $blobs ) ) {
			$blob = $blobs[$key];
			$value = $this->unserialize( $blob );

			$casToken = ( $value !== false ) ? $blob : null;

			return $value;
		}

		return false;
	}

	protected function doGetMulti( array $keys, $flags = 0 ) {
		$values = [];

		$blobs = $this->fetchBlobMulti( $keys );
		foreach ( $blobs as $key => $blob ) {
			$values[$key] = $this->unserialize( $blob );
		}

		return $values;
	}

	protected function fetchBlobMulti( array $keys, $flags = 0 ) {
		$values = []; // array of (key => value)

		$keysByTable = [];
		foreach ( $keys as $key ) {
			list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$serverIndex][$tableName][] = $key;
		}

		$dataRows = [];
		foreach ( $keysByTable as $serverIndex => $serverKeys ) {
			try {
				$db = $this->getDB( $serverIndex );
				foreach ( $serverKeys as $tableName => $tableKeys ) {
					$res = $db->select( $tableName,
						[ 'keyname', 'value', 'exptime' ],
						[ 'keyname' => $tableKeys ],
						__METHOD__,
						// Approximate write-on-the-fly BagOStuff API via blocking.
						// This approximation fails if a ROLLBACK happens (which is rare).
						// We do not want to flush the TRX as that can break callers.
						$db->trxLevel() ? [ 'LOCK IN SHARE MODE' ] : []
					);
					if ( $res === false ) {
						continue;
					}
					foreach ( $res as $row ) {
						$row->serverIndex = $serverIndex;
						$row->tableName = $tableName;
						$dataRows[$row->keyname] = $row;
					}
				}
			} catch ( DBError $e ) {
				$this->handleReadError( $e, $serverIndex );
			}
		}

		foreach ( $keys as $key ) {
			if ( isset( $dataRows[$key] ) ) { // HIT?
				$row = $dataRows[$key];
				$this->debug( "get: retrieved data; expiry time is " . $row->exptime );
				$db = null; // in case of connection failure
				try {
					$db = $this->getDB( $row->serverIndex );
					if ( $this->isExpired( $db, $row->exptime ) ) { // MISS
						$this->debug( "get: key has expired" );
					} else { // HIT
						$values[$key] = $db->decodeBlob( $row->value );
					}
				} catch ( DBQueryError $e ) {
					$this->handleWriteError( $e, $db, $row->serverIndex );
				}
			} else { // MISS
				$this->debug( 'get: no matching rows' );
			}
		}

		return $values;
	}

	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		return $this->modifyMulti( $data, $exptime, $flags, self::$OP_SET );
	}

	/**
	 * @param mixed[]|null[] $data Map of (key => new value or null)
	 * @param int $exptime UNIX timestamp, TTL in seconds, or 0 (no expiration)
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @param string $op Cache operation
	 * @return bool
	 */
	private function modifyMulti( array $data, $exptime, $flags, $op ) {
		$keysByTable = [];
		foreach ( $data as $key => $value ) {
			list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$serverIndex][$tableName][] = $key;
		}

		$exptime = $this->getExpirationAsTimestamp( $exptime );

		$result = true;
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		foreach ( $keysByTable as $serverIndex => $serverKeys ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getDB( $serverIndex );
				$this->occasionallyGarbageCollect( $db ); // expire old entries if any
				$dbExpiry = $exptime ? $db->timestamp( $exptime ) : $this->getMaxDateTime( $db );
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
				$result = false;
				continue;
			}

			foreach ( $serverKeys as $tableName => $tableKeys ) {
				try {
					$result = $this->updateTableKeys(
						$op,
						$db,
						$tableName,
						$tableKeys,
						$data,
						$dbExpiry
					) && $result;
				} catch ( DBError $e ) {
					$this->handleWriteError( $e, $db, $serverIndex );
					$result = false;
				}

			}
		}

		if ( $this->fieldHasFlags( $flags, self::WRITE_SYNC ) ) {
			$result = $this->waitForReplication() && $result;
		}

		return $result;
	}

	/**
	 * @param string $op
	 * @param IDatabase $db
	 * @param string $table
	 * @param string[] $tableKeys Keys in $data to update
	 * @param mixed[]|null[] $data Map of (key => new value or null)
	 * @param string $dbExpiry DB-encoded expiry
	 * @return bool
	 * @throws DBError
	 * @throws InvalidArgumentException
	 */
	private function updateTableKeys( $op, $db, $table, $tableKeys, $data, $dbExpiry ) {
		$success = true;

		if ( $op === self::$OP_ADD ) {
			$rows = [];
			foreach ( $tableKeys as $key ) {
				$rows[] = [
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $data[$key] ) ),
					'exptime' => $dbExpiry
				];
			}
			$db->delete(
				$table,
				[
					'keyname' => $tableKeys,
					'exptime <= ' . $db->addQuotes( $db->timestamp() )
				],
				__METHOD__
			);
			$db->insert( $table, $rows, __METHOD__, [ 'IGNORE' ] );

			$success = ( $db->affectedRows() == count( $rows ) );
		} elseif ( $op === self::$OP_SET ) {
			$rows = [];
			foreach ( $tableKeys as $key ) {
				$rows[] = [
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $data[$key] ) ),
					'exptime' => $dbExpiry
				];
			}
			$db->replace( $table, [ 'keyname' ], $rows, __METHOD__ );
		} elseif ( $op === self::$OP_DELETE ) {
			$db->delete( $table, [ 'keyname' => $tableKeys ], __METHOD__ );
		} elseif ( $op === self::$OP_TOUCH ) {
			$db->update(
				$table,
				[ 'exptime' => $dbExpiry ],
				[
					'keyname' => $tableKeys,
					'exptime > ' . $db->addQuotes( $db->timestamp() )
				],
				__METHOD__
			);

			$success = ( $db->affectedRows() == count( $tableKeys ) );
		} else {
			throw new InvalidArgumentException( "Invalid operation '$op'" );
		}

		return $success;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->modifyMulti( [ $key => $value ], $exptime, $flags, self::$OP_SET );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->modifyMulti( [ $key => $value ], $exptime, $flags, self::$OP_ADD );
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		$exptime = $this->getExpirationAsTimestamp( $exptime );

		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		$db = null; // in case of connection failure
		try {
			$db = $this->getDB( $serverIndex );
			// (T26425) use a replace if the db supports it instead of
			// delete/insert to avoid clashes with conflicting keynames
			$db->update(
				$tableName,
				[
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $value ) ),
					'exptime' => $exptime
						? $db->timestamp( $exptime )
						: $this->getMaxDateTime( $db )
				],
				[
					'keyname' => $key,
					'value' => $db->encodeBlob( $casToken ),
					'exptime > ' . $db->addQuotes( $db->timestamp() )
				],
				__METHOD__
			);
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );

			return false;
		}

		return (bool)$db->affectedRows();
	}

	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		return $this->modifyMulti(
			array_fill_keys( $keys, null ),
			0,
			$flags,
			self::$OP_DELETE
		);
	}

	protected function doDelete( $key, $flags = 0 ) {
		return $this->modifyMulti( [ $key => null ], 0, $flags, self::$OP_DELETE );
	}

	public function incr( $key, $step = 1 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );

		$newCount = false;
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		$db = null; // in case of connection failure
		try {
			$db = $this->getDB( $serverIndex );
			$encTimestamp = $db->addQuotes( $db->timestamp() );
			$db->update(
				$tableName,
				[ 'value = value + ' . (int)$step ],
				[ 'keyname' => $key, "exptime > $encTimestamp" ],
				__METHOD__
			);
			if ( $db->affectedRows() > 0 ) {
				$newValue = $db->selectField(
					$tableName,
					'value',
					[ 'keyname' => $key, "exptime > $encTimestamp" ],
					__METHOD__
				);
				if ( $this->isInteger( $newValue ) ) {
					$newCount = (int)$newValue;
				}
			}
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );
		}

		return $newCount;
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$ok = $this->mergeViaCas( $key, $callback, $exptime, $attempts, $flags );
		if ( $this->fieldHasFlags( $flags, self::WRITE_SYNC ) ) {
			$ok = $this->waitForReplication() && $ok;
		}

		return $ok;
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		return $this->modifyMulti(
			array_fill_keys( $keys, null ),
			$exptime,
			$flags,
			self::$OP_TOUCH
		);
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		return $this->modifyMulti( [ $key => null ], $exptime, $flags, self::$OP_TOUCH );
	}

	/**
	 * @param IDatabase $db
	 * @param string $exptime
	 * @return bool
	 */
	protected function isExpired( $db, $exptime ) {
		return (
			$exptime != $this->getMaxDateTime( $db ) &&
			wfTimestamp( TS_UNIX, $exptime ) < $this->getCurrentTime()
		);
	}

	/**
	 * @param IDatabase $db
	 * @return string
	 */
	protected function getMaxDateTime( $db ) {
		if ( (int)$this->getCurrentTime() > 0x7fffffff ) {
			return $db->timestamp( 1 << 62 );
		} else {
			return $db->timestamp( 0x7fffffff );
		}
	}

	/**
	 * @param IDatabase $db
	 * @throws DBError
	 */
	protected function occasionallyGarbageCollect( IDatabase $db ) {
		if (
			// Random purging is enabled
			$this->purgePeriod &&
			// Only purge on one in every $this->purgePeriod writes
			mt_rand( 0, $this->purgePeriod - 1 ) == 0 &&
			// Avoid repeating the delete within a few seconds
			( $this->getCurrentTime() - $this->lastGarbageCollect ) > self::$GC_DELAY_SEC
		) {
			$garbageCollector = function () use ( $db ) {
				$this->deleteServerObjectsExpiringBefore(
					$db, $this->getCurrentTime(),
					null,
					$this->purgeLimit
				);
				$this->lastGarbageCollect = time();
			};
			if ( $this->asyncHandler ) {
				$this->lastGarbageCollect = $this->getCurrentTime(); // avoid duplicate enqueues
				( $this->asyncHandler )( $garbageCollector );
			} else {
				$garbageCollector();
			}
		}
	}

	public function expireAll() {
		$this->deleteObjectsExpiringBefore( $this->getCurrentTime() );
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF
	) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();

		$serverIndexes = range( 0, $this->numServers - 1 );
		shuffle( $serverIndexes );

		$ok = true;

		$keysDeletedCount = 0;
		foreach ( $serverIndexes as $numServersDone => $serverIndex ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getDB( $serverIndex );
				$this->deleteServerObjectsExpiringBefore(
					$db,
					$timestamp,
					$progress,
					$limit,
					$numServersDone,
					$keysDeletedCount
				);
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * @param IDatabase $db
	 * @param string|int $timestamp
	 * @param callable|null $progressCallback
	 * @param int $limit
	 * @param int $serversDoneCount
	 * @param int &$keysDeletedCount
	 * @throws DBError
	 */
	private function deleteServerObjectsExpiringBefore(
		IDatabase $db,
		$timestamp,
		$progressCallback,
		$limit,
		$serversDoneCount = 0,
		&$keysDeletedCount = 0
	) {
		$cutoffUnix = wfTimestamp( TS_UNIX, $timestamp );
		$shardIndexes = range( 0, $this->shards - 1 );
		shuffle( $shardIndexes );

		foreach ( $shardIndexes as $numShardsDone => $shardIndex ) {
			$continue = null; // last exptime
			$lag = null; // purge lag
			do {
				$res = $db->select(
					$this->getTableNameByShard( $shardIndex ),
					[ 'keyname', 'exptime' ],
					array_merge(
						[ 'exptime < ' . $db->addQuotes( $db->timestamp( $cutoffUnix ) ) ],
						$continue ? [ 'exptime >= ' . $db->addQuotes( $continue ) ] : []
					),
					__METHOD__,
					[ 'LIMIT' => min( $limit, 100 ), 'ORDER BY' => 'exptime' ]
				);

				if ( $res->numRows() ) {
					$row = $res->current();
					if ( $lag === null ) {
						$lag = max( $cutoffUnix - wfTimestamp( TS_UNIX, $row->exptime ), 1 );
					}

					$keys = [];
					foreach ( $res as $row ) {
						$keys[] = $row->keyname;
						$continue = $row->exptime;
					}

					$db->delete(
						$this->getTableNameByShard( $shardIndex ),
						[
							'exptime < ' . $db->addQuotes( $db->timestamp( $cutoffUnix ) ),
							'keyname' => $keys
						],
						__METHOD__
					);
					$keysDeletedCount += $db->affectedRows();
				}

				if ( is_callable( $progressCallback ) ) {
					if ( $lag ) {
						$remainingLag = $cutoffUnix - wfTimestamp( TS_UNIX, $continue );
						$processedLag = max( $lag - $remainingLag, 0 );
						$doneRatio = ( $numShardsDone + $processedLag / $lag ) / $this->shards;
					} else {
						$doneRatio = 1;
					}

					$overallRatio = ( $doneRatio / $this->numServers )
						+ ( $serversDoneCount / $this->numServers );
					call_user_func( $progressCallback, $overallRatio * 100 );
				}
			} while ( $res->numRows() && $keysDeletedCount < $limit );
		}
	}

	/**
	 * Delete content of shard tables in every server.
	 * Return true if the operation is successful, false otherwise.
	 * @return bool
	 */
	public function deleteAll() {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getDB( $serverIndex );
				for ( $i = 0; $i < $this->shards; $i++ ) {
					$db->delete( $this->getTableNameByShard( $i ), '*', __METHOD__ );
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
				return false;
			}
		}
		return true;
	}

	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		// Avoid deadlocks and allow lock reentry if specified
		if ( isset( $this->locks[$key] ) ) {
			if ( $rclass != '' && $this->locks[$key]['class'] === $rclass ) {
				++$this->locks[$key]['depth'];
				return true;
			} else {
				return false;
			}
		}

		list( $serverIndex ) = $this->getTableByKey( $key );

		$db = null; // in case of connection failure
		try {
			$db = $this->getDB( $serverIndex );
			$ok = $db->lock( $key, __METHOD__, $timeout );
			if ( $ok ) {
				$this->locks[$key] = [ 'class' => $rclass, 'depth' => 1 ];
			}

			$this->logger->warning(
				__METHOD__ . " failed due to timeout for {key}.",
				[ 'key' => $key, 'timeout' => $timeout ]
			);

			return $ok;
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );
			$ok = false;
		}

		return $ok;
	}

	public function unlock( $key ) {
		if ( !isset( $this->locks[$key] ) ) {
			return false;
		}

		if ( --$this->locks[$key]['depth'] <= 0 ) {
			unset( $this->locks[$key] );

			list( $serverIndex ) = $this->getTableByKey( $key );

			$db = null; // in case of connection failure
			try {
				$db = $this->getDB( $serverIndex );
				$ok = $db->unlock( $key, __METHOD__ );
				if ( !$ok ) {
					$this->logger->warning(
						__METHOD__ . ' failed to release lock for {key}.',
						[ 'key' => $key ]
					);
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
				$ok = false;
			}

			return $ok;
		}

		return true;
	}

	/**
	 * Serialize an object and, if possible, compress the representation.
	 * On typical message and page data, this can provide a 3X decrease
	 * in storage requirements.
	 *
	 * @param mixed $data
	 * @return string|int
	 */
	protected function serialize( $data ) {
		if ( is_int( $data ) ) {
			return $data;
		}

		$serial = serialize( $data );
		if ( function_exists( 'gzdeflate' ) ) {
			$serial = gzdeflate( $serial );
		}

		return $serial;
	}

	/**
	 * Unserialize and, if necessary, decompress an object.
	 * @param string $serial
	 * @return mixed
	 */
	protected function unserialize( $serial ) {
		if ( $this->isInteger( $serial ) ) {
			return (int)$serial;
		}

		if ( function_exists( 'gzinflate' ) ) {
			Wikimedia\suppressWarnings();
			$decomp = gzinflate( $serial );
			Wikimedia\restoreWarnings();

			if ( $decomp !== false ) {
				$serial = $decomp;
			}
		}

		return unserialize( $serial );
	}

	/**
	 * Handle a DBError which occurred during a read operation.
	 *
	 * @param DBError $exception
	 * @param int $serverIndex
	 */
	protected function handleReadError( DBError $exception, $serverIndex ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->markServerDown( $exception, $serverIndex );
		}

		$this->setAndLogDBError( $exception );
	}

	/**
	 * Handle a DBQueryError which occurred during a write operation.
	 *
	 * @param DBError $exception
	 * @param IDatabase|null $db DB handle or null if connection failed
	 * @param int $serverIndex
	 * @throws Exception
	 */
	protected function handleWriteError( DBError $exception, $db, $serverIndex ) {
		if ( !( $db instanceof IDatabase ) ) {
			$this->markServerDown( $exception, $serverIndex );
		}

		$this->setAndLogDBError( $exception );
	}

	/**
	 * @param DBError $exception
	 */
	private function setAndLogDBError( DBError $exception ) {
		$this->logger->error( "DBError: {$exception->getMessage()}" );
		if ( $exception instanceof DBConnectionError ) {
			$this->setLastError( BagOStuff::ERR_UNREACHABLE );
			$this->logger->debug( __METHOD__ . ": ignoring connection error" );
		} else {
			$this->setLastError( BagOStuff::ERR_UNEXPECTED );
			$this->logger->debug( __METHOD__ . ": ignoring query error" );
		}
	}

	/**
	 * Mark a server down due to a DBConnectionError exception
	 *
	 * @param DBError $exception
	 * @param int $serverIndex
	 */
	protected function markServerDown( DBError $exception, $serverIndex ) {
		unset( $this->conns[$serverIndex] ); // bug T103435

		$now = $this->getCurrentTime();
		if ( isset( $this->connFailureTimes[$serverIndex] ) ) {
			if ( $now - $this->connFailureTimes[$serverIndex] >= 60 ) {
				unset( $this->connFailureTimes[$serverIndex] );
				unset( $this->connFailureErrors[$serverIndex] );
			} else {
				$this->logger->debug( __METHOD__ . ": Server #$serverIndex already down" );
				return;
			}
		}
		$this->logger->info( __METHOD__ . ": Server #$serverIndex down until " . ( $now + 60 ) );
		$this->connFailureTimes[$serverIndex] = $now;
		$this->connFailureErrors[$serverIndex] = $exception;
	}

	/**
	 * Create shard tables. For use from eval.php.
	 */
	public function createTables() {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			$db = $this->getDB( $serverIndex );
			if ( $db->getType() !== 'mysql' ) {
				throw new MWException( __METHOD__ . ' is not supported on this DB server' );
			}

			for ( $i = 0; $i < $this->shards; $i++ ) {
				$db->query(
					'CREATE TABLE ' . $db->tableName( $this->getTableNameByShard( $i ) ) .
					' LIKE ' . $db->tableName( 'objectcache' ),
					__METHOD__ );
			}
		}
	}

	/**
	 * @return bool Whether the main DB is used, e.g. wfGetDB( DB_MASTER )
	 */
	protected function usesMainDB() {
		return !$this->serverInfos;
	}

	protected function waitForReplication() {
		if ( !$this->usesMainDB() ) {
			// Custom DB server list; probably doesn't use replication
			return true;
		}

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		if ( $lb->getServerCount() <= 1 ) {
			return true; // no replica DBs
		}

		// Main LB is used; wait for any replica DBs to catch up
		try {
			$masterPos = $lb->getMasterPos();
			if ( !$masterPos ) {
				return true; // not applicable
			}

			$loop = new WaitConditionLoop(
				function () use ( $lb, $masterPos ) {
					return $lb->waitForAll( $masterPos, 1 );
				},
				$this->syncTimeout,
				$this->busyCallbacks
			);

			return ( $loop->invoke() === $loop::CONDITION_REACHED );
		} catch ( DBError $e ) {
			$this->setAndLogDBError( $e );

			return false;
		}
	}

	/**
	 * Returns a ScopedCallback which resets the silence flag in the transaction profiler when it is
	 * destroyed on the end of a scope, for example on return or throw
	 * @return ScopedCallback
	 * @since 1.32
	 */
	protected function silenceTransactionProfiler() {
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$oldSilenced = $trxProfiler->setSilenced( true );
		return new ScopedCallback( function () use ( $trxProfiler, $oldSilenced ) {
			$trxProfiler->setSilenced( $oldSilenced );
		} );
	}
}
