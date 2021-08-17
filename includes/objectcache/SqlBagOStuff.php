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
use Wikimedia\ObjectFactory;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\ScopedCallback;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\WaitConditionLoop;

/**
 * Class to store objects in the database
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends MediumSpecificBagOStuff {
	/** @var ILoadBalancer|null */
	protected $localKeyLb;
	/** @var ILoadBalancer|null */
	protected $globalKeyLb;

	/** @var array[] (server index => server config) */
	protected $serverInfos = [];
	/** @var string[] (server index => tag/host name) */
	protected $serverTags = [];
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
	protected $replicaOnly;

	/** @var IMaintainableDatabase[] Map of (shard index => DB handle) */
	protected $conns;
	/** @var float[] Map of (shard index => UNIX timestamps) */
	protected $connFailureTimes = [];
	/** @var Exception[] Map of (shard index => Exception) */
	protected $connFailureErrors = [];

	/** How many seconds must pass before triggering a garbage collection */
	private const GC_DELAY_SEC = 1;

	private const OP_SET = 'set';
	private const OP_ADD = 'add';
	private const OP_TOUCH = 'touch';
	private const OP_DELETE = 'delete';

	private const SHARD_LOCAL = 'local';
	private const SHARD_GLOBAL = 'global';

	/**
	 * Placeholder timestamp to use for TTL_INDEFINITE that can be stored in all RDBMs types.
	 * We use BINARY(14) for MySQL, BLOB for Sqlite, and TIMESTAMPZ for Postgres (which goes
	 * up to 294276 AD). The last second of the year 9999 can be stored in all these cases.
	 * https://www.postgresql.org/docs/9.0/datatype-datetime.html
	 */
	private const INF_TIMESTAMP_PLACEHOLDER = '99991231235959';

	/**
	 * Constructor. Parameters are:
	 *   - server: Server config map for Database::factory() that describes the database
	 *      to use for all key operations. This overrides "localKeyLB" and "globalKeyLB".
	 *   - servers: Array of server config maps, each for Database::factory(), describing a set
	 *      of database servers to distribute keys to. If this is specified, the "server" option
	 *      will be ignored. If string keys are used, then they will be used for consistent
	 *      hashing *instead* of the host name (from the server config). This is useful
	 *      when a cluster is replicated to another site (with different host names)
	 *      but each server has a corresponding replica in the other cluster.
	 *   - localKeyLB: ObjectFactory::getObjectFromSpec array yielding ILoadBalancer.
	 *      This load balancer is used for local keys, e.g. those using makeKey().
	 *      This is overriden by 'server'/'servers'.
	 *   - globalKeyLB: ObjectFactory::getObjectFromSpec array yielding ILoadBalancer.
	 *      This load balancer is used for local keys, e.g. those using makeGlobalKey().
	 *      This is overriden by 'server'/'servers'.
	 *   - purgePeriod: The average number of object cache writes in between garbage collection
	 *      operations, where expired entries are removed from the database. Or in other words,
	 *      the reciprocal of the probability of purging on any given write. If this is set to
	 *      zero, purging will never be done. [optional]
	 *   - purgeLimit: Maximum number of rows to purge at once. [optional]
	 *   - tableName: The table name to use, default is "objectcache". [optional]
	 *   - shards: The number of tables to use for data storage on each server.
	 *      If this is more than 1, table names will be formed in the style objectcacheNNN where
	 *      NNN is the shard index, between 0 and shards-1. The number of digits will be the
	 *      minimum number required to hold the largest shard index. Data will be distributed
	 *      across all tables by key hash. This is for MySQL bugs 61735
	 *      <https://bugs.mysql.com/bug.php?id=61735> and 61736
	 *      <https://bugs.mysql.com/bug.php?id=61736>. [optional]
	 *   - replicaOnly: Whether to only use replica DBs and avoid triggering garbage collection
	 *      logic of expired items. This only makes sense if the primary DB is used and only if
	 *      get() calls will be used. This is used by ReplicatedBagOStuff. [optional]
	 *   - syncTimeout: Max seconds to wait for replica DBs to catch up for WRITE_SYNC. [optional]
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->attrMap[self::ATTR_EMULATION] = self::QOS_EMULATION_SQL;

		if ( isset( $params['servers'] ) || isset( $params['server'] ) ) {
			$index = 0;
			foreach ( ( $params['servers'] ?? [ $params['server'] ] ) as $tag => $info ) {
				$this->serverInfos[$index] = $info;
				$this->serverTags[$index] = is_string( $tag ) ? $tag : "#$index";
				++$index;
			}
			$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_NONE;
		} else {
			if ( isset( $params['localKeyLB'] ) ) {
				$this->localKeyLb = ( $params['localKeyLB'] instanceof ILoadBalancer )
					? $params['localKeyLB']
					: ObjectFactory::getObjectFromSpec( $params['localKeyLB'] );
			}
			if ( isset( $params['globalKeyLB'] ) ) {
				$this->globalKeyLb = ( $params['globalKeyLB'] instanceof ILoadBalancer )
					? $params['globalKeyLB']
					: ObjectFactory::getObjectFromSpec( $params['globalKeyLB'] );
			}
			$this->localKeyLb = $this->localKeyLb ?: $this->globalKeyLb;
			if ( !$this->localKeyLb ) {
				throw new InvalidArgumentException(
					"Config requires 'server', 'servers', or 'localKeyLB'/'globalKeyLB'"
				);
			}
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
		$this->replicaOnly = $params['replicaOnly'] ?? false;

		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_RDBMS;
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$blobs = $this->fetchBlobMulti( [ $key ] );
		if ( array_key_exists( $key, $blobs ) ) {
			$blob = $blobs[$key];
			$result = $this->unserialize( $blob );
			if ( $getToken && $blob !== false ) {
				$casToken = $blob;
			}
			$valueSize = strlen( $blob );
		} else {
			$result = false;
			$valueSize = false;
		}

		$this->updateOpStats( self::METRIC_OP_GET, [ $key => [ null, $valueSize ] ] );

		return $result;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->modifyMulti( [ $key => $value ], $exptime, $flags, self::OP_SET );
	}

	protected function doDelete( $key, $flags = 0 ) {
		return $this->modifyMulti( [ $key => null ], 0, $flags, self::OP_DELETE );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->modifyMulti( [ $key => $value ], $exptime, $flags, self::OP_ADD );
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		list( $shardIndex, $tableName ) = $this->getKeyLocation( $key );
		$expiry = $this->getExpirationAsTimestamp( $exptime );
		$serialized = $this->getSerialized( $value, $key );

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
					'value' => $db->encodeBlob( $serialized ),
					'exptime' => $this->encodeDbExpiry( $db, $expiry )
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
			$success = $this->waitForReplication( $shardIndex ) && $success;
		}

		$this->updateOpStats( self::METRIC_OP_CAS, [ $key => [ strlen( $serialized ), null ] ] );

		return $success;
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		return $this->modifyMulti( [ $key => null ], $exptime, $flags, self::OP_TOUCH );
	}

	public function incr( $key, $step = 1, $flags = 0 ) {
		list( $shardIndex, $tableName ) = $this->getKeyLocation( $key );

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

		$this->updateOpStats( $step >= 0 ? self::METRIC_OP_INCR : self::METRIC_OP_DECR, [ $key ] );

		return $newCount;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		return $this->incr( $key, -$value, $flags );
	}

	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		return $this->modifyMulti( $data, $exptime, $flags, self::OP_SET );
	}

	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		return $this->modifyMulti(
			array_fill_keys( $keys, null ),
			0,
			$flags,
			self::OP_DELETE
		);
	}

	public function doChangeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		return $this->modifyMulti(
			array_fill_keys( $keys, null ),
			$exptime,
			$flags,
			self::OP_TOUCH
		);
	}

	/**
	 * Get a connection to the specified database
	 *
	 * @param int|string $shardIndex Server index or self::SHARD_LOCAL/self::SHARD_GLOBAL
	 * @return IMaintainableDatabase
	 * @throws DBConnectionError
	 * @throws UnexpectedValueException
	 */
	private function getConnection( $shardIndex ) {
		// Don't keep timing out trying to connect if the server is down
		if (
			isset( $this->connFailureErrors[$shardIndex] ) &&
			( $this->getCurrentTime() - $this->connFailureTimes[$shardIndex] ) < 60
		) {
			throw $this->connFailureErrors[$shardIndex];
		}

		if ( $shardIndex === self::SHARD_LOCAL ) {
			$conn = $this->getConnectionViaLoadBalancer( $shardIndex );
		} elseif ( $shardIndex === self::SHARD_GLOBAL ) {
			$conn = $this->getConnectionViaLoadBalancer( $shardIndex );
		} elseif ( is_int( $shardIndex ) ) {
			if ( isset( $this->serverInfos[$shardIndex] ) ) {
				$server = $this->serverInfos[$shardIndex];
				$conn = $this->getConnectionFromServerInfo( $shardIndex, $server );
			} else {
				throw new UnexpectedValueException( "Invalid server index #$shardIndex" );
			}
		} else {
			throw new UnexpectedValueException( "Invalid server index '$shardIndex'" );
		}

		return $conn;
	}

	/**
	 * Get the server index and table name for a given key
	 * @param string $key
	 * @return array (server index or self::SHARD_LOCAL/self::SHARD_GLOBAL, table name)
	 */
	private function getKeyLocation( $key ) {
		if ( $this->serverTags ) {
			// Striped array of database servers
			if ( count( $this->serverTags ) == 1 ) {
				$shardIndex = 0; // short-circuit
			} else {
				$sortedServers = $this->serverTags;
				ArrayUtils::consistentHashSort( $sortedServers, $key );
				reset( $sortedServers );
				$shardIndex = key( $sortedServers );
			}
		} else {
			// LoadBalancer based configuration
			$shardIndex = ( strpos( $key, 'global:' ) === 0 && $this->globalKeyLb )
				? self::SHARD_GLOBAL
				: self::SHARD_LOCAL;
		}

		if ( $this->numTableShards > 1 ) {
			$hash = hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
			$tableIndex = $hash % $this->numTableShards;
		} else {
			$tableIndex = null;
		}

		return [ $shardIndex, $this->getTableNameByShard( $tableIndex ) ];
	}

	/**
	 * Get the table name for a given shard index
	 * @param int|null $index
	 * @return string
	 */
	private function getTableNameByShard( $index ) {
		if ( $index !== null && $this->numTableShards > 1 ) {
			$decimals = strlen( $this->numTableShards - 1 );

			return $this->tableName . sprintf( "%0{$decimals}d", $index );
		}

		return $this->tableName;
	}

	protected function doGetMulti( array $keys, $flags = 0 ) {
		$values = [];
		$valueSizeByKey = [];

		$blobsByKey = $this->fetchBlobMulti( $keys );
		foreach ( $keys as $key ) {
			if ( array_key_exists( $key, $blobsByKey ) ) {
				$blob = $blobsByKey[$key];
				$value = $this->unserialize( $blob );
				if ( $value !== false ) {
					$values[$key] = $value;
				}
				$valueSize = strlen( $blob );
			} else {
				$valueSize = false;
			}
			$valueSizeByKey[$key] = [ null, $valueSize ];
		}

		$this->updateOpStats( self::METRIC_OP_GET, $valueSizeByKey );

		return $values;
	}

	private function fetchBlobMulti( array $keys ) {
		$values = []; // array of (key => value)

		$keysByTableByShardIndex = [];
		foreach ( $keys as $key ) {
			list( $shardIndex, $tableName ) = $this->getKeyLocation( $key );
			$keysByTableByShardIndex[$shardIndex][$tableName][] = $key;
		}

		$now = $this->getCurrentTime();
		$dataRows = [];
		foreach ( $keysByTableByShardIndex as $shardIndex => $serverKeys ) {
			try {
				$db = $this->getConnection( $shardIndex );
				foreach ( $serverKeys as $tableName => $tableKeys ) {
					$res = $db->select(
						$tableName,
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
					$expiry = $this->decodeDbExpiry( $db, $row->exptime );
					if ( $expiry !== self::TTL_INDEFINITE && $expiry < $now ) { // MISS
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

	/**
	 * @param mixed[]|null[] $data Map of (key => new value or null)
	 * @param int $exptime UNIX timestamp, TTL in seconds, or 0 (no expiration)
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @param string $op Cache operation
	 * @return bool
	 */
	private function modifyMulti( array $data, $exptime, $flags, $op ) {
		$keysByTableByShardIndex = [];
		foreach ( $data as $key => $value ) {
			list( $shardIndex, $tableName ) = $this->getKeyLocation( $key );
			$keysByTableByShardIndex[$shardIndex][$tableName][] = $key;
		}

		$expiry = $this->getExpirationAsTimestamp( $exptime );

		$result = true;
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();
		foreach ( $keysByTableByShardIndex as $shardIndex => $serverKeys ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				$this->occasionallyGarbageCollect( $db ); // expire old entries if any
				$dbExpiry = $this->encodeDbExpiry( $db, $expiry );
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $shardIndex );
				$result = false;
				continue;
			}

			foreach ( $serverKeys as $tableName => $tableKeys ) {
				try {
					$result = $this->updateTable(
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
			foreach ( $keysByTableByShardIndex as $shardIndex => $unused ) {
				$result = $this->waitForReplication( $shardIndex ) && $result;
			}
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
	private function updateTable( $op, $db, $table, $tableKeys, $data, $dbExpiry ) {
		$success = true;

		if ( $op === self::OP_ADD ) {
			$valueSizesByKey = [];

			$rows = [];
			foreach ( $tableKeys as $key ) {
				$serialized = $this->getSerialized( $data[$key], $key );
				$rows[] = [
					'keyname' => $key,
					'value' => $db->encodeBlob( $serialized ),
					'exptime' => $dbExpiry
				];
				$valueSizesByKey[$key] = [ strlen( $serialized ), null ];
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

			$this->updateOpStats( self::METRIC_OP_ADD, $valueSizesByKey );
		} elseif ( $op === self::OP_SET ) {
			$valueSizesByKey = [];

			$rows = [];
			foreach ( $tableKeys as $key ) {
				$serialized = $this->getSerialized( $data[$key], $key );
				$rows[] = [
					'keyname' => $key,
					'value' => $db->encodeBlob( $serialized ),
					'exptime' => $dbExpiry
				];
				$valueSizesByKey[$key] = [ strlen( $serialized ), null ];
			}
			$db->replace( $table, 'keyname', $rows, __METHOD__ );

			$this->updateOpStats( self::METRIC_OP_SET, $valueSizesByKey );
		} elseif ( $op === self::OP_DELETE ) {
			$db->delete( $table, [ 'keyname' => $tableKeys ], __METHOD__ );

			$this->updateOpStats( self::METRIC_OP_DELETE, $tableKeys );
		} elseif ( $op === self::OP_TOUCH ) {
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

			$this->updateOpStats( self::METRIC_OP_CHANGE_TTL, $tableKeys );
		} else {
			throw new InvalidArgumentException( "Invalid operation '$op'" );
		}

		return $success;
	}

	/**
	 * @param IDatabase $db
	 * @param int $expiry UNIX timestamp of expiration or TTL_INDEFINITE
	 * @return string
	 */
	private function encodeDbExpiry( IDatabase $db, int $expiry ) {
		return ( $expiry === self::TTL_INDEFINITE )
			// Use the maximum timestamp that the column can store
			? $db->timestamp( self::INF_TIMESTAMP_PLACEHOLDER )
			// Convert the absolute timestamp into the DB timestamp format
			: $db->timestamp( $expiry );
	}

	/**
	 * @param IDatabase $db
	 * @param string $dbExpiry DB timestamp of expiration
	 * @return string UNIX timestamp of expiration or TTL_INDEFINITE
	 */
	private function decodeDbExpiry( IDatabase $db, string $dbExpiry ) {
		return ( $dbExpiry === $db->timestamp( self::INF_TIMESTAMP_PLACEHOLDER ) )
			? self::TTL_INDEFINITE
			: ConvertibleTimestamp::convert( TS_UNIX, $dbExpiry );
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
			( $this->getCurrentTime() - $this->lastGarbageCollect ) > self::GC_DELAY_SEC
		) {
			$garbageCollector = function () use ( $db ) {
				$this->deleteServerObjectsExpiringBefore(
					$db,
					$this->getCurrentTime(),
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
		$limit = INF,
		string $tag = null
	) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();

		if ( $tag !== null ) {
			// Purge one server only, to support concurrent purging in large wiki farms (T282761).
			$shardIndexes = [ $this->getServerIndexByTag( $tag ) ];
		} else {
			$shardIndexes = $this->getServerShardIndexes();
			shuffle( $shardIndexes );
		}

		$ok = true;
		$numServers = count( $shardIndexes );

		$keysDeletedCount = 0;
		foreach ( $shardIndexes as $numServersDone => $shardIndex ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				$this->deleteServerObjectsExpiringBefore(
					$db,
					$timestamp,
					$limit,
					$keysDeletedCount,
					[ 'fn' => $progress, 'serversDone' => $numServersDone, 'serversTotal' => $numServers ]
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
	 * @param int $limit Maximum number of rows to delete in total
	 * @param int &$keysDeletedCount
	 * @param null|array{fn:callback,serversDone:int,serversTotal:int} $progress
	 * @throws DBError
	 */
	private function deleteServerObjectsExpiringBefore(
		IDatabase $db,
		$timestamp,
		$limit,
		&$keysDeletedCount = 0,
		array $progress = null
	) {
		$cutoffUnix = ConvertibleTimestamp::convert( TS_UNIX, $timestamp );
		$tableIndexes = range( 0, $this->numTableShards - 1 );
		shuffle( $tableIndexes );

		$config = MediaWikiServices::getInstance()->getMainConfig();
		$maxUpdateRows = $config->get( 'UpdateRowsPerQuery' );
		$batchSize = min( $maxUpdateRows, $limit );

		foreach ( $tableIndexes as $numShardsDone => $tableIndex ) {
			// The oldest expiry of a row we have deleted on this shard
			// (the first row that we deleted)
			$minExpUnix = null;
			// The most recent expiry time so far, from a row we have deleted on this shard
			$maxExp = null;
			// Size of the time range we'll delete, in seconds (for progress estimate)
			$totalSeconds = null;

			do {
				$res = $db->select(
					$this->getTableNameByShard( $tableIndex ),
					[ 'keyname', 'exptime' ],
					array_merge(
						[ 'exptime < ' . $db->addQuotes( $db->timestamp( $cutoffUnix ) ) ],
						$maxExp ? [ 'exptime >= ' . $db->addQuotes( $maxExp ) ] : []
					),
					__METHOD__,
					[ 'LIMIT' => $batchSize, 'ORDER BY' => 'exptime ASC' ]
				);

				if ( $res->numRows() ) {
					$row = $res->current();
					if ( $minExpUnix === null ) {
						$minExpUnix = ConvertibleTimestamp::convert( TS_UNIX, $row->exptime );
						$totalSeconds = max( $cutoffUnix - $minExpUnix, 1 );
					}

					$keys = [];
					foreach ( $res as $row ) {
						$keys[] = $row->keyname;
						$maxExp = $row->exptime;
					}

					$db->delete(
						$this->getTableNameByShard( $tableIndex ),
						[
							'exptime < ' . $db->addQuotes( $db->timestamp( $cutoffUnix ) ),
							'keyname' => $keys
						],
						__METHOD__
					);
					$keysDeletedCount += $db->affectedRows();
				}

				if ( $progress && is_callable( $progress['fn'] ) ) {
					if ( $totalSeconds ) {
						$maxExpUnix = ConvertibleTimestamp::convert( TS_UNIX, $maxExp );
						$remainingSeconds = $cutoffUnix - $maxExpUnix;
						$processedSeconds = max( $totalSeconds - $remainingSeconds, 0 );
						// For example, if we've done 1.5 table shard, and are thus half-way on the
						// 2nd of perhaps 5 tables on this server, then this might be:
						// `( 1 + ( 43200 / 86400 ) ) / 5 = 0.3`, or 30% done, of tables on this server.
						$tablesDoneRatio =
							( $numShardsDone + ( $processedSeconds / $totalSeconds ) ) / $this->numTableShards;
					} else {
						$tablesDoneRatio = 1;
					}

					// For example, if we're 30% done on the last of 10 servers, then this might be:
					// `( 9 / 10 ) + ( 0.3 / 10 ) = 0.93`, or 93% done, overall.
					$overallRatio = ( $progress['serversDone'] / $progress['serversTotal'] ) +
						( $tablesDoneRatio / $progress['serversTotal'] );
					( $progress['fn'] )( $overallRatio * 100 );
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
		foreach ( $this->getServerShardIndexes() as $shardIndex ) {
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

	protected function doLock( $key, $timeout = 6, $exptime = 6 ) {
		list( $shardIndex ) = $this->getKeyLocation( $key );

		$lockTsUnix = null;

		$db = null; // in case of connection failure
		try {
			$db = $this->getConnection( $shardIndex );
			$lockTsUnix = $db->lock( $key, __METHOD__, $timeout, $db::LOCK_TIMESTAMP );
			if ( $lockTsUnix === null ) {
				$this->logger->warning(
					__METHOD__ . " failed due to timeout for {key}.",
					[ 'key' => $key, 'timeout' => $timeout ]
				);
			}
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $shardIndex );
		}

		return $lockTsUnix;
	}

	protected function doUnlock( $key ) {
		list( $shardIndex ) = $this->getKeyLocation( $key );

		$db = null; // in case of connection failure
		try {
			$db = $this->getConnection( $shardIndex );
			$released = $db->unlock( $key, __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $shardIndex );
			$released = false;
		}

		return $released;
	}

	/**
	 * Construct a cache key.
	 *
	 * @since 1.35
	 * @param string $keyspace
	 * @param array $components
	 * @return string
	 */
	public function makeKeyInternal( $keyspace, $components ) {
		// SQL schema for 'objectcache' specifies keys as varchar(255). From that,
		// subtract the number of characters we need for the keyspace and for
		// the separator character needed for each argument. To handle some
		// custom prefixes used by thing like WANObjectCache, limit to 205.
		$keyspace = strtr( $keyspace, ' ', '_' );
		$charsLeft = 205 - strlen( $keyspace ) - count( $components );
		foreach ( $components as &$component ) {
			$component = strtr( $component, [
				' ' => '_', // Avoid unnecessary misses from pre-1.35 code
				':' => '%3A',
			] );

			// 33 = 32 characters for the MD5 + 1 for the '#' prefix.
			if ( $charsLeft > 33 && strlen( $component ) > $charsLeft ) {
				$component = '#' . md5( $component );
			}
			$charsLeft -= strlen( $component );
		}

		if ( $charsLeft < 0 ) {
			return $keyspace . ':BagOStuff-long-key:##' . md5( implode( ':', $components ) );
		}
		return $keyspace . ':' . implode( ':', $components );
	}

	/**
	 * Serialize an object and, if possible, compress the representation.
	 * On typical message and page data, this can provide a 3X decrease
	 * in storage requirements.
	 *
	 * @param mixed $value
	 * @return string|int
	 */
	protected function serialize( $value ) {
		if ( is_int( $value ) ) {
			return $value;
		}

		$serial = serialize( $value );
		if ( function_exists( 'gzdeflate' ) ) {
			$serial = gzdeflate( $serial );
		}

		return $serial;
	}

	/**
	 * Unserialize and, if necessary, decompress an object.
	 * @param string $value
	 * @return mixed
	 */
	protected function unserialize( $value ) {
		if ( $this->isInteger( $value ) ) {
			return (int)$value;
		}

		if ( function_exists( 'gzinflate' ) ) {
			AtEase::suppressWarnings();
			$decompressed = gzinflate( $value );
			AtEase::restoreWarnings();

			if ( $decompressed !== false ) {
				$value = $decompressed;
			}
		}

		return unserialize( $value );
	}

	/**
	 * @param string $shardIndex self::SHARD_LOCAL/self::SHARD_GLOBAL
	 * @return IMaintainableDatabase
	 * @throws DBError
	 */
	private function getConnectionViaLoadBalancer( $shardIndex ) {
		$lb = ( $shardIndex === self::SHARD_LOCAL ) ? $this->localKeyLb : $this->globalKeyLb;
		if ( $lb->getServerAttributes( $lb->getWriterIndex() )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			// Use the main connection to avoid transaction deadlocks
			$conn = $lb->getMaintenanceConnectionRef( DB_PRIMARY );
		} else {
			// If the RDBMs has row/table/page level locking, then use separate auto-commit
			// connection to avoid needless contention and deadlocks.
			$conn = $lb->getMaintenanceConnectionRef(
				$this->replicaOnly ? DB_REPLICA : DB_PRIMARY, [],
				false,
				$lb::CONN_TRX_AUTOCOMMIT
			);
		}

		return $conn;
	}

	/**
	 * @param int $shardIndex
	 * @param array $server Server config map
	 * @return IMaintainableDatabase
	 * @throws DBError
	 */
	private function getConnectionFromServerInfo( $shardIndex, array $server ) {
		if ( !isset( $this->conns[$shardIndex] ) ) {
			/** @var IMaintainableDatabase Auto-commit connection to the server */
			$conn = Database::factory( $server['type'], array_merge(
				$server,
				[
					'flags' => ( $server['flags'] ?? 0 ) & ~IDatabase::DBO_TRX,
					'connLogger' => $this->logger,
					'queryLogger' => $this->logger
				]
			) );
			// Automatically create the objectcache table for sqlite as needed
			if ( $conn->getType() === 'sqlite' && !$conn->tableExists( 'objectcache', __METHOD__ ) ) {
				$this->initSqliteDatabase( $conn );
			}
			$this->conns[$shardIndex] = $conn;
		}

		return $this->conns[$shardIndex];
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
		if ( $db->tableExists( 'objectcache', __METHOD__ ) ) {
			return;
		}
		// Use one table for SQLite; sharding does not seem to have much benefit
		$db->query( "PRAGMA journal_mode=WAL", __METHOD__ ); // this is permanent
		$db->startAtomic( __METHOD__ ); // atomic DDL
		try {
			$encTable = $db->tableName( 'objectcache' );
			$encExptimeIndex = $db->addIdentifierQuotes( $db->tablePrefix() . 'exptime' );
			$db->query(
				"CREATE TABLE $encTable (\n" .
				"	keyname BLOB NOT NULL default '' PRIMARY KEY,\n" .
				"	value BLOB,\n" .
				"	exptime BLOB NOT NULL\n" .
				")",
				__METHOD__
			);
			$db->query( "CREATE INDEX $encExptimeIndex ON $encTable (exptime)", __METHOD__ );
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
		foreach ( $this->getServerShardIndexes() as $shardIndex ) {
			$db = $this->getConnection( $shardIndex );
			if ( in_array( $db->getType(), [ 'mysql', 'postgres' ], true ) ) {
				for ( $i = 0; $i < $this->numTableShards; $i++ ) {
					$encBaseTable = $db->tableName( 'objectcache' );
					$encShardTable = $db->tableName( $this->getTableNameByShard( $i ) );
					$db->query( "CREATE TABLE $encShardTable LIKE $encBaseTable", __METHOD__ );
				}
			}
		}
	}

	/**
	 * @return string[]|int[] List of server indexes or self::SHARD_LOCAL/self::SHARD_GLOBAL
	 */
	private function getServerShardIndexes() {
		if ( $this->serverTags ) {
			// Striped array of database servers
			$shardIndexes = array_keys( $this->serverTags );
		} else {
			// LoadBalancer based configuration
			$shardIndexes = [];
			if ( $this->localKeyLb ) {
				$shardIndexes[] = self::SHARD_LOCAL;
			}
			if ( $this->globalKeyLb ) {
				$shardIndexes[] = self::SHARD_GLOBAL;
			}
		}

		return $shardIndexes;
	}

	/**
	 * @param string $tag
	 * @return int Server index for use with ::getConnection()
	 * @throws InvalidArgumentException If tag is unknown
	 */
	private function getServerIndexByTag( string $tag ) {
		if ( !$this->serverTags ) {
			throw new InvalidArgumentException( "Given a tag but no tags are configured" );
		}
		foreach ( $this->serverTags as $serverShardIndex => $serverTag ) {
			if ( $tag === $serverTag ) {
				return $serverShardIndex;
			}
		}
		throw new InvalidArgumentException( "Unknown server tag: $tag" );
	}

	/**
	 * Wait for replica DBs to catch up to the primary DB
	 *
	 * @param int|string $shardIndex Server index or self::SHARD_LOCAL/self::SHARD_GLOBAL
	 * @return bool Success
	 */
	private function waitForReplication( $shardIndex ) {
		if ( is_int( $shardIndex ) ) {
			return true; // striped only, no LoadBalancer
		}

		$lb = ( $shardIndex === self::SHARD_LOCAL ) ? $this->localKeyLb : $this->globalKeyLb;
		if ( !$lb->hasStreamingReplicaServers() ) {
			return true;
		}

		try {
			// Wait for any replica DBs to catch up
			$masterPos = $lb->getPrimaryPos();
			if ( !$masterPos ) {
				return true; // not applicable
			}

			$loop = new WaitConditionLoop(
				static function () use ( $lb, $masterPos ) {
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
		if ( $this->serverInfos ) {
			return null; // no TransactionProfiler injected anyway
		}
		return Profiler::instance()->getTransactionProfiler()->silenceForScope();
	}
}
