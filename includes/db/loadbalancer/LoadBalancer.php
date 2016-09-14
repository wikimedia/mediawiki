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
 * @ingroup Database
 */
use Psr\Log\LoggerInterface;

/**
 * Database load balancing, tracking, and transaction management object
 *
 * @ingroup Database
 */
class LoadBalancer implements ILoadBalancer {
	/** @var array[] Map of (server index => server config array) */
	private $mServers;
	/** @var array[] Map of (local/foreignUsed/foreignFree => server index => DatabaseBase array) */
	private $mConns;
	/** @var array Map of (server index => weight) */
	private $mLoads;
	/** @var array[] Map of (group => server index => weight) */
	private $mGroupLoads;
	/** @var bool Whether to disregard replica DB lag as a factor in replica DB selection */
	private $mAllowLagged;
	/** @var integer Seconds to spend waiting on replica DB lag to resolve */
	private $mWaitTimeout;
	/** @var string The LoadMonitor subclass name */
	private $mLoadMonitorClass;

	/** @var LoadMonitor */
	private $mLoadMonitor;
	/** @var BagOStuff */
	private $srvCache;
	/** @var BagOStuff */
	private $memCache;
	/** @var WANObjectCache */
	private $wanCache;
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

	/** @var bool|DatabaseBase Database connection that caused a problem */
	private $mErrorConnection;
	/** @var integer The generic (not query grouped) replica DB index (of $mServers) */
	private $mReadIndex;
	/** @var bool|DBMasterPos False if not set */
	private $mWaitForPos;
	/** @var bool Whether the generic reader fell back to a lagged replica DB */
	private $laggedReplicaMode = false;
	/** @var bool Whether the generic reader fell back to a lagged replica DB */
	private $allReplicasDownMode = false;
	/** @var string The last DB selection or connection error */
	private $mLastError = 'Unknown error';
	/** @var string|bool Reason the LB is read-only or false if not */
	private $readOnlyReason = false;
	/** @var integer Total connections opened */
	private $connsOpened = 0;
	/** @var string|bool String if a requested DBO_TRX transaction round is active */
	private $trxRoundId = false;
	/** @var array[] Map of (name => callable) */
	private $trxRecurringCallbacks = [];
	/** @var string Local Wiki ID and default for selectDB() calls */
	private $localDomain;
	/** @var string Current server name */
	private $host;

	/** @var callable Exception logger */
	private $errorLogger;

	/** @var boolean */
	private $disabled = false;

	/** @var integer Warn when this many connection are held */
	const CONN_HELD_WARN_THRESHOLD = 10;
	/** @var integer Default 'max lag' when unspecified */
	const MAX_LAG_DEFAULT = 10;
	/** @var integer Max time to wait for a replica DB to catch up (e.g. ChronologyProtector) */
	const POS_WAIT_TIMEOUT = 10;
	/** @var integer Seconds to cache master server read-only status */
	const TTL_CACHE_READONLY = 5;

	public function __construct( array $params ) {
		if ( !isset( $params['servers'] ) ) {
			throw new InvalidArgumentException( __CLASS__ . ': missing servers parameter' );
		}
		$this->mServers = $params['servers'];
		$this->mWaitTimeout = isset( $params['waitTimeout'] )
			? $params['waitTimeout']
			: self::POS_WAIT_TIMEOUT;
		$this->localDomain = isset( $params['localDomain'] ) ? $params['localDomain'] : '';

		$this->mReadIndex = -1;
		$this->mConns = [
			'local' => [],
			'foreignUsed' => [],
			'foreignFree' => [] ];
		$this->mLoads = [];
		$this->mWaitForPos = false;
		$this->mErrorConnection = false;
		$this->mAllowLagged = false;

		if ( isset( $params['readOnlyReason'] ) && is_string( $params['readOnlyReason'] ) ) {
			$this->readOnlyReason = $params['readOnlyReason'];
		}

		if ( isset( $params['loadMonitor'] ) ) {
			$this->mLoadMonitorClass = $params['loadMonitor'];
		} else {
			$master = reset( $params['servers'] );
			if ( isset( $master['type'] ) && $master['type'] === 'mysql' ) {
				$this->mLoadMonitorClass = 'LoadMonitorMySQL';
			} else {
				$this->mLoadMonitorClass = 'LoadMonitorNull';
			}
		}

		foreach ( $params['servers'] as $i => $server ) {
			$this->mLoads[$i] = $server['load'];
			if ( isset( $server['groupLoads'] ) ) {
				foreach ( $server['groupLoads'] as $group => $ratio ) {
					if ( !isset( $this->mGroupLoads[$group] ) ) {
						$this->mGroupLoads[$group] = [];
					}
					$this->mGroupLoads[$group][$i] = $ratio;
				}
			}
		}

		if ( isset( $params['srvCache'] ) ) {
			$this->srvCache = $params['srvCache'];
		} else {
			$this->srvCache = new EmptyBagOStuff();
		}
		if ( isset( $params['memCache'] ) ) {
			$this->memCache = $params['memCache'];
		} else {
			$this->memCache = new EmptyBagOStuff();
		}
		if ( isset( $params['wanCache'] ) ) {
			$this->wanCache = $params['wanCache'];
		} else {
			$this->wanCache = WANObjectCache::newEmpty();
		}
		if ( isset( $params['trxProfiler'] ) ) {
			$this->trxProfiler = $params['trxProfiler'];
		} else {
			$this->trxProfiler = new TransactionProfiler();
		}

		$this->errorLogger = isset( $params['errorLogger'] )
			? $params['errorLogger']
			: function ( Exception $e ) {
				trigger_error( E_WARNING, $e->getMessage() );
			};

		foreach ( [ 'replLogger', 'connLogger', 'queryLogger', 'perfLogger' ] as $key ) {
			$this->$key = isset( $params[$key] ) ? $params[$key] : new \Psr\Log\NullLogger();
		}

		$this->host = isset( $params['hostname'] )
			? $params['hostname']
			: ( gethostname() ?: 'unknown' );
	}

	/**
	 * Get a LoadMonitor instance
	 *
	 * @return LoadMonitor
	 */
	private function getLoadMonitor() {
		if ( !isset( $this->mLoadMonitor ) ) {
			$class = $this->mLoadMonitorClass;
			$this->mLoadMonitor = new $class( $this, $this->srvCache, $this->memCache );
			$this->mLoadMonitor->setLogger( $this->replLogger );
		}

		return $this->mLoadMonitor;
	}

	/**
	 * @param array $loads
	 * @param bool|string $wiki Wiki to get non-lagged for
	 * @param int $maxLag Restrict the maximum allowed lag to this many seconds
	 * @return bool|int|string
	 */
	private function getRandomNonLagged( array $loads, $wiki = false, $maxLag = INF ) {
		$lags = $this->getLagTimes( $wiki );

		# Unset excessively lagged servers
		foreach ( $lags as $i => $lag ) {
			if ( $i != 0 ) {
				# How much lag this server nominally is allowed to have
				$maxServerLag = isset( $this->mServers[$i]['max lag'] )
					? $this->mServers[$i]['max lag']
					: self::MAX_LAG_DEFAULT; // default
				# Constrain that futher by $maxLag argument
				$maxServerLag = min( $maxServerLag, $maxLag );

				$host = $this->getServerName( $i );
				if ( $lag === false && !is_infinite( $maxServerLag ) ) {
					$this->replLogger->error( "Server $host (#$i) is not replicating?" );
					unset( $loads[$i] );
				} elseif ( $lag > $maxServerLag ) {
					$this->replLogger->warning( "Server $host (#$i) has >= $lag seconds of lag" );
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

	public function getReaderIndex( $group = false, $wiki = false ) {
		if ( count( $this->mServers ) == 1 ) {
			# Skip the load balancing if there's only one server
			return $this->getWriterIndex();
		} elseif ( $group === false && $this->mReadIndex >= 0 ) {
			# Shortcut if generic reader exists already
			return $this->mReadIndex;
		}

		# Find the relevant load array
		if ( $group !== false ) {
			if ( isset( $this->mGroupLoads[$group] ) ) {
				$nonErrorLoads = $this->mGroupLoads[$group];
			} else {
				# No loads for this group, return false and the caller can use some other group
				$this->connLogger->info( __METHOD__ . ": no loads for group $group" );

				return false;
			}
		} else {
			$nonErrorLoads = $this->mLoads;
		}

		if ( !count( $nonErrorLoads ) ) {
			throw new InvalidArgumentException( "Empty server array given to LoadBalancer" );
		}

		# Scale the configured load ratios according to the dynamic load if supported
		$this->getLoadMonitor()->scaleLoads( $nonErrorLoads, $group, $wiki );

		$laggedReplicaMode = false;

		# No server found yet
		$i = false;
		# First try quickly looking through the available servers for a server that
		# meets our criteria
		$currentLoads = $nonErrorLoads;
		while ( count( $currentLoads ) ) {
			if ( $this->mAllowLagged || $laggedReplicaMode ) {
				$i = ArrayUtils::pickRandom( $currentLoads );
			} else {
				$i = false;
				if ( $this->mWaitForPos && $this->mWaitForPos->asOfTime() ) {
					# ChronologyProtecter causes mWaitForPos to be set via sessions.
					# This triggers doWait() after connect, so it's especially good to
					# avoid lagged servers so as to avoid just blocking in that method.
					$ago = microtime( true ) - $this->mWaitForPos->asOfTime();
					# Aim for <= 1 second of waiting (being too picky can backfire)
					$i = $this->getRandomNonLagged( $currentLoads, $wiki, $ago + 1 );
				}
				if ( $i === false ) {
					# Any server with less lag than it's 'max lag' param is preferable
					$i = $this->getRandomNonLagged( $currentLoads, $wiki );
				}
				if ( $i === false && count( $currentLoads ) != 0 ) {
					# All replica DBs lagged. Switch to read-only mode
					$this->replLogger->error( "All replica DBs lagged. Switch to read-only mode" );
					$i = ArrayUtils::pickRandom( $currentLoads );
					$laggedReplicaMode = true;
				}
			}

			if ( $i === false ) {
				# pickRandom() returned false
				# This is permanent and means the configuration or the load monitor
				# wants us to return false.
				$this->connLogger->debug( __METHOD__ . ": pickRandom() returned false" );

				return false;
			}

			$serverName = $this->getServerName( $i );
			$this->connLogger->debug( __METHOD__ . ": Using reader #$i: $serverName..." );

			$conn = $this->openConnection( $i, $wiki );
			if ( !$conn ) {
				$this->connLogger->warning( __METHOD__ . ": Failed connecting to $i/$wiki" );
				unset( $nonErrorLoads[$i] );
				unset( $currentLoads[$i] );
				$i = false;
				continue;
			}

			// Decrement reference counter, we are finished with this connection.
			// It will be incremented for the caller later.
			if ( $wiki !== false ) {
				$this->reuseConnection( $conn );
			}

			# Return this server
			break;
		}

		# If all servers were down, quit now
		if ( !count( $nonErrorLoads ) ) {
			$this->connLogger->error( "All servers down" );
		}

		if ( $i !== false ) {
			# Replica DB connection successful.
			# Wait for the session master pos for a short time.
			if ( $this->mWaitForPos && $i > 0 ) {
				$this->doWait( $i );
			}
			if ( $this->mReadIndex <= 0 && $this->mLoads[$i] > 0 && $group === false ) {
				$this->mReadIndex = $i;
				# Record if the generic reader index is in "lagged replica DB" mode
				if ( $laggedReplicaMode ) {
					$this->laggedReplicaMode = true;
				}
			}
			$serverName = $this->getServerName( $i );
			$this->connLogger->debug(
				__METHOD__ . ": using server $serverName for group '$group'" );
		}

		return $i;
	}

	public function waitFor( $pos ) {
		$this->mWaitForPos = $pos;
		$i = $this->mReadIndex;

		if ( $i > 0 ) {
			if ( !$this->doWait( $i ) ) {
				$this->laggedReplicaMode = true;
			}
		}
	}

	/**
	 * Set the master wait position and wait for a "generic" replica DB to catch up to it
	 *
	 * This can be used a faster proxy for waitForAll()
	 *
	 * @param DBMasterPos $pos
	 * @param int $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 * @since 1.26
	 */
	public function waitForOne( $pos, $timeout = null ) {
		$this->mWaitForPos = $pos;

		$i = $this->mReadIndex;
		if ( $i <= 0 ) {
			// Pick a generic replica DB if there isn't one yet
			$readLoads = $this->mLoads;
			unset( $readLoads[$this->getWriterIndex()] ); // replica DBs only
			$readLoads = array_filter( $readLoads ); // with non-zero load
			$i = ArrayUtils::pickRandom( $readLoads );
		}

		if ( $i > 0 ) {
			$ok = $this->doWait( $i, true, $timeout );
		} else {
			$ok = true; // no applicable loads
		}

		return $ok;
	}

	public function waitForAll( $pos, $timeout = null ) {
		$this->mWaitForPos = $pos;
		$serverCount = count( $this->mServers );

		$ok = true;
		for ( $i = 1; $i < $serverCount; $i++ ) {
			if ( $this->mLoads[$i] > 0 ) {
				$ok = $this->doWait( $i, true, $timeout ) && $ok;
			}
		}

		return $ok;
	}

	public function getAnyOpenConnection( $i ) {
		foreach ( $this->mConns as $connsByServer ) {
			if ( !empty( $connsByServer[$i] ) ) {
				return reset( $connsByServer[$i] );
			}
		}

		return false;
	}

	/**
	 * Wait for a given replica DB to catch up to the master pos stored in $this
	 * @param int $index Server index
	 * @param bool $open Check the server even if a new connection has to be made
	 * @param int $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool
	 */
	protected function doWait( $index, $open = false, $timeout = null ) {
		$close = false; // close the connection afterwards

		// Check if we already know that the DB has reached this point
		$server = $this->getServerName( $index );
		$key = $this->srvCache->makeGlobalKey( __CLASS__, 'last-known-pos', $server );
		/** @var DBMasterPos $knownReachedPos */
		$knownReachedPos = $this->srvCache->get( $key );
		if ( $knownReachedPos && $knownReachedPos->hasReached( $this->mWaitForPos ) ) {
			$this->replLogger->debug( __METHOD__ .
				": replica DB $server known to be caught up (pos >= $knownReachedPos)." );
			return true;
		}

		// Find a connection to wait on, creating one if needed and allowed
		$conn = $this->getAnyOpenConnection( $index );
		if ( !$conn ) {
			if ( !$open ) {
				$this->replLogger->debug( __METHOD__ . ": no connection open for $server" );

				return false;
			} else {
				$conn = $this->openConnection( $index, '' );
				if ( !$conn ) {
					$this->replLogger->warning( __METHOD__ . ": failed to connect to $server" );

					return false;
				}
				// Avoid connection spam in waitForAll() when connections
				// are made just for the sake of doing this lag check.
				$close = true;
			}
		}

		$this->replLogger->info( __METHOD__ . ": Waiting for replica DB $server to catch up..." );
		$timeout = $timeout ?: $this->mWaitTimeout;
		$result = $conn->masterPosWait( $this->mWaitForPos, $timeout );

		if ( $result == -1 || is_null( $result ) ) {
			// Timed out waiting for replica DB, use master instead
			$msg = __METHOD__ . ": Timed out waiting on $server pos {$this->mWaitForPos}";
			$this->replLogger->warning( "$msg" );
			$this->perfLogger->warning( "$msg:" );
			$ok = false;
		} else {
			$this->replLogger->info( __METHOD__ . ": Done" );
			$ok = true;
			// Remember that the DB reached this point
			$this->srvCache->set( $key, $this->mWaitForPos, BagOStuff::TTL_DAY );
		}

		if ( $close ) {
			$this->closeConnection( $conn );
		}

		return $ok;
	}

	public function getConnection( $i, $groups = [], $wiki = false ) {
		if ( $i === null || $i === false ) {
			throw new InvalidArgumentException( 'Attempt to call ' . __METHOD__ .
				' with invalid server index' );
		}

		if ( $wiki === $this->localDomain ) {
			$wiki = false;
		}

		$groups = ( $groups === false || $groups === [] )
			? [ false ] // check one "group": the generic pool
			: (array)$groups;

		$masterOnly = ( $i == DB_MASTER || $i == $this->getWriterIndex() );
		$oldConnsOpened = $this->connsOpened; // connections open now

		if ( $i == DB_MASTER ) {
			$i = $this->getWriterIndex();
		} else {
			# Try to find an available server in any the query groups (in order)
			foreach ( $groups as $group ) {
				$groupIndex = $this->getReaderIndex( $group, $wiki );
				if ( $groupIndex !== false ) {
					$i = $groupIndex;
					break;
				}
			}
		}

		# Operation-based index
		if ( $i == DB_REPLICA ) {
			$this->mLastError = 'Unknown error'; // reset error string
			# Try the general server pool if $groups are unavailable.
			$i = in_array( false, $groups, true )
				? false // don't bother with this if that is what was tried above
				: $this->getReaderIndex( false, $wiki );
			# Couldn't find a working server in getReaderIndex()?
			if ( $i === false ) {
				$this->mLastError = 'No working replica DB server: ' . $this->mLastError;

				return $this->reportConnectionError();
			}
		}

		# Now we have an explicit index into the servers array
		$conn = $this->openConnection( $i, $wiki );
		if ( !$conn ) {
			return $this->reportConnectionError();
		}

		# Profile any new connections that happen
		if ( $this->connsOpened > $oldConnsOpened ) {
			$host = $conn->getServer();
			$dbname = $conn->getDBname();
			$this->trxProfiler->recordConnection( $host, $dbname, $masterOnly );
		}

		if ( $masterOnly ) {
			# Make master-requested DB handles inherit any read-only mode setting
			$conn->setLBInfo( 'readOnlyReason', $this->getReadOnlyReason( $wiki, $conn ) );
		}

		return $conn;
	}

	public function reuseConnection( $conn ) {
		$serverIndex = $conn->getLBInfo( 'serverIndex' );
		$refCount = $conn->getLBInfo( 'foreignPoolRefCount' );
		if ( $serverIndex === null || $refCount === null ) {
			/**
			 * This can happen in code like:
			 *   foreach ( $dbs as $db ) {
			 *     $conn = $lb->getConnection( DB_REPLICA, [], $db );
			 *     ...
			 *     $lb->reuseConnection( $conn );
			 *   }
			 * When a connection to the local DB is opened in this way, reuseConnection()
			 * should be ignored
			 */
			return;
		}

		$dbName = $conn->getDBname();
		$prefix = $conn->tablePrefix();
		if ( strval( $prefix ) !== '' ) {
			$wiki = "$dbName-$prefix";
		} else {
			$wiki = $dbName;
		}
		if ( $this->mConns['foreignUsed'][$serverIndex][$wiki] !== $conn ) {
			throw new InvalidArgumentException( __METHOD__ . ": connection not found, has " .
				"the connection been freed already?" );
		}
		$conn->setLBInfo( 'foreignPoolRefCount', --$refCount );
		if ( $refCount <= 0 ) {
			$this->mConns['foreignFree'][$serverIndex][$wiki] = $conn;
			unset( $this->mConns['foreignUsed'][$serverIndex][$wiki] );
			$this->connLogger->debug( __METHOD__ . ": freed connection $serverIndex/$wiki" );
		} else {
			$this->connLogger->debug( __METHOD__ .
				": reference count for $serverIndex/$wiki reduced to $refCount" );
		}
	}

	/**
	 * Get a database connection handle reference
	 *
	 * The handle's methods wrap simply wrap those of a DatabaseBase handle
	 *
	 * @see LoadBalancer::getConnection() for parameter information
	 *
	 * @param int $db
	 * @param array|string|bool $groups Query group(s), or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return DBConnRef
	 * @since 1.22
	 */
	public function getConnectionRef( $db, $groups = [], $wiki = false ) {
		return new DBConnRef( $this, $this->getConnection( $db, $groups, $wiki ) );
	}

	/**
	 * Get a database connection handle reference without connecting yet
	 *
	 * The handle's methods wrap simply wrap those of a DatabaseBase handle
	 *
	 * @see LoadBalancer::getConnection() for parameter information
	 *
	 * @param int $db
	 * @param array|string|bool $groups Query group(s), or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return DBConnRef
	 * @since 1.22
	 */
	public function getLazyConnectionRef( $db, $groups = [], $wiki = false ) {
		$wiki = ( $wiki !== false ) ? $wiki : $this->localDomain;

		return new DBConnRef( $this, [ $db, $groups, $wiki ] );
	}

	public function openConnection( $i, $wiki = false ) {
		if ( $wiki !== false ) {
			$conn = $this->openForeignConnection( $i, $wiki );
		} elseif ( isset( $this->mConns['local'][$i][0] ) ) {
			$conn = $this->mConns['local'][$i][0];
		} else {
			$server = $this->mServers[$i];
			$server['serverIndex'] = $i;
			$conn = $this->reallyOpenConnection( $server, false );
			$serverName = $this->getServerName( $i );
			if ( $conn->isOpen() ) {
				$this->connLogger->debug( "Connected to database $i at '$serverName'." );
				$this->mConns['local'][$i][0] = $conn;
			} else {
				$this->connLogger->warning( "Failed to connect to database $i at '$serverName'." );
				$this->mErrorConnection = $conn;
				$conn = false;
			}
		}

		if ( $conn && !$conn->isOpen() ) {
			// Connection was made but later unrecoverably lost for some reason.
			// Do not return a handle that will just throw exceptions on use,
			// but let the calling code (e.g. getReaderIndex) try another server.
			// See DatabaseMyslBase::ping() for how this can happen.
			$this->mErrorConnection = $conn;
			$conn = false;
		}

		return $conn;
	}

	/**
	 * Open a connection to a foreign DB, or return one if it is already open.
	 *
	 * Increments a reference count on the returned connection which locks the
	 * connection to the requested wiki. This reference count can be
	 * decremented by calling reuseConnection().
	 *
	 * If a connection is open to the appropriate server already, but with the wrong
	 * database, it will be switched to the right database and returned, as long as
	 * it has been freed first with reuseConnection().
	 *
	 * On error, returns false, and the connection which caused the
	 * error will be available via $this->mErrorConnection.
	 *
	 * @note If disable() was called on this LoadBalancer, this method will throw a DBAccessError.
	 *
	 * @param int $i Server index
	 * @param string $wiki Wiki ID to open
	 * @return DatabaseBase
	 */
	private function openForeignConnection( $i, $wiki ) {
		list( $dbName, $prefix ) = explode( '-', $wiki, 2 ) + [ '', '' ];

		if ( isset( $this->mConns['foreignUsed'][$i][$wiki] ) ) {
			// Reuse an already-used connection
			$conn = $this->mConns['foreignUsed'][$i][$wiki];
			$this->connLogger->debug( __METHOD__ . ": reusing connection $i/$wiki" );
		} elseif ( isset( $this->mConns['foreignFree'][$i][$wiki] ) ) {
			// Reuse a free connection for the same wiki
			$conn = $this->mConns['foreignFree'][$i][$wiki];
			unset( $this->mConns['foreignFree'][$i][$wiki] );
			$this->mConns['foreignUsed'][$i][$wiki] = $conn;
			$this->connLogger->debug( __METHOD__ . ": reusing free connection $i/$wiki" );
		} elseif ( !empty( $this->mConns['foreignFree'][$i] ) ) {
			// Reuse a connection from another wiki
			$conn = reset( $this->mConns['foreignFree'][$i] );
			$oldWiki = key( $this->mConns['foreignFree'][$i] );

			// The empty string as a DB name means "don't care".
			// DatabaseMysqlBase::open() already handle this on connection.
			if ( $dbName !== '' && !$conn->selectDB( $dbName ) ) {
				$this->mLastError = "Error selecting database $dbName on server " .
					$conn->getServer() . " from client host {$this->host}";
				$this->mErrorConnection = $conn;
				$conn = false;
			} else {
				$conn->tablePrefix( $prefix );
				unset( $this->mConns['foreignFree'][$i][$oldWiki] );
				$this->mConns['foreignUsed'][$i][$wiki] = $conn;
				$this->connLogger->debug( __METHOD__ .
					": reusing free connection from $oldWiki for $wiki" );
			}
		} else {
			// Open a new connection
			$server = $this->mServers[$i];
			$server['serverIndex'] = $i;
			$server['foreignPoolRefCount'] = 0;
			$server['foreign'] = true;
			$conn = $this->reallyOpenConnection( $server, $dbName );
			if ( !$conn->isOpen() ) {
				$this->connLogger->warning( __METHOD__ . ": connection error for $i/$wiki" );
				$this->mErrorConnection = $conn;
				$conn = false;
			} else {
				$conn->tablePrefix( $prefix );
				$this->mConns['foreignUsed'][$i][$wiki] = $conn;
				$this->connLogger->debug( __METHOD__ . ": opened new connection for $i/$wiki" );
			}
		}

		// Increment reference count
		if ( $conn ) {
			$refCount = $conn->getLBInfo( 'foreignPoolRefCount' );
			$conn->setLBInfo( 'foreignPoolRefCount', $refCount + 1 );
		}

		return $conn;
	}

	/**
	 * Test if the specified index represents an open connection
	 *
	 * @param int $index Server index
	 * @access private
	 * @return bool
	 */
	private function isOpen( $index ) {
		if ( !is_integer( $index ) ) {
			return false;
		}

		return (bool)$this->getAnyOpenConnection( $index );
	}

	/**
	 * Really opens a connection. Uncached.
	 * Returns a Database object whether or not the connection was successful.
	 * @access private
	 *
	 * @param array $server
	 * @param bool $dbNameOverride
	 * @return DatabaseBase
	 * @throws DBAccessError
	 * @throws MWException
	 */
	protected function reallyOpenConnection( $server, $dbNameOverride = false ) {
		if ( $this->disabled ) {
			throw new DBAccessError();
		}

		if ( !is_array( $server ) ) {
			throw new InvalidArgumentException( 'You must update your load-balancing configuration. ' .
				'See DefaultSettings.php entry for $wgDBservers.' );
		}

		if ( $dbNameOverride !== false ) {
			$server['dbname'] = $dbNameOverride;
		}

		// Let the handle know what the cluster master is (e.g. "db1052")
		$masterName = $this->getServerName( $this->getWriterIndex() );
		$server['clusterMasterHost'] = $masterName;

		// Log when many connection are made on requests
		if ( ++$this->connsOpened >= self::CONN_HELD_WARN_THRESHOLD ) {
			$this->perfLogger->warning( __METHOD__ . ": " .
				"{$this->connsOpened}+ connections made (master=$masterName)" );
		}

		// Set loggers
		$server['connLogger'] = $this->connLogger;
		$server['queryLogger'] = $this->queryLogger;

		// Create a live connection object
		try {
			$db = DatabaseBase::factory( $server['type'], $server );
		} catch ( DBConnectionError $e ) {
			// FIXME: This is probably the ugliest thing I have ever done to
			// PHP. I'm half-expecting it to segfault, just out of disgust. -- TS
			$db = $e->db;
		}

		$db->setLBInfo( $server );
		$db->setLazyMasterHandle(
			$this->getLazyConnectionRef( DB_MASTER, [], $db->getWikiID() )
		);
		$db->setTransactionProfiler( $this->trxProfiler );

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
	 * @return bool
	 */
	private function reportConnectionError() {
		$conn = $this->mErrorConnection; // The connection which caused the error
		$context = [
			'method' => __METHOD__,
			'last_error' => $this->mLastError,
		];

		if ( !is_object( $conn ) ) {
			// No last connection, probably due to all servers being too busy
			$this->connLogger->error(
				"LB failure with no last connection. Connection error: {last_error}",
				$context
			);

			// If all servers were busy, mLastError will contain something sensible
			throw new DBConnectionError( null, $this->mLastError );
		} else {
			$context['db_server'] = $conn->getProperty( 'mServer' );
			$this->connLogger->warning(
				"Connection error: {last_error} ({db_server})",
				$context
			);

			// throws DBConnectionError
			$conn->reportConnectionError( "{$this->mLastError} ({$context['db_server']})" );
		}

		return false; /* not reached */
	}

	public function getWriterIndex() {
		return 0;
	}

	public function haveIndex( $i ) {
		return array_key_exists( $i, $this->mServers );
	}

	public function isNonZeroLoad( $i ) {
		return array_key_exists( $i, $this->mServers ) && $this->mLoads[$i] != 0;
	}

	public function getServerCount() {
		return count( $this->mServers );
	}

	public function getServerName( $i ) {
		if ( isset( $this->mServers[$i]['hostName'] ) ) {
			$name = $this->mServers[$i]['hostName'];
		} elseif ( isset( $this->mServers[$i]['host'] ) ) {
			$name = $this->mServers[$i]['host'];
		} else {
			$name = '';
		}

		return ( $name != '' ) ? $name : 'localhost';
	}

	public function getServerInfo( $i ) {
		if ( isset( $this->mServers[$i] ) ) {
			return $this->mServers[$i];
		} else {
			return false;
		}
	}

	public function setServerInfo( $i, array $serverInfo ) {
		$this->mServers[$i] = $serverInfo;
	}

	public function getMasterPos() {
		# If this entire request was served from a replica DB without opening a connection to the
		# master (however unlikely that may be), then we can fetch the position from the replica DB.
		$masterConn = $this->getAnyOpenConnection( $this->getWriterIndex() );
		if ( !$masterConn ) {
			$serverCount = count( $this->mServers );
			for ( $i = 1; $i < $serverCount; $i++ ) {
				$conn = $this->getAnyOpenConnection( $i );
				if ( $conn ) {
					return $conn->getSlavePos();
				}
			}
		} else {
			return $masterConn->getMasterPos();
		}

		return false;
	}

	/**
	 * Disable this load balancer. All connections are closed, and any attempt to
	 * open a new connection will result in a DBAccessError.
	 *
	 * @since 1.27
	 */
	public function disable() {
		$this->closeAll();
		$this->disabled = true;
	}

	public function closeAll() {
		$this->forEachOpenConnection( function ( DatabaseBase $conn ) {
			$conn->close();
		} );

		$this->mConns = [
			'local' => [],
			'foreignFree' => [],
			'foreignUsed' => [],
		];
		$this->connsOpened = 0;
	}

	public function closeConnection( IDatabase $conn ) {
		$serverIndex = $conn->getLBInfo( 'serverIndex' ); // second index level of mConns
		foreach ( $this->mConns as $type => $connsByServer ) {
			if ( !isset( $connsByServer[$serverIndex] ) ) {
				continue;
			}

			foreach ( $connsByServer[$serverIndex] as $i => $trackedConn ) {
				if ( $conn === $trackedConn ) {
					unset( $this->mConns[$type][$serverIndex][$i] );
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
			function ( DatabaseBase $conn ) use ( $fname, $restore, &$failures ) {
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

	/**
	 * Perform all pre-commit callbacks that remain part of the atomic transactions
	 * and disable any post-commit callbacks until runMasterPostTrxCallbacks()
	 * @since 1.28
	 */
	public function finalizeMasterChanges() {
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) {
			// Any error should cause all DB transactions to be rolled back together
			$conn->setTrxEndCallbackSuppression( false );
			$conn->runOnTransactionPreCommitCallbacks();
			// Defer post-commit callbacks until COMMIT finishes for all DBs
			$conn->setTrxEndCallbackSuppression( true );
		} );
	}

	/**
	 * Perform all pre-commit checks for things like replication safety
	 * @param array $options Includes:
	 *   - maxWriteDuration : max write query duration time in seconds
	 * @throws DBTransactionError
	 * @since 1.28
	 */
	public function approveMasterChanges( array $options ) {
		$limit = isset( $options['maxWriteDuration'] ) ? $options['maxWriteDuration'] : 0;
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) use ( $limit ) {
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
				throw new DBTransactionError(
					$conn,
					"Transaction spent $time second(s) in writes, exceeding the $limit limit.",
					wfMessage( 'transaction-duration-limit-exceeded', $time, $limit )->text()
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

	/**
	 * Flush any master transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitMasterChanges()
	 *   - rollbackMasterChanges()
	 *   - commitAll()
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname
	 * @throws DBExpectedError
	 * @since 1.28
	 */
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
			function ( DatabaseBase $conn ) use ( $fname, &$failures ) {
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

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->forEachOpenMasterConnection(
			function ( DatabaseBase $conn ) use ( $fname, $restore, &$failures ) {
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

	/**
	 * Issue all pending post-COMMIT/ROLLBACK callbacks
	 * @param integer $type IDatabase::TRIGGER_* constant
	 * @return Exception|null The first exception or null if there were none
	 * @since 1.28
	 */
	public function runMasterPostTrxCallbacks( $type ) {
		$e = null; // first exception
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) use ( $type, &$e ) {
			$conn->setTrxEndCallbackSuppression( false );
			if ( $conn->writesOrCallbacksPending() ) {
				// This happens if onTransactionIdle() callbacks leave callbacks on *another* DB
				// (which finished its callbacks already). Warn and recover in this case. Let the
				// callbacks run in the final commitMasterChanges() in LBFactory::shutdown().
				$this->queryLogger->error( __METHOD__ . ": found writes/callbacks pending." );
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

	/**
	 * Issue ROLLBACK only on master, only if queries were done on connection
	 * @param string $fname Caller name
	 * @throws DBExpectedError
	 * @since 1.23
	 */
	public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->forEachOpenMasterConnection(
			function ( DatabaseBase $conn ) use ( $fname, $restore ) {
				if ( $conn->writesOrCallbacksPending() ) {
					$conn->rollback( $fname, $conn::FLUSHING_ALL_PEERS );
				}
				if ( $restore ) {
					$this->undoTransactionRoundFlags( $conn );
				}
			}
		);
	}

	/**
	 * Suppress all pending post-COMMIT/ROLLBACK callbacks
	 * @return Exception|null The first exception or null if there were none
	 * @since 1.28
	 */
	public function suppressTransactionEndCallbacks() {
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) {
			$conn->setTrxEndCallbackSuppression( true );
		} );
	}

	/**
	 * @param IDatabase $conn
	 */
	private function applyTransactionRoundFlags( IDatabase $conn ) {
		if ( $conn->getFlag( DBO_DEFAULT ) ) {
			// DBO_TRX is controlled entirely by CLI mode presence with DBO_DEFAULT.
			// Force DBO_TRX even in CLI mode since a commit round is expected soon.
			$conn->setFlag( DBO_TRX, $conn::REMEMBER_PRIOR );
			// If config has explicitly requested DBO_TRX be either on or off by not
			// setting DBO_DEFAULT, then respect that. Forcing no transactions is useful
			// for things like blob stores (ExternalStore) which want auto-commit mode.
		}
	}

	/**
	 * @param IDatabase $conn
	 */
	private function undoTransactionRoundFlags( IDatabase $conn ) {
		if ( $conn->getFlag( DBO_DEFAULT ) ) {
			$conn->restoreFlags( $conn::RESTORE_PRIOR );
		}
	}

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshot
	 *
	 * @param string $fname Caller name
	 * @since 1.28
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		$this->forEachOpenReplicaConnection( function ( DatabaseBase $conn ) {
			$conn->flushSnapshot( __METHOD__ );
		} );
	}

	/**
	 * @return bool Whether a master connection is already open
	 * @since 1.24
	 */
	public function hasMasterConnection() {
		return $this->isOpen( $this->getWriterIndex() );
	}

	/**
	 * Determine if there are pending changes in a transaction by this thread
	 * @since 1.23
	 * @return bool
	 */
	public function hasMasterChanges() {
		$pending = 0;
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) use ( &$pending ) {
			$pending |= $conn->writesOrCallbacksPending();
		} );

		return (bool)$pending;
	}

	/**
	 * Get the timestamp of the latest write query done by this thread
	 * @since 1.25
	 * @return float|bool UNIX timestamp or false
	 */
	public function lastMasterChangeTimestamp() {
		$lastTime = false;
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) use ( &$lastTime ) {
			$lastTime = max( $lastTime, $conn->lastDoneWrites() );
		} );

		return $lastTime;
	}

	/**
	 * Check if this load balancer object had any recent or still
	 * pending writes issued against it by this PHP thread
	 *
	 * @param float $age How many seconds ago is "recent" [defaults to mWaitTimeout]
	 * @return bool
	 * @since 1.25
	 */
	public function hasOrMadeRecentMasterChanges( $age = null ) {
		$age = ( $age === null ) ? $this->mWaitTimeout : $age;

		return ( $this->hasMasterChanges()
			|| $this->lastMasterChangeTimestamp() > microtime( true ) - $age );
	}

	/**
	 * Get the list of callers that have pending master changes
	 *
	 * @return string[] List of method names
	 * @since 1.27
	 */
	public function pendingMasterChangeCallers() {
		$fnames = [];
		$this->forEachOpenMasterConnection( function ( DatabaseBase $conn ) use ( &$fnames ) {
			$fnames = array_merge( $fnames, $conn->pendingWriteCallers() );
		} );

		return $fnames;
	}

	public function getLaggedReplicaMode( $wiki = false ) {
		// No-op if there is only one DB (also avoids recursion)
		if ( !$this->laggedReplicaMode && $this->getServerCount() > 1 ) {
			try {
				// See if laggedReplicaMode gets set
				$conn = $this->getConnection( DB_REPLICA, false, $wiki );
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
	 * @param bool $wiki
	 * @return bool
	 * @deprecated 1.28; use getLaggedReplicaMode()
	 */
	public function getLaggedSlaveMode( $wiki = false ) {
		return $this->getLaggedReplicaMode( $wiki );
	}

	/**
	 * @note This method will never cause a new DB connection
	 * @return bool Whether any generic connection used for reads was highly "lagged"
	 * @since 1.28
	 */
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

	/**
	 * @note This method may trigger a DB connection if not yet done
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @param IDatabase|null DB master connection; used to avoid loops [optional]
	 * @return string|bool Reason the master is read-only or false if it is not
	 * @since 1.27
	 */
	public function getReadOnlyReason( $wiki = false, IDatabase $conn = null ) {
		if ( $this->readOnlyReason !== false ) {
			return $this->readOnlyReason;
		} elseif ( $this->getLaggedReplicaMode( $wiki ) ) {
			if ( $this->allReplicasDownMode ) {
				return 'The database has been automatically locked ' .
					'until the replica database servers become available';
			} else {
				return 'The database has been automatically locked ' .
					'while the replica database servers catch up to the master.';
			}
		} elseif ( $this->masterRunningReadOnly( $wiki, $conn ) ) {
			return 'The database master is running in read-only mode.';
		}

		return false;
	}

	/**
	 * @param string $wiki Wiki ID, or false for the current wiki
	 * @param IDatabase|null DB master connectionl used to avoid loops [optional]
	 * @return bool
	 */
	private function masterRunningReadOnly( $wiki, IDatabase $conn = null ) {
		$cache = $this->wanCache;
		$masterServer = $this->getServerName( $this->getWriterIndex() );

		return (bool)$cache->getWithSetCallback(
			$cache->makeGlobalKey( __CLASS__, 'server-read-only', $masterServer ),
			self::TTL_CACHE_READONLY,
			function () use ( $wiki, $conn ) {
				$this->trxProfiler->setSilenced( true );
				try {
					$dbw = $conn ?: $this->getConnection( DB_MASTER, [], $wiki );
					$readOnly = (int)$dbw->serverIsReadOnly();
				} catch ( DBError $e ) {
					$readOnly = 0;
				}
				$this->trxProfiler->setSilenced( false );
				return $readOnly;
			},
			[ 'pcTTL' => $cache::TTL_PROC_LONG, 'busyValue' => 0 ]
		);
	}

	public function allowLagged( $mode = null ) {
		if ( $mode === null ) {
			return $this->mAllowLagged;
		}
		$this->mAllowLagged = $mode;

		return $this->mAllowLagged;
	}

	public function pingAll() {
		$success = true;
		$this->forEachOpenConnection( function ( DatabaseBase $conn ) use ( &$success ) {
			if ( !$conn->ping() ) {
				$success = false;
			}
		} );

		return $success;
	}

	public function forEachOpenConnection( $callback, array $params = [] ) {
		foreach ( $this->mConns as $connsByServer ) {
			foreach ( $connsByServer as $serverConns ) {
				foreach ( $serverConns as $conn ) {
					$mergedParams = array_merge( [ $conn ], $params );
					call_user_func_array( $callback, $mergedParams );
				}
			}
		}
	}

	/**
	 * Call a function with each open connection object to a master
	 * @param callable $callback
	 * @param array $params
	 * @since 1.28
	 */
	public function forEachOpenMasterConnection( $callback, array $params = [] ) {
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $connsByServer ) {
			if ( isset( $connsByServer[$masterIndex] ) ) {
				/** @var DatabaseBase $conn */
				foreach ( $connsByServer[$masterIndex] as $conn ) {
					$mergedParams = array_merge( [ $conn ], $params );
					call_user_func_array( $callback, $mergedParams );
				}
			}
		}
	}

	/**
	 * Call a function with each open replica DB connection object
	 * @param callable $callback
	 * @param array $params
	 * @since 1.28
	 */
	public function forEachOpenReplicaConnection( $callback, array $params = [] ) {
		foreach ( $this->mConns as $connsByServer ) {
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

	public function getMaxLag( $wiki = false ) {
		$maxLag = -1;
		$host = '';
		$maxIndex = 0;

		if ( $this->getServerCount() <= 1 ) {
			return [ $host, $maxLag, $maxIndex ]; // no replication = no lag
		}

		$lagTimes = $this->getLagTimes( $wiki );
		foreach ( $lagTimes as $i => $lag ) {
			if ( $this->mLoads[$i] > 0 && $lag > $maxLag ) {
				$maxLag = $lag;
				$host = $this->mServers[$i]['host'];
				$maxIndex = $i;
			}
		}

		return [ $host, $maxLag, $maxIndex ];
	}

	public function getLagTimes( $wiki = false ) {
		if ( $this->getServerCount() <= 1 ) {
			return [ 0 => 0 ]; // no replication = no lag
		}

		# Send the request to the load monitor
		return $this->getLoadMonitor()->getLagTimes( array_keys( $this->mServers ), $wiki );
	}

	public function safeGetLag( IDatabase $conn ) {
		if ( $this->getServerCount() == 1 ) {
			return 0;
		} else {
			return $conn->getLag();
		}
	}

	/**
	 * Wait for a replica DB to reach a specified master position
	 *
	 * This will connect to the master to get an accurate position if $pos is not given
	 *
	 * @param IDatabase $conn Replica DB
	 * @param DBMasterPos|bool $pos Master position; default: current position
	 * @param integer $timeout Timeout in seconds
	 * @return bool Success
	 * @since 1.27
	 */
	public function safeWaitForMasterPos( IDatabase $conn, $pos = false, $timeout = 10 ) {
		if ( $this->getServerCount() == 1 || !$conn->getLBInfo( 'replica' ) ) {
			return true; // server is not a replica DB
		}

		$pos = $pos ?: $this->getConnection( DB_MASTER )->getMasterPos();
		if ( !( $pos instanceof DBMasterPos ) ) {
			return false; // something is misconfigured
		}

		$result = $conn->masterPosWait( $pos, $timeout );
		if ( $result == -1 || is_null( $result ) ) {
			$msg = __METHOD__ . ": Timed out waiting on {$conn->getServer()} pos {$pos}";
			$this->replLogger->warning( "$msg" );
			$this->perfLogger->warning( "$msg:" );
			$ok = false;
		} else {
			$this->replLogger->info( __METHOD__ . ": Done" );
			$ok = true;
		}

		return $ok;
	}

	/**
	 * Clear the cache for slag lag delay times
	 *
	 * This is only used for testing
	 * @since 1.26
	 */
	public function clearLagTimeCache() {
		$this->getLoadMonitor()->clearCaches();
	}

	/**
	 * Set a callback via DatabaseBase::setTransactionListener() on
	 * all current and future master connections of this load balancer
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback
	 * @since 1.28
	 */
	public function setTransactionListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->trxRecurringCallbacks[$name] = $callback;
		} else {
			unset( $this->trxRecurringCallbacks[$name] );
		}
		$this->forEachOpenMasterConnection(
			function ( DatabaseBase $conn ) use ( $name, $callback ) {
				$conn->setTransactionListener( $name, $callback );
			}
		);
	}

	/**
	 * Set a new table prefix for the existing local wiki ID for testing
	 *
	 * @param string $prefix
	 * @since 1.28
	 */
	public function setDomainPrefix( $prefix ) {
		list( $dbName, ) = explode( '-', $this->localDomain, 2 );

		$this->localDomain = "{$dbName}-{$prefix}";
	}
}
