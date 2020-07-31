<?php
/**
 * Database load balancing manager
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
 */
namespace Wikimedia\Rdbms;

use ArrayUtils;
use BagOStuff;
use EmptyBagOStuff;
use InvalidArgumentException;
use LogicException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use UnexpectedValueException;
use WANObjectCache;
use Wikimedia\ScopedCallback;

/**
 * Database connection, tracking, load balancing, and transaction manager for a cluster
 *
 * @ingroup Database
 */
class LoadBalancer implements ILoadBalancer {
	/** @var ILoadMonitor */
	private $loadMonitor;
	/** @var callable|null Callback to run before the first connection attempt */
	private $chronologyCallback;
	/** @var BagOStuff */
	private $srvCache;
	/** @var WANObjectCache */
	private $wanCache;
	/** @var mixed Class name or object With profileIn/profileOut methods */
	private $profiler;
	/** @var TransactionProfiler */
	private $trxProfiler;
	/** @var LoggerInterface */
	private $connLogger;
	/** @var LoggerInterface */
	private $queryLogger;
	/** @var LoggerInterface */
	private $replLogger;
	/** @var LoggerInterface */
	private $perfLogger;
	/** @var callable Exception logger */
	private $errorLogger;
	/** @var callable Deprecation logger */
	private $deprecationLogger;

	/** @var DatabaseDomain Local DB domain ID and default for selectDB() calls */
	private $localDomain;

	/**
	 * @var IDatabase[][][]|Database[][][] Map of (connection category => server index => IDatabase[])
	 */
	private $conns;

	/** @var array[] Map of (server index => server config array) */
	private $servers;
	/** @var array[] Map of (group => server index => weight) */
	private $groupLoads;
	/** @var int[] Map of (server index => seconds of lag considered "high") */
	private $maxLagByIndex;
	/** @var int Seconds to spend waiting on replica DB lag to resolve */
	private $waitTimeout;
	/** @var array The LoadMonitor configuration */
	private $loadMonitorConfig;
	/** @var int */
	private $maxLag;
	/** @var string|null Default query group to use with getConnection() */
	private $defaultGroup;

	/** @var string Current server name */
	private $hostname;
	/** @var bool Whether this PHP instance is for a CLI script */
	private $cliMode;
	/** @var string Agent name for query profiling */
	private $agent;

	/** @var array[] $aliases Map of (table => (dbname, schema, prefix) map) */
	private $tableAliases = [];
	/** @var string[] Map of (index alias => index) */
	private $indexAliases = [];
	/** @var DatabaseDomain[]|string[] Map of (domain alias => DB domain) */
	private $domainAliases = [];
	/** @var callable[] Map of (name => callable) */
	private $trxRecurringCallbacks = [];
	/** @var bool[] Map of (domain => whether to use "temp tables only" mode) */
	private $tempTablesOnlyMode = [];

	/** @var string|bool Explicit DBO_TRX transaction round active or false if none */
	private $trxRoundId = false;
	/** @var string Stage of the current transaction round in the transaction round life-cycle */
	private $trxRoundStage = self::ROUND_CURSORY;
	/** @var Database Connection handle that caused a problem */
	private $errorConnection;
	/** @var int[] The group replica server indexes keyed by group */
	private $readIndexByGroup = [];
	/** @var bool|DBMasterPos Replication sync position or false if not set */
	private $waitForPos;
	/** @var bool Whether to disregard replica DB lag as a factor in replica DB selection */
	private $allowLagged = false;
	/** @var bool Whether the generic reader fell back to a lagged replica DB */
	private $laggedReplicaMode = false;
	/** @var string The last DB selection or connection error */
	private $lastError = 'Unknown error';
	/** @var string|bool Reason this instance is read-only or false if not */
	private $readOnlyReason = false;
	/** @var int Total number of new connections ever made with this instance */
	private $connectionCounter = 0;
	/** @var bool */
	private $disabled = false;
	/** @var bool Whether any connection has been attempted yet */
	private $connectionAttempted = false;

	/** var int An identifier for this class instance */
	private $id;
	/** @var int|null Integer ID of the managing LBFactory instance or null if none */
	private $ownerId;

	/** @var DatabaseDomain[] Map of (domain ID => domain instance) */
	private $nonLocalDomainCache = [];

	private static $INFO_SERVER_INDEX = 'serverIndex';
	private static $INFO_AUTOCOMMIT_ONLY = 'autoCommitOnly';
	private static $INFO_FORIEGN = 'foreign';
	private static $INFO_FOREIGN_REF_COUNT = 'foreignPoolRefCount';

	/** @var int Warn when this many connection are held */
	private const CONN_HELD_WARN_THRESHOLD = 10;

	/** @var int Default 'maxLag' when unspecified */
	private const MAX_LAG_DEFAULT = 6;
	/** @var int Default 'waitTimeout' when unspecified */
	private const MAX_WAIT_DEFAULT = 10;
	/** @var int Seconds to cache master DB server read-only status */
	private const TTL_CACHE_READONLY = 5;

	private const KEY_LOCAL = 'local';
	private const KEY_FOREIGN_FREE = 'foreignFree';
	private const KEY_FOREIGN_INUSE = 'foreignInUse';

	private const KEY_LOCAL_NOROUND = 'localAutoCommit';
	private const KEY_FOREIGN_FREE_NOROUND = 'foreignFreeAutoCommit';
	private const KEY_FOREIGN_INUSE_NOROUND = 'foreignInUseAutoCommit';

	/** @var string Transaction round, explicit or implicit, has not finished writing */
	private const ROUND_CURSORY = 'cursory';
	/** @var string Transaction round writes are complete and ready for pre-commit checks */
	private const ROUND_FINALIZED = 'finalized';
	/** @var string Transaction round passed final pre-commit checks */
	private const ROUND_APPROVED = 'approved';
	/** @var string Transaction round was committed and post-commit callbacks must be run */
	private const ROUND_COMMIT_CALLBACKS = 'commit-callbacks';
	/** @var string Transaction round was rolled back and post-rollback callbacks must be run */
	private const ROUND_ROLLBACK_CALLBACKS = 'rollback-callbacks';
	/** @var string Transaction round encountered an error */
	private const ROUND_ERROR = 'error';

	public function __construct( array $params ) {
		if ( !isset( $params['servers'] ) || !count( $params['servers'] ) ) {
			throw new InvalidArgumentException( 'Missing or empty "servers" parameter' );
		}

		$localDomain = isset( $params['localDomain'] )
			? DatabaseDomain::newFromId( $params['localDomain'] )
			: DatabaseDomain::newUnspecified();
		$this->setLocalDomain( $localDomain );

		$this->maxLag = $params['maxLag'] ?? self::MAX_LAG_DEFAULT;

		$listKey = -1;
		$this->servers = [];
		$this->groupLoads = [ self::GROUP_GENERIC => [] ];
		foreach ( $params['servers'] as $i => $server ) {
			if ( ++$listKey !== $i ) {
				throw new UnexpectedValueException( 'List expected for "servers" parameter' );
			}
			$this->servers[$i] = $server;
			foreach ( ( $server['groupLoads'] ?? [] ) as $group => $ratio ) {
				$this->groupLoads[$group][$i] = $ratio;
			}
			$this->groupLoads[self::GROUP_GENERIC][$i] = $server['load'];
			$this->maxLagByIndex[$i] = $server['max lag'] ?? $this->maxLag;
		}

		$this->waitTimeout = $params['waitTimeout'] ?? self::MAX_WAIT_DEFAULT;

		$this->conns = self::newTrackedConnectionsArray();

		if ( isset( $params['readOnlyReason'] ) && is_string( $params['readOnlyReason'] ) ) {
			$this->readOnlyReason = $params['readOnlyReason'];
		}

		$this->loadMonitorConfig = $params['loadMonitor'] ?? [ 'class' => 'LoadMonitorNull' ];
		$this->loadMonitorConfig += [ 'lagWarnThreshold' => $this->maxLag ];

		$this->srvCache = $params['srvCache'] ?? new EmptyBagOStuff();
		$this->wanCache = $params['wanCache'] ?? WANObjectCache::newEmpty();
		$this->profiler = $params['profiler'] ?? null;
		$this->trxProfiler = $params['trxProfiler'] ?? new TransactionProfiler();

		$this->errorLogger = $params['errorLogger'] ?? function ( Throwable $e ) {
			trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
		};
		$this->deprecationLogger = $params['deprecationLogger'] ?? function ( $msg ) {
			trigger_error( $msg, E_USER_DEPRECATED );
		};
		foreach ( [ 'replLogger', 'connLogger', 'queryLogger', 'perfLogger' ] as $key ) {
			$this->$key = $params[$key] ?? new NullLogger();
		}

		$this->hostname = $params['hostname'] ?? ( gethostname() ?: 'unknown' );
		$this->cliMode = $params['cliMode'] ?? ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
		$this->agent = $params['agent'] ?? '';

		if ( isset( $params['chronologyCallback'] ) ) {
			$this->chronologyCallback = $params['chronologyCallback'];
		}

		if ( isset( $params['roundStage'] ) ) {
			if ( $params['roundStage'] === self::STAGE_POSTCOMMIT_CALLBACKS ) {
				$this->trxRoundStage = self::ROUND_COMMIT_CALLBACKS;
			} elseif ( $params['roundStage'] === self::STAGE_POSTROLLBACK_CALLBACKS ) {
				$this->trxRoundStage = self::ROUND_ROLLBACK_CALLBACKS;
			}
		}

		$group = $params['defaultGroup'] ?? self::GROUP_GENERIC;
		$this->defaultGroup = isset( $this->groupLoads[$group] ) ? $group : self::GROUP_GENERIC;

		static $nextId;
		$this->id = $nextId = ( is_int( $nextId ) ? $nextId++ : mt_rand() );
		$this->ownerId = $params['ownerId'] ?? null;
	}

	private static function newTrackedConnectionsArray() {
		return [
			// Connection were transaction rounds may be applied
			self::KEY_LOCAL => [],
			self::KEY_FOREIGN_INUSE => [],
			self::KEY_FOREIGN_FREE => [],
			// Auto-committing counterpart connections that ignore transaction rounds
			self::KEY_LOCAL_NOROUND => [],
			self::KEY_FOREIGN_INUSE_NOROUND => [],
			self::KEY_FOREIGN_FREE_NOROUND => []
		];
	}

	public function getLocalDomainID() {
		return $this->localDomain->getId();
	}

	public function resolveDomainID( $domain ) {
		return $this->resolveDomainInstance( $domain )->getId();
	}

	/**
	 * @param DatabaseDomain|string|bool $domain
	 * @return DatabaseDomain
	 */
	final protected function resolveDomainInstance( $domain ) {
		if ( $domain instanceof DatabaseDomain ) {
			return $domain; // already a domain instance
		} elseif ( $domain === false || $domain === $this->localDomain->getId() ) {
			return $this->localDomain;
		} elseif ( isset( $this->domainAliases[$domain] ) ) {
			$this->domainAliases[$domain] =
				DatabaseDomain::newFromId( $this->domainAliases[$domain] );

			return $this->domainAliases[$domain];
		}

		$cachedDomain = $this->nonLocalDomainCache[$domain] ?? null;
		if ( $cachedDomain === null ) {
			$cachedDomain = DatabaseDomain::newFromId( $domain );
			$this->nonLocalDomainCache = [ $domain => $cachedDomain ];
		}

		return $cachedDomain;
	}

	/**
	 * Resolve $groups into a list of query groups defining as having database servers
	 *
	 * @param string[]|string|bool $groups Query group(s) in preference order, [], or false
	 * @param int $i Specific server index or DB_MASTER/DB_REPLICA
	 * @return string[] Non-empty group list in preference order with the default group appended
	 */
	private function resolveGroups( $groups, $i ) {
		// If a specific replica server was specified, then $groups makes no sense
		if ( $i > 0 && $groups !== [] && $groups !== false ) {
			$list = implode( ', ', (array)$groups );
			throw new LogicException( "Query group(s) ($list) given with server index (#$i)" );
		}

		if ( $groups === [] || $groups === false || $groups === $this->defaultGroup ) {
			$resolvedGroups = [ $this->defaultGroup ]; // common case
		} elseif ( is_string( $groups ) && isset( $this->groupLoads[$groups] ) ) {
			$resolvedGroups = [ $groups, $this->defaultGroup ];
		} elseif ( is_array( $groups ) ) {
			$resolvedGroups = array_keys( array_flip( $groups ) + [ self::GROUP_GENERIC => 1 ] );
		} else {
			$resolvedGroups = [ $this->defaultGroup ];
		}

		return $resolvedGroups;
	}

	/**
	 * @param int $flags Bitfield of class CONN_* constants
	 * @param int $i Specific server index or DB_MASTER/DB_REPLICA
	 * @param string $domain Database domain
	 * @return int Sanitized bitfield
	 */
	private function sanitizeConnectionFlags( $flags, $i, $domain ) {
		// Whether an outside caller is explicitly requesting the master database server
		if ( $i === self::DB_MASTER || $i === $this->getWriterIndex() ) {
			$flags |= self::CONN_INTENT_WRITABLE;
		}

		if ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT ) {
			// Callers use CONN_TRX_AUTOCOMMIT to bypass REPEATABLE-READ staleness without
			// resorting to row locks (e.g. FOR UPDATE) or to make small out-of-band commits
			// during larger transactions. This is useful for avoiding lock contention.

			// Master DB server attributes (should match those of the replica DB servers)
			$attributes = $this->getServerAttributes( $this->getWriterIndex() );
			if ( $attributes[Database::ATTR_DB_LEVEL_LOCKING] ) {
				// The RDBMS does not support concurrent writes (e.g. SQLite), so attempts
				// to use separate connections would just cause self-deadlocks. Note that
				// REPEATABLE-READ staleness is not an issue since DB-level locking means
				// that transactions are Strict Serializable anyway.
				$flags &= ~self::CONN_TRX_AUTOCOMMIT;
				$type = $this->getServerType( $this->getWriterIndex() );
				$this->connLogger->info( __METHOD__ . ": CONN_TRX_AUTOCOMMIT disallowed ($type)" );
			} elseif ( isset( $this->tempTablesOnlyMode[$domain] ) ) {
				// T202116: integration tests are active and queries should be all be using
				// temporary clone tables (via prefix). Such tables are not visible accross
				// different connections nor can there be REPEATABLE-READ snapshot staleness,
				// so use the same connection for everything.
				$flags &= ~self::CONN_TRX_AUTOCOMMIT;
			}
		}

		return $flags;
	}

	/**
	 * @param IDatabase $conn
	 * @param int $flags
	 * @throws DBUnexpectedError
	 */
	private function enforceConnectionFlags( IDatabase $conn, $flags ) {
		if ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT ) {
			if ( $conn->trxLevel() ) { // sanity
				throw new DBUnexpectedError(
					$conn,
					'Handle requested with CONN_TRX_AUTOCOMMIT yet it has a transaction'
				);
			}

			$conn->clearFlag( $conn::DBO_TRX ); // auto-commit mode
		}
	}

	/**
	 * Get a LoadMonitor instance
	 *
	 * @return ILoadMonitor
	 */
	private function getLoadMonitor() {
		if ( !isset( $this->loadMonitor ) ) {
			$compat = [
				'LoadMonitor' => LoadMonitor::class,
				'LoadMonitorNull' => LoadMonitorNull::class,
				'LoadMonitorMySQL' => LoadMonitorMySQL::class,
			];

			$class = $this->loadMonitorConfig['class'];
			if ( isset( $compat[$class] ) ) {
				$class = $compat[$class];
			}

			$this->loadMonitor = new $class(
				$this, $this->srvCache, $this->wanCache, $this->loadMonitorConfig );
			$this->loadMonitor->setLogger( $this->replLogger );
		}

		return $this->loadMonitor;
	}

	/**
	 * @param array $loads
	 * @param bool|string $domain Domain to get non-lagged for
	 * @param int $maxLag Restrict the maximum allowed lag to this many seconds
	 * @return bool|int|string
	 */
	private function getRandomNonLagged( array $loads, $domain = false, $maxLag = INF ) {
		$lags = $this->getLagTimes( $domain );

		# Unset excessively lagged servers
		foreach ( $lags as $i => $lag ) {
			if ( $i !== $this->getWriterIndex() ) {
				# How much lag this server nominally is allowed to have
				$maxServerLag = $this->servers[$i]['max lag'] ?? $this->maxLag; // default
				# Constrain that futher by $maxLag argument
				$maxServerLag = min( $maxServerLag, $maxLag );

				$host = $this->getServerName( $i );
				if ( $lag === false && !is_infinite( $maxServerLag ) ) {
					$this->replLogger->debug(
						__METHOD__ .
						": server {dbserver} is not replicating?", [ 'dbserver' => $host ] );
					unset( $loads[$i] );
				} elseif ( $lag > $maxServerLag ) {
					$this->replLogger->debug(
						__METHOD__ .
						": server {dbserver} has {lag} seconds of lag (>= {maxlag})",
						[ 'dbserver' => $host, 'lag' => $lag, 'maxlag' => $maxServerLag ]
					);
					unset( $loads[$i] );
				}
			}
		}

		# Find out if all the replica DBs with non-zero load are lagged
		$sum = 0;
		foreach ( $loads as $load ) {
			$sum += $load;
		}
		if ( $sum == 0 ) {
			# No appropriate DB servers except maybe the master and some replica DBs with zero load
			# Do NOT use the master
			# Instead, this function will return false, triggering read-only mode,
			# and a lagged replica DB will be used instead.
			return false;
		}

		if ( count( $loads ) == 0 ) {
			return false;
		}

		# Return a random representative of the remainder
		return ArrayUtils::pickRandom( $loads );
	}

	/**
	 * Get the server index to use for a specified server index and query group list
	 *
	 * @param int $i Specific server index or DB_MASTER/DB_REPLICA
	 * @param string[] $groups Non-empty query group list in preference order
	 * @param string|bool $domain
	 * @return int A specific server index (replica DBs are checked for connectivity)
	 */
	private function getConnectionIndex( $i, array $groups, $domain ) {
		if ( $i === self::DB_MASTER ) {
			$i = $this->getWriterIndex();
		} elseif ( $i === self::DB_REPLICA ) {
			foreach ( $groups as $group ) {
				$groupIndex = $this->getReaderIndex( $group, $domain );
				if ( $groupIndex !== false ) {
					$i = $groupIndex; // group connection succeeded
					break;
				}
			}
		} elseif ( !isset( $this->servers[$i] ) ) {
			throw new UnexpectedValueException( "Invalid server index index #$i" );
		}

		if ( $i === self::DB_REPLICA ) {
			$this->lastError = 'Unknown error'; // set here in case of worse failure
			$this->lastError = 'No working replica DB server: ' . $this->lastError;
			$this->reportConnectionError();
			return null; // unreachable due to exception
		}

		return $i;
	}

	public function getReaderIndex( $group = false, $domain = false ) {
		if ( $this->getServerCount() == 1 ) {
			// Skip the load balancing if there's only one server
			return $this->getWriterIndex();
		}

		$group = is_string( $group ) ? $group : self::GROUP_GENERIC;

		$index = $this->getExistingReaderIndex( $group );
		if ( $index >= 0 ) {
			// A reader index was already selected and "waitForPos" was handled
			return $index;
		}

		// Use the server weight array for this load group
		if ( isset( $this->groupLoads[$group] ) ) {
			$loads = $this->groupLoads[$group];
		} else {
			$this->connLogger->info( __METHOD__ . ": no loads for group $group" );

			return false;
		}

		// Scale the configured load ratios according to each server's load and state
		$this->getLoadMonitor()->scaleLoads( $loads, $domain );

		// Pick a server to use, accounting for weights, load, lag, and "waitForPos"
		$this->lazyLoadReplicationPositions(); // optimizes server candidate selection
		list( $i, $laggedReplicaMode ) = $this->pickReaderIndex( $loads, $domain );
		if ( $i === false ) {
			// DB connection unsuccessful
			return false;
		}

		// If data seen by queries is expected to reflect the transactions committed as of
		// or after a given replication position then wait for the DB to apply those changes
		if ( $this->waitForPos && $i !== $this->getWriterIndex() && !$this->doWait( $i ) ) {
			// Data will be outdated compared to what was expected
			$laggedReplicaMode = true;
		}

		// Cache the reader index for future DB_REPLICA handles
		$this->setExistingReaderIndex( $group, $i );
		// Record whether the generic reader index is in "lagged replica DB" mode
		if ( $group === self::GROUP_GENERIC && $laggedReplicaMode ) {
			$this->laggedReplicaMode = true;
		}

		$serverName = $this->getServerName( $i );
		$this->connLogger->debug( __METHOD__ . ": using server $serverName for group '$group'" );

		return $i;
	}

	/**
	 * Get the server index chosen by the load balancer for use with the given query group
	 *
	 * @param string $group Query group; use false for the generic group
	 * @return int Server index or -1 if none was chosen
	 */
	protected function getExistingReaderIndex( $group ) {
		return $this->readIndexByGroup[$group] ?? -1;
	}

	/**
	 * Set the server index chosen by the load balancer for use with the given query group
	 *
	 * @param string $group Query group; use false for the generic group
	 * @param int $index The index of a specific server
	 */
	private function setExistingReaderIndex( $group, $index ) {
		if ( $index < 0 ) {
			throw new UnexpectedValueException( "Cannot set a negative read server index" );
		}
		$this->readIndexByGroup[$group] = $index;
	}

	/**
	 * @param array $loads List of server weights
	 * @param string|bool $domain
	 * @return array (reader index, lagged replica mode) or (false, false) on failure
	 */
	private function pickReaderIndex( array $loads, $domain = false ) {
		if ( $loads === [] ) {
			throw new InvalidArgumentException( "Server configuration array is empty" );
		}

		/** @var int|bool $i Index of selected server */
		$i = false;
		/** @var bool $laggedReplicaMode Whether server is considered lagged */
		$laggedReplicaMode = false;

		// Quickly look through the available servers for a server that meets criteria...
		$currentLoads = $loads;
		while ( count( $currentLoads ) ) {
			if ( $this->allowLagged || $laggedReplicaMode ) {
				$i = ArrayUtils::pickRandom( $currentLoads );
			} else {
				$i = false;
				if ( $this->waitForPos && $this->waitForPos->asOfTime() ) {
					$this->replLogger->debug( __METHOD__ . ": replication positions detected" );
					// "chronologyCallback" sets "waitForPos" for session consistency.
					// This triggers doWait() after connect, so it's especially good to
					// avoid lagged servers so as to avoid excessive delay in that method.
					$ago = microtime( true ) - $this->waitForPos->asOfTime();
					// Aim for <= 1 second of waiting (being too picky can backfire)
					$i = $this->getRandomNonLagged( $currentLoads, $domain, $ago + 1 );
				}
				if ( $i === false ) {
					// Any server with less lag than it's 'max lag' param is preferable
					$i = $this->getRandomNonLagged( $currentLoads, $domain );
				}
				if ( $i === false && count( $currentLoads ) ) {
					// All replica DBs lagged. Switch to read-only mode
					$this->replLogger->error(
						__METHOD__ . ": all replica DBs lagged. Switch to read-only mode" );
					$i = ArrayUtils::pickRandom( $currentLoads );
					$laggedReplicaMode = true;
				}
			}

			if ( $i === false ) {
				// pickRandom() returned false.
				// This is permanent and means the configuration or the load monitor
				// wants us to return false.
				$this->connLogger->debug( __METHOD__ . ": pickRandom() returned false" );

				return [ false, false ];
			}

			$serverName = $this->getServerName( $i );
			$this->connLogger->debug( __METHOD__ . ": Using reader #$i: $serverName..." );

			// Get a connection to this server without triggering other server connections
			$conn = $this->getServerConnection( $i, $domain, self::CONN_SILENCE_ERRORS );
			if ( !$conn ) {
				$this->connLogger->warning( __METHOD__ . ": Failed connecting to $i/$domain" );
				unset( $currentLoads[$i] ); // avoid this server next iteration
				$i = false;
				continue;
			}

			// Decrement reference counter, we are finished with this connection.
			// It will be incremented for the caller later.
			if ( $domain !== false ) {
				$this->reuseConnection( $conn );
			}

			// Return this server
			break;
		}

		// If all servers were down, quit now
		if ( $currentLoads === [] ) {
			$this->connLogger->error( __METHOD__ . ": all servers down" );
		}

		return [ $i, $laggedReplicaMode ];
	}

	public function waitFor( $pos ) {
		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;
			// If a generic reader connection was already established, then wait now
			$i = $this->getExistingReaderIndex( self::GROUP_GENERIC );
			if ( $i > 0 && !$this->doWait( $i ) ) {
				$this->laggedReplicaMode = true;
			}
			// Otherwise, wait until a connection is established in getReaderIndex()
		} finally {
			// Restore the older position if it was higher since this is used for lag-protection
			$this->setWaitForPositionIfHigher( $oldPos );
		}
	}

	public function waitForOne( $pos, $timeout = null ) {
		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;

			$i = $this->getExistingReaderIndex( self::GROUP_GENERIC );
			if ( $i <= 0 ) {
				// Pick a generic replica DB if there isn't one yet
				$readLoads = $this->groupLoads[self::GROUP_GENERIC];
				unset( $readLoads[$this->getWriterIndex()] ); // replica DBs only
				$readLoads = array_filter( $readLoads ); // with non-zero load
				$i = ArrayUtils::pickRandom( $readLoads );
			}

			if ( $i > 0 ) {
				$ok = $this->doWait( $i, true, $timeout );
			} else {
				$ok = true; // no applicable loads
			}
		} finally {
			// Restore the old position; this is used for throttling, not lag-protection
			$this->waitForPos = $oldPos;
		}

		return $ok;
	}

	public function waitForAll( $pos, $timeout = null ) {
		$timeout = $timeout ?: $this->waitTimeout;

		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;
			$serverCount = $this->getServerCount();

			$ok = true;
			for ( $i = 1; $i < $serverCount; $i++ ) {
				if ( $this->serverHasLoadInAnyGroup( $i ) ) {
					$start = microtime( true );
					$ok = $this->doWait( $i, true, $timeout ) && $ok;
					$timeout -= intval( microtime( true ) - $start );
					if ( $timeout <= 0 ) {
						break; // timeout reached
					}
				}
			}
		} finally {
			// Restore the old position; this is used for throttling, not lag-protection
			$this->waitForPos = $oldPos;
		}

		return $ok;
	}

	/**
	 * @param int $i Specific server index
	 * @return bool
	 */
	private function serverHasLoadInAnyGroup( $i ) {
		foreach ( $this->groupLoads as $loadsByIndex ) {
			if ( ( $loadsByIndex[$i] ?? 0 ) > 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param DBMasterPos|bool $pos
	 */
	private function setWaitForPositionIfHigher( $pos ) {
		if ( !$pos ) {
			return;
		}

		if ( !$this->waitForPos || $pos->hasReached( $this->waitForPos ) ) {
			$this->waitForPos = $pos;
		}
	}

	public function getAnyOpenConnection( $i, $flags = 0 ) {
		$i = ( $i === self::DB_MASTER ) ? $this->getWriterIndex() : $i;
		// Connection handles required to be in auto-commit mode use a separate connection
		// pool since the main pool is effected by implicit and explicit transaction rounds
		$autocommit = ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT );

		$conn = false;
		foreach ( $this->conns as $connsByServer ) {
			// Get the connection array server indexes to inspect
			if ( $i === self::DB_REPLICA ) {
				$indexes = array_keys( $connsByServer );
			} else {
				$indexes = isset( $connsByServer[$i] ) ? [ $i ] : [];
			}

			foreach ( $indexes as $index ) {
				$conn = $this->pickAnyOpenConnection( $connsByServer[$index], $autocommit );
				if ( $conn ) {
					break;
				}
			}
		}

		if ( $conn ) {
			$this->enforceConnectionFlags( $conn, $flags );
		}

		return $conn;
	}

	/**
	 * @param IDatabase[] $candidateConns
	 * @param bool $autocommit Whether to only look for auto-commit connections
	 * @return IDatabase|false An appropriate open connection or false if none found
	 */
	private function pickAnyOpenConnection( $candidateConns, $autocommit ) {
		$conn = false;

		foreach ( $candidateConns as $candidateConn ) {
			if ( !$candidateConn->isOpen() ) {
				continue; // some sort of error occurred?
			} elseif (
				$autocommit &&
				(
					// Connection is transaction round aware
					!$candidateConn->getLBInfo( self::$INFO_AUTOCOMMIT_ONLY ) ||
					// Some sort of error left a transaction open?
					$candidateConn->trxLevel()
				)
			) {
				continue; // some sort of error left a transaction open?
			}

			$conn = $candidateConn;
		}

		return $conn;
	}

	/**
	 * Wait for a given replica DB to catch up to the master pos stored in "waitForPos"
	 * @param int $index Specific server index
	 * @param bool $open Check the server even if a new connection has to be made
	 * @param int|null $timeout Max seconds to wait; default is "waitTimeout"
	 * @return bool
	 */
	protected function doWait( $index, $open = false, $timeout = null ) {
		$timeout = max( 1, intval( $timeout ?: $this->waitTimeout ) );

		// Check if we already know that the DB has reached this point
		$server = $this->getServerName( $index );
		$key = $this->srvCache->makeGlobalKey( __CLASS__, 'last-known-pos', $server, 'v1' );
		/** @var DBMasterPos $knownReachedPos */
		$knownReachedPos = $this->srvCache->get( $key );
		if (
			$knownReachedPos instanceof DBMasterPos &&
			$knownReachedPos->hasReached( $this->waitForPos )
		) {
			$this->replLogger->debug(
				__METHOD__ .
				": replica DB {dbserver} known to be caught up (pos >= $knownReachedPos).",
				[ 'dbserver' => $server ]
			);
			return true;
		}

		// Find a connection to wait on, creating one if needed and allowed
		$close = false; // close the connection afterwards
		$flags = self::CONN_SILENCE_ERRORS;
		$conn = $this->getAnyOpenConnection( $index, $flags );
		if ( !$conn ) {
			if ( !$open ) {
				$this->replLogger->debug(
					__METHOD__ . ': no connection open for {dbserver}',
					[ 'dbserver' => $server ]
				);

				return false;
			}
			// Get a connection to this server without triggering other server connections
			$conn = $this->getServerConnection( $index, self::DOMAIN_ANY, $flags );
			if ( !$conn ) {
				$this->replLogger->warning(
					__METHOD__ . ': failed to connect to {dbserver}',
					[ 'dbserver' => $server ]
				);

				return false;
			}
			// Avoid connection spam in waitForAll() when connections
			// are made just for the sake of doing this lag check.
			$close = true;
		}

		$this->replLogger->info(
			__METHOD__ .
			': waiting for replica DB {dbserver} to catch up...',
			[ 'dbserver' => $server ]
		);

		$result = $conn->masterPosWait( $this->waitForPos, $timeout );

		$ok = ( $result !== null && $result != -1 );
		if ( $ok ) {
			// Remember that the DB reached this point
			$this->srvCache->set( $key, $this->waitForPos, BagOStuff::TTL_DAY );
		}

		if ( $close ) {
			$this->closeConnection( $conn );
		}

		return $ok;
	}

	public function getConnection( $i, $groups = [], $domain = false, $flags = 0 ) {
		$domain = $this->resolveDomainID( $domain );
		$groups = $this->resolveGroups( $groups, $i );
		$flags = $this->sanitizeConnectionFlags( $flags, $i, $domain );
		// If given DB_MASTER/DB_REPLICA, resolve it to a specific server index. Resolving
		// DB_REPLICA might trigger getServerConnection() calls due to the getReaderIndex()
		// connectivity checks or LoadMonitor::scaleLoads() server state cache regeneration.
		// The use of getServerConnection() instead of getConnection() avoids infinite loops.
		$serverIndex = $this->getConnectionIndex( $i, $groups, $domain );
		// Get an open connection to that server (might trigger a new connection)
		$conn = $this->getServerConnection( $serverIndex, $domain, $flags );
		// Set master DB handles as read-only if there is high replication lag
		if (
			$serverIndex === $this->getWriterIndex() &&
			$this->getLaggedReplicaMode( $domain ) &&
			!is_string( $conn->getLBInfo( $conn::LB_READ_ONLY_REASON ) )
		) {
			$reason = ( $this->getExistingReaderIndex( self::GROUP_GENERIC ) >= 0 )
				? 'The database is read-only until replication lag decreases.'
				: 'The database is read-only until replica database servers becomes reachable.';
			$conn->setLBInfo( $conn::LB_READ_ONLY_REASON, $reason );
		}

		return $conn;
	}

	/**
	 * @param int $i Specific server index
	 * @param string $domain Resolved DB domain
	 * @param int $flags Bitfield of class CONN_* constants
	 * @return IDatabase|bool
	 * @throws InvalidArgumentException When the server index is invalid
	 */
	public function getServerConnection( $i, $domain, $flags = 0 ) {
		// Number of connections made before getting the server index and handle
		$priorConnectionsMade = $this->connectionCounter;
		// Get an open connection to this server (might trigger a new connection)
		$conn = $this->localDomain->equals( $domain )
			? $this->getLocalConnection( $i, $flags )
			: $this->getForeignConnection( $i, $domain, $flags );
		// Throw an error or otherwise bail out if the connection attempt failed
		if ( !( $conn instanceof IDatabase ) ) {
			if ( ( $flags & self::CONN_SILENCE_ERRORS ) != self::CONN_SILENCE_ERRORS ) {
				$this->reportConnectionError();
			}

			return false;
		}

		// Profile any new connections caused by this method
		if ( $this->connectionCounter > $priorConnectionsMade ) {
			$this->trxProfiler->recordConnection(
				$conn->getServer(),
				$conn->getDBname(),
				( ( $flags & self::CONN_INTENT_WRITABLE ) == self::CONN_INTENT_WRITABLE )
			);
		}

		if ( !$conn->isOpen() ) {
			$this->errorConnection = $conn;
			// Connection was made but later unrecoverably lost for some reason.
			// Do not return a handle that will just throw exceptions on use, but
			// let the calling code, e.g. getReaderIndex(), try another server.
			return false;
		}

		// Make sure that flags like CONN_TRX_AUTOCOMMIT are respected by this handle
		$this->enforceConnectionFlags( $conn, $flags );
		// Set master DB handles as read-only if the load balancer is configured as read-only
		// or the master database server is running in server-side read-only mode. Note that
		// replica DB handles are always read-only via Database::assertIsWritableMaster().
		// Read-only mode due to replication lag is *avoided* here to avoid recursion.
		if ( $i === $this->getWriterIndex() ) {
			if ( $this->readOnlyReason !== false ) {
				$readOnlyReason = $this->readOnlyReason;
			} elseif ( $this->isMasterConnectionReadOnly( $conn, $flags ) ) {
				$readOnlyReason = 'The master database server is running in read-only mode.';
			} else {
				$readOnlyReason = false;
			}
			$conn->setLBInfo( $conn::LB_READ_ONLY_REASON, $readOnlyReason );
		}

		return $conn;
	}

	public function reuseConnection( IDatabase $conn ) {
		$serverIndex = $conn->getLBInfo( self::$INFO_SERVER_INDEX );
		$refCount = $conn->getLBInfo( self::$INFO_FOREIGN_REF_COUNT );
		if ( $serverIndex === null || $refCount === null ) {
			return; // non-foreign connection; no domain-use tracking to update
		} elseif ( $conn instanceof DBConnRef ) {
			// DBConnRef already handles calling reuseConnection() and only passes the live
			// Database instance to this method. Any caller passing in a DBConnRef is broken.
			$this->connLogger->error(
				__METHOD__ . ": got DBConnRef instance.\n" .
				( new LogicException() )->getTraceAsString() );

			return;
		}

		if ( $this->disabled ) {
			return; // DBConnRef handle probably survived longer than the LoadBalancer
		}

		if ( $conn->getLBInfo( self::$INFO_AUTOCOMMIT_ONLY ) ) {
			$connFreeKey = self::KEY_FOREIGN_FREE_NOROUND;
			$connInUseKey = self::KEY_FOREIGN_INUSE_NOROUND;
		} else {
			$connFreeKey = self::KEY_FOREIGN_FREE;
			$connInUseKey = self::KEY_FOREIGN_INUSE;
		}

		$domain = $conn->getDomainID();
		if ( !isset( $this->conns[$connInUseKey][$serverIndex][$domain] ) ) {
			throw new InvalidArgumentException(
				"Connection $serverIndex/$domain not found; it may have already been freed" );
		} elseif ( $this->conns[$connInUseKey][$serverIndex][$domain] !== $conn ) {
			throw new InvalidArgumentException(
				"Connection $serverIndex/$domain mismatched; it may have already been freed" );
		}

		$conn->setLBInfo( self::$INFO_FOREIGN_REF_COUNT, --$refCount );
		if ( $refCount <= 0 ) {
			$this->conns[$connFreeKey][$serverIndex][$domain] = $conn;
			unset( $this->conns[$connInUseKey][$serverIndex][$domain] );
			if ( !$this->conns[$connInUseKey][$serverIndex] ) {
				unset( $this->conns[$connInUseKey][$serverIndex] ); // clean up
			}
			$this->connLogger->debug( __METHOD__ . ": freed connection $serverIndex/$domain" );
		} else {
			$this->connLogger->debug( __METHOD__ .
				": reference count for $serverIndex/$domain reduced to $refCount" );
		}
	}

	public function getConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ) {
		$domain = $this->resolveDomainID( $domain );
		$role = $this->getRoleFromIndex( $i );

		return new DBConnRef( $this, $this->getConnection( $i, $groups, $domain, $flags ), $role );
	}

	public function getLazyConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ) {
		$domain = $this->resolveDomainID( $domain );
		$role = $this->getRoleFromIndex( $i );

		return new DBConnRef( $this, [ $i, $groups, $domain, $flags ], $role );
	}

	public function getMaintenanceConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ) {
		$domain = $this->resolveDomainID( $domain );
		$role = $this->getRoleFromIndex( $i );

		return new MaintainableDBConnRef(
			$this, $this->getConnection( $i, $groups, $domain, $flags ), $role );
	}

	/**
	 * @param int $i Server index or DB_MASTER/DB_REPLICA
	 * @return int One of DB_MASTER/DB_REPLICA
	 */
	private function getRoleFromIndex( $i ) {
		return ( $i === self::DB_MASTER || $i === $this->getWriterIndex() )
			? self::DB_MASTER
			: self::DB_REPLICA;
	}

	/**
	 * @param int $i
	 * @param string|bool $domain
	 * @param int $flags
	 * @return Database|bool Live database handle or false on failure
	 * @deprecated Since 1.34 Use getConnection() instead
	 */
	public function openConnection( $i, $domain = false, $flags = 0 ) {
		return $this->getConnection( $i, [], $domain, $flags | self::CONN_SILENCE_ERRORS );
	}

	/**
	 * Open a connection to a local DB, or return one if it is already open.
	 *
	 * On error, returns false, and the connection which caused the
	 * error will be available via $this->errorConnection.
	 *
	 * @note If disable() was called on this LoadBalancer, this method will throw a DBAccessError.
	 *
	 * @param int $i Specific server index
	 * @param int $flags Class CONN_* constant bitfield
	 * @return Database
	 * @throws InvalidArgumentException When the server index is invalid
	 * @throws UnexpectedValueException When the DB domain of the connection is corrupted
	 */
	private function getLocalConnection( $i, $flags = 0 ) {
		$autoCommit = ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT );
		// Connection handles required to be in auto-commit mode use a separate connection
		// pool since the main pool is effected by implicit and explicit transaction rounds
		$connKey = $autoCommit ? self::KEY_LOCAL_NOROUND : self::KEY_LOCAL;

		if ( isset( $this->conns[$connKey][$i][0] ) ) {
			$conn = $this->conns[$connKey][$i][0];
		} else {
			$conn = $this->reallyOpenConnection(
				$i,
				$this->localDomain,
				[ self::$INFO_AUTOCOMMIT_ONLY => $autoCommit ]
			);
			if ( $conn->isOpen() ) {
				$this->connLogger->debug( __METHOD__ . ": opened new connection for $i" );
				$this->conns[$connKey][$i][0] = $conn;
			} else {
				$this->connLogger->warning( __METHOD__ . ": connection error for $i" );
				$this->errorConnection = $conn;
				$conn = false;
			}
		}

		// Sanity check to make sure that the right domain is selected
		if (
			$conn instanceof IDatabase &&
			!$this->localDomain->isCompatible( $conn->getDomainID() )
		) {
			throw new UnexpectedValueException(
				"Got connection to '{$conn->getDomainID()}', " .
				"but expected local domain ('{$this->localDomain}')"
			);
		}

		return $conn;
	}

	/**
	 * Open a connection to a foreign DB, or return one if it is already open.
	 *
	 * Increments a reference count on the returned connection which locks the
	 * connection to the requested domain. This reference count can be
	 * decremented by calling reuseConnection().
	 *
	 * If a connection is open to the appropriate server already, but with the wrong
	 * database, it will be switched to the right database and returned, as long as
	 * it has been freed first with reuseConnection().
	 *
	 * On error, returns false, and the connection which caused the
	 * error will be available via $this->errorConnection.
	 *
	 * @note If disable() was called on this LoadBalancer, this method will throw a DBAccessError.
	 *
	 * @param int $i Specific server index
	 * @param string $domain Domain ID to open
	 * @param int $flags Class CONN_* constant bitfield
	 * @return Database|bool Returns false on connection error
	 * @throws DBError When database selection fails
	 * @throws InvalidArgumentException When the server index is invalid
	 * @throws UnexpectedValueException When the DB domain of the connection is corrupted
	 */
	private function getForeignConnection( $i, $domain, $flags = 0 ) {
		$domainInstance = DatabaseDomain::newFromId( $domain );
		$autoCommit = ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT );
		// Connection handles required to be in auto-commit mode use a separate connection
		// pool since the main pool is effected by implicit and explicit transaction rounds
		if ( $autoCommit ) {
			$connFreeKey = self::KEY_FOREIGN_FREE_NOROUND;
			$connInUseKey = self::KEY_FOREIGN_INUSE_NOROUND;
		} else {
			$connFreeKey = self::KEY_FOREIGN_FREE;
			$connInUseKey = self::KEY_FOREIGN_INUSE;
		}

		/** @var Database $conn */
		$conn = null;

		if ( isset( $this->conns[$connInUseKey][$i][$domain] ) ) {
			// Reuse an in-use connection for the same domain
			$conn = $this->conns[$connInUseKey][$i][$domain];
			$this->connLogger->debug( __METHOD__ . ": reusing connection $i/$domain" );
		} elseif ( isset( $this->conns[$connFreeKey][$i][$domain] ) ) {
			// Reuse a free connection for the same domain
			$conn = $this->conns[$connFreeKey][$i][$domain];
			unset( $this->conns[$connFreeKey][$i][$domain] );
			$this->conns[$connInUseKey][$i][$domain] = $conn;
			$this->connLogger->debug( __METHOD__ . ": reusing free connection $i/$domain" );
		} elseif ( !empty( $this->conns[$connFreeKey][$i] ) ) {
			// Reuse a free connection from another domain if possible
			foreach ( $this->conns[$connFreeKey][$i] as $oldDomain => $oldConn ) {
				if ( $domainInstance->getDatabase() !== null ) {
					// Check if changing the database will require a new connection.
					// In that case, leave the connection handle alone and keep looking.
					// This prevents connections from being closed mid-transaction and can
					// also avoid overhead if the same database will later be requested.
					if (
						$oldConn->databasesAreIndependent() &&
						$oldConn->getDBname() !== $domainInstance->getDatabase()
					) {
						continue;
					}
					// Select the new database, schema, and prefix
					$conn = $oldConn;
					$conn->selectDomain( $domainInstance );
				} else {
					// Stay on the current database, but update the schema/prefix
					$conn = $oldConn;
					$conn->dbSchema( $domainInstance->getSchema() );
					$conn->tablePrefix( $domainInstance->getTablePrefix() );
				}
				unset( $this->conns[$connFreeKey][$i][$oldDomain] );
				// Note that if $domain is an empty string, getDomainID() might not match it
				$this->conns[$connInUseKey][$i][$conn->getDomainID()] = $conn;
				$this->connLogger->debug( __METHOD__ .
					": reusing free connection from $oldDomain for $domain" );
				break;
			}
		}

		if ( !$conn ) {
			$conn = $this->reallyOpenConnection(
				$i,
				$domainInstance,
				[
					self::$INFO_AUTOCOMMIT_ONLY => $autoCommit,
					self::$INFO_FORIEGN => true,
					self::$INFO_FOREIGN_REF_COUNT => 0
				]
			);
			if ( $conn->isOpen() ) {
				// Note that if $domain is an empty string, getDomainID() might not match it
				$this->conns[$connInUseKey][$i][$conn->getDomainID()] = $conn;
				$this->connLogger->debug( __METHOD__ . ": opened new connection for $i/$domain" );
			} else {
				$this->connLogger->warning( __METHOD__ . ": connection error for $i/$domain" );
				$this->errorConnection = $conn;
				$conn = false;
			}
		}

		if ( $conn instanceof IDatabase ) {
			// Sanity check to make sure that the right domain is selected
			if ( !$domainInstance->isCompatible( $conn->getDomainID() ) ) {
				throw new UnexpectedValueException(
					"Got connection to '{$conn->getDomainID()}', but expected '$domain'" );
			}
			// Increment reference count
			$refCount = $conn->getLBInfo( self::$INFO_FOREIGN_REF_COUNT );
			$conn->setLBInfo( self::$INFO_FOREIGN_REF_COUNT, $refCount + 1 );
		}

		return $conn;
	}

	public function getServerAttributes( $i ) {
		return Database::attributesFromType(
			$this->getServerType( $i ),
			$this->servers[$i]['driver'] ?? null
		);
	}

	/**
	 * Test if the specified index represents an open connection
	 *
	 * @param int $index Server index
	 * @return bool
	 */
	private function isOpen( $index ) {
		return (bool)$this->getAnyOpenConnection( $index );
	}

	/**
	 * Open a new network connection to a server (uncached)
	 *
	 * Returns a Database object whether or not the connection was successful.
	 *
	 * @param int $i Specific server index
	 * @param DatabaseDomain $domain Domain the connection is for, possibly unspecified
	 * @param array $lbInfo Additional information for setLBInfo()
	 * @return Database
	 * @throws DBAccessError
	 * @throws InvalidArgumentException
	 */
	protected function reallyOpenConnection( $i, DatabaseDomain $domain, array $lbInfo ) {
		if ( $this->disabled ) {
			throw new DBAccessError();
		}

		$server = $this->getServerInfoStrict( $i );

		$conn = Database::factory(
			$server['type'],
			array_merge( $server, [
				// Basic replication role information
				'topologyRole' => $this->getTopologyRole( $i, $server ),
				'topologicalMaster' => $this->getMasterServerName(),
				// Use the database specified in $domain (null means "none or entrypoint DB");
				// fallback to the $server default if the RDBMs is an embedded library using a
				// file on disk since there would be nothing to access to without a DB/file name.
				'dbname' => $this->getServerAttributes( $i )[Database::ATTR_DB_IS_FILE]
					? ( $domain->getDatabase() ?? $server['dbname'] ?? null )
					: $domain->getDatabase(),
				// Override the $server default schema with that of $domain if specified
				'schema' => $domain->getSchema() ?? $server['schema'] ?? null,
				// Use the table prefix specified in $domain
				'tablePrefix' => $domain->getTablePrefix(),
				// Participate in transaction rounds if $server does not specify otherwise
				'flags' => $this->initConnFlags( $server['flags'] ?? IDatabase::DBO_DEFAULT ),
				// Inject the PHP execution mode and the agent string
				'cliMode' => $this->cliMode,
				'agent' => $this->agent,
				'ownerId' => $this->id,
				// Inject object and callback dependencies
				'lazyMasterHandle' => $this->getLazyConnectionRef(
					self::DB_MASTER,
					[],
					$domain->getId()
				),
				'srvCache' => $this->srvCache,
				'connLogger' => $this->connLogger,
				'queryLogger' => $this->queryLogger,
				'replLogger' => $this->replLogger,
				'errorLogger' => $this->errorLogger,
				'deprecationLogger' => $this->deprecationLogger,
				'profiler' => $this->profiler,
				'trxProfiler' => $this->trxProfiler
			] ),
			Database::NEW_UNCONNECTED
		);
		// Attach load balancer information to the handle
		$conn->setLBInfo( [ self::$INFO_SERVER_INDEX => $i ] + $lbInfo );
		// Set alternative table/index names before any queries can be issued
		$conn->setTableAliases( $this->tableAliases );
		$conn->setIndexAliases( $this->indexAliases );
		// Account for any active transaction round and listeners
		if ( $i === $this->getWriterIndex() ) {
			if ( $this->trxRoundId !== false ) {
				$this->applyTransactionRoundFlags( $conn );
			}
			foreach ( $this->trxRecurringCallbacks as $name => $callback ) {
				$conn->setTransactionListener( $name, $callback );
			}
		}

		// Make the connection handle live
		try {
			$conn->initConnection();
			++$this->connectionCounter;
		} catch ( DBConnectionError $e ) {
			// ignore; let the DB handle the logging
		}

		// Try to maintain session consistency for clients that trigger write transactions
		// in a request or script and then return soon after in another request or script.
		// This requires cooperation with ChronologyProtector and the application wiring.
		if ( $conn->isOpen() ) {
			$this->lazyLoadReplicationPositions();
		}

		// Log when many connection are made during a single request/script
		$count = $this->getCurrentConnectionCount();
		if ( $count >= self::CONN_HELD_WARN_THRESHOLD ) {
			$this->perfLogger->warning(
				__METHOD__ . ": {connections}+ connections made (master={masterdb})",
				[
					'connections' => $count,
					'dbserver' => $conn->getServer(),
					'masterdb' => $this->getMasterServerName()
				]
			);
		}

		return $conn;
	}

	/**
	 * @param int $i Specific server index
	 * @param array $server Server config map
	 * @return string IDatabase::ROLE_* constant
	 */
	private function getTopologyRole( $i, array $server ) {
		if ( !empty( $server['is static'] ) ) {
			return IDatabase::ROLE_STATIC_CLONE;
		}

		return ( $i === $this->getWriterIndex() )
			? IDatabase::ROLE_STREAMING_MASTER
			: IDatabase::ROLE_STREAMING_REPLICA;
	}

	/**
	 * @see IDatabase::DBO_DEFAULT
	 * @param int $flags Bit field of IDatabase::DBO_* constants from configuration
	 * @return int Bit field of IDatabase::DBO_* constants to use with Database::factory()
	 */
	private function initConnFlags( $flags ) {
		if ( ( $flags & IDatabase::DBO_DEFAULT ) === IDatabase::DBO_DEFAULT ) {
			if ( $this->cliMode ) {
				$flags &= ~IDatabase::DBO_TRX;
			} else {
				$flags |= IDatabase::DBO_TRX;
			}
		}

		return $flags;
	}

	/**
	 * Make sure that any "waitForPos" positions are loaded and available to doWait()
	 */
	private function lazyLoadReplicationPositions() {
		if ( !$this->connectionAttempted && $this->chronologyCallback ) {
			$this->connectionAttempted = true;
			( $this->chronologyCallback )( $this ); // generally calls waitFor()
			$this->connLogger->debug( __METHOD__ . ': executed chronology callback.' );
		}
	}

	/**
	 * @throws DBConnectionError
	 */
	private function reportConnectionError() {
		$conn = $this->errorConnection; // the connection which caused the error
		$context = [
			'method' => __METHOD__,
			'last_error' => $this->lastError,
		];

		if ( $conn instanceof IDatabase ) {
			$context['db_server'] = $conn->getServer();
			$this->connLogger->warning(
				__METHOD__ . ": connection error: {last_error} ({db_server})",
				$context
			);

			throw new DBConnectionError( $conn, "{$this->lastError} ({$context['db_server']})" );
		} else {
			// No last connection, probably due to all servers being too busy
			$this->connLogger->error(
				__METHOD__ .
				": LB failure with no last connection. Connection error: {last_error}",
				$context
			);

			// If all servers were busy, "lastError" will contain something sensible
			throw new DBConnectionError( null, $this->lastError );
		}
	}

	public function getWriterIndex() {
		return 0;
	}

	/**
	 * Returns true if the specified index is a valid server index
	 *
	 * @param int $i
	 * @return bool
	 * @deprecated Since 1.34
	 */
	public function haveIndex( $i ) {
		return array_key_exists( $i, $this->servers );
	}

	/**
	 * Returns true if the specified index is valid and has non-zero load
	 *
	 * @param int $i
	 * @return bool
	 * @deprecated Since 1.34
	 */
	public function isNonZeroLoad( $i ) {
		return ( isset( $this->servers[$i] ) && $this->groupLoads[self::GROUP_GENERIC][$i] > 0 );
	}

	public function getServerCount() {
		return count( $this->servers );
	}

	public function hasReplicaServers() {
		return ( $this->getServerCount() > 1 );
	}

	public function hasStreamingReplicaServers() {
		foreach ( $this->servers as $i => $server ) {
			if ( $i !== $this->getWriterIndex() && empty( $server['is static'] ) ) {
				return true;
			}
		}

		return false;
	}

	public function getServerName( $i ) {
		$name = $this->servers[$i]['hostName'] ?? ( $this->servers[$i]['host'] ?? '' );

		return ( $name != '' ) ? $name : 'localhost';
	}

	public function getServerInfo( $i ) {
		return $this->servers[$i] ?? false;
	}

	public function getServerType( $i ) {
		return $this->servers[$i]['type'] ?? 'unknown';
	}

	public function getMasterPos() {
		$index = $this->getWriterIndex();

		$conn = $this->getAnyOpenConnection( $index );
		if ( $conn ) {
			return $conn->getMasterPos();
		}

		$conn = $this->getConnection( $index, self::CONN_SILENCE_ERRORS );
		if ( !$conn ) {
			$this->reportConnectionError();
			return null; // unreachable due to exception
		}

		try {
			$pos = $conn->getMasterPos();
		} finally {
			$this->closeConnection( $conn );
		}

		return $pos;
	}

	public function getReplicaResumePos() {
		// Get the position of any existing master server connection
		$masterConn = $this->getAnyOpenConnection( $this->getWriterIndex() );
		if ( $masterConn ) {
			return $masterConn->getMasterPos();
		}

		// Get the highest position of any existing replica server connection
		$highestPos = false;
		$serverCount = $this->getServerCount();
		for ( $i = 1; $i < $serverCount; $i++ ) {
			if ( !empty( $this->servers[$i]['is static'] ) ) {
				continue; // server does not use replication
			}

			$conn = $this->getAnyOpenConnection( $i );
			$pos = $conn ? $conn->getReplicaPos() : false;
			if ( !$pos ) {
				continue; // no open connection or could not get position
			}

			$highestPos = $highestPos ?: $pos;
			if ( $pos->hasReached( $highestPos ) ) {
				$highestPos = $pos;
			}
		}

		return $highestPos;
	}

	public function disable( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		$this->closeAll( $fname, $owner );
		$this->disabled = true;
	}

	public function closeAll( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}
		$this->forEachOpenConnection( function ( IDatabase $conn ) use ( $fname ) {
			$host = $conn->getServer();
			$this->connLogger->debug( "$fname: closing connection to database '$host'." );
			$conn->close( $fname, $this->id );
		} );

		$this->conns = self::newTrackedConnectionsArray();
	}

	public function closeConnection( IDatabase $conn ) {
		if ( $conn instanceof DBConnRef ) {
			// Avoid calling close() but still leaving the handle in the pool
			throw new RuntimeException( 'Cannot close DBConnRef instance; it must be shareable' );
		}

		$serverIndex = $conn->getLBInfo( self::$INFO_SERVER_INDEX );
		foreach ( $this->conns as $type => $connsByServer ) {
			if ( !isset( $connsByServer[$serverIndex] ) ) {
				continue;
			}

			foreach ( $connsByServer[$serverIndex] as $i => $trackedConn ) {
				if ( $conn === $trackedConn ) {
					$host = $this->getServerName( $i );
					$this->connLogger->debug(
						__METHOD__ . ": closing connection to database $i at '$host'." );
					unset( $this->conns[$type][$serverIndex][$i] );
					break 2;
				}
			}
		}

		$conn->close( __METHOD__ );
	}

	public function commitAll( $fname = __METHOD__, $owner = null ) {
		$this->commitMasterChanges( $fname, $owner );
		$this->flushMasterSnapshots( $fname, $owner );
		$this->flushReplicaSnapshots( $fname, $owner );
	}

	public function finalizeMasterChanges( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		$this->assertTransactionRoundStage( [ self::ROUND_CURSORY, self::ROUND_FINALIZED ] );
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		// Loop until callbacks stop adding callbacks on other connections
		$total = 0;
		do {
			$count = 0; // callbacks execution attempts
			$this->forEachOpenMasterConnection( function ( Database $conn ) use ( &$count ) {
				// Run any pre-commit callbacks while leaving the post-commit ones suppressed.
				// Any error should cause all (peer) transactions to be rolled back together.
				$count += $conn->runOnTransactionPreCommitCallbacks();
			} );
			$total += $count;
		} while ( $count > 0 );
		// Defer post-commit callbacks until after COMMIT/ROLLBACK happens on all handles
		$this->forEachOpenMasterConnection( function ( Database $conn ) {
			$conn->setTrxEndCallbackSuppression( true );
		} );
		$this->trxRoundStage = self::ROUND_FINALIZED;

		return $total;
	}

	public function approveMasterChanges( array $options, $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		$this->assertTransactionRoundStage( self::ROUND_FINALIZED );
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		$limit = $options['maxWriteDuration'] ?? 0;

		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( $limit ) {
			// If atomic sections or explicit transactions are still open, some caller must have
			// caught an exception but failed to properly rollback any changes. Detect that and
			// throw an error (causing rollback).
			$conn->assertNoOpenTransactions();
			// Assert that the time to replicate the transaction will be sane.
			// If this fails, then all DB transactions will be rollback back together.
			$time = $conn->pendingWriteQueryDuration( $conn::ESTIMATE_DB_APPLY );
			if ( $limit > 0 && $time > $limit ) {
				throw new DBTransactionSizeError(
					$conn,
					"Transaction spent $time second(s) in writes, exceeding the limit of $limit",
					[ $time, $limit ]
				);
			}
			// If a connection sits idle while slow queries execute on another, that connection
			// may end up dropped before the commit round is reached. Ping servers to detect this.
			if ( $conn->writesOrCallbacksPending() && !$conn->ping() ) {
				throw new DBTransactionError(
					$conn,
					"A connection to the {$conn->getDBname()} database was lost before commit"
				);
			}
		} );
		$this->trxRoundStage = self::ROUND_APPROVED;
	}

	public function beginMasterChanges( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		if ( $this->trxRoundId !== false ) {
			throw new DBTransactionError(
				null,
				"$fname: Transaction round '{$this->trxRoundId}' already started"
			);
		}
		$this->assertTransactionRoundStage( self::ROUND_CURSORY );
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		// Clear any empty transactions (no writes/callbacks) from the implicit round
		$this->flushMasterSnapshots( $fname, $owner );

		$this->trxRoundId = $fname;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		// Mark applicable handles as participating in this explicit transaction round.
		// For each of these handles, any writes and callbacks will be tied to a single
		// transaction. The (peer) handles will reject begin()/commit() calls unless they
		// are part of an en masse commit or an en masse rollback.
		$this->forEachOpenMasterConnection( function ( Database $conn ) {
			$this->applyTransactionRoundFlags( $conn );
		} );
		$this->trxRoundStage = self::ROUND_CURSORY;
	}

	public function commitMasterChanges( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		$this->assertTransactionRoundStage( self::ROUND_APPROVED );
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		$failures = [];

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		// Commit any writes and clear any snapshots as well (callbacks require AUTOCOMMIT).
		// Note that callbacks should already be suppressed due to finalizeMasterChanges().
		$this->forEachOpenMasterConnection(
			function ( IDatabase $conn ) use ( $fname, &$failures ) {
				try {
					$conn->commit( $fname, $conn::FLUSHING_ALL_PEERS );
				} catch ( DBError $e ) {
					( $this->errorLogger )( $e );
					$failures[] = "{$conn->getServer()}: {$e->getMessage()}";
				}
			}
		);
		if ( $failures ) {
			throw new DBTransactionError(
				null,
				"$fname: Commit failed on server(s) " . implode( "\n", array_unique( $failures ) )
			);
		}
		if ( $restore ) {
			// Unmark handles as participating in this explicit transaction round
			$this->forEachOpenMasterConnection( function ( Database $conn ) {
				$this->undoTransactionRoundFlags( $conn );
			} );
		}
		$this->trxRoundStage = self::ROUND_COMMIT_CALLBACKS;
	}

	public function runMasterTransactionIdleCallbacks( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		if ( $this->trxRoundStage === self::ROUND_COMMIT_CALLBACKS ) {
			$type = IDatabase::TRIGGER_COMMIT;
		} elseif ( $this->trxRoundStage === self::ROUND_ROLLBACK_CALLBACKS ) {
			$type = IDatabase::TRIGGER_ROLLBACK;
		} else {
			throw new DBTransactionError(
				null,
				"Transaction should be in the callback stage (not '{$this->trxRoundStage}')"
			);
		}
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		$oldStage = $this->trxRoundStage;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise

		// Now that the COMMIT/ROLLBACK step is over, enable post-commit callback runs
		$this->forEachOpenMasterConnection( function ( Database $conn ) {
			$conn->setTrxEndCallbackSuppression( false );
		} );

		$e = null; // first exception
		$fname = __METHOD__;
		// Loop until callbacks stop adding callbacks on other connections
		do {
			// Run any pending callbacks for each connection...
			$count = 0; // callback execution attempts
			$this->forEachOpenMasterConnection(
				function ( Database $conn ) use ( $type, &$e, &$count ) {
					if ( $conn->trxLevel() ) {
						return; // retry in the next iteration, after commit() is called
					}
					try {
						$count += $conn->runOnTransactionIdleCallbacks( $type );
					} catch ( Throwable $ex ) {
						$e = $e ?: $ex;
					}
				}
			);
			// Clear out any active transactions left over from callbacks...
			$this->forEachOpenMasterConnection( function ( Database $conn ) use ( &$e, $fname ) {
				if ( $conn->writesPending() ) {
					// A callback from another handle wrote to this one and DBO_TRX is set
					$this->queryLogger->warning( $fname . ": found writes pending." );
					$fnames = implode( ', ', $conn->pendingWriteAndCallbackCallers() );
					$this->queryLogger->warning(
						"$fname: found writes pending ($fnames).",
						[
							'db_server' => $conn->getServer(),
							'db_name' => $conn->getDBname(),
							'exception' => new RuntimeException()
						]
					);
				} elseif ( $conn->trxLevel() ) {
					// A callback from another handle read from this one and DBO_TRX is set,
					// which can easily happen if there is only one DB (no replicas)
					$this->queryLogger->debug( "$fname: found empty transaction." );
				}
				try {
					$conn->commit( $fname, $conn::FLUSHING_ALL_PEERS );
				} catch ( Throwable $ex ) {
					$e = $e ?: $ex;
				}
			} );
		} while ( $count > 0 );

		$this->trxRoundStage = $oldStage;

		return $e;
	}

	public function runMasterTransactionListenerCallbacks( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		if ( $this->trxRoundStage === self::ROUND_COMMIT_CALLBACKS ) {
			$type = IDatabase::TRIGGER_COMMIT;
		} elseif ( $this->trxRoundStage === self::ROUND_ROLLBACK_CALLBACKS ) {
			$type = IDatabase::TRIGGER_ROLLBACK;
		} else {
			throw new DBTransactionError(
				null,
				"Transaction should be in the callback stage (not '{$this->trxRoundStage}')"
			);
		}
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		$e = null;

		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		$this->forEachOpenMasterConnection( function ( Database $conn ) use ( $type, &$e ) {
			try {
				$conn->runTransactionListenerCallbacks( $type );
			} catch ( Throwable $ex ) {
				$e = $e ?: $ex;
			}
		} );
		$this->trxRoundStage = self::ROUND_CURSORY;

		return $e;
	}

	public function rollbackMasterChanges( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		if ( $this->ownerId === null ) {
			/** @noinspection PhpUnusedLocalVariableInspection */
			$scope = ScopedCallback::newScopedIgnoreUserAbort();
		}

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( $fname ) {
			$conn->rollback( $fname, $conn::FLUSHING_ALL_PEERS );
		} );
		if ( $restore ) {
			// Unmark handles as participating in this explicit transaction round
			$this->forEachOpenMasterConnection( function ( Database $conn ) {
				$this->undoTransactionRoundFlags( $conn );
			} );
		}
		$this->trxRoundStage = self::ROUND_ROLLBACK_CALLBACKS;
	}

	/**
	 * @param string|string[] $stage
	 * @throws DBTransactionError
	 */
	private function assertTransactionRoundStage( $stage ) {
		$stages = (array)$stage;

		if ( !in_array( $this->trxRoundStage, $stages, true ) ) {
			$stageList = implode(
				'/',
				array_map( function ( $v ) {
					return "'$v'";
				}, $stages )
			);
			throw new DBTransactionError(
				null,
				"Transaction round stage must be $stageList (not '{$this->trxRoundStage}')"
			);
		}
	}

	/**
	 * Assure that if this instance is owned, the caller is either the owner or is internal
	 *
	 * If an LBFactory owns the LoadBalancer, then certain methods should only called through
	 * that LBFactory to avoid broken contracts. Otherwise, those methods can publically be
	 * called by anything. In any case, internal methods from the LoadBalancer itself should
	 * always be allowed.
	 *
	 * @param string $fname
	 * @param int|null $owner Owner ID of the caller
	 * @throws DBTransactionError
	 */
	private function assertOwnership( $fname, $owner ) {
		if ( $this->ownerId !== null && $owner !== $this->ownerId && $owner !== $this->id ) {
			throw new DBTransactionError(
				null,
				"$fname: LoadBalancer is owned by ID '{$this->ownerId}' (got '$owner')."
			);
		}
	}

	/**
	 * Make all DB servers with DBO_DEFAULT/DBO_TRX set join the transaction round
	 *
	 * Some servers may have neither flag enabled, meaning that they opt out of such
	 * transaction rounds and remain in auto-commit mode. Such behavior might be desired
	 * when a DB server is used for something like simple key/value storage.
	 *
	 * @param Database $conn
	 */
	private function applyTransactionRoundFlags( Database $conn ) {
		if ( $conn->getLBInfo( self::$INFO_AUTOCOMMIT_ONLY ) ) {
			return; // transaction rounds do not apply to these connections
		}

		if ( $conn->getFlag( $conn::DBO_DEFAULT ) ) {
			// DBO_TRX is controlled entirely by CLI mode presence with DBO_DEFAULT.
			// Force DBO_TRX even in CLI mode since a commit round is expected soon.
			$conn->setFlag( $conn::DBO_TRX, $conn::REMEMBER_PRIOR );
		}

		if ( $conn->getFlag( $conn::DBO_TRX ) ) {
			$conn->setLBInfo( $conn::LB_TRX_ROUND_ID, $this->trxRoundId );
		}
	}

	/**
	 * @param Database $conn
	 */
	private function undoTransactionRoundFlags( Database $conn ) {
		if ( $conn->getLBInfo( self::$INFO_AUTOCOMMIT_ONLY ) ) {
			return; // transaction rounds do not apply to these connections
		}

		if ( $conn->getFlag( $conn::DBO_TRX ) ) {
			$conn->setLBInfo( $conn::LB_TRX_ROUND_ID, null ); // remove the round ID
		}

		if ( $conn->getFlag( $conn::DBO_DEFAULT ) ) {
			$conn->restoreFlags( $conn::RESTORE_PRIOR );
		}
	}

	public function flushReplicaSnapshots( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		$this->forEachOpenReplicaConnection( function ( IDatabase $conn ) use ( $fname ) {
			$conn->flushSnapshot( $fname );
		} );
	}

	public function flushMasterSnapshots( $fname = __METHOD__, $owner = null ) {
		$this->assertOwnership( $fname, $owner );
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( $fname ) {
			$conn->flushSnapshot( $fname );
		} );
	}

	/**
	 * @return string
	 * @since 1.32
	 */
	public function getTransactionRoundStage() {
		return $this->trxRoundStage;
	}

	public function hasMasterConnection() {
		return $this->isOpen( $this->getWriterIndex() );
	}

	public function hasMasterChanges() {
		$pending = false;
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( &$pending ) {
			$pending = $pending || $conn->writesOrCallbacksPending();
		} );

		return $pending;
	}

	public function lastMasterChangeTimestamp() {
		$lastTime = false;
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( &$lastTime ) {
			$lastTime = max( $lastTime, $conn->lastDoneWrites() );
		} );

		return $lastTime;
	}

	public function hasOrMadeRecentMasterChanges( $age = null ) {
		$age = ( $age === null ) ? $this->waitTimeout : $age;

		return ( $this->hasMasterChanges()
			|| $this->lastMasterChangeTimestamp() > microtime( true ) - $age );
	}

	public function pendingMasterChangeCallers() {
		$fnames = [];
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( &$fnames ) {
			$fnames = array_merge( $fnames, $conn->pendingWriteCallers() );
		} );

		return $fnames;
	}

	public function getLaggedReplicaMode( $domain = false ) {
		if ( $this->laggedReplicaMode ) {
			return true; // stay in lagged replica mode
		}

		if ( $this->hasStreamingReplicaServers() ) {
			// This will set "laggedReplicaMode" as needed
			$this->getReaderIndex( self::GROUP_GENERIC, $domain );
		}

		return $this->laggedReplicaMode;
	}

	public function laggedReplicaUsed() {
		return $this->laggedReplicaMode;
	}

	public function getReadOnlyReason( $domain = false ) {
		$domainInstance = DatabaseDomain::newFromId( $this->resolveDomainID( $domain ) );

		if ( $this->readOnlyReason !== false ) {
			return $this->readOnlyReason;
		} elseif ( $this->isMasterRunningReadOnly( $domainInstance ) ) {
			return 'The master database server is running in read-only mode.';
		} elseif ( $this->getLaggedReplicaMode( $domain ) ) {
			return ( $this->getExistingReaderIndex( self::GROUP_GENERIC ) >= 0 )
				? 'The database is read-only until replication lag decreases.'
				: 'The database is read-only until a replica database server becomes reachable.';
		}

		return false;
	}

	/**
	 * @param IDatabase $conn Master connection
	 * @param int $flags Bitfield of class CONN_* constants
	 * @return bool Whether the entire server or currently selected DB/schema is read-only
	 */
	private function isMasterConnectionReadOnly( IDatabase $conn, $flags = 0 ) {
		// Note that table prefixes are not related to server-side read-only mode
		$key = $this->srvCache->makeGlobalKey(
			'rdbms-server-readonly',
			$conn->getServer(),
			$conn->getDBname(),
			$conn->dbSchema()
		);

		if ( ( $flags & self::CONN_REFRESH_READ_ONLY ) == self::CONN_REFRESH_READ_ONLY ) {
			try {
				$readOnly = (int)$conn->serverIsReadOnly();
			} catch ( DBError $e ) {
				$readOnly = 0;
			}
			$this->srvCache->set( $key, $readOnly, BagOStuff::TTL_PROC_SHORT );
		} else {
			$readOnly = $this->srvCache->getWithSetCallback(
				$key,
				BagOStuff::TTL_PROC_SHORT,
				function () use ( $conn ) {
					try {
						return (int)$conn->serverIsReadOnly();
					} catch ( DBError $e ) {
						return 0;
					}
				}
			);
		}

		return (bool)$readOnly;
	}

	/**
	 * @param DatabaseDomain $domain
	 * @return bool Whether the entire master server or the local domain DB is read-only
	 */
	private function isMasterRunningReadOnly( DatabaseDomain $domain ) {
		// Context will often be HTTP GET/HEAD; heavily cache the results
		return (bool)$this->wanCache->getWithSetCallback(
			// Note that table prefixes are not related to server-side read-only mode
			$this->wanCache->makeGlobalKey(
				'rdbms-server-readonly',
				$this->getMasterServerName(),
				$domain->getDatabase(),
				$domain->getSchema()
			),
			self::TTL_CACHE_READONLY,
			function () use ( $domain ) {
				$old = $this->trxProfiler->setSilenced( true );
				try {
					$index = $this->getWriterIndex();
					// Reset the cache for isMasterConnectionReadOnly()
					$flags = self::CONN_REFRESH_READ_ONLY;
					$conn = $this->getServerConnection( $index, $domain->getId(), $flags );
					// Reuse the process cache set above
					$readOnly = (int)$this->isMasterConnectionReadOnly( $conn );
					$this->reuseConnection( $conn );
				} catch ( DBError $e ) {
					$readOnly = 0;
				}
				$this->trxProfiler->setSilenced( $old );

				return $readOnly;
			},
			[ 'pcTTL' => WANObjectCache::TTL_PROC_LONG, 'lockTSE' => 10, 'busyValue' => 0 ]
		);
	}

	public function allowLagged( $mode = null ) {
		if ( $mode === null ) {
			return $this->allowLagged;
		}
		$this->allowLagged = $mode;

		return $this->allowLagged;
	}

	public function pingAll() {
		$success = true;
		$this->forEachOpenConnection( function ( IDatabase $conn ) use ( &$success ) {
			if ( !$conn->ping() ) {
				$success = false;
			}
		} );

		return $success;
	}

	public function forEachOpenConnection( $callback, array $params = [] ) {
		foreach ( $this->conns as $connsByServer ) {
			foreach ( $connsByServer as $serverConns ) {
				foreach ( $serverConns as $conn ) {
					$callback( $conn, ...$params );
				}
			}
		}
	}

	public function forEachOpenMasterConnection( $callback, array $params = [] ) {
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->conns as $connsByServer ) {
			if ( isset( $connsByServer[$masterIndex] ) ) {
				/** @var IDatabase $conn */
				foreach ( $connsByServer[$masterIndex] as $conn ) {
					$callback( $conn, ...$params );
				}
			}
		}
	}

	public function forEachOpenReplicaConnection( $callback, array $params = [] ) {
		foreach ( $this->conns as $connsByServer ) {
			foreach ( $connsByServer as $i => $serverConns ) {
				if ( $i === $this->getWriterIndex() ) {
					continue; // skip master
				}
				foreach ( $serverConns as $conn ) {
					$callback( $conn, ...$params );
				}
			}
		}
	}

	/**
	 * @return int
	 */
	private function getCurrentConnectionCount() {
		$count = 0;
		foreach ( $this->conns as $connsByServer ) {
			foreach ( $connsByServer as $serverConns ) {
				$count += count( $serverConns );
			}
		}

		return $count;
	}

	public function getMaxLag( $domain = false ) {
		$host = '';
		$maxLag = -1;
		$maxIndex = 0;

		if ( $this->hasReplicaServers() ) {
			$lagTimes = $this->getLagTimes( $domain );
			foreach ( $lagTimes as $i => $lag ) {
				if ( $this->groupLoads[self::GROUP_GENERIC][$i] > 0 && $lag > $maxLag ) {
					$maxLag = $lag;
					$host = $this->getServerInfoStrict( $i, 'host' );
					$maxIndex = $i;
				}
			}
		}

		return [ $host, $maxLag, $maxIndex ];
	}

	public function getLagTimes( $domain = false ) {
		if ( !$this->hasReplicaServers() ) {
			return [ $this->getWriterIndex() => 0 ]; // no replication = no lag
		}

		$knownLagTimes = []; // map of (server index => 0 seconds)
		$indexesWithLag = [];
		foreach ( $this->servers as $i => $server ) {
			if ( empty( $server['is static'] ) ) {
				$indexesWithLag[] = $i; // DB server might have replication lag
			} else {
				$knownLagTimes[$i] = 0; // DB server is a non-replicating and read-only archive
			}
		}

		return $this->getLoadMonitor()->getLagTimes( $indexesWithLag, $domain ) + $knownLagTimes;
	}

	/**
	 * Get the lag in seconds for a given connection, or zero if this load
	 * balancer does not have replication enabled.
	 *
	 * This should be used in preference to Database::getLag() in cases where
	 * replication may not be in use, since there is no way to determine if
	 * replication is in use at the connection level without running
	 * potentially restricted queries such as SHOW SLAVE STATUS. Using this
	 * function instead of Database::getLag() avoids a fatal error in this
	 * case on many installations.
	 *
	 * @param IDatabase $conn
	 * @return int|bool Returns false on error
	 * @deprecated Since 1.34 Use IDatabase::getLag() instead
	 */
	public function safeGetLag( IDatabase $conn ) {
		return $conn->getLag();
	}

	public function waitForMasterPos( IDatabase $conn, $pos = false, $timeout = null ) {
		$timeout = max( 1, $timeout ?: $this->waitTimeout );

		if ( $conn->getLBInfo( self::$INFO_SERVER_INDEX ) === $this->getWriterIndex() ) {
			return true; // not a replica DB server
		}

		if ( !$pos ) {
			// Get the current master position, opening a connection if needed
			$index = $this->getWriterIndex();
			$flags = self::CONN_SILENCE_ERRORS;
			$masterConn = $this->getAnyOpenConnection( $index, $flags );
			if ( $masterConn ) {
				$pos = $masterConn->getMasterPos();
			} else {
				$masterConn = $this->getServerConnection( $index, self::DOMAIN_ANY, $flags );
				if ( !$masterConn ) {
					throw new DBReplicationWaitError(
						null,
						"Could not obtain a master database connection to get the position"
					);
				}
				$pos = $masterConn->getMasterPos();
				$this->closeConnection( $masterConn );
			}
		}

		if ( $pos instanceof DBMasterPos ) {
			$start = microtime( true );
			$result = $conn->masterPosWait( $pos, $timeout );
			$seconds = max( microtime( true ) - $start, 0 );

			$ok = ( $result !== null && $result != -1 );
			if ( $ok ) {
				$this->replLogger->warning(
					__METHOD__ . ': timed out waiting on {dbserver} pos {pos} [{seconds}s]',
					[
						'dbserver' => $conn->getServer(),
						'pos' => $pos,
						'seconds' => round( $seconds, 6 ),
						'trace' => ( new RuntimeException() )->getTraceAsString()
					]
				);
			} else {
				$this->replLogger->debug( __METHOD__ . ': done waiting' );
			}
		} else {
			$ok = false; // something is misconfigured
			$this->replLogger->error(
				__METHOD__ . ': could not get master pos for {dbserver}',
				[
					'dbserver' => $conn->getServer(),
					'trace' => ( new RuntimeException() )->getTraceAsString()
				]
			);
		}

		return $ok;
	}

	/**
	 * Wait for a replica DB to reach a specified master position
	 *
	 * This will connect to the master to get an accurate position if $pos is not given
	 *
	 * @param IDatabase $conn Replica DB
	 * @param DBMasterPos|bool $pos Master position; default: current position
	 * @param int $timeout Timeout in seconds [optional]
	 * @return bool Success
	 * @since 1.28
	 * @deprecated Since 1.34 Use waitForMasterPos() instead
	 */
	public function safeWaitForMasterPos( IDatabase $conn, $pos = false, $timeout = null ) {
		return $this->waitForMasterPos( $conn, $pos, $timeout );
	}

	public function setTransactionListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->trxRecurringCallbacks[$name] = $callback;
		} else {
			unset( $this->trxRecurringCallbacks[$name] );
		}
		$this->forEachOpenMasterConnection(
			function ( IDatabase $conn ) use ( $name, $callback ) {
				$conn->setTransactionListener( $name, $callback );
			}
		);
	}

	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	public function setIndexAliases( array $aliases ) {
		$this->indexAliases = $aliases;
	}

	public function setDomainAliases( array $aliases ) {
		$this->domainAliases = $aliases;
	}

	public function setLocalDomainPrefix( $prefix ) {
		// Find connections to explicit foreign domains still marked as in-use...
		$domainsInUse = [];
		$this->forEachOpenConnection( function ( IDatabase $conn ) use ( &$domainsInUse ) {
			// Once reuseConnection() is called on a handle, its reference count goes from 1 to 0.
			// Until then, it is still in use by the caller (explicitly or via DBConnRef scope).
			if ( $conn->getLBInfo( self::$INFO_FOREIGN_REF_COUNT ) > 0 ) {
				$domainsInUse[] = $conn->getDomainID();
			}
		} );

		// Do not switch connections to explicit foreign domains unless marked as safe
		if ( $domainsInUse ) {
			$domains = implode( ', ', $domainsInUse );
			throw new DBUnexpectedError( null,
				"Foreign domain connections are still in use ($domains)" );
		}

		$this->setLocalDomain( new DatabaseDomain(
			$this->localDomain->getDatabase(),
			$this->localDomain->getSchema(),
			$prefix
		) );

		// Update the prefix for all local connections...
		$this->forEachOpenConnection( function ( IDatabase $conn ) use ( $prefix ) {
			if ( !$conn->getLBInfo( self::$INFO_FORIEGN ) ) {
				$conn->tablePrefix( $prefix );
			}
		} );
	}

	public function redefineLocalDomain( $domain ) {
		$this->closeAll( __METHOD__, $this->id );

		$this->setLocalDomain( DatabaseDomain::newFromId( $domain ) );
	}

	public function setTempTablesOnlyMode( $value, $domain ) {
		$old = $this->tempTablesOnlyMode[$domain] ?? false;
		if ( $value ) {
			$this->tempTablesOnlyMode[$domain] = true;
		} else {
			unset( $this->tempTablesOnlyMode[$domain] );
		}

		return $old;
	}

	/**
	 * @param DatabaseDomain $domain
	 */
	private function setLocalDomain( DatabaseDomain $domain ) {
		$this->localDomain = $domain;
	}

	/**
	 * @param int $i Server index
	 * @param string|null $field Server index field [optional]
	 * @return array|mixed
	 * @throws InvalidArgumentException
	 */
	private function getServerInfoStrict( $i, $field = null ) {
		if ( !isset( $this->servers[$i] ) || !is_array( $this->servers[$i] ) ) {
			throw new InvalidArgumentException( "No server with index '$i'" );
		}

		if ( $field !== null ) {
			if ( !array_key_exists( $field, $this->servers[$i] ) ) {
				throw new InvalidArgumentException( "No field '$field' in server index '$i'" );
			}

			return $this->servers[$i][$field];
		}

		return $this->servers[$i];
	}

	/**
	 * @return string Name of the master server of the relevant DB cluster (e.g. "db1052")
	 */
	private function getMasterServerName() {
		return $this->getServerName( $this->getWriterIndex() );
	}

	public function __destruct() {
		// Avoid connection leaks for sanity
		$this->disable( __METHOD__, $this->ownerId );
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( LoadBalancer::class, 'LoadBalancer' );
