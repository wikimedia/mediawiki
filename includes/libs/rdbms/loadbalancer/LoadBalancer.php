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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ScopedCallback;
use BagOStuff;
use EmptyBagOStuff;
use WANObjectCache;
use ArrayUtils;
use InvalidArgumentException;
use RuntimeException;
use Exception;

/**
 * Database connection, tracking, load balancing, and transaction manager for a cluster
 *
 * @ingroup Database
 */
class LoadBalancer implements ILoadBalancer {
	/** @var array[] Map of (server index => server config array) */
	private $servers;
	/** @var Database[][][] Map of (connection category => server index => IDatabase[]) */
	private $conns;
	/** @var float[] Map of (server index => weight) */
	private $loads;
	/** @var array[] Map of (group => server index => weight) */
	private $groupLoads;
	/** @var bool Whether to disregard replica DB lag as a factor in replica DB selection */
	private $allowLagged;
	/** @var int Seconds to spend waiting on replica DB lag to resolve */
	private $waitTimeout;
	/** @var array The LoadMonitor configuration */
	private $loadMonitorConfig;
	/** @var array[] $aliases Map of (table => (dbname, schema, prefix) map) */
	private $tableAliases = [];
	/** @var string[] Map of (index alias => index) */
	private $indexAliases = [];

	/** @var ILoadMonitor */
	private $loadMonitor;
	/** @var callable|null Callback to run before the first connection attempt */
	private $chronologyCallback;
	/** @var BagOStuff */
	private $srvCache;
	/** @var WANObjectCache */
	private $wanCache;
	/** @var object|string Class name or object With profileIn/profileOut methods */
	protected $profiler;
	/** @var TransactionProfiler */
	protected $trxProfiler;
	/** @var LoggerInterface */
	protected $replLogger;
	/** @var LoggerInterface */
	protected $connLogger;
	/** @var LoggerInterface */
	protected $queryLogger;
	/** @var LoggerInterface */
	protected $perfLogger;

	/** @var Database DB connection object that caused a problem */
	private $errorConnection;
	/** @var int The generic (not query grouped) replica DB index (of $mServers) */
	private $readIndex;
	/** @var bool|DBMasterPos False if not set */
	private $waitForPos;
	/** @var bool Whether the generic reader fell back to a lagged replica DB */
	private $laggedReplicaMode = false;
	/** @var bool Whether the generic reader fell back to a lagged replica DB */
	private $allReplicasDownMode = false;
	/** @var string The last DB selection or connection error */
	private $lastError = 'Unknown error';
	/** @var string|bool Reason the LB is read-only or false if not */
	private $readOnlyReason = false;
	/** @var int Total connections opened */
	private $connsOpened = 0;
	/** @var string|bool String if a requested DBO_TRX transaction round is active */
	private $trxRoundId = false;
	/** @var array[] Map of (name => callable) */
	private $trxRecurringCallbacks = [];
	/** @var DatabaseDomain Local Domain ID and default for selectDB() calls */
	private $localDomain;
	/** @var string Alternate ID string for the domain instead of DatabaseDomain::getId() */
	private $localDomainIdAlias;
	/** @var string Current server name */
	private $host;
	/** @var bool Whether this PHP instance is for a CLI script */
	protected $cliMode;
	/** @var string Agent name for query profiling */
	protected $agent;

	/** @var callable Exception logger */
	private $errorLogger;
	/** @var callable Deprecation logger */
	private $deprecationLogger;

	/** @var bool */
	private $disabled = false;
	/** @var bool Whether any connection has been attempted yet */
	private $connectionAttempted = false;
	/** @var int */
	private $maxLag = self::MAX_LAG_DEFAULT;

	/** @var int Warn when this many connection are held */
	const CONN_HELD_WARN_THRESHOLD = 10;

	/** @var int Default 'maxLag' when unspecified */
	const MAX_LAG_DEFAULT = 10;
	/** @var int Default 'waitTimeout' when unspecified */
	const MAX_WAIT_DEFAULT = 10;
	/** @var int Seconds to cache master server read-only status */
	const TTL_CACHE_READONLY = 5;

	const KEY_LOCAL = 'local';
	const KEY_FOREIGN_FREE = 'foreignFree';
	const KEY_FOREIGN_INUSE = 'foreignInUse';

	const KEY_LOCAL_NOROUND = 'localAutoCommit';
	const KEY_FOREIGN_FREE_NOROUND = 'foreignFreeAutoCommit';
	const KEY_FOREIGN_INUSE_NOROUND = 'foreignInUseAutoCommit';

	public function __construct( array $params ) {
		if ( !isset( $params['servers'] ) ) {
			throw new InvalidArgumentException( __CLASS__ . ': missing servers parameter' );
		}
		$this->servers = $params['servers'];
		foreach ( $this->servers as $i => $server ) {
			if ( $i == 0 ) {
				$this->servers[$i]['master'] = true;
			} else {
				$this->servers[$i]['replica'] = true;
			}
		}

		$localDomain = isset( $params['localDomain'] )
			? DatabaseDomain::newFromId( $params['localDomain'] )
			: DatabaseDomain::newUnspecified();
		$this->setLocalDomain( $localDomain );

		$this->waitTimeout = isset( $params['waitTimeout'] )
			? $params['waitTimeout']
			: self::MAX_WAIT_DEFAULT;

		$this->readIndex = -1;
		$this->conns = [
			// Connection were transaction rounds may be applied
			self::KEY_LOCAL => [],
			self::KEY_FOREIGN_INUSE => [],
			self::KEY_FOREIGN_FREE => [],
			// Auto-committing counterpart connections that ignore transaction rounds
			self::KEY_LOCAL_NOROUND => [],
			self::KEY_FOREIGN_INUSE_NOROUND => [],
			self::KEY_FOREIGN_FREE_NOROUND => []
		];
		$this->loads = [];
		$this->waitForPos = false;
		$this->allowLagged = false;

		if ( isset( $params['readOnlyReason'] ) && is_string( $params['readOnlyReason'] ) ) {
			$this->readOnlyReason = $params['readOnlyReason'];
		}

		if ( isset( $params['maxLag'] ) ) {
			$this->maxLag = $params['maxLag'];
		}

		if ( isset( $params['loadMonitor'] ) ) {
			$this->loadMonitorConfig = $params['loadMonitor'];
		} else {
			$this->loadMonitorConfig = [ 'class' => 'LoadMonitorNull' ];
		}
		$this->loadMonitorConfig += [ 'lagWarnThreshold' => $this->maxLag ];

		foreach ( $params['servers'] as $i => $server ) {
			$this->loads[$i] = $server['load'];
			if ( isset( $server['groupLoads'] ) ) {
				foreach ( $server['groupLoads'] as $group => $ratio ) {
					if ( !isset( $this->groupLoads[$group] ) ) {
						$this->groupLoads[$group] = [];
					}
					$this->groupLoads[$group][$i] = $ratio;
				}
			}
		}

		if ( isset( $params['srvCache'] ) ) {
			$this->srvCache = $params['srvCache'];
		} else {
			$this->srvCache = new EmptyBagOStuff();
		}
		if ( isset( $params['wanCache'] ) ) {
			$this->wanCache = $params['wanCache'];
		} else {
			$this->wanCache = WANObjectCache::newEmpty();
		}
		$this->profiler = isset( $params['profiler'] ) ? $params['profiler'] : null;
		if ( isset( $params['trxProfiler'] ) ) {
			$this->trxProfiler = $params['trxProfiler'];
		} else {
			$this->trxProfiler = new TransactionProfiler();
		}

		$this->errorLogger = isset( $params['errorLogger'] )
			? $params['errorLogger']
			: function ( Exception $e ) {
				trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
			};
		$this->deprecationLogger = isset( $params['deprecationLogger'] )
			? $params['deprecationLogger']
			: function ( $msg ) {
				trigger_error( $msg, E_USER_DEPRECATED );
			};

		foreach ( [ 'replLogger', 'connLogger', 'queryLogger', 'perfLogger' ] as $key ) {
			$this->$key = isset( $params[$key] ) ? $params[$key] : new NullLogger();
		}

		$this->host = isset( $params['hostname'] )
			? $params['hostname']
			: ( gethostname() ?: 'unknown' );
		$this->cliMode = isset( $params['cliMode'] )
			? $params['cliMode']
			: ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
		$this->agent = isset( $params['agent'] ) ? $params['agent'] : '';

		if ( isset( $params['chronologyCallback'] ) ) {
			$this->chronologyCallback = $params['chronologyCallback'];
		}
	}

	/**
	 * Get the local (and default) database domain ID of connection handles
	 *
	 * @see DatabaseDomain
	 * @return string Database domain ID; this specifies DB name, schema, and table prefix
	 * @since 1.31
	 */
	public function getLocalDomainID() {
		return $this->localDomain->getId();
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
			if ( $i != 0 ) {
				# How much lag this server nominally is allowed to have
				$maxServerLag = isset( $this->servers[$i]['max lag'] )
					? $this->servers[$i]['max lag']
					: $this->maxLag; // default
				# Constrain that futher by $maxLag argument
				$maxServerLag = min( $maxServerLag, $maxLag );

				$host = $this->getServerName( $i );
				if ( $lag === false && !is_infinite( $maxServerLag ) ) {
					$this->replLogger->error(
						__METHOD__ .
						": server {host} is not replicating?", [ 'host' => $host ] );
					unset( $loads[$i] );
				} elseif ( $lag > $maxServerLag ) {
					$this->replLogger->info(
						__METHOD__ .
						": server {host} has {lag} seconds of lag (>= {maxlag})",
						[ 'host' => $host, 'lag' => $lag, 'maxlag' => $maxServerLag ]
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

	public function getReaderIndex( $group = false, $domain = false ) {
		if ( count( $this->servers ) == 1 ) {
			// Skip the load balancing if there's only one server
			return $this->getWriterIndex();
		} elseif ( $group === false && $this->readIndex >= 0 ) {
			// Shortcut if the generic reader index was already cached
			return $this->readIndex;
		}

		if ( $group !== false ) {
			// Use the server weight array for this load group
			if ( isset( $this->groupLoads[$group] ) ) {
				$loads = $this->groupLoads[$group];
			} else {
				// No loads for this group, return false and the caller can use some other group
				$this->connLogger->info( __METHOD__ . ": no loads for group $group" );

				return false;
			}
		} else {
			// Use the generic load group
			$loads = $this->loads;
		}

		// Scale the configured load ratios according to each server's load and state
		$this->getLoadMonitor()->scaleLoads( $loads, $domain );

		// Pick a server to use, accounting for weights, load, lag, and "waitForPos"
		list( $i, $laggedReplicaMode ) = $this->pickReaderIndex( $loads, $domain );
		if ( $i === false ) {
			// Replica DB connection unsuccessful
			return false;
		}

		if ( $this->waitForPos && $i != $this->getWriterIndex() ) {
			// Before any data queries are run, wait for the server to catch up to the
			// specified position. This is used to improve session consistency. Note that
			// when LoadBalancer::waitFor() sets "waitForPos", the waiting triggers here,
			// so update laggedReplicaMode as needed for consistency.
			if ( !$this->doWait( $i ) ) {
				$laggedReplicaMode = true;
			}
		}

		if ( $this->readIndex <= 0 && $this->loads[$i] > 0 && $group === false ) {
			// Cache the generic reader index for future ungrouped DB_REPLICA handles
			$this->readIndex = $i;
			// Record if the generic reader index is in "lagged replica DB" mode
			if ( $laggedReplicaMode ) {
				$this->laggedReplicaMode = true;
			}
		}

		$serverName = $this->getServerName( $i );
		$this->connLogger->debug( __METHOD__ . ": using server $serverName for group '$group'" );

		return $i;
	}

	/**
	 * @param array $loads List of server weights
	 * @param string|bool $domain
	 * @return array (reader index, lagged replica mode) or false on failure
	 */
	private function pickReaderIndex( array $loads, $domain = false ) {
		if ( !count( $loads ) ) {
			throw new InvalidArgumentException( "Empty server array given to LoadBalancer" );
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
				if ( $i === false && count( $currentLoads ) != 0 ) {
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

			$conn = $this->openConnection( $i, $domain );
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
		if ( !count( $currentLoads ) ) {
			$this->connLogger->error( __METHOD__ . ": all servers down" );
		}

		return [ $i, $laggedReplicaMode ];
	}

	public function waitFor( $pos ) {
		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;
			// If a generic reader connection was already established, then wait now
			$i = $this->readIndex;
			if ( $i > 0 ) {
				if ( !$this->doWait( $i ) ) {
					$this->laggedReplicaMode = true;
				}
			}
		} finally {
			// Restore the older position if it was higher since this is used for lag-protection
			$this->setWaitForPositionIfHigher( $oldPos );
		}
	}

	public function waitForOne( $pos, $timeout = null ) {
		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;

			$i = $this->readIndex;
			if ( $i <= 0 ) {
				// Pick a generic replica DB if there isn't one yet
				$readLoads = $this->loads;
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
			# Restore the old position, as this is not used for lag-protection but for throttling
			$this->waitForPos = $oldPos;
		}

		return $ok;
	}

	public function waitForAll( $pos, $timeout = null ) {
		$timeout = $timeout ?: $this->waitTimeout;

		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;
			$serverCount = count( $this->servers );

			$ok = true;
			for ( $i = 1; $i < $serverCount; $i++ ) {
				if ( $this->loads[$i] > 0 ) {
					$start = microtime( true );
					$ok = $this->doWait( $i, true, $timeout ) && $ok;
					$timeout -= intval( microtime( true ) - $start );
					if ( $timeout <= 0 ) {
						break; // timeout reached
					}
				}
			}
		} finally {
			# Restore the old position, as this is not used for lag-protection but for throttling
			$this->waitForPos = $oldPos;
		}

		return $ok;
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
		$autocommit = ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT );
		foreach ( $this->conns as $connsByServer ) {
			if ( !isset( $connsByServer[$i] ) ) {
				continue;
			}

			foreach ( $connsByServer[$i] as $conn ) {
				if ( !$autocommit || $conn->getLBInfo( 'autoCommitOnly' ) ) {
					return $conn;
				}
			}
		}

		return false;
	}

	/**
	 * Wait for a given replica DB to catch up to the master pos stored in $this
	 * @param int $index Server index
	 * @param bool $open Check the server even if a new connection has to be made
	 * @param int $timeout Max seconds to wait; default is "waitTimeout" given to __construct()
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
				': replica DB {dbserver} known to be caught up (pos >= $knownReachedPos).',
				[ 'dbserver' => $server ]
			);
			return true;
		}

		// Find a connection to wait on, creating one if needed and allowed
		$close = false; // close the connection afterwards
		$conn = $this->getAnyOpenConnection( $index );
		if ( !$conn ) {
			if ( !$open ) {
				$this->replLogger->debug(
					__METHOD__ . ': no connection open for {dbserver}',
					[ 'dbserver' => $server ]
				);

				return false;
			} else {
				$conn = $this->openConnection( $index, self::DOMAIN_ANY );
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
		}

		$this->replLogger->info(
			__METHOD__ .
			': waiting for replica DB {dbserver} to catch up...',
			[ 'dbserver' => $server ]
		);

		$result = $conn->masterPosWait( $this->waitForPos, $timeout );

		if ( $result === null ) {
			$this->replLogger->warning(
				__METHOD__ . ': Errored out waiting on {host} pos {pos}',
				[
					'host' => $server,
					'pos' => $this->waitForPos,
					'trace' => ( new RuntimeException() )->getTraceAsString()
				]
			);
			$ok = false;
		} elseif ( $result == -1 ) {
			$this->replLogger->warning(
				__METHOD__ . ': Timed out waiting on {host} pos {pos}',
				[
					'host' => $server,
					'pos' => $this->waitForPos,
					'trace' => ( new RuntimeException() )->getTraceAsString()
				]
			);
			$ok = false;
		} else {
			$this->replLogger->debug( __METHOD__ . ": done waiting" );
			$ok = true;
			// Remember that the DB reached this point
			$this->srvCache->set( $key, $this->waitForPos, BagOStuff::TTL_DAY );
		}

		if ( $close ) {
			$this->closeConnection( $conn );
		}

		return $ok;
	}

	public function getConnection( $i, $groups = [], $domain = false, $flags = 0 ) {
		if ( $i === null || $i === false ) {
			throw new InvalidArgumentException( 'Attempt to call ' . __METHOD__ .
				' with invalid server index' );
		}

		if ( $this->localDomain->equals( $domain ) || $domain === $this->localDomainIdAlias ) {
			$domain = false; // local connection requested
		}

		if ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) === self::CONN_TRX_AUTOCOMMIT ) {
			// Assuming all servers are of the same type (or similar), which is overwhelmingly
			// the case, use the master server information to get the attributes. The information
			// for $i cannot be used since it might be DB_REPLICA, which might require connection
			// attempts in order to be resolved into a real server index.
			$attributes = $this->getServerAttributes( $this->getWriterIndex() );
			if ( $attributes[Database::ATTR_DB_LEVEL_LOCKING] ) {
				// Callers sometimes want to (a) escape REPEATABLE-READ stateness without locking
				// rows (e.g. FOR UPDATE) or (b) make small commits during a larger transactions
				// to reduce lock contention. None of these apply for sqlite and using separate
				// connections just causes self-deadlocks.
				$flags &= ~self::CONN_TRX_AUTOCOMMIT;
				$this->connLogger->info( __METHOD__ .
					': ignoring CONN_TRX_AUTOCOMMIT to avoid deadlocks.' );
			}
		}

		$groups = ( $groups === false || $groups === [] )
			? [ false ] // check one "group": the generic pool
			: (array)$groups;

		$masterOnly = ( $i == self::DB_MASTER || $i == $this->getWriterIndex() );
		$oldConnsOpened = $this->connsOpened; // connections open now

		if ( $i == self::DB_MASTER ) {
			$i = $this->getWriterIndex();
		} elseif ( $i == self::DB_REPLICA ) {
			# Try to find an available server in any the query groups (in order)
			foreach ( $groups as $group ) {
				$groupIndex = $this->getReaderIndex( $group, $domain );
				if ( $groupIndex !== false ) {
					$i = $groupIndex;
					break;
				}
			}
		}

		# Operation-based index
		if ( $i == self::DB_REPLICA ) {
			$this->lastError = 'Unknown error'; // reset error string
			# Try the general server pool if $groups are unavailable.
			$i = ( $groups === [ false ] )
				? false // don't bother with this if that is what was tried above
				: $this->getReaderIndex( false, $domain );
			# Couldn't find a working server in getReaderIndex()?
			if ( $i === false ) {
				$this->lastError = 'No working replica DB server: ' . $this->lastError;
				// Throw an exception
				$this->reportConnectionError();
				return null; // not reached
			}
		}

		# Now we have an explicit index into the servers array
		$conn = $this->openConnection( $i, $domain, $flags );
		if ( !$conn ) {
			// Throw an exception
			$this->reportConnectionError();
			return null; // not reached
		}

		# Profile any new connections that happen
		if ( $this->connsOpened > $oldConnsOpened ) {
			$host = $conn->getServer();
			$dbname = $conn->getDBname();
			$this->trxProfiler->recordConnection( $host, $dbname, $masterOnly );
		}

		if ( $masterOnly ) {
			# Make master-requested DB handles inherit any read-only mode setting
			$conn->setLBInfo( 'readOnlyReason', $this->getReadOnlyReason( $domain, $conn ) );
		}

		return $conn;
	}

	public function reuseConnection( IDatabase $conn ) {
		$serverIndex = $conn->getLBInfo( 'serverIndex' );
		$refCount = $conn->getLBInfo( 'foreignPoolRefCount' );
		if ( $serverIndex === null || $refCount === null ) {
			/**
			 * This can happen in code like:
			 *   foreach ( $dbs as $db ) {
			 *     $conn = $lb->getConnection( $lb::DB_REPLICA, [], $db );
			 *     ...
			 *     $lb->reuseConnection( $conn );
			 *   }
			 * When a connection to the local DB is opened in this way, reuseConnection()
			 * should be ignored
			 */
			return;
		} elseif ( $conn instanceof DBConnRef ) {
			// DBConnRef already handles calling reuseConnection() and only passes the live
			// Database instance to this method. Any caller passing in a DBConnRef is broken.
			$this->connLogger->error(
				__METHOD__ . ": got DBConnRef instance.\n" .
				( new RuntimeException() )->getTraceAsString() );

			return;
		}

		if ( $this->disabled ) {
			return; // DBConnRef handle probably survived longer than the LoadBalancer
		}

		if ( $conn->getLBInfo( 'autoCommitOnly' ) ) {
			$connFreeKey = self::KEY_FOREIGN_FREE_NOROUND;
			$connInUseKey = self::KEY_FOREIGN_INUSE_NOROUND;
		} else {
			$connFreeKey = self::KEY_FOREIGN_FREE;
			$connInUseKey = self::KEY_FOREIGN_INUSE;
		}

		$domain = $conn->getDomainID();
		if ( !isset( $this->conns[$connInUseKey][$serverIndex][$domain] ) ) {
			throw new InvalidArgumentException( __METHOD__ .
				": connection $serverIndex/$domain not found; it may have already been freed." );
		} elseif ( $this->conns[$connInUseKey][$serverIndex][$domain] !== $conn ) {
			throw new InvalidArgumentException( __METHOD__ .
				": connection $serverIndex/$domain mismatched; it may have already been freed." );
		}

		$conn->setLBInfo( 'foreignPoolRefCount', --$refCount );
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

	public function getConnectionRef( $db, $groups = [], $domain = false, $flags = 0 ) {
		$domain = ( $domain !== false ) ? $domain : $this->localDomain;

		return new DBConnRef( $this, $this->getConnection( $db, $groups, $domain, $flags ) );
	}

	public function getLazyConnectionRef( $db, $groups = [], $domain = false, $flags = 0 ) {
		$domain = ( $domain !== false ) ? $domain : $this->localDomain;

		return new DBConnRef( $this, [ $db, $groups, $domain, $flags ] );
	}

	public function getMaintenanceConnectionRef( $db, $groups = [], $domain = false, $flags = 0 ) {
		$domain = ( $domain !== false ) ? $domain : $this->localDomain;

		return new MaintainableDBConnRef(
			$this, $this->getConnection( $db, $groups, $domain, $flags ) );
	}

	public function openConnection( $i, $domain = false, $flags = 0 ) {
		if ( $this->localDomain->equals( $domain ) || $domain === $this->localDomainIdAlias ) {
			$domain = false; // local connection requested
		}

		if ( !$this->connectionAttempted && $this->chronologyCallback ) {
			$this->connLogger->debug( __METHOD__ . ': calling initLB() before first connection.' );
			// Load any "waitFor" positions before connecting so that doWait() is triggered
			$this->connectionAttempted = true;
			call_user_func( $this->chronologyCallback, $this );
		}

		// Check if an auto-commit connection is being requested. If so, it will not reuse the
		// main set of DB connections but rather its own pool since:
		// a) those are usually set to implicitly use transaction rounds via DBO_TRX
		// b) those must support the use of explicit transaction rounds via beginMasterChanges()
		$autoCommit = ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT );

		if ( $domain !== false ) {
			// Connection is to a foreign domain
			$conn = $this->openForeignConnection( $i, $domain, $flags );
		} else {
			// Connection is to the local domain
			$connKey = $autoCommit ? self::KEY_LOCAL_NOROUND : self::KEY_LOCAL;
			if ( isset( $this->conns[$connKey][$i][0] ) ) {
				$conn = $this->conns[$connKey][$i][0];
			} else {
				if ( !isset( $this->servers[$i] ) || !is_array( $this->servers[$i] ) ) {
					throw new InvalidArgumentException( "No server with index '$i'." );
				}
				// Open a new connection
				$server = $this->servers[$i];
				$server['serverIndex'] = $i;
				$server['autoCommitOnly'] = $autoCommit;
				if ( $this->localDomain->getDatabase() !== null ) {
					// Use the local domain table prefix if the local domain is specified
					$server['tablePrefix'] = $this->localDomain->getTablePrefix();
				}
				$conn = $this->reallyOpenConnection( $server, $this->localDomain );
				$host = $this->getServerName( $i );
				if ( $conn->isOpen() ) {
					$this->connLogger->debug(
						__METHOD__ . ": connected to database $i at '$host'." );
					$this->conns[$connKey][$i][0] = $conn;
				} else {
					$this->connLogger->warning(
						__METHOD__ . ": failed to connect to database $i at '$host'." );
					$this->errorConnection = $conn;
					$conn = false;
				}
			}
		}

		if ( $conn instanceof IDatabase && !$conn->isOpen() ) {
			// Connection was made but later unrecoverably lost for some reason.
			// Do not return a handle that will just throw exceptions on use,
			// but let the calling code (e.g. getReaderIndex) try another server.
			// See DatabaseMyslBase::ping() for how this can happen.
			$this->errorConnection = $conn;
			$conn = false;
		}

		if ( $autoCommit && $conn instanceof IDatabase ) {
			$conn->clearFlag( $conn::DBO_TRX ); // auto-commit mode
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
	 * @param int $i Server index
	 * @param string $domain Domain ID to open
	 * @param int $flags Class CONN_* constant bitfield
	 * @return Database
	 */
	private function openForeignConnection( $i, $domain, $flags = 0 ) {
		$domainInstance = DatabaseDomain::newFromId( $domain );
		$dbName = $domainInstance->getDatabase();
		$prefix = $domainInstance->getTablePrefix();
		$autoCommit = ( ( $flags & self::CONN_TRX_AUTOCOMMIT ) == self::CONN_TRX_AUTOCOMMIT );

		if ( $autoCommit ) {
			$connFreeKey = self::KEY_FOREIGN_FREE_NOROUND;
			$connInUseKey = self::KEY_FOREIGN_INUSE_NOROUND;
		} else {
			$connFreeKey = self::KEY_FOREIGN_FREE;
			$connInUseKey = self::KEY_FOREIGN_INUSE;
		}

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
			// Reuse a free connection from another domain
			$conn = reset( $this->conns[$connFreeKey][$i] );
			$oldDomain = key( $this->conns[$connFreeKey][$i] );
			if ( strlen( $dbName ) && !$conn->selectDB( $dbName ) ) {
				$this->lastError = "Error selecting database '$dbName' on server " .
					$conn->getServer() . " from client host {$this->host}";
				$this->errorConnection = $conn;
				$conn = false;
			} else {
				$conn->tablePrefix( $prefix );
				unset( $this->conns[$connFreeKey][$i][$oldDomain] );
				// Note that if $domain is an empty string, getDomainID() might not match it
				$this->conns[$connInUseKey][$i][$conn->getDomainId()] = $conn;
				$this->connLogger->debug( __METHOD__ .
					": reusing free connection from $oldDomain for $domain" );
			}
		} else {
			if ( !isset( $this->servers[$i] ) || !is_array( $this->servers[$i] ) ) {
				throw new InvalidArgumentException( "No server with index '$i'." );
			}
			// Open a new connection
			$server = $this->servers[$i];
			$server['serverIndex'] = $i;
			$server['foreignPoolRefCount'] = 0;
			$server['foreign'] = true;
			$server['autoCommitOnly'] = $autoCommit;
			$conn = $this->reallyOpenConnection( $server, $domainInstance );
			if ( !$conn->isOpen() ) {
				$this->connLogger->warning( __METHOD__ . ": connection error for $i/$domain" );
				$this->errorConnection = $conn;
				$conn = false;
			} else {
				$conn->tablePrefix( $prefix ); // as specified
				// Note that if $domain is an empty string, getDomainID() might not match it
				$this->conns[$connInUseKey][$i][$conn->getDomainID()] = $conn;
				$this->connLogger->debug( __METHOD__ . ": opened new connection for $i/$domain" );
			}
		}

		// Increment reference count
		if ( $conn instanceof IDatabase ) {
			$refCount = $conn->getLBInfo( 'foreignPoolRefCount' );
			$conn->setLBInfo( 'foreignPoolRefCount', $refCount + 1 );
		}

		return $conn;
	}

	public function getServerAttributes( $i ) {
		return Database::attributesFromType(
			$this->getServerType( $i ),
			isset( $this->servers[$i]['driver'] ) ? $this->servers[$i]['driver'] : null
		);
	}

	/**
	 * Test if the specified index represents an open connection
	 *
	 * @param int $index Server index
	 * @access private
	 * @return bool
	 */
	private function isOpen( $index ) {
		if ( !is_int( $index ) ) {
			return false;
		}

		return (bool)$this->getAnyOpenConnection( $index );
	}

	/**
	 * Open a new network connection to a server (uncached)
	 *
	 * Returns a Database object whether or not the connection was successful.
	 *
	 * @param array $server
	 * @param DatabaseDomain $domainOverride Use an unspecified domain to not select any database
	 * @return Database
	 * @throws DBAccessError
	 * @throws InvalidArgumentException
	 */
	protected function reallyOpenConnection( array $server, DatabaseDomain $domainOverride ) {
		if ( $this->disabled ) {
			throw new DBAccessError();
		}

		// Handle $domainOverride being a specified or an unspecified domain
		if ( $domainOverride->getDatabase() === null ) {
			// Normally, an RDBMS requires a DB name specified on connection and the $server
			// configuration array is assumed to already specify an appropriate DB name.
			if ( $server['type'] === 'mysql' ) {
				// For MySQL, DATABASE and SCHEMA are synonyms, connections need not specify a DB,
				// and the DB name in $server might not exist due to legacy reasons (the default
				// domain used to ignore the local LB domain, even when mismatched).
				$server['dbname'] = null;
			}
		} else {
			$server['dbname'] = $domainOverride->getDatabase();
			$server['schema'] = $domainOverride->getSchema();
		}

		// Let the handle know what the cluster master is (e.g. "db1052")
		$masterName = $this->getServerName( $this->getWriterIndex() );
		$server['clusterMasterHost'] = $masterName;

		// Log when many connection are made on requests
		if ( ++$this->connsOpened >= self::CONN_HELD_WARN_THRESHOLD ) {
			$this->perfLogger->warning( __METHOD__ . ": " .
				"{$this->connsOpened}+ connections made (master=$masterName)" );
		}

		$server['srvCache'] = $this->srvCache;
		// Set loggers and profilers
		$server['connLogger'] = $this->connLogger;
		$server['queryLogger'] = $this->queryLogger;
		$server['errorLogger'] = $this->errorLogger;
		$server['deprecationLogger'] = $this->deprecationLogger;
		$server['profiler'] = $this->profiler;
		$server['trxProfiler'] = $this->trxProfiler;
		// Use the same agent and PHP mode for all DB handles
		$server['cliMode'] = $this->cliMode;
		$server['agent'] = $this->agent;
		// Use DBO_DEFAULT flags by default for LoadBalancer managed databases. Assume that the
		// application calls LoadBalancer::commitMasterChanges() before the PHP script completes.
		$server['flags'] = isset( $server['flags'] ) ? $server['flags'] : IDatabase::DBO_DEFAULT;

		// Create a live connection object
		try {
			$db = Database::factory( $server['type'], $server );
		} catch ( DBConnectionError $e ) {
			// FIXME: This is probably the ugliest thing I have ever done to
			// PHP. I'm half-expecting it to segfault, just out of disgust. -- TS
			$db = $e->db;
		}

		$db->setLBInfo( $server );
		$db->setLazyMasterHandle(
			$this->getLazyConnectionRef( self::DB_MASTER, [], $db->getDomainID() )
		);
		$db->setTableAliases( $this->tableAliases );
		$db->setIndexAliases( $this->indexAliases );

		if ( $server['serverIndex'] === $this->getWriterIndex() ) {
			if ( $this->trxRoundId !== false ) {
				$this->applyTransactionRoundFlags( $db );
			}
			foreach ( $this->trxRecurringCallbacks as $name => $callback ) {
				$db->setTransactionListener( $name, $callback );
			}
		}

		return $db;
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

			// throws DBConnectionError
			$conn->reportConnectionError( "{$this->lastError} ({$context['db_server']})" );
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

	public function haveIndex( $i ) {
		return array_key_exists( $i, $this->servers );
	}

	public function isNonZeroLoad( $i ) {
		return array_key_exists( $i, $this->servers ) && $this->loads[$i] != 0;
	}

	public function getServerCount() {
		return count( $this->servers );
	}

	public function getServerName( $i ) {
		if ( isset( $this->servers[$i]['hostName'] ) ) {
			$name = $this->servers[$i]['hostName'];
		} elseif ( isset( $this->servers[$i]['host'] ) ) {
			$name = $this->servers[$i]['host'];
		} else {
			$name = '';
		}

		return ( $name != '' ) ? $name : 'localhost';
	}

	public function getServerInfo( $i ) {
		if ( isset( $this->servers[$i] ) ) {
			return $this->servers[$i];
		} else {
			return false;
		}
	}

	public function getServerType( $i ) {
		return isset( $this->servers[$i]['type'] ) ? $this->servers[$i]['type'] : 'unknown';
	}

	public function getMasterPos() {
		# If this entire request was served from a replica DB without opening a connection to the
		# master (however unlikely that may be), then we can fetch the position from the replica DB.
		$masterConn = $this->getAnyOpenConnection( $this->getWriterIndex() );
		if ( !$masterConn ) {
			$serverCount = count( $this->servers );
			for ( $i = 1; $i < $serverCount; $i++ ) {
				$conn = $this->getAnyOpenConnection( $i );
				if ( $conn ) {
					return $conn->getReplicaPos();
				}
			}
		} else {
			return $masterConn->getMasterPos();
		}

		return false;
	}

	public function disable() {
		$this->closeAll();
		$this->disabled = true;
	}

	public function closeAll() {
		$this->forEachOpenConnection( function ( IDatabase $conn ) {
			$host = $conn->getServer();
			$this->connLogger->debug(
				__METHOD__ . ": closing connection to database '$host'." );
			$conn->close();
		} );

		$this->conns = [
			self::KEY_LOCAL => [],
			self::KEY_FOREIGN_INUSE => [],
			self::KEY_FOREIGN_FREE => [],
			self::KEY_LOCAL_NOROUND => [],
			self::KEY_FOREIGN_INUSE_NOROUND => [],
			self::KEY_FOREIGN_FREE_NOROUND => []
		];
		$this->connsOpened = 0;
	}

	public function closeConnection( IDatabase $conn ) {
		$serverIndex = $conn->getLBInfo( 'serverIndex' );
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
					--$this->connsOpened;
					break 2;
				}
			}
		}

		$conn->close();
	}

	public function commitAll( $fname = __METHOD__ ) {
		$failures = [];

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->forEachOpenConnection(
			function ( IDatabase $conn ) use ( $fname, $restore, &$failures ) {
				try {
					$conn->commit( $fname, $conn::FLUSHING_ALL_PEERS );
				} catch ( DBError $e ) {
					call_user_func( $this->errorLogger, $e );
					$failures[] = "{$conn->getServer()}: {$e->getMessage()}";
				}
				if ( $restore && $conn->getLBInfo( 'master' ) ) {
					$this->undoTransactionRoundFlags( $conn );
				}
			}
		);

		if ( $failures ) {
			throw new DBExpectedError(
				null,
				"Commit failed on server(s) " . implode( "\n", array_unique( $failures ) )
			);
		}
	}

	public function finalizeMasterChanges() {
		$this->forEachOpenMasterConnection( function ( Database $conn ) {
			// Any error should cause all DB transactions to be rolled back together
			$conn->setTrxEndCallbackSuppression( false );
			$conn->runOnTransactionPreCommitCallbacks();
			// Defer post-commit callbacks until COMMIT finishes for all DBs
			$conn->setTrxEndCallbackSuppression( true );
		} );
	}

	public function approveMasterChanges( array $options ) {
		$limit = isset( $options['maxWriteDuration'] ) ? $options['maxWriteDuration'] : 0;
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( $limit ) {
			// If atomic sections or explicit transactions are still open, some caller must have
			// caught an exception but failed to properly rollback any changes. Detect that and
			// throw and error (causing rollback).
			if ( $conn->explicitTrxActive() ) {
				throw new DBTransactionError(
					$conn,
					"Explicit transaction still active. A caller may have caught an error."
				);
			}
			// Assert that the time to replicate the transaction will be sane.
			// If this fails, then all DB transactions will be rollback back together.
			$time = $conn->pendingWriteQueryDuration( $conn::ESTIMATE_DB_APPLY );
			if ( $limit > 0 && $time > $limit ) {
				throw new DBTransactionSizeError(
					$conn,
					"Transaction spent $time second(s) in writes, exceeding the limit of $limit.",
					[ $time, $limit ]
				);
			}
			// If a connection sits idle while slow queries execute on another, that connection
			// may end up dropped before the commit round is reached. Ping servers to detect this.
			if ( $conn->writesOrCallbacksPending() && !$conn->ping() ) {
				throw new DBTransactionError(
					$conn,
					"A connection to the {$conn->getDBname()} database was lost before commit."
				);
			}
		} );
	}

	public function beginMasterChanges( $fname = __METHOD__ ) {
		if ( $this->trxRoundId !== false ) {
			throw new DBTransactionError(
				null,
				"$fname: Transaction round '{$this->trxRoundId}' already started."
			);
		}
		$this->trxRoundId = $fname;

		$failures = [];
		$this->forEachOpenMasterConnection(
			function ( Database $conn ) use ( $fname, &$failures ) {
				$conn->setTrxEndCallbackSuppression( true );
				try {
					$conn->flushSnapshot( $fname );
				} catch ( DBError $e ) {
					call_user_func( $this->errorLogger, $e );
					$failures[] = "{$conn->getServer()}: {$e->getMessage()}";
				}
				$conn->setTrxEndCallbackSuppression( false );
				$this->applyTransactionRoundFlags( $conn );
			}
		);

		if ( $failures ) {
			throw new DBExpectedError(
				null,
				"$fname: Flush failed on server(s) " . implode( "\n", array_unique( $failures ) )
			);
		}
	}

	public function commitMasterChanges( $fname = __METHOD__ ) {
		$failures = [];

		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = $this->getScopedPHPBehaviorForCommit(); // try to ignore client aborts

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->forEachOpenMasterConnection(
			function ( IDatabase $conn ) use ( $fname, $restore, &$failures ) {
				try {
					if ( $conn->writesOrCallbacksPending() ) {
						$conn->commit( $fname, $conn::FLUSHING_ALL_PEERS );
					} elseif ( $restore ) {
						$conn->flushSnapshot( $fname );
					}
				} catch ( DBError $e ) {
					call_user_func( $this->errorLogger, $e );
					$failures[] = "{$conn->getServer()}: {$e->getMessage()}";
				}
				if ( $restore ) {
					$this->undoTransactionRoundFlags( $conn );
				}
			}
		);

		if ( $failures ) {
			throw new DBExpectedError(
				null,
				"$fname: Commit failed on server(s) " . implode( "\n", array_unique( $failures ) )
			);
		}
	}

	public function runMasterPostTrxCallbacks( $type ) {
		$e = null; // first exception
		$this->forEachOpenMasterConnection( function ( Database $conn ) use ( $type, &$e ) {
			$conn->setTrxEndCallbackSuppression( false );
			// Callbacks run in AUTO-COMMIT mode, so make sure no transactions are pending...
			if ( $conn->writesPending() ) {
				// This happens if onTransactionIdle() callbacks write to *other* handles
				// (which already finished their callbacks). Let any callbacks run in the final
				// commitMasterChanges() in LBFactory::shutdown(), when the transaction is gone.
				$this->queryLogger->warning( __METHOD__ . ": found writes pending." );
				return;
			} elseif ( $conn->trxLevel() ) {
				// This happens for single-DB setups where DB_REPLICA uses the master DB,
				// thus leaving an implicit read-only transaction open at this point. It
				// also happens if onTransactionIdle() callbacks leave implicit transactions
				// open on *other* DBs (which is slightly improper). Let these COMMIT on the
				// next call to commitMasterChanges(), possibly in LBFactory::shutdown().
				return;
			}
			try {
				$conn->runOnTransactionIdleCallbacks( $type );
			} catch ( Exception $ex ) {
				$e = $e ?: $ex;
			}
			try {
				$conn->runTransactionListenerCallbacks( $type );
			} catch ( Exception $ex ) {
				$e = $e ?: $ex;
			}
		} );

		return $e;
	}

	public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->forEachOpenMasterConnection(
			function ( IDatabase $conn ) use ( $fname, $restore ) {
				$conn->rollback( $fname, $conn::FLUSHING_ALL_PEERS );
				if ( $restore ) {
					$this->undoTransactionRoundFlags( $conn );
				}
			}
		);
	}

	public function suppressTransactionEndCallbacks() {
		$this->forEachOpenMasterConnection( function ( Database $conn ) {
			$conn->setTrxEndCallbackSuppression( true );
		} );
	}

	/**
	 * Make all DB servers with DBO_DEFAULT/DBO_TRX set join the transaction round
	 *
	 * Some servers may have neither flag enabled, meaning that they opt out of such
	 * transaction rounds and remain in auto-commit mode. Such behavior might be desired
	 * when a DB server is used for something like simple key/value storage.
	 *
	 * @param IDatabase $conn
	 */
	private function applyTransactionRoundFlags( IDatabase $conn ) {
		if ( $conn->getLBInfo( 'autoCommitOnly' ) ) {
			return; // transaction rounds do not apply to these connections
		}

		if ( $conn->getFlag( $conn::DBO_DEFAULT ) ) {
			// DBO_TRX is controlled entirely by CLI mode presence with DBO_DEFAULT.
			// Force DBO_TRX even in CLI mode since a commit round is expected soon.
			$conn->setFlag( $conn::DBO_TRX, $conn::REMEMBER_PRIOR );
		}

		if ( $conn->getFlag( $conn::DBO_TRX ) ) {
			$conn->setLBInfo( 'trxRoundId', $this->trxRoundId );
		}
	}

	/**
	 * @param IDatabase $conn
	 */
	private function undoTransactionRoundFlags( IDatabase $conn ) {
		if ( $conn->getLBInfo( 'autoCommitOnly' ) ) {
			return; // transaction rounds do not apply to these connections
		}

		if ( $conn->getFlag( $conn::DBO_TRX ) ) {
			$conn->setLBInfo( 'trxRoundId', false );
		}

		if ( $conn->getFlag( $conn::DBO_DEFAULT ) ) {
			$conn->restoreFlags( $conn::RESTORE_PRIOR );
		}
	}

	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		$this->forEachOpenReplicaConnection( function ( IDatabase $conn ) {
			$conn->flushSnapshot( __METHOD__ );
		} );
	}

	public function hasMasterConnection() {
		return $this->isOpen( $this->getWriterIndex() );
	}

	public function hasMasterChanges() {
		$pending = 0;
		$this->forEachOpenMasterConnection( function ( IDatabase $conn ) use ( &$pending ) {
			$pending |= $conn->writesOrCallbacksPending();
		} );

		return (bool)$pending;
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
		// No-op if there is only one DB (also avoids recursion)
		if ( !$this->laggedReplicaMode && $this->getServerCount() > 1 ) {
			try {
				// See if laggedReplicaMode gets set
				$conn = $this->getConnection( self::DB_REPLICA, false, $domain );
				$this->reuseConnection( $conn );
			} catch ( DBConnectionError $e ) {
				// Avoid expensive re-connect attempts and failures
				$this->allReplicasDownMode = true;
				$this->laggedReplicaMode = true;
			}
		}

		return $this->laggedReplicaMode;
	}

	/**
	 * @param bool $domain
	 * @return bool
	 * @deprecated 1.28; use getLaggedReplicaMode()
	 */
	public function getLaggedSlaveMode( $domain = false ) {
		return $this->getLaggedReplicaMode( $domain );
	}

	public function laggedReplicaUsed() {
		return $this->laggedReplicaMode;
	}

	/**
	 * @return bool
	 * @since 1.27
	 * @deprecated Since 1.28; use laggedReplicaUsed()
	 */
	public function laggedSlaveUsed() {
		return $this->laggedReplicaUsed();
	}

	public function getReadOnlyReason( $domain = false, IDatabase $conn = null ) {
		if ( $this->readOnlyReason !== false ) {
			return $this->readOnlyReason;
		} elseif ( $this->getLaggedReplicaMode( $domain ) ) {
			if ( $this->allReplicasDownMode ) {
				return 'The database has been automatically locked ' .
					'until the replica database servers become available';
			} else {
				return 'The database has been automatically locked ' .
					'while the replica database servers catch up to the master.';
			}
		} elseif ( $this->masterRunningReadOnly( $domain, $conn ) ) {
			return 'The database master is running in read-only mode.';
		}

		return false;
	}

	/**
	 * @param string $domain Domain ID, or false for the current domain
	 * @param IDatabase|null $conn DB master connectionl used to avoid loops [optional]
	 * @return bool
	 */
	private function masterRunningReadOnly( $domain, IDatabase $conn = null ) {
		$cache = $this->wanCache;
		$masterServer = $this->getServerName( $this->getWriterIndex() );

		return (bool)$cache->getWithSetCallback(
			$cache->makeGlobalKey( __CLASS__, 'server-read-only', $masterServer ),
			self::TTL_CACHE_READONLY,
			function () use ( $domain, $conn ) {
				$old = $this->trxProfiler->setSilenced( true );
				try {
					$dbw = $conn ?: $this->getConnection( self::DB_MASTER, [], $domain );
					$readOnly = (int)$dbw->serverIsReadOnly();
					if ( !$conn ) {
						$this->reuseConnection( $dbw );
					}
				} catch ( DBError $e ) {
					$readOnly = 0;
				}
				$this->trxProfiler->setSilenced( $old );
				return $readOnly;
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG, 'busyValue' => 0 ]
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
					$mergedParams = array_merge( [ $conn ], $params );
					call_user_func_array( $callback, $mergedParams );
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
					$mergedParams = array_merge( [ $conn ], $params );
					call_user_func_array( $callback, $mergedParams );
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
					$mergedParams = array_merge( [ $conn ], $params );
					call_user_func_array( $callback, $mergedParams );
				}
			}
		}
	}

	public function getMaxLag( $domain = false ) {
		$maxLag = -1;
		$host = '';
		$maxIndex = 0;

		if ( $this->getServerCount() <= 1 ) {
			return [ $host, $maxLag, $maxIndex ]; // no replication = no lag
		}

		$lagTimes = $this->getLagTimes( $domain );
		foreach ( $lagTimes as $i => $lag ) {
			if ( $this->loads[$i] > 0 && $lag > $maxLag ) {
				$maxLag = $lag;
				$host = $this->servers[$i]['host'];
				$maxIndex = $i;
			}
		}

		return [ $host, $maxLag, $maxIndex ];
	}

	public function getLagTimes( $domain = false ) {
		if ( $this->getServerCount() <= 1 ) {
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

	public function safeGetLag( IDatabase $conn ) {
		if ( $this->getServerCount() <= 1 ) {
			return 0;
		} else {
			return $conn->getLag();
		}
	}

	/**
	 * @param IDatabase $conn
	 * @param DBMasterPos|bool $pos
	 * @param int|null $timeout
	 * @return bool
	 */
	public function safeWaitForMasterPos( IDatabase $conn, $pos = false, $timeout = null ) {
		$timeout = max( 1, $timeout ?: $this->waitTimeout );

		if ( $this->getServerCount() <= 1 || !$conn->getLBInfo( 'replica' ) ) {
			return true; // server is not a replica DB
		}

		if ( !$pos ) {
			// Get the current master position, opening a connection if needed
			$masterConn = $this->getAnyOpenConnection( $this->getWriterIndex() );
			if ( $masterConn ) {
				$pos = $masterConn->getMasterPos();
			} else {
				$masterConn = $this->openConnection( $this->getWriterIndex(), self::DOMAIN_ANY );
				$pos = $masterConn->getMasterPos();
				$this->closeConnection( $masterConn );
			}
		}

		if ( $pos instanceof DBMasterPos ) {
			$result = $conn->masterPosWait( $pos, $timeout );
			if ( $result == -1 || is_null( $result ) ) {
				$msg = __METHOD__ . ': timed out waiting on {host} pos {pos}';
				$this->replLogger->warning( $msg, [
					'host' => $conn->getServer(),
					'pos' => $pos,
					'trace' => ( new RuntimeException() )->getTraceAsString()
				] );
				$ok = false;
			} else {
				$this->replLogger->debug( __METHOD__ . ': done waiting' );
				$ok = true;
			}
		} else {
			$ok = false; // something is misconfigured
			$this->replLogger->error(
				__METHOD__ . ': could not get master pos for {host}',
				[
					'host' => $conn->getServer(),
					'trace' => ( new RuntimeException() )->getTraceAsString()
				]
			);
		}

		return $ok;
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

	public function setDomainPrefix( $prefix ) {
		// Find connections to explicit foreign domains still marked as in-use...
		$domainsInUse = [];
		$this->forEachOpenConnection( function ( IDatabase $conn ) use ( &$domainsInUse ) {
			// Once reuseConnection() is called on a handle, its reference count goes from 1 to 0.
			// Until then, it is still in use by the caller (explicitly or via DBConnRef scope).
			if ( $conn->getLBInfo( 'foreignPoolRefCount' ) > 0 ) {
				$domainsInUse[] = $conn->getDomainID();
			}
		} );

		// Do not switch connections to explicit foreign domains unless marked as safe
		if ( $domainsInUse ) {
			$domains = implode( ', ', $domainsInUse );
			throw new DBUnexpectedError( null,
				"Foreign domain connections are still in use ($domains)." );
		}

		$oldDomain = $this->localDomain->getId();
		$this->setLocalDomain( new DatabaseDomain(
			$this->localDomain->getDatabase(),
			$this->localDomain->getSchema(),
			$prefix
		) );

		$this->forEachOpenConnection( function ( IDatabase $db ) use ( $prefix, $oldDomain ) {
			if ( !$db->getLBInfo( 'foreign' ) ) {
				$db->tablePrefix( $prefix );
			}
		} );
	}

	/**
	 * @param DatabaseDomain $domain
	 */
	private function setLocalDomain( DatabaseDomain $domain ) {
		$this->localDomain = $domain;
		// In case a caller assumes that the domain ID is simply <db>-<prefix>, which is almost
		// always true, gracefully handle the case when they fail to account for escaping.
		if ( $this->localDomain->getTablePrefix() != '' ) {
			$this->localDomainIdAlias =
				$this->localDomain->getDatabase() . '-' . $this->localDomain->getTablePrefix();
		} else {
			$this->localDomainIdAlias = $this->localDomain->getDatabase();
		}
	}

	/**
	 * Make PHP ignore user aborts/disconnects until the returned
	 * value leaves scope. This returns null and does nothing in CLI mode.
	 *
	 * @return ScopedCallback|null
	 */
	final protected function getScopedPHPBehaviorForCommit() {
		if ( PHP_SAPI != 'cli' ) { // https://bugs.php.net/bug.php?id=47540
			$old = ignore_user_abort( true ); // avoid half-finished operations
			return new ScopedCallback( function () use ( $old ) {
				ignore_user_abort( $old );
			} );
		}

		return null;
	}

	function __destruct() {
		// Avoid connection leaks for sanity
		$this->disable();
	}
}

class_alias( LoadBalancer::class, 'LoadBalancer' );
