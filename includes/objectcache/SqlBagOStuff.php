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
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\ScopedCallback;
use Wikimedia\Timestamp\ConvertibleTimestamp;
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
	protected $numServerShards;
	/** @var int UNIX timestamp */
	protected $lastGarbageCollect = 0;
	/** @var int */
	protected $purgePeriod = 10;
	/** @var int */
	protected $purgeLimit = 100;
	/** @var int */
	protected $numTableShards = 1;
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
			$this->numServerShards = count( $params['servers'] );
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
			$this->numServerShards = count( $this->serverInfos );
		} else {
			// Default to using the main wiki's database servers
			$this->serverInfos = [];
			$this->numServerShards = 1;
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
			$this->numTableShards = intval( $params['shards'] );
		}
		// Backwards-compatibility for < 1.34
		$this->replicaOnly = $params['replicaOnly'] ?? ( $params['slaveOnly'] ?? false );
	}

	/**
	 * Get a connection to the specified database
	 *
	 * @param int $shardIndex
	 * @return IMaintainableDatabase
	 * @throws MWException
	 */
	private function getConnection( $shardIndex ) {
		if ( $shardIndex >= $this->numServerShards ) {
			throw new MWException( __METHOD__ . ": Invalid server index \"$shardIndex\"" );
		}

		# Don't keep timing out trying to connect for each call if the DB is down
		if (
			isset( $this->connFailureErrors[$shardIndex] ) &&
			( $this->getCurrentTime() - $this->connFailureTimes[$shardIndex] ) < 60
		) {
			throw $this->connFailureErrors[$shardIndex];
		}

		if ( $this->serverInfos ) {
			if ( !isset( $this->conns[$shardIndex] ) ) {
				// Use custom database defined by server connection info
				$info = $this->serverInfos[$shardIndex];
				$type = $info['type'] ?? 'mysql';
				$host = $info['host'] ?? '[unknown]';
				$this->logger->debug( __CLASS__ . ": connecting to $host" );
				$conn = Database::factory( $type, $info );
				$conn->clearFlag( DBO_TRX ); // auto-commit mode
				$this->conns[$shardIndex] = $conn;
				// Automatically create the objectcache table for sqlite as needed
				if ( $conn->getType() === 'sqlite' ) {
					$this->initSqliteDatabase( $conn );
				}
			}
			$conn = $this->conns[$shardIndex];
		} else {
			// Use the main LB database
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$index = $this->replicaOnly ? DB_REPLICA : DB_MASTER;
			// If the RDBMS has row-level locking, use the autocommit connection to avoid
			// contention and deadlocks. Do not do this if it only has DB-level locking since
			// that would just cause deadlocks.
			$attribs = $lb->getServerAttributes( $lb->getWriterIndex() );
			$flags = $attribs[Database::ATTR_DB_LEVEL_LOCKING] ? 0 : $lb::CONN_TRX_AUTOCOMMIT;
			$conn = $lb->getMaintenanceConnectionRef( $index, [], false, $flags );
		}

		$this->logger->debug( sprintf( "Connection %s will be used for SqlBagOStuff", $conn ) );

		return $conn;
	}

	/**
	 * Get the server index and table name for a given key
	 * @param string $key
	 * @return array Server index and table name
	 */
	private function getTableByKey( $key ) {
		if ( $this->numTableShards > 1 ) {
			$hash = hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
			$tableIndex = $hash % $this->numTableShards;
		} else {
			$tableIndex = 0;
		}
		if ( $this->numServerShards > 1 ) {
			$sortedServers = $this->serverTags;
			ArrayUtils::consistentHashSort( $sortedServers, $key );
			reset( $sortedServers );
			$shardIndex = key( $sortedServers );
		} else {
			$shardIndex = 0;
		}
		return [ $shardIndex, $this->getTableNameByShard( $tableIndex ) ];
	}

	/**
	 * Get the table name for a given shard index
	 * @param int $index
	 * @return string
	 */
	private function getTableNameByShard( $index ) {
		if ( $this->numTableShards > 1 ) {
			$decimals = strlen( $this->numTableShards - 1 );
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

	private function fetchBlobMulti( array $keys, $flags = 0 ) {
		$values = []; // array of (key => value)

		$keysByTable = [];
		foreach ( $keys as $key ) {
			list( $shardIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$shardIndex][$tableName][] = $key;
		}

		$dataRows = [];
		foreach ( $keysByTable as $shardIndex => $serverKeys ) {
			try {
				$db = $this->getConnection( $shardIndex );
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
						$row->shardIndex = $shardIndex;
						$row->tableName = $tableName;
						$dataRows[$row->keyname] = $row;
					}
				}
			} catch ( DBError $e ) {
				$this->handleReadError( $e, $shardIndex );
			}
		}

		foreach ( $keys as $key ) {
			if ( isset( $dataRows[$key] ) ) { // HIT?
				$row = $dataRows[$key];
				$this->debug( "get: retrieved data; expiry time is " . $row->exptime );
				$db = null; // in case of connection failure
				try {
					$db = $this->getConnection( $row->shardIndex );
					if ( $this->isExpired( $db, $row->exptime ) ) { // MISS
						$this->debug( "get: key has expired" );
					} else { // HIT
						$values[$key] = $db->decodeBlob( $row->value );
					}
				} catch ( DBQueryError $e ) {
					$this->handleWriteError( $e, $db, $row->shardIndex );
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
			list( $shardIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$shardIndex][$tableName][] = $key;
		}

		$exptime = $this->getExpirationAsTimestamp( $exptime );

		$result = true;
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		foreach ( $keysByTable as $shardIndex => $serverKeys ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				$this->occasionallyGarbageCollect( $db ); // expire old entries if any
				$dbExpiry = $exptime ? $db->timestamp( $exptime ) : $this->getMaxDateTime( $db );
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $shardIndex );
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
					$this->handleWriteError( $e, $db, $shardIndex );
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
		list( $shardIndex, $tableName ) = $this->getTableByKey( $key );
		$exptime = $this->getExpirationAsTimestamp( $exptime );

		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		$db = null; // in case of connection failure
		try {
			$db = $this->getConnection( $shardIndex );
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
			$this->handleWriteError( $e, $db, $shardIndex );

			return false;
		}

		$success = (bool)$db->affectedRows();
		if ( $this->fieldHasFlags( $flags, self::WRITE_SYNC ) ) {
			$success = $this->waitForReplication() && $success;
		}

		return $success;
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

	public function incr( $key, $step = 1, $flags = 0 ) {
		list( $shardIndex, $tableName ) = $this->getTableByKey( $key );

		$newCount = false;
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		$db = null; // in case of connection failure
		try {
			$db = $this->getConnection( $shardIndex );
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
			$this->handleWriteError( $e, $db, $shardIndex );
		}

		return $newCount;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		return $this->incr( $key, -$value, $flags );
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
	private function isExpired( IDatabase $db, $exptime ) {
		return (
			$exptime != $this->getMaxDateTime( $db ) &&
			ConvertibleTimestamp::convert( TS_UNIX, $exptime ) < $this->getCurrentTime()
		);
	}

	/**
	 * @param IDatabase $db
	 * @return string
	 */
	private function getMaxDateTime( $db ) {
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
	private function occasionallyGarbageCollect( IDatabase $db ) {
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

		$shardIndexes = range( 0, $this->numServerShards - 1 );
		shuffle( $shardIndexes );

		$ok = true;

		$keysDeletedCount = 0;
		foreach ( $shardIndexes as $numServersDone => $shardIndex ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				$this->deleteServerObjectsExpiringBefore(
					$db,
					$timestamp,
					$progress,
					$limit,
					$numServersDone,
					$keysDeletedCount
				);
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $shardIndex );
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
		$cutoffUnix = ConvertibleTimestamp::convert( TS_UNIX, $timestamp );
		$shardIndexes = range( 0, $this->numTableShards - 1 );
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
						$rowExpUnix = ConvertibleTimestamp::convert( TS_UNIX, $row->exptime );
						$lag = max( $cutoffUnix - $rowExpUnix, 1 );
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
						$continueUnix = ConvertibleTimestamp::convert( TS_UNIX, $continue );
						$remainingLag = $cutoffUnix - $continueUnix;
						$processedLag = max( $lag - $remainingLag, 0 );
						$doneRatio = ( $numShardsDone + $processedLag / $lag ) / $this->numTableShards;
					} else {
						$doneRatio = 1;
					}

					$overallRatio = ( $doneRatio / $this->numServerShards )
						+ ( $serversDoneCount / $this->numServerShards );
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
		for ( $shardIndex = 0; $shardIndex < $this->numServerShards; $shardIndex++ ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				for ( $i = 0; $i < $this->numTableShards; $i++ ) {
					$db->delete( $this->getTableNameByShard( $i ), '*', __METHOD__ );
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $shardIndex );
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

		list( $shardIndex ) = $this->getTableByKey( $key );

		$db = null; // in case of connection failure
		try {
			$db = $this->getConnection( $shardIndex );
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
			$this->handleWriteError( $e, $db, $shardIndex );
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

			list( $shardIndex ) = $this->getTableByKey( $key );

			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				$ok = $db->unlock( $key, __METHOD__ );
				if ( !$ok ) {
					$this->logger->warning(
						__METHOD__ . ' failed to release lock for {key}.',
						[ 'key' => $key ]
					);
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $shardIndex );
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
			AtEase::suppressWarnings();
			$decomp = gzinflate( $serial );
			AtEase::restoreWarnings();

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
	 * @param int $shardIndex
	 */
	private function handleReadError( DBError $exception, $shardIndex ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->markServerDown( $exception, $shardIndex );
		}

		$this->setAndLogDBError( $exception );
	}

	/**
	 * Handle a DBQueryError which occurred during a write operation.
	 *
	 * @param DBError $exception
	 * @param IDatabase|null $db DB handle or null if connection failed
	 * @param int $shardIndex
	 * @throws Exception
	 */
	private function handleWriteError( DBError $exception, $db, $shardIndex ) {
		if ( !( $db instanceof IDatabase ) ) {
			$this->markServerDown( $exception, $shardIndex );
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
	 * @param int $shardIndex
	 */
	private function markServerDown( DBError $exception, $shardIndex ) {
		unset( $this->conns[$shardIndex] ); // bug T103435

		$now = $this->getCurrentTime();
		if ( isset( $this->connFailureTimes[$shardIndex] ) ) {
			if ( $now - $this->connFailureTimes[$shardIndex] >= 60 ) {
				unset( $this->connFailureTimes[$shardIndex] );
				unset( $this->connFailureErrors[$shardIndex] );
			} else {
				$this->logger->debug( __METHOD__ . ": Server #$shardIndex already down" );
				return;
			}
		}
		$this->logger->info( __METHOD__ . ": Server #$shardIndex down until " . ( $now + 60 ) );
		$this->connFailureTimes[$shardIndex] = $now;
		$this->connFailureErrors[$shardIndex] = $exception;
	}

	/**
	 * @param IMaintainableDatabase $db
	 * @throws DBError
	 */
	private function initSqliteDatabase( IMaintainableDatabase $db ) {
		if ( $db->tableExists( 'objectcache' ) ) {
			return;
		}
		// Use one table for SQLite; sharding does not seem to have much benefit
		$db->query( "PRAGMA journal_mode=WAL" ); // this is permanent
		$db->startAtomic( __METHOD__ ); // atomic DDL
		try {
			$encTable = $db->tableName( 'objectcache' );
			$encExptimeIndex = $db->addIdentifierQuotes( $db->tablePrefix() . 'exptime' );
			$db->query(
				"CREATE TABLE $encTable (\n" .
				"	keyname BLOB NOT NULL default '' PRIMARY KEY,\n" .
				"	value BLOB,\n" .
				"	exptime TEXT\n" .
				")",
				__METHOD__
			);
			$db->query( "CREATE INDEX $encExptimeIndex ON $encTable (exptime)" );
			$db->endAtomic( __METHOD__ );
		} catch ( DBError $e ) {
			$db->rollback( __METHOD__ );
			throw $e;
		}
	}

	/**
	 * Create the shard tables on all databases (e.g. via eval.php/shell.php)
	 */
	public function createTables() {
		for ( $shardIndex = 0; $shardIndex < $this->numServerShards; $shardIndex++ ) {
			$db = $this->getConnection( $shardIndex );
			if ( in_array( $db->getType(), [ 'mysql', 'postgres' ], true ) ) {
				for ( $i = 0; $i < $this->numTableShards; $i++ ) {
					$encBaseTable = $db->tableName( 'objectcache' );
					$encShardTable = $db->tableName( $this->getTableNameByShard( $i ) );
					$db->query( "CREATE TABLE $encShardTable LIKE $encBaseTable" );
				}
			}
		}
	}

	/**
	 * @return bool Whether the main DB is used, e.g. wfGetDB( DB_MASTER )
	 */
	private function usesMainDB() {
		return !$this->serverInfos;
	}

	private function waitForReplication() {
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
	 * Silence the transaction profiler until the return value falls out of scope
	 *
	 * @return ScopedCallback|null
	 */
	private function silenceTransactionProfiler() {
		if ( !$this->usesMainDB() ) {
			// Custom DB is configured which either has no TransactionProfiler injected,
			// or has one specific for cache use, which we shouldn't silence
			return null;
		}

		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$oldSilenced = $trxProfiler->setSilenced( true );
		return new ScopedCallback( function () use ( $trxProfiler, $oldSilenced ) {
			$trxProfiler->setSilenced( $oldSilenced );
		} );
	}
}
