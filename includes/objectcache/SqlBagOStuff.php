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

use Wikimedia\AtEase\AtEase;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\Blob;
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
 * RDBMS-based caching module
 *
 * The following database sharding schemes are supported:
 *   - None; all keys map to the same shard
 *   - Hash; keys map to shards via consistent hashing
 *   - Keyspace; global keys map to the global shard and non-global keys map to the local shard
 *
 * The following database replication topologies are supported:
 *   - A primary database server for each shard, all within one datacenter
 *   - A co-primary database server for each shard within each datacenter
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends MediumSpecificBagOStuff {
	/** @var ILoadBalancer|null */
	protected $localKeyLb;
	/** @var ILoadBalancer|null */
	protected $globalKeyLb;
	/** @var string|false|null DB name used for keys using the "global key" LoadBalancer */
	protected $globalKeyLbDomain;

	/** @var array[] (server index => server config) */
	protected $serverInfos = [];
	/** @var string[] (server index => tag/host name) */
	protected $serverTags = [];
	/** @var float UNIX timestamp */
	protected $lastGarbageCollect = 0;
	/** @var int Average number of writes required to trigger garbage collection */
	protected $purgePeriod = 10;
	/** @var int Max expired rows to purge during randomized garbage collection */
	protected $purgeLimit = 100;
	/** @var int Number of table shards to use on each server */
	protected $numTableShards = 1;
	/** @var int */
	protected $writeBatchSize = 100;
	/** @var string */
	protected $tableName = 'objectcache';
	/** @var bool Whether to use replicas instead of primaries (if using LoadBalancer) */
	protected $replicaOnly;
	/** @var string|null Multi-primary mode DB type ("mysql",...); null if not enabled */
	protected $multiPrimaryModeType;

	/** @var IMaintainableDatabase[] Map of (shard index => DB handle) */
	protected $conns;
	/** @var float[] Map of (shard index => UNIX timestamps) */
	protected $connFailureTimes = [];
	/** @var Exception[] Map of (shard index => Exception) */
	protected $connFailureErrors = [];

	private const SHARD_LOCAL = 'local';
	private const SHARD_GLOBAL = 'global';

	/** A number of seconds well above any expected clock skew */
	private const SAFE_CLOCK_BOUND_SEC = 15;
	/** A number of seconds well above any expected clock skew and replication lag */
	private const SAFE_PURGE_DELAY_SEC = 3600;
	/** Distinct string for tombstones stored in the "serialized" value column */
	private const TOMB_SERIAL = '';
	/** Relative seconds-to-live to use for tombstones */
	private const TOMB_EXPTIME = -self::SAFE_CLOCK_BOUND_SEC;
	/** How many seconds must pass before triggering a garbage collection */
	private const GC_DELAY_SEC = 1;

	private const BLOB_VALUE = 0;
	private const BLOB_EXPIRY = 1;
	private const BLOB_CASTOKEN = 2;

	/**
	 * Placeholder timestamp to use for TTL_INDEFINITE that can be stored in all RDBMs types.
	 * We use BINARY(14) for MySQL, BLOB for Sqlite, and TIMESTAMPZ for Postgres (which goes
	 * up to 294276 AD). The last second of the year 9999 can be stored in all these cases.
	 * https://www.postgresql.org/docs/9.0/datatype-datetime.html
	 */
	private const INF_TIMESTAMP_PLACEHOLDER = '99991231235959';

	/**
	 * Create a new backend instance from configuration
	 *
	 * The database servers must be provided by *either* the "server" parameter, the "servers"
	 * parameter, the "globalKeyLB" parameter, or both the "globalKeyLB"/"localKeyLB" parameters.
	 *
	 * Parameters include:
	 *   - server: Server config map for Database::factory() that describes the database to
	 *      use for all key operations in the current region. This is overriden by "servers".
	 *   - servers: Map of tag strings to server config maps, each for Database::factory(),
	 *      describing the set of database servers on which to distribute key operations in the
	 *      current region. Data is distributed among the servers via key hashing based on the
	 *      server tags. Therefore, each tag represents a shard of the dataset. Tags are useful
	 *      for failover using cold-standby servers and for managing shards with replica servers
	 *      in multiple regions (each having different hostnames).
	 *   - localKeyLB: ObjectFactory::getObjectFromSpec array yielding ILoadBalancer.
	 *      This load balancer is used for local keys, e.g. those using makeKey().
	 *      This is overriden by "server" and "servers".
	 *   - globalKeyLB: ObjectFactory::getObjectFromSpec array yielding ILoadBalancer.
	 *      This load balancer is used for global keys, e.g. those using makeGlobalKey().
	 *      This is overriden by "server" and "servers".
	 *   - globalKeyLbDomain: database name to use for "globalKeyLB" load balancer.
	 *   - multiPrimaryMode: Whether the portion of the dataset belonging to each tag/shard is
	 *      replicated among one or more regions, with one "co-primary" server in each region.
	 *      Queries are issued in a manner that provides Last-Write-Wins eventual consistency.
	 *      This option requires the "server" or "servers" options. Only MySQL, with statment
	 *      based replication (log_bin='ON' and binlog_format='STATEMENT') is supported. Also,
	 *      the `modtoken` column must exist on the `objectcache` table(s). [optional]
	 *   - purgePeriod: The average number of object cache writes in between garbage collection
	 *      operations, where expired entries are removed from the database. Or in other words,
	 *      the reciprocal of the probability of purging on any given write. If this is set to
	 *      zero, purging will never be done. [optional]
	 *   - purgeLimit: Maximum number of rows to purge at once. [optional]
	 *   - tableName: The table name to use, default is "objectcache". [optional]
	 *   - shards: The number of tables to use for data storage on each server. If greater than
	 *      1, table names are formed in the style objectcacheNNN where NNN is the shard index,
	 *      between 0 and shards-1. The number of digits used in the suffix is the minimum number
	 *      required to hold the largest shard index. Data is distributed among the tables via
	 *      key hashing. This helps mitigate MySQL bugs 61735 and 61736. [optional]
	 *   - replicaOnly: Whether to only use replica servers and only support read operations.
	 *      This option requires the use of LoadBalancer ("localKeyLB"/"globalKeyLB") and
	 *      should only be used by ReplicatedBagOStuff. [optional]
	 *   - syncTimeout: Max seconds to wait for replica DBs to catch up for WRITE_SYNC. [optional]
	 *   - writeBatchSize: Default maximum number of rows to change in each query for write
	 *      operations that can be chunked into a set of smaller writes. [optional]
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$dbType = null;
		if ( isset( $params['servers'] ) || isset( $params['server'] ) ) {
			// Configuration uses a direct list of servers.
			// Object data is horizontally partitioned via key hash.
			$index = 0;
			foreach ( ( $params['servers'] ?? [ $params['server'] ] ) as $tag => $info ) {
				$this->serverInfos[$index] = $info;
				// Allow integer-indexes arrays for b/c
				$this->serverTags[$index] = is_string( $tag ) ? $tag : "#$index";
				$dbType = $info['type'];
				++$index;
			}
		} else {
			// Configuration uses the servers defined in LoadBalancer instances.
			// Object data is vertically partitioned via global vs local keys.
			if ( isset( $params['globalKeyLB'] ) ) {
				$this->globalKeyLb = ( $params['globalKeyLB'] instanceof ILoadBalancer )
					? $params['globalKeyLB']
					: ObjectFactory::getObjectFromSpec( $params['globalKeyLB'] );
				$this->globalKeyLbDomain = $params['globalKeyLbDomain'] ?? null;
				if ( $this->globalKeyLbDomain === null ) {
					throw new InvalidArgumentException(
						"Config requires 'globalKeyLbDomain' if 'globalKeyLB' is set"
					);
				}
			}
			if ( isset( $params['localKeyLB'] ) ) {
				$this->localKeyLb = ( $params['localKeyLB'] instanceof ILoadBalancer )
					? $params['localKeyLB']
					: ObjectFactory::getObjectFromSpec( $params['localKeyLB'] );
			} else {
				$this->localKeyLb = $this->globalKeyLb;
			}
			// When using LoadBalancer instances, one *must* be defined for local keys
			if ( !$this->localKeyLb ) {
				throw new InvalidArgumentException(
					"Config requires 'server', 'servers', or 'localKeyLB'/'globalKeyLB'"
				);
			}
		}

		$this->purgePeriod = intval( $params['purgePeriod'] ?? $this->purgePeriod );
		$this->purgeLimit = intval( $params['purgeLimit'] ?? $this->purgeLimit );
		$this->tableName = $params['tableName'] ?? $this->tableName;
		$this->numTableShards = intval( $params['shards'] ?? $this->numTableShards );
		$this->writeBatchSize = intval( $params['writeBatchSize'] ?? $this->writeBatchSize );
		$this->replicaOnly = $params['replicaOnly'] ?? false;

		if ( $params['multiPrimaryMode'] ?? false ) {
			if ( $dbType !== 'mysql' ) {
				throw new InvalidArgumentException( "Multi-primary mode only supports MySQL" );
			}

			$this->multiPrimaryModeType = $dbType;
		}

		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_RDBMS;
		$this->attrMap[self::ATTR_EMULATION] = self::QOS_EMULATION_SQL;
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$data = $this->fetchBlobs( [ $key ], $getToken )[$key];
		if ( $data ) {
			$result = $this->unserialize( $data[self::BLOB_VALUE] );
			if ( $getToken && $result !== false ) {
				$casToken = $data[self::BLOB_CASTOKEN];
			}
			$valueSize = strlen( $data[self::BLOB_VALUE] );
		} else {
			$result = false;
			$valueSize = false;
		}

		$this->updateOpStats( self::METRIC_OP_GET, [ $key => [ 0, $valueSize ] ] );

		return $result;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$mtime = $this->getCurrentTime();

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForSet' ],
			$mtime,
			[ $key => [ $value, $exptime ] ],
			$flags
		);
	}

	protected function doDelete( $key, $flags = 0 ) {
		$mtime = $this->getCurrentTime();

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForDelete' ],
			$mtime,
			[ $key => [] ],
			$flags
		);
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		$mtime = $this->newLockingWriteSectionModificationTimestamp( $key, $scope );
		if ( $mtime === null ) {
			// Timeout or I/O error during lock acquisition
			return false;
		}

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForAdd' ],
			$mtime,
			[ $key => [ $value, $exptime ] ],
			$flags
		);
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		$mtime = $this->newLockingWriteSectionModificationTimestamp( $key, $scope );
		if ( $mtime === null ) {
			// Timeout or I/O error during lock acquisition
			return false;
		}

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForCas' ],
			$mtime,
			[ $key => [ $value, $exptime, $casToken ] ],
			$flags
		);
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		$mtime = $this->getCurrentTime();

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForChangeTTL' ],
			$mtime,
			[ $key => [ $exptime ] ],
			$flags
		);
	}

	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		$mtime = $this->getCurrentTime();

		$result = $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForIncrInit' ],
			$mtime,
			[ $key => [ $step, $init, $exptime ] ],
			$flags,
			$resByKey
		) ? $resByKey[$key] : false;

		return $result;
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		return $this->doIncr( $key, $value, $flags );
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		return $this->doIncr( $key, -$value, $flags );
	}

	private function doIncr( $key, $value = 1, $flags = 0 ) {
		$mtime = $this->newLockingWriteSectionModificationTimestamp( $key, $scope );
		if ( $mtime === null ) {
			// Timeout or I/O error during lock acquisition
			return false;
		}

		$data = $this->fetchBlobs( [ $key ] )[$key];
		if ( $data ) {
			$serialValue = $data[self::BLOB_VALUE];
			if ( $this->isInteger( $serialValue ) ) {
				$newValue = max( (int)$serialValue + (int)$value, 0 );
				$result = $this->modifyBlobs(
					[ $this, 'modifyTableSpecificBlobsForSet' ],
					$mtime,
					// Preserve the old expiry timestamp
					[ $key => [ $newValue, $data[self::BLOB_EXPIRY] ] ],
					$flags
				) ? $newValue : false;
			} else {
				$result = false;
				$this->logger->warning( __METHOD__ . ": $key is a non-integer" );
			}
		} else {
			$result = false;
			$this->logger->debug( __METHOD__ . ": $key does not exists" );
		}

		$this->updateOpStats( $value >= 0 ? self::METRIC_OP_INCR : self::METRIC_OP_DECR, [ $key ] );

		return $result;
	}

	protected function doGetMulti( array $keys, $flags = 0 ) {
		$result = [];
		$valueSizeByKey = [];

		$dataByKey = $this->fetchBlobs( $keys );
		foreach ( $keys as $key ) {
			$data = $dataByKey[$key];
			if ( $data ) {
				$serialValue = $data[self::BLOB_VALUE];
				$value = $this->unserialize( $serialValue );
				if ( $value !== false ) {
					$result[$key] = $value;
				}
				$valueSize = strlen( $serialValue );
			} else {
				$valueSize = false;
			}
			$valueSizeByKey[$key] = [ 0, $valueSize ];
		}

		$this->updateOpStats( self::METRIC_OP_GET, $valueSizeByKey );

		return $result;
	}

	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		$mtime = $this->getCurrentTime();

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForSet' ],
			$mtime,
			array_map(
				static function ( $value ) use ( $exptime ) {
					return [ $value, $exptime ];
				},
				$data
			),
			$flags
		);
	}

	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		$mtime = $this->getCurrentTime();

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForDelete' ],
			$mtime,
			array_fill_keys( $keys, [] ),
			$flags
		);
	}

	public function doChangeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$mtime = $this->getCurrentTime();

		return $this->modifyBlobs(
			[ $this, 'modifyTableSpecificBlobsForChangeTTL' ],
			$mtime,
			array_fill_keys( $keys, [ $exptime ] ),
			$flags
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
			$decimals = strlen( (string)( $this->numTableShards - 1 ) );

			return $this->tableName . sprintf( "%0{$decimals}d", $index );
		}

		return $this->tableName;
	}

	/**
	 * @param string[] $keys
	 * @param bool $getCasToken Whether to get a CAS token
	 * @return array<string,array|null> Order-preserved map of (key => (value,expiry,token) or null)
	 */
	private function fetchBlobs( array $keys, bool $getCasToken = false ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();

		// Initialize order-preserved per-key results; set values for live keys below
		$dataByKey = array_fill_keys( $keys, null );

		$readTime = (int)$this->getCurrentTime();
		$keysByTableByShard = [];
		foreach ( $keys as $key ) {
			list( $shardIndex, $partitionTable ) = $this->getKeyLocation( $key );
			$keysByTableByShard[$shardIndex][$partitionTable][] = $key;
		}

		foreach ( $keysByTableByShard as $shardIndex => $serverKeys ) {
			try {
				$db = $this->getConnection( $shardIndex );
				foreach ( $serverKeys as $partitionTable => $tableKeys ) {
					$res = $db->select(
						$partitionTable,
						$getCasToken
							? $this->addCasTokenFields( $db, [ 'keyname', 'value', 'exptime' ] )
							: [ 'keyname', 'value', 'exptime' ],
						$this->buildExistenceConditions( $db, $tableKeys, $readTime ),
						__METHOD__
					);
					foreach ( $res as $row ) {
						$row->shardIndex = $shardIndex;
						$row->tableName = $partitionTable;
						$dataByKey[$row->keyname] = $row;
					}
				}
			} catch ( DBError $e ) {
				$this->handleDBError( $e, $shardIndex );
			}
		}

		foreach ( $keys as $key ) {
			$row = $dataByKey[$key] ?? null;
			if ( !$row ) {
				continue;
			}

			$this->debug( __METHOD__ . ": retrieved $key; expiry time is {$row->exptime}" );
			try {
				$db = $this->getConnection( $row->shardIndex );
				$dataByKey[$key] = [
					self::BLOB_VALUE => $this->dbDecodeSerialValue( $db, $row->value ),
					self::BLOB_EXPIRY => $this->decodeDbExpiry( $db, $row->exptime ),
					self::BLOB_CASTOKEN => $getCasToken
						? $this->getCasTokenFromRow( $db, $row )
						: null
				];
			} catch ( DBQueryError $e ) {
				$this->handleDBError( $e, $row->shardIndex );
			}
		}

		return $dataByKey;
	}

	/**
	 * @param callable $tableWriteCallback Callback the takes the following arguments:
	 *  - IDatabase instance
	 *  - Partition table name string
	 *  - UNIX modification timestamp
	 *  - Map of (key => list of arguments) for keys belonging to the server/table partition
	 *  - Map of (key => result) [returned]
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Map of (key => list of arguments)
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @param array<string,mixed> &$resByKey Order-preserved map of (key => result) [returned]
	 * @return bool Whether all keys were processed
	 * @param-taint $argsByKey none
	 */
	private function modifyBlobs(
		callable $tableWriteCallback,
		float $mtime,
		array $argsByKey,
		int $flags,
		&$resByKey = []
	) {
		// Initialize order-preserved per-key results; callbacks mark successful results
		$resByKey = array_fill_keys( array_keys( $argsByKey ), false );

		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();

		$argsByKeyByTableByShard = [];
		foreach ( $argsByKey as $key => $args ) {
			list( $shardIndex, $partitionTable ) = $this->getKeyLocation( $key );
			$argsByKeyByTableByShard[$shardIndex][$partitionTable][$key] = $args;
		}

		$shardIndexesAffected = [];
		foreach ( $argsByKeyByTableByShard as $shardIndex => $argsByKeyByTables ) {
			foreach ( $argsByKeyByTables as $table => $ptKeyArgs ) {
				try {
					$db = $this->getConnection( $shardIndex );
					$shardIndexesAffected[] = $shardIndex;
					$tableWriteCallback( $db, $table, $mtime, $ptKeyArgs, $resByKey );
				} catch ( DBError $e ) {
					$this->handleDBError( $e, $shardIndex );
					continue;
				}
			}
		}

		$success = !in_array( false, $resByKey, true );

		if ( $this->fieldHasFlags( $flags, self::WRITE_SYNC ) ) {
			foreach ( $shardIndexesAffected as $shardIndex ) {
				if ( !$this->waitForReplication( $shardIndex ) ) {
					$success = false;
				}
			}
		}

		foreach ( $shardIndexesAffected as $shardIndex ) {
			try {
				$db = $this->getConnection( $shardIndex );
				$this->occasionallyGarbageCollect( $db );
			} catch ( DBError $e ) {
				$this->handleDBError( $e, $shardIndex );
			}
		}

		return $success;
	}

	/**
	 * Set key/value pairs belonging to a partition table on the the given server
	 *
	 * In multi-primary mode, if the current row for a key exists and has a modification token
	 * with a greater integral UNIX timestamp than that of the provided modification timestamp,
	 * then the write to that key will be aborted with a "false" result. Successfully modified
	 * key rows will be assigned a new modification token using the provided timestamp.
	 *
	 * @param IDatabase $db Handle to the database server where the argument keys belong
	 * @param string $ptable Name of the partition table where the argument keys belong
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Non-empty (key => (value,exptime)) map
	 * @param array<string,mixed> &$resByKey Map of (key => result) for succesful writes [returned]
	 * @throws DBError
	 */
	private function modifyTableSpecificBlobsForSet(
		IDatabase $db,
		string $ptable,
		float $mtime,
		array $argsByKey,
		array &$resByKey
	) {
		$valueSizesByKey = [];

		$mt = $this->makeTimestampedModificationToken( $mtime, $db );

		if ( $this->multiPrimaryModeType !== null ) {
			// @TODO: use multi-row upsert() with VALUES() once supported in Database
			foreach ( $argsByKey as $key => list( $value, $exptime ) ) {
				$serialValue = $this->getSerialized( $value, $key );
				$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );
				$db->upsert(
					$ptable,
					$this->buildUpsertRow( $db, $key, $serialValue, $expiry, $mt ),
					[ [ 'keyname' ] ],
					$this->buildUpsertSetForOverwrite( $db, $serialValue, $expiry, $mt ),
					__METHOD__
				);
				$resByKey[$key] = true;

				$valueSizesByKey[$key] = [ strlen( $serialValue ), 0 ];
			}
		} else {
			// T288998: use REPLACE, if possible, to avoid cluttering the binlogs
			$rows = [];
			foreach ( $argsByKey as $key => list( $value, $exptime ) ) {
				$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );
				$serialValue = $this->getSerialized( $value, $key );
				$rows[] = $this->buildUpsertRow( $db, $key, $serialValue, $expiry, $mt );

				$valueSizesByKey[$key] = [ strlen( $serialValue ), 0 ];
			}
			$db->replace( $ptable, 'keyname', $rows, __METHOD__ );
			foreach ( $argsByKey as $key => $unused ) {
				$resByKey[$key] = true;
			}
		}

		$this->updateOpStats( self::METRIC_OP_SET, $valueSizesByKey );
	}

	/**
	 * Purge/tombstone key/value pairs belonging to a partition table on the the given server
	 *
	 * In multi-primary mode, if the current row for a key exists and has a modification token
	 * with a greater integral UNIX timestamp than that of the provided modification timestamp,
	 * then the write to that key will be aborted with a "false" result. Successfully modified
	 * key rows will be assigned a new modification token/timestamp, an empty value, and an
	 * expiration timestamp dated slightly before the new modification timestamp.
	 *
	 * @param IDatabase $db Handle to the database server where the argument keys belong
	 * @param string $ptable Name of the partition table where the argument keys belong
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Non-empty (key => []) map
	 * @param array<string,mixed> &$resByKey Map of (key => result) prefilled with false [returned]
	 * @throws DBError
	 */
	private function modifyTableSpecificBlobsForDelete(
		IDatabase $db,
		string $ptable,
		float $mtime,
		array $argsByKey,
		array &$resByKey
	) {
		if ( $this->isMultiPrimaryModeEnabled() ) {
			// Tombstone keys in order to respect eventual consistency
			$mt = $this->makeTimestampedModificationToken( $mtime, $db );
			$expiry = $this->makeNewKeyExpiry( self::TOMB_EXPTIME, (int)$mtime );
			$rows = [];
			foreach ( $argsByKey as $key => $arg ) {
				$rows[] = $this->buildUpsertRow( $db, $key, self::TOMB_SERIAL, $expiry, $mt );
			}
			$db->upsert(
				$ptable,
				$rows,
				[ [ 'keyname' ] ],
				$this->buildUpsertSetForOverwrite( $db, self::TOMB_SERIAL, $expiry, $mt ),
				__METHOD__
			);
		} else {
			// Just purge the keys since there is only one primary (e.g. "source of truth")
			$db->delete( $ptable, [ 'keyname' => array_keys( $argsByKey ) ], __METHOD__ );
		}

		foreach ( $argsByKey as $key => $arg ) {
			$resByKey[$key] = true;
		}

		$this->updateOpStats( self::METRIC_OP_DELETE, array_keys( $argsByKey ) );
	}

	/**
	 * Insert key/value pairs belonging to a partition table on the the given server
	 *
	 * If the current row for a key exists and has an integral UNIX timestamp of expiration
	 * greater than that of the provided modification timestamp, then the write to that key
	 * will be aborted with a "false" result. Acquisition of advisory key locks must be handled
	 * by calling functions.
	 *
	 * In multi-primary mode, if the current row for a key exists and has a modification token
	 * with a greater integral UNIX timestamp than that of the provided modification timestamp,
	 * then the write to that key will be aborted with a "false" result. Successfully modified
	 * key rows will be assigned a new modification token/timestamp.
	 *
	 * @param IDatabase $db Handle to the database server where the argument keys belong
	 * @param string $ptable Name of the partition table where the argument keys belong
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Non-empty (key => (value,exptime)) map
	 * @param array<string,mixed> &$resByKey Map of (key => result) prefilled with false [returned]
	 * @throws DBError
	 */
	private function modifyTableSpecificBlobsForAdd(
		IDatabase $db,
		string $ptable,
		float $mtime,
		array $argsByKey,
		array &$resByKey
	) {
		$valueSizesByKey = [];

		$mt = $this->makeTimestampedModificationToken( $mtime, $db );

		// This check must happen outside the write query to respect eventual consistency
		$existingKeys = $db->selectFieldValues(
			$ptable,
			'keyname',
			$this->buildExistenceConditions( $db, array_keys( $argsByKey ), (int)$mtime ),
			__METHOD__
		);
		$existingByKey = array_fill_keys( $existingKeys, true );

		// @TODO: use multi-row upsert() with VALUES() once supported in Database
		foreach ( $argsByKey as $key => list( $value, $exptime ) ) {
			if ( isset( $existingByKey[$key] ) ) {
				$this->logger->debug( __METHOD__ . ": $key already exists" );
				continue;
			}

			$serialValue = $this->getSerialized( $value, $key );
			$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );
			$db->upsert(
				$ptable,
				$this->buildUpsertRow( $db, $key, $serialValue, $expiry, $mt ),
				[ [ 'keyname' ] ],
				$this->buildUpsertSetForOverwrite( $db, $serialValue, $expiry, $mt ),
				__METHOD__
			);
			$resByKey[$key] = true;

			$valueSizesByKey[$key] = [ strlen( $serialValue ), 0 ];
		}

		$this->updateOpStats( self::METRIC_OP_ADD, $valueSizesByKey );
	}

	/**
	 * Insert key/value pairs belonging to a partition table on the the given server
	 *
	 * If the current row for a key exists, has an integral UNIX timestamp of expiration greater
	 * than that of the provided modification timestamp, and the CAS token does not match, then
	 * the write to that key will be aborted with a "false" result. Acquisition of advisory key
	 * locks must be handled by calling functions.
	 *
	 * In multi-primary mode, if the current row for a key exists and has a modification token
	 * with a greater integral UNIX timestamp than that of the provided modification timestamp,
	 * then the write to that key will be aborted with a "false" result. Successfully modified
	 * key rows will be assigned a new modification token/timestamp.
	 *
	 * @param IDatabase $db Handle to the database server where the argument keys belong
	 * @param string $ptable Name of the partition table where the argument keys belong
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Non-empty (key => (value, exptime, CAS token)) map
	 * @param array<string,mixed> &$resByKey Map of (key => result) prefilled with false [returned]
	 * @throws DBError
	 */
	private function modifyTableSpecificBlobsForCas(
		IDatabase $db,
		string $ptable,
		float $mtime,
		array $argsByKey,
		array &$resByKey
	) {
		$valueSizesByKey = [];

		$mt = $this->makeTimestampedModificationToken( $mtime, $db );

		// This check must happen outside the write query to respect eventual consistency
		$res = $db->select(
			$ptable,
			$this->addCasTokenFields( $db, [ 'keyname' ] ),
			$this->buildExistenceConditions( $db, array_keys( $argsByKey ), (int)$mtime ),
			__METHOD__
		);

		$curTokensByKey = [];
		foreach ( $res as $row ) {
			$curTokensByKey[$row->keyname] = $this->getCasTokenFromRow( $db, $row );
		}

		// @TODO: use multi-row upsert() with VALUES() once supported in Database
		foreach ( $argsByKey as $key => list( $value, $exptime, $casToken ) ) {
			$curToken = $curTokensByKey[$key] ?? null;
			if ( $curToken === null ) {
				$this->logger->debug( __METHOD__ . ": $key does not exists" );
				continue;
			}

			if ( $curToken !== $casToken ) {
				$this->logger->debug( __METHOD__ . ": $key does not have a matching token" );
				continue;
			}

			$serialValue = $this->getSerialized( $value, $key );
			$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );
			$db->upsert(
				$ptable,
				$this->buildUpsertRow( $db, $key, $serialValue, $expiry, $mt ),
				[ [ 'keyname' ] ],
				$this->buildUpsertSetForOverwrite( $db, $serialValue, $expiry, $mt ),
				__METHOD__
			);
			$resByKey[$key] = true;

			$valueSizesByKey[$key] = [ strlen( $serialValue ), 0 ];
		}

		$this->updateOpStats( self::METRIC_OP_CAS, $valueSizesByKey );
	}

	/**
	 * Update the TTL for keys belonging to a partition table on the the given server
	 *
	 * If no current row for a key exists or the current row has an integral UNIX timestamp of
	 * expiration less than that of the provided modification timestamp, then the write to that
	 * key will be aborted with a "false" result.
	 *
	 * In multi-primary mode, if the current row for a key exists and has a modification token
	 * with a greater integral UNIX timestamp than that of the provided modification timestamp,
	 * then the write to that key will be aborted with a "false" result. Successfully modified
	 * key rows will be assigned a new modification token/timestamp.
	 *
	 * @param IDatabase $db Handle to the database server where the argument keys belong
	 * @param string $ptable Name of the partition table where the argument keys belong
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Non-empty (key => (exptime)) map
	 * @param array<string,mixed> &$resByKey Map of (key => result) prefilled with false [returned]
	 * @throws DBError
	 */
	private function modifyTableSpecificBlobsForChangeTTL(
		IDatabase $db,
		string $ptable,
		float $mtime,
		array $argsByKey,
		array &$resByKey
	) {
		if ( $this->isMultiPrimaryModeEnabled() ) {
			$mt = $this->makeTimestampedModificationToken( $mtime, $db );

			$res = $db->select(
				$ptable,
				[ 'keyname', 'value' ],
				$this->buildExistenceConditions( $db, array_keys( $argsByKey ), (int)$mtime ),
				__METHOD__
			);
			// @TODO: use multi-row upsert() with VALUES() once supported in Database
			foreach ( $res as $curRow ) {
				$key = $curRow->keyname;
				$serialValue = $this->dbDecodeSerialValue( $db, $curRow->value );
				list( $exptime ) = $argsByKey[$key];
				$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );

				$db->upsert(
					$ptable,
					$this->buildUpsertRow( $db, $key, $serialValue, $expiry, $mt ),
					[ [ 'keyname' ] ],
					$this->buildUpsertSetForOverwrite( $db, $serialValue, $expiry, $mt ),
					__METHOD__
				);
				$resByKey[$key] = true;
			}
		} else {
			$keysBatchesByExpiry = [];
			foreach ( $argsByKey as $key => list( $exptime ) ) {
				$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );
				$keysBatchesByExpiry[$expiry][] = $key;
			}

			$existingCount = 0;
			foreach ( $keysBatchesByExpiry as $expiry => $keyBatch ) {
				$db->update(
					$ptable,
					[ 'exptime' => $this->encodeDbExpiry( $db, $expiry ) ],
					$this->buildExistenceConditions( $db, $keyBatch, (int)$mtime ),
					__METHOD__
				);
				$existingCount += $db->affectedRows();
			}
			if ( $existingCount === count( $argsByKey ) ) {
				foreach ( $argsByKey as $key => $args ) {
					$resByKey[$key] = true;
				}
			}
		}

		$this->updateOpStats( self::METRIC_OP_CHANGE_TTL, array_keys( $argsByKey ) );
	}

	/**
	 * Either increment a counter key, if it exists, or initialize it, otherwise
	 *
	 * If no current row for a key exists or the current row has an integral UNIX timestamp of
	 * expiration less than that of the provided modification timestamp, then the key row will
	 * be set to the initial value. Otherwise, the current row will be incremented.
	 *
	 * In multi-primary mode, if the current row for a key exists and has a modification token
	 * with a greater integral UNIX timestamp than that of the provided modification timestamp,
	 * then the write to that key will be aborted with a "false" result. Successfully initialized
	 * key rows will be assigned a new modification token/timestamp.
	 *
	 * @param IDatabase $db Handle to the database server where the argument keys belong
	 * @param string $ptable Name of the partition table where the argument keys belong
	 * @param float $mtime UNIX modification timestamp
	 * @param array<string,array> $argsByKey Non-empty (key => (step, init, exptime) map
	 * @param array<string,mixed> &$resByKey Map of (key => result) prefilled with false [returned]
	 * @throws DBError
	 */
	private function modifyTableSpecificBlobsForIncrInit(
		IDatabase $db,
		string $ptable,
		float $mtime,
		array $argsByKey,
		array &$resByKey
	) {
		foreach ( $argsByKey as $key => list( $step, $init, $exptime ) ) {
			$mt = $this->makeTimestampedModificationToken( $mtime, $db );
			$expiry = $this->makeNewKeyExpiry( $exptime, (int)$mtime );

			// Use a transaction so that changes from other threads are not visible due to
			// "consistent reads". This way, the exact post-increment value can be returned.
			// The "live key exists" check can go inside the write query and remain safe for
			// replication since the TTL for such keys is either indefinite or very short.
			$db->startAtomic( __METHOD__ );
			$db->upsert(
				$ptable,
				$this->buildUpsertRow( $db, $key, $init, $expiry, $mt ),
				[ [ 'keyname' ] ],
				$this->buildIncrUpsertSet( $db, $step, $init, $expiry, $mt, (int)$mtime ),
				__METHOD__
			);
			$affectedCount = $db->affectedRows();
			$row = $db->selectRow( $ptable, 'value', [ 'keyname' => $key ], __METHOD__ );
			$db->endAtomic( __METHOD__ );

			if ( !$affectedCount || $row === false ) {
				$this->logger->warning( __METHOD__ . ": failed to set new $key value" );
				continue;
			}

			$serialValue = $this->dbDecodeSerialValue( $db, $row->value );
			if ( !$this->isInteger( $serialValue ) ) {
				$this->logger->warning( __METHOD__ . ": got non-integer $key value" );
				continue;
			}

			$resByKey[$key] = (int)$serialValue;
		}

		$this->updateOpStats( self::METRIC_OP_INCR, array_keys( $argsByKey ) );
	}

	/**
	 * @param int $exptime Relative or absolute expiration
	 * @param int $nowTsUnix Current UNIX timestamp
	 * @return int UNIX timestamp or TTL_INDEFINITE
	 */
	private function makeNewKeyExpiry( $exptime, int $nowTsUnix ) {
		$expiry = $this->getExpirationAsTimestamp( $exptime );
		// Eventual consistency requires the preservation of recently modified keys.
		// Do not create rows with `exptime` fields so low that they might get garbage
		// collected before being replicated.
		if ( $expiry !== self::TTL_INDEFINITE ) {
			$expiry = max( $expiry, $nowTsUnix - self::SAFE_CLOCK_BOUND_SEC );
		}

		return $expiry;
	}

	/**
	 * Get a scoped lock and modification timestamp for a critical section of reads/writes
	 *
	 * This is used instead of BagOStuff::getCurrentTime() for certain writes (such as "add",
	 * "incr", and "cas"), for which we want to support tight race conditions where the same
	 * key is repeatedly written to by multiple web servers that each get to see the previous
	 * value, act on it, and modify it in some way.
	 *
	 * It is assumed that this method is normally only invoked from the primary datacenter.
	 * A lock is acquired on the primary server of the local datacenter in order to avoid race
	 * conditions within the critical section. The clock on the SQL server is used to get the
	 * modification timestamp in order to minimize issues with clock drift between web servers;
	 * thus key writes will not be rejected due to some web servers having lagged clocks.
	 *
	 * @param string $key
	 * @param ?ScopedCallback &$scope Unlocker callback; null on failure [returned]
	 * @return float|null UNIX timestamp with 6 decimal places; null on failure
	 */
	private function newLockingWriteSectionModificationTimestamp( $key, &$scope ) {
		if ( !$this->lock( $key, 0 ) ) {
			return null;
		}

		$scope = new ScopedCallback( function () use ( $key ) {
			$this->unlock( $key );
		} );

		// sprintf is used to adjust precision
		return (float)sprintf( '%.6f', $this->locks[$key][self::LOCK_TIME] );
	}

	/**
	 * Make a `modtoken` column value with the original time and source database server of a write
	 *
	 * @param int|float $mtime UNIX modification timestamp
	 * @param IDatabase $db Handle to the primary database server sourcing the write
	 * @return string String of the form "<SECONDS_SOURCE><MICROSECONDS>", where SECONDS_SOURCE
	 *  is "<35 bit seconds portion of UNIX time><32 bit database server ID>" as 13 base 36 chars,
	 *  and MICROSECONDS is "<20 bit microseconds portion of UNIX time>" as 4 base 36 chars
	 */
	private function makeTimestampedModificationToken( $mtime, IDatabase $db ) {
		// We have reserved space for upto 6 digits in the microsecond portion of the token.
		// This is for future use only (maybe CAS tokens) and not currently used.
		// It is currently populated by the microsecond portion returned by microtime,
		// which generally has fewer than 6 digits of meaningful precision but can still be useful
		// in debugging (to see the token continuously change even during rapid testing).
		$seconds = (int)$mtime;
		list( , $microseconds ) = explode( '.', sprintf( '%.6f', $mtime ) );

		$id = $db->getTopologyBasedServerId() ?? sprintf( '%u', crc32( $db->getServerName() ) );

		$token = implode( '', [
			// 67 bit integral portion of UNIX timestamp, qualified
			\Wikimedia\base_convert(
				// 35 bit integral seconds portion of UNIX timestamp
				str_pad( base_convert( (string)$seconds, 10, 2 ), 35, '0', STR_PAD_LEFT ) .
				// 32 bit ID of the primary database server handling the write
				str_pad( base_convert( $id, 10, 2 ), 32, '0', STR_PAD_LEFT ),
				2,
				36,
				13
			),
			// 20 bit fractional portion of UNIX timestamp, as integral microseconds
			str_pad( base_convert( $microseconds, 10, 36 ), 4, '0', STR_PAD_LEFT )
		] );

		if ( strlen( $token ) !== 17 ) {
			throw new RuntimeException( "Modification timestamp overflow detected" );
		}

		return $token;
	}

	/**
	 * WHERE conditions that check for existence and liveness of keys
	 *
	 * @param IDatabase $db
	 * @param string[]|string $keys
	 * @param int $time UNIX modification timestamp
	 * @return array
	 */
	private function buildExistenceConditions( IDatabase $db, $keys, int $time ) {
		// Note that tombstones always have past expiration dates
		return [
			'keyname' => $keys,
			'exptime >= ' . $db->addQuotes( $db->timestamp( $time ) )
		];
	}

	/**
	 * INSERT array for handling key writes/overwrites when no live nor stale key exists
	 *
	 * @param IDatabase $db
	 * @param string $key
	 * @param string|int $serialValue New value
	 * @param int $expiry Expiration timestamp or TTL_INDEFINITE
	 * @param string $mt Modification token
	 * @return array
	 */
	private function buildUpsertRow(
		IDatabase $db,
		$key,
		$serialValue,
		int $expiry,
		string $mt
	) {
		$row = [
			'keyname' => $key,
			'value'   => $this->dbEncodeSerialValue( $db, $serialValue ),
			'exptime' => $this->encodeDbExpiry( $db, $expiry )
		];
		if ( $this->isMultiPrimaryModeEnabled() ) {
			$row['modtoken'] = $mt;
		}

		return $row;
	}

	/**
	 * SET array for handling key overwrites when a live or stale key exists
	 *
	 * @param IDatabase $db
	 * @param string|int $serialValue New value
	 * @param int $expiry Expiration timestamp or TTL_INDEFINITE
	 * @param string $mt Modification token
	 * @return array
	 */
	private function buildUpsertSetForOverwrite(
		IDatabase $db,
		$serialValue,
		int $expiry,
		string $mt
	) {
		$expressionsByColumn = [
			'value'   => $db->addQuotes( $this->dbEncodeSerialValue( $db, $serialValue ) ),
			'exptime' => $db->addQuotes( $this->encodeDbExpiry( $db, $expiry ) )
		];

		$set = [];
		if ( $this->isMultiPrimaryModeEnabled() ) {
			// The query might take a while to replicate, during which newer values might get
			// written. Qualify the query so that it does not override such values. Note that
			// duplicate tokens generated around the same time for a key should still result
			// in convergence given the use of server_id in modtoken (providing a total order
			// among primary DB servers) and MySQL binlog ordering (providing a total order
			// for writes replicating from a given primary DB server).
			$expressionsByColumn['modtoken'] = $db->addQuotes( $mt );
			foreach ( $expressionsByColumn as $column => $updateExpression ) {
				$rhs = $db->conditional(
					$db->addQuotes( substr( $mt, 0, 13 ) ) . ' >= SUBSTR(modtoken,0,13)',
					$updateExpression,
					$column
				);
				$set[] = "{$column}=" . trim( $rhs );
			}
		} else {
			foreach ( $expressionsByColumn as $column => $updateExpression ) {
				$set[] = "{$column}={$updateExpression}";
			}
		}

		return $set;
	}

	/**
	 * SET array for handling key overwrites when a live or stale key exists
	 *
	 * @param IDatabase $db
	 * @param int $step Positive counter incrementation value
	 * @param int $init Positive initial counter value
	 * @param int $expiry Expiration timestamp or TTL_INDEFINITE
	 * @param string $mt Modification token
	 * @param int $mtUnixTs UNIX timestamp of modification token
	 * @return array
	 */
	private function buildIncrUpsertSet(
		IDatabase $db,
		int $step,
		int $init,
		int $expiry,
		string $mt,
		int $mtUnixTs
	) {
		// Map of (column => (SQL for non-expired key rows, SQL for expired key rows))
		$expressionsByColumn = [
			'value'   => [
				$db->buildIntegerCast( 'value' ) . " + {$db->addQuotes( $step )}",
				$db->addQuotes( $this->dbEncodeSerialValue( $db, $init ) )
			],
			'exptime' => [
				'exptime',
				$db->addQuotes( $this->encodeDbExpiry( $db, $expiry ) )
			]
		];
		if ( $this->isMultiPrimaryModeEnabled() ) {
			$expressionsByColumn['modtoken'] = [ 'modtoken', $db->addQuotes( $mt ) ];
		}

		$set = [];
		foreach ( $expressionsByColumn as $column => list( $updateExpression, $initExpression ) ) {
			$rhs = $db->conditional(
				'exptime >= ' . $db->addQuotes( $db->timestamp( $mtUnixTs ) ),
				$updateExpression,
				$initExpression
			);
			$set[] = "{$column}=" . trim( $rhs );
		}

		return $set;
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
	 * @return int UNIX timestamp of expiration or TTL_INDEFINITE
	 */
	private function decodeDbExpiry( IDatabase $db, string $dbExpiry ) {
		return ( $dbExpiry === $db->timestamp( self::INF_TIMESTAMP_PLACEHOLDER ) )
			? self::TTL_INDEFINITE
			: (int)ConvertibleTimestamp::convert( TS_UNIX, $dbExpiry );
	}

	/**
	 * @param IDatabase $db
	 * @param string|int $serialValue
	 * @return string|int
	 */
	private function dbEncodeSerialValue( IDatabase $db, $serialValue ) {
		return is_int( $serialValue ) ? $serialValue : $db->encodeBlob( $serialValue );
	}

	/**
	 * @param IDatabase $db
	 * @param Blob|string|int $blob
	 * @return string|int
	 */
	private function dbDecodeSerialValue( IDatabase $db, $blob ) {
		return $this->isInteger( $blob ) ? (int)$blob : $db->decodeBlob( $blob );
	}

	/**
	 * Either append a 'castoken' field or append the fields needed to compute the CAS token
	 *
	 * @param IDatabase $db
	 * @param string[] $fields SELECT field array
	 * @return string[] SELECT field array
	 */
	private function addCasTokenFields( IDatabase $db, array $fields ) {
		$type = $db->getType();

		if ( $type === 'mysql' ) {
			$fields['castoken'] = $db->buildConcat( [
				'SHA1(value)',
				$db->addQuotes( '@' ),
				'exptime'
			] );
		} elseif ( $type === 'postgres' ) {
			$fields['castoken'] = $db->buildConcat( [
				'md5(value)',
				$db->addQuotes( '@' ),
				'exptime'
			] );
		} else {
			if ( !in_array( 'value', $fields, true ) ) {
				$fields[] = 'value';
			}
			if ( !in_array( 'exptime', $fields, true ) ) {
				$fields[] = 'exptime';
			}
		}

		return $fields;
	}

	/**
	 * Get a CAS token from a SELECT result row
	 *
	 * @param IDatabase $db
	 * @param stdClass $row A row for a key
	 * @return string CAS token
	 */
	private function getCasTokenFromRow( IDatabase $db, stdClass $row ) {
		if ( isset( $row->castoken ) ) {
			$token = $row->castoken;
		} else {
			$token = sha1( $this->dbDecodeSerialValue( $db, $row->value ) ) . '@' . $row->exptime;
			$this->logger->debug( __METHOD__ . ": application computed hash for CAS token" );
		}

		return $token;
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
					(int)$this->getCurrentTime(),
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
		$this->deleteObjectsExpiringBefore( (int)$this->getCurrentTime() );
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
			$shardIndexes = [ $this->getShardServerIndexForTag( $tag ) ];
		} else {
			$shardIndexes = $this->getShardServerIndexes();
			shuffle( $shardIndexes );
		}

		$ok = true;
		$numServers = count( $shardIndexes );

		$keysDeletedCount = 0;
		foreach ( $shardIndexes as $numServersDone => $shardIndex ) {
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
				$this->handleDBError( $e, $shardIndex );
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * @param IDatabase $db
	 * @param string|int $timestamp
	 * @param int|float $limit Maximum number of rows to delete in total or INF for no limit
	 * @param int &$keysDeletedCount
	 * @param null|array{fn:?callback,serversDone:int,serversTotal:int} $progress
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
		if ( $this->isMultiPrimaryModeEnabled() ) {
			// Eventual consistency requires the preservation of any key that was recently
			// modified. The key must exist on this database server long enough for the server
			// to receive, via replication, all writes to the key with lower timestamps. Such
			// writes should be no-ops since the existing key value should "win". If the network
			// partitions between datacenters A and B for 30 minutes, the database servers in
			// each datacenter will see an initial burst of writes with "old" timestamps via
			// replication. This might include writes with lower timestamps that the existing
			// key value. Therefore, clock skew and replication delay are both factors.
			$cutoffUnixSafe = (int)$this->getCurrentTime() - self::SAFE_PURGE_DELAY_SEC;
			$cutoffUnix = min( $cutoffUnix, $cutoffUnixSafe );
		}
		$tableIndexes = range( 0, $this->numTableShards - 1 );
		shuffle( $tableIndexes );

		$batchSize = min( $this->writeBatchSize, $limit );

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
						$minExpUnix = (int)ConvertibleTimestamp::convert( TS_UNIX, $row->exptime );
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
						$maxExpUnix = (int)ConvertibleTimestamp::convert( TS_UNIX, $maxExp );
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
		foreach ( $this->getShardServerIndexes() as $shardIndex ) {
			$db = null; // in case of connection failure
			try {
				$db = $this->getConnection( $shardIndex );
				for ( $i = 0; $i < $this->numTableShards; $i++ ) {
					$db->delete( $this->getTableNameByShard( $i ), '*', __METHOD__ );
				}
			} catch ( DBError $e ) {
				$this->handleDBError( $e, $shardIndex );
				return false;
			}
		}
		return true;
	}

	public function doLock( $key, $timeout = 6, $exptime = 6 ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();

		$lockTsUnix = null;

		list( $shardIndex ) = $this->getKeyLocation( $key );
		try {
			$db = $this->getConnection( $shardIndex );
			$lockTsUnix = $db->lock( $key, __METHOD__, $timeout, $db::LOCK_TIMESTAMP );
		} catch ( DBError $e ) {
			$this->handleDBError( $e, $shardIndex );
			$this->logger->warning(
				__METHOD__ . ' failed due to I/O error for {key}.',
				[ 'key' => $key ]
			);
		}

		return $lockTsUnix;
	}

	public function doUnlock( $key ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$silenceScope = $this->silenceTransactionProfiler();

		list( $shardIndex ) = $this->getKeyLocation( $key );

		try {
			$db = $this->getConnection( $shardIndex );
			$released = $db->unlock( $key, __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleDBError( $e, $shardIndex );
			$released = false;
		}

		return $released;
	}

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

	protected function serialize( $value ) {
		if ( is_int( $value ) ) {
			return $value;
		}

		$serial = serialize( $value );
		if ( function_exists( 'gzdeflate' ) ) {
			// On typical message and page data, this can provide a 3X storage savings
			$serial = gzdeflate( $serial );
		}

		return $serial;
	}

	protected function unserialize( $value ) {
		if ( $value === self::TOMB_SERIAL ) {
			return false; // tombstone
		}

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
		if ( $shardIndex === self::SHARD_GLOBAL ) {
			$lb = $this->globalKeyLb;
			$dbDomain = $this->globalKeyLbDomain;
		} else {
			$lb = $this->localKeyLb;
			$dbDomain = false;
		}

		if ( $lb->getServerAttributes( $lb->getWriterIndex() )[Database::ATTR_DB_LEVEL_LOCKING] ) {
			// Use the main connection to avoid transaction deadlocks
			$conn = $lb->getMaintenanceConnectionRef( DB_PRIMARY, [], $dbDomain );
		} else {
			// If the RDBMs has row/table/page level locking, then use separate auto-commit
			// connection to avoid needless contention and deadlocks.
			$conn = $lb->getMaintenanceConnectionRef(
				$this->replicaOnly ? DB_REPLICA : DB_PRIMARY,
				[],
				$dbDomain,
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
			$conn = Database::factory(
				$server['type'],
				array_merge(
					$server,
					[
						// Make sure the handle uses autocommit mode
						'flags' => ( $server['flags'] ?? 0 ) & ~IDatabase::DBO_TRX,
						'connLogger' => $this->logger,
						'queryLogger' => $this->logger
					]
				)
			);
			// Automatically create the objectcache table for sqlite as needed
			if ( $conn->getType() === 'sqlite' ) {
				if ( !$conn->tableExists( 'objectcache', __METHOD__ ) ) {
					$this->initSqliteDatabase( $conn );
				}
			}
			$this->conns[$shardIndex] = $conn;
		}

		return $this->conns[$shardIndex];
	}

	/**
	 * Handle a DBError which occurred during a read operation.
	 *
	 * @param DBError $exception
	 * @param int|string $shardIndex Server index or self::SHARD_LOCAL/self::SHARD_GLOBAL
	 */
	private function handleDBError( DBError $exception, $shardIndex ) {
		if ( $exception instanceof DBConnectionError ) {
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
			$this->setLastError( self::ERR_UNREACHABLE );
			$this->logger->warning( __METHOD__ . ": ignoring connection error" );
		} else {
			$this->setLastError( self::ERR_UNEXPECTED );
			$this->logger->warning( __METHOD__ . ": ignoring query error" );
		}
	}

	/**
	 * Mark a server down due to a DBConnectionError exception
	 *
	 * @param DBError $exception
	 * @param int|string $shardIndex Server index or self::SHARD_LOCAL/self::SHARD_GLOBAL
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
		foreach ( $this->getShardServerIndexes() as $shardIndex ) {
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
	private function getShardServerIndexes() {
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
	private function getShardServerIndexForTag( string $tag ) {
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
	 * @return bool Whether queries should be multi-primary safe
	 */
	private function isMultiPrimaryModeEnabled() {
		return ( $this->multiPrimaryModeType !== null );
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
			$primaryPos = $lb->getPrimaryPos();
			if ( !$primaryPos ) {
				return true; // not applicable
			}

			$loop = new WaitConditionLoop(
				static function () use ( $lb, $primaryPos ) {
					return $lb->waitForAll( $primaryPos, 1 );
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
