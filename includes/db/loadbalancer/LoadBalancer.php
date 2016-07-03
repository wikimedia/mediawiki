<?php
/**
 * Database load balancing.
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

/**
 * Database load balancing object
 *
 * @todo document
 * @ingroup Database
 */
class LoadBalancer {
	/** @var array[] Map of (server index => server config array) */
	private $mServers;
	/** @var array[] Map of (local/foreignUsed/foreignFree => server index => DatabaseBase array) */
	private $mConns;
	/** @var array Map of (server index => weight) */
	private $mLoads;
	/** @var array[] Map of (group => server index => weight) */
	private $mGroupLoads;
	/** @var bool Whether to disregard slave lag as a factor in slave selection */
	private $mAllowLagged;
	/** @var integer Seconds to spend waiting on slave lag to resolve */
	private $mWaitTimeout;
	/** @var array LBFactory information */
	private $mParentInfo;

	/** @var string The LoadMonitor subclass name */
	private $mLoadMonitorClass;
	/** @var LoadMonitor */
	private $mLoadMonitor;
	/** @var BagOStuff */
	private $srvCache;

	/** @var bool|DatabaseBase Database connection that caused a problem */
	private $mErrorConnection;
	/** @var integer The generic (not query grouped) slave index (of $mServers) */
	private $mReadIndex;
	/** @var bool|DBMasterPos False if not set */
	private $mWaitForPos;
	/** @var bool Whether the generic reader fell back to a lagged slave */
	private $laggedSlaveMode = false;
	/** @var bool Whether the generic reader fell back to a lagged slave */
	private $slavesDownMode = false;
	/** @var string The last DB selection or connection error */
	private $mLastError = 'Unknown error';
	/** @var string|bool Reason the LB is read-only or false if not */
	private $readOnlyReason = false;
	/** @var integer Total connections opened */
	private $connsOpened = 0;

	/** @var TransactionProfiler */
	protected $trxProfiler;

	/** @var integer Warn when this many connection are held */
	const CONN_HELD_WARN_THRESHOLD = 10;
	/** @var integer Default 'max lag' when unspecified */
	const MAX_LAG = 10;
	/** @var integer Max time to wait for a slave to catch up (e.g. ChronologyProtector) */
	const POS_WAIT_TIMEOUT = 10;

	/**
	 * @param array $params Array with keys:
	 *  - servers : Required. Array of server info structures.
	 *  - loadMonitor : Name of a class used to fetch server lag and load.
	 *  - readOnlyReason : Reason the master DB is read-only if so [optional]
	 * @throws MWException
	 */
	public function __construct( array $params ) {
		if ( !isset( $params['servers'] ) ) {
			throw new MWException( __CLASS__ . ': missing servers parameter' );
		}
		$this->mServers = $params['servers'];
		$this->mWaitTimeout = self::POS_WAIT_TIMEOUT;

		$this->mReadIndex = -1;
		$this->mWriteIndex = -1;
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

		$this->srvCache = ObjectCache::getLocalServerInstance();

		if ( isset( $params['trxProfiler'] ) ) {
			$this->trxProfiler = $params['trxProfiler'];
		} else {
			$this->trxProfiler = new TransactionProfiler();
		}
	}

	/**
	 * Get a LoadMonitor instance
	 *
	 * @return LoadMonitor
	 */
	private function getLoadMonitor() {
		if ( !isset( $this->mLoadMonitor ) ) {
			$class = $this->mLoadMonitorClass;
			$this->mLoadMonitor = new $class( $this );
		}

		return $this->mLoadMonitor;
	}

	/**
	 * Get or set arbitrary data used by the parent object, usually an LBFactory
	 * @param mixed $x
	 * @return mixed
	 */
	public function parentInfo( $x = null ) {
		return wfSetVar( $this->mParentInfo, $x );
	}

	/**
	 * @param array $loads
	 * @param bool|string $wiki Wiki to get non-lagged for
	 * @param int $maxLag Restrict the maximum allowed lag to this many seconds
	 * @return bool|int|string
	 */
	private function getRandomNonLagged( array $loads, $wiki = false, $maxLag = self::MAX_LAG ) {
		$lags = $this->getLagTimes( $wiki );

		# Unset excessively lagged servers
		foreach ( $lags as $i => $lag ) {
			if ( $i != 0 ) {
				$maxServerLag = $maxLag;
				if ( isset( $this->mServers[$i]['max lag'] ) ) {
					$maxServerLag = min( $maxServerLag, $this->mServers[$i]['max lag'] );
				}

				$host = $this->getServerName( $i );
				if ( $lag === false ) {
					wfDebugLog( 'replication', "Server $host (#$i) is not replicating?" );
					unset( $loads[$i] );
				} elseif ( $lag > $maxServerLag ) {
					wfDebugLog( 'replication', "Server $host (#$i) has >= $lag seconds of lag" );
					unset( $loads[$i] );
				}
			}
		}

		# Find out if all the slaves with non-zero load are lagged
		$sum = 0;
		foreach ( $loads as $load ) {
			$sum += $load;
		}
		if ( $sum == 0 ) {
			# No appropriate DB servers except maybe the master and some slaves with zero load
			# Do NOT use the master
			# Instead, this function will return false, triggering read-only mode,
			# and a lagged slave will be used instead.
			return false;
		}

		if ( count( $loads ) == 0 ) {
			return false;
		}

		# Return a random representative of the remainder
		return ArrayUtils::pickRandom( $loads );
	}

	/**
	 * Get the index of the reader connection, which may be a slave
	 * This takes into account load ratios and lag times. It should
	 * always return a consistent index during a given invocation
	 *
	 * Side effect: opens connections to databases
	 * @param string|bool $group Query group, or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @throws MWException
	 * @return bool|int|string
	 */
	public function getReaderIndex( $group = false, $wiki = false ) {
		global $wgDBtype;

		# @todo FIXME: For now, only go through all this for mysql databases
		if ( $wgDBtype != 'mysql' ) {
			return $this->getWriterIndex();
		}

		if ( count( $this->mServers ) == 1 ) {
			# Skip the load balancing if there's only one server
			return 0;
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
				wfDebugLog( 'connect', __METHOD__ . ": no loads for group $group\n" );

				return false;
			}
		} else {
			$nonErrorLoads = $this->mLoads;
		}

		if ( !count( $nonErrorLoads ) ) {
			throw new MWException( "Empty server array given to LoadBalancer" );
		}

		# Scale the configured load ratios according to the dynamic load (if the load monitor supports it)
		$this->getLoadMonitor()->scaleLoads( $nonErrorLoads, $group, $wiki );

		$laggedSlaveMode = false;

		# No server found yet
		$i = false;
		$conn = false;
		# First try quickly looking through the available servers for a server that
		# meets our criteria
		$currentLoads = $nonErrorLoads;
		while ( count( $currentLoads ) ) {
			if ( $this->mAllowLagged || $laggedSlaveMode ) {
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
					# All slaves lagged. Switch to read-only mode
					wfDebugLog( 'replication', "All slaves lagged. Switch to read-only mode" );
					$i = ArrayUtils::pickRandom( $currentLoads );
					$laggedSlaveMode = true;
				}
			}

			if ( $i === false ) {
				# pickRandom() returned false
				# This is permanent and means the configuration or the load monitor
				# wants us to return false.
				wfDebugLog( 'connect', __METHOD__ . ": pickRandom() returned false" );

				return false;
			}

			$serverName = $this->getServerName( $i );
			wfDebugLog( 'connect', __METHOD__ . ": Using reader #$i: $serverName..." );

			$conn = $this->openConnection( $i, $wiki );
			if ( !$conn ) {
				wfDebugLog( 'connect', __METHOD__ . ": Failed connecting to $i/$wiki" );
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
			wfDebugLog( 'connect', "All servers down" );
		}

		if ( $i !== false ) {
			# Slave connection successful
			# Wait for the session master pos for a short time
			if ( $this->mWaitForPos && $i > 0 ) {
				if ( !$this->doWait( $i ) ) {
					$this->mServers[$i]['slave pos'] = $conn->getSlavePos();
				}
			}
			if ( $this->mReadIndex <= 0 && $this->mLoads[$i] > 0 && $group === false ) {
				$this->mReadIndex = $i;
				# Record if the generic reader index is in "lagged slave" mode
				if ( $laggedSlaveMode ) {
					$this->laggedSlaveMode = true;
				}
			}
			$serverName = $this->getServerName( $i );
			wfDebugLog( 'connect', __METHOD__ .
				": using server $serverName for group '$group'\n" );
		}

		return $i;
	}

	/**
	 * Set the master wait position
	 * If a DB_SLAVE connection has been opened already, waits
	 * Otherwise sets a variable telling it to wait if such a connection is opened
	 * @param DBMasterPos $pos
	 */
	public function waitFor( $pos ) {
		$this->mWaitForPos = $pos;
		$i = $this->mReadIndex;

		if ( $i > 0 ) {
			if ( !$this->doWait( $i ) ) {
				$this->mServers[$i]['slave pos'] = $this->getAnyOpenConnection( $i )->getSlavePos();
				$this->laggedSlaveMode = true;
			}
		}
	}

	/**
	 * Set the master wait position and wait for a "generic" slave to catch up to it
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
			// Pick a generic slave if there isn't one yet
			$readLoads = $this->mLoads;
			unset( $readLoads[$this->getWriterIndex()] ); // slaves only
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

	/**
	 * Set the master wait position and wait for ALL slaves to catch up to it
	 * @param DBMasterPos $pos
	 * @param int $timeout Max seconds to wait; default is mWaitTimeout
	 * @return bool Success (able to connect and no timeouts reached)
	 */
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

	/**
	 * Get any open connection to a given server index, local or foreign
	 * Returns false if there is no connection open
	 *
	 * @param int $i
	 * @return DatabaseBase|bool False on failure
	 */
	public function getAnyOpenConnection( $i ) {
		foreach ( $this->mConns as $conns ) {
			if ( !empty( $conns[$i] ) ) {
				return reset( $conns[$i] );
			}
		}

		return false;
	}

	/**
	 * Wait for a given slave to catch up to the master pos stored in $this
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
			wfDebugLog( 'replication', __METHOD__ .
				": slave $server known to be caught up (pos >= $knownReachedPos).\n" );
			return true;
		}

		// Find a connection to wait on, creating one if needed and allowed
		$conn = $this->getAnyOpenConnection( $index );
		if ( !$conn ) {
			if ( !$open ) {
				wfDebugLog( 'replication', __METHOD__ . ": no connection open for $server\n" );

				return false;
			} else {
				$conn = $this->openConnection( $index, '' );
				if ( !$conn ) {
					wfDebugLog( 'replication', __METHOD__ . ": failed to connect to $server\n" );

					return false;
				}
				// Avoid connection spam in waitForAll() when connections
				// are made just for the sake of doing this lag check.
				$close = true;
			}
		}

		wfDebugLog( 'replication', __METHOD__ . ": Waiting for slave $server to catch up...\n" );
		$timeout = $timeout ?: $this->mWaitTimeout;
		$result = $conn->masterPosWait( $this->mWaitForPos, $timeout );

		if ( $result == -1 || is_null( $result ) ) {
			// Timed out waiting for slave, use master instead
			$msg = __METHOD__ . ": Timed out waiting on $server pos {$this->mWaitForPos}";
			wfDebugLog( 'replication', "$msg\n" );
			wfDebugLog( 'DBPerformance', "$msg:\n" . wfBacktrace( true ) );
			$ok = false;
		} else {
			wfDebugLog( 'replication', __METHOD__ . ": Done\n" );
			$ok = true;
			// Remember that the DB reached this point
			$this->srvCache->set( $key, $this->mWaitForPos, BagOStuff::TTL_DAY );
		}

		if ( $close ) {
			$this->closeConnection( $conn );
		}

		return $ok;
	}

	/**
	 * Get a connection by index
	 * This is the main entry point for this class.
	 *
	 * @param int $i Server index
	 * @param array|string|bool $groups Query group(s), or false for the generic reader
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 *
	 * @throws MWException
	 * @return DatabaseBase
	 */
	public function getConnection( $i, $groups = [], $wiki = false ) {
		if ( $i === null || $i === false ) {
			throw new MWException( 'Attempt to call ' . __METHOD__ .
				' with invalid server index' );
		}

		if ( $wiki === wfWikiID() ) {
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
		if ( $i == DB_SLAVE ) {
			$this->mLastError = 'Unknown error'; // reset error string
			# Try the general server pool if $groups are unavailable.
			$i = in_array( false, $groups, true )
				? false // don't bother with this if that is what was tried above
				: $this->getReaderIndex( false, $wiki );
			# Couldn't find a working server in getReaderIndex()?
			if ( $i === false ) {
				$this->mLastError = 'No working slave server: ' . $this->mLastError;

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
			$trxProf = Profiler::instance()->getTransactionProfiler();
			$trxProf->recordConnection( $host, $dbname, $masterOnly );
		}

		if ( $masterOnly ) {
			# Make master-requested DB handles inherit any read-only mode setting
			$conn->setLBInfo( 'readOnlyReason', $this->getReadOnlyReason( $wiki ) );
		}

		return $conn;
	}

	/**
	 * Mark a foreign connection as being available for reuse under a different
	 * DB name or prefix. This mechanism is reference-counted, and must be called
	 * the same number of times as getConnection() to work.
	 *
	 * @param DatabaseBase $conn
	 * @throws MWException
	 */
	public function reuseConnection( $conn ) {
		$serverIndex = $conn->getLBInfo( 'serverIndex' );
		$refCount = $conn->getLBInfo( 'foreignPoolRefCount' );
		if ( $serverIndex === null || $refCount === null ) {
			wfDebug( __METHOD__ . ": this connection was not opened as a foreign connection\n" );
			/**
			 * This can happen in code like:
			 *   foreach ( $dbs as $db ) {
			 *     $conn = $lb->getConnection( DB_SLAVE, array(), $db );
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
			throw new MWException( __METHOD__ . ": connection not found, has " .
				"the connection been freed already?" );
		}
		$conn->setLBInfo( 'foreignPoolRefCount', --$refCount );
		if ( $refCount <= 0 ) {
			$this->mConns['foreignFree'][$serverIndex][$wiki] = $conn;
			unset( $this->mConns['foreignUsed'][$serverIndex][$wiki] );
			wfDebug( __METHOD__ . ": freed connection $serverIndex/$wiki\n" );
		} else {
			wfDebug( __METHOD__ . ": reference count for $serverIndex/$wiki reduced to $refCount\n" );
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
	 */
	public function getLazyConnectionRef( $db, $groups = [], $wiki = false ) {
		return new DBConnRef( $this, [ $db, $groups, $wiki ] );
	}

	/**
	 * Open a connection to the server given by the specified index
	 * Index must be an actual index into the array.
	 * If the server is already open, returns it.
	 *
	 * On error, returns false, and the connection which caused the
	 * error will be available via $this->mErrorConnection.
	 *
	 * @param int $i Server index
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return DatabaseBase|bool Returns false on errors
	 */
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
				wfDebugLog( 'connect', "Connected to database $i at $serverName\n" );
				$this->mConns['local'][$i][0] = $conn;
			} else {
				wfDebugLog( 'connect', "Failed to connect to database $i at $serverName\n" );
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
	 * @param int $i Server index
	 * @param string $wiki Wiki ID to open
	 * @return DatabaseBase
	 */
	private function openForeignConnection( $i, $wiki ) {
		list( $dbName, $prefix ) = wfSplitWikiID( $wiki );
		if ( isset( $this->mConns['foreignUsed'][$i][$wiki] ) ) {
			// Reuse an already-used connection
			$conn = $this->mConns['foreignUsed'][$i][$wiki];
			wfDebug( __METHOD__ . ": reusing connection $i/$wiki\n" );
		} elseif ( isset( $this->mConns['foreignFree'][$i][$wiki] ) ) {
			// Reuse a free connection for the same wiki
			$conn = $this->mConns['foreignFree'][$i][$wiki];
			unset( $this->mConns['foreignFree'][$i][$wiki] );
			$this->mConns['foreignUsed'][$i][$wiki] = $conn;
			wfDebug( __METHOD__ . ": reusing free connection $i/$wiki\n" );
		} elseif ( !empty( $this->mConns['foreignFree'][$i] ) ) {
			// Reuse a connection from another wiki
			$conn = reset( $this->mConns['foreignFree'][$i] );
			$oldWiki = key( $this->mConns['foreignFree'][$i] );

			// The empty string as a DB name means "don't care".
			// DatabaseMysqlBase::open() already handle this on connection.
			if ( $dbName !== '' && !$conn->selectDB( $dbName ) ) {
				$this->mLastError = "Error selecting database $dbName on server " .
					$conn->getServer() . " from client host " . wfHostname() . "\n";
				$this->mErrorConnection = $conn;
				$conn = false;
			} else {
				$conn->tablePrefix( $prefix );
				unset( $this->mConns['foreignFree'][$i][$oldWiki] );
				$this->mConns['foreignUsed'][$i][$wiki] = $conn;
				wfDebug( __METHOD__ . ": reusing free connection from $oldWiki for $wiki\n" );
			}
		} else {
			// Open a new connection
			$server = $this->mServers[$i];
			$server['serverIndex'] = $i;
			$server['foreignPoolRefCount'] = 0;
			$server['foreign'] = true;
			$conn = $this->reallyOpenConnection( $server, $dbName );
			if ( !$conn->isOpen() ) {
				wfDebug( __METHOD__ . ": error opening connection for $i/$wiki\n" );
				$this->mErrorConnection = $conn;
				$conn = false;
			} else {
				$conn->tablePrefix( $prefix );
				$this->mConns['foreignUsed'][$i][$wiki] = $conn;
				wfDebug( __METHOD__ . ": opened new connection for $i/$wiki\n" );
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
	 * @throws MWException
	 * @return DatabaseBase
	 */
	protected function reallyOpenConnection( $server, $dbNameOverride = false ) {
		if ( !is_array( $server ) ) {
			throw new MWException( 'You must update your load-balancing configuration. ' .
				'See DefaultSettings.php entry for $wgDBservers.' );
		}

		if ( $dbNameOverride !== false ) {
			$server['dbname'] = $dbNameOverride;
		}

		// Let the handle know what the cluster master is (e.g. "db1052")
		$masterName = $this->getServerName( 0 );
		$server['clusterMasterHost'] = $masterName;

		// Log when many connection are made on requests
		if ( ++$this->connsOpened >= self::CONN_HELD_WARN_THRESHOLD ) {
			wfDebugLog( 'DBPerformance', __METHOD__ . ": " .
				"{$this->connsOpened}+ connections made (master=$masterName)\n" .
				wfBacktrace( true ) );
		}

		# Create object
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
			wfLogDBError(
				"LB failure with no last connection. Connection error: {last_error}",
				$context
			);

			// If all servers were busy, mLastError will contain something sensible
			throw new DBConnectionError( null, $this->mLastError );
		} else {
			$context['db_server'] = $conn->getProperty( 'mServer' );
			wfLogDBError(
				"Connection error: {last_error} ({db_server})",
				$context
			);

			// throws DBConnectionError
			$conn->reportConnectionError( "{$this->mLastError} ({$context['db_server']})" );
		}

		return false; /* not reached */
	}

	/**
	 * @return int
	 * @since 1.26
	 */
	public function getWriterIndex() {
		return 0;
	}

	/**
	 * Returns true if the specified index is a valid server index
	 *
	 * @param string $i
	 * @return bool
	 */
	public function haveIndex( $i ) {
		return array_key_exists( $i, $this->mServers );
	}

	/**
	 * Returns true if the specified index is valid and has non-zero load
	 *
	 * @param string $i
	 * @return bool
	 */
	public function isNonZeroLoad( $i ) {
		return array_key_exists( $i, $this->mServers ) && $this->mLoads[$i] != 0;
	}

	/**
	 * Get the number of defined servers (not the number of open connections)
	 *
	 * @return int
	 */
	public function getServerCount() {
		return count( $this->mServers );
	}

	/**
	 * Get the host name or IP address of the server with the specified index
	 * Prefer a readable name if available.
	 * @param string $i
	 * @return string
	 */
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

	/**
	 * Return the server info structure for a given index, or false if the index is invalid.
	 * @param int $i
	 * @return array|bool
	 */
	public function getServerInfo( $i ) {
		if ( isset( $this->mServers[$i] ) ) {
			return $this->mServers[$i];
		} else {
			return false;
		}
	}

	/**
	 * Sets the server info structure for the given index. Entry at index $i
	 * is created if it doesn't exist
	 * @param int $i
	 * @param array $serverInfo
	 */
	public function setServerInfo( $i, array $serverInfo ) {
		$this->mServers[$i] = $serverInfo;
	}

	/**
	 * Get the current master position for chronology control purposes
	 * @return mixed
	 */
	public function getMasterPos() {
		# If this entire request was served from a slave without opening a connection to the
		# master (however unlikely that may be), then we can fetch the position from the slave.
		$masterConn = $this->getAnyOpenConnection( 0 );
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
	 * Close all open connections
	 */
	public function closeAll() {
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				/** @var DatabaseBase $conn */
				foreach ( $conns3 as $conn ) {
					$conn->close();
				}
			}
		}
		$this->mConns = [
			'local' => [],
			'foreignFree' => [],
			'foreignUsed' => [],
		];
		$this->connsOpened = 0;
	}

	/**
	 * Close a connection
	 * Using this function makes sure the LoadBalancer knows the connection is closed.
	 * If you use $conn->close() directly, the load balancer won't update its state.
	 * @param DatabaseBase $conn
	 */
	public function closeConnection( $conn ) {
		$done = false;
		foreach ( $this->mConns as $i1 => $conns2 ) {
			foreach ( $conns2 as $i2 => $conns3 ) {
				foreach ( $conns3 as $i3 => $candidateConn ) {
					if ( $conn === $candidateConn ) {
						$conn->close();
						unset( $this->mConns[$i1][$i2][$i3] );
						--$this->connsOpened;
						$done = true;
						break;
					}
				}
			}
		}
		if ( !$done ) {
			$conn->close();
		}
	}

	/**
	 * Commit transactions on all open connections
	 * @param string $fname Caller name
	 */
	public function commitAll( $fname = __METHOD__ ) {
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				/** @var DatabaseBase[] $conns3 */
				foreach ( $conns3 as $conn ) {
					if ( $conn->trxLevel() ) {
						$conn->commit( $fname, 'flush' );
					}
				}
			}
		}
	}

	/**
	 * Issue COMMIT only on master, only if queries were done on connection
	 * @param string $fname Caller name
	 */
	public function commitMasterChanges( $fname = __METHOD__ ) {
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				if ( $conn->trxLevel() && $conn->writesOrCallbacksPending() ) {
					$conn->commit( $fname, 'flush' );
				}
			}
		}
	}

	/**
	 * Issue ROLLBACK only on master, only if queries were done on connection
	 * @param string $fname Caller name
	 * @throws DBExpectedError
	 * @since 1.23
	 */
	public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$failedServers = [];

		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				if ( $conn->trxLevel() && $conn->writesOrCallbacksPending() ) {
					try {
						$conn->rollback( $fname, 'flush' );
					} catch ( DBError $e ) {
						MWExceptionHandler::logException( $e );
						$failedServers[] = $conn->getServer();
					}
				}
			}
		}

		if ( $failedServers ) {
			throw new DBExpectedError( null, "Rollback failed on server(s) " .
				implode( ', ', array_unique( $failedServers ) ) );
		}
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
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				if ( $conn->trxLevel() && $conn->writesOrCallbacksPending() ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Get the timestamp of the latest write query done by this thread
	 * @since 1.25
	 * @return float|bool UNIX timestamp or false
	 */
	public function lastMasterChangeTimestamp() {
		$lastTime = false;
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				$lastTime = max( $lastTime, $conn->lastDoneWrites() );
			}
		}
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
	 * @return array
	 * @since 1.27
	 */
	public function pendingMasterChangeCallers() {
		$fnames = [];

		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				$fnames = array_merge( $fnames, $conn->pendingWriteCallers() );
			}
		}

		return $fnames;
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function waitTimeout( $value = null ) {
		return wfSetVar( $this->mWaitTimeout, $value );
	}

	/**
	 * @note This method will trigger a DB connection if not yet done
	 *
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return bool Whether the generic connection for reads is highly "lagged"
	 */
	public function getLaggedSlaveMode( $wiki = false ) {
		// No-op if there is only one DB (also avoids recursion)
		if ( !$this->laggedSlaveMode && $this->getServerCount() > 1 ) {
			try {
				// See if laggedSlaveMode gets set
				$conn = $this->getConnection( DB_SLAVE, false, $wiki );
				$this->reuseConnection( $conn );
			} catch ( DBConnectionError $e ) {
				// Avoid expensive re-connect attempts and failures
				$this->slavesDownMode = true;
				$this->laggedSlaveMode = true;
			}
		}

		return $this->laggedSlaveMode;
	}

	/**
	 * @note This method will never cause a new DB connection
	 * @return bool Whether any generic connection used for reads was highly "lagged"
	 * @since 1.27
	 */
	public function laggedSlaveUsed() {
		return $this->laggedSlaveMode;
	}

	/**
	 * @note This method may trigger a DB connection if not yet done
	 * @param string|bool $wiki Wiki ID, or false for the current wiki
	 * @return string|bool Reason the master is read-only or false if it is not
	 * @since 1.27
	 */
	public function getReadOnlyReason( $wiki = false ) {
		if ( $this->readOnlyReason !== false ) {
			return $this->readOnlyReason;
		} elseif ( $this->getLaggedSlaveMode( $wiki ) ) {
			if ( $this->slavesDownMode ) {
				return 'The database has been automatically locked ' .
					'until the slave database servers become available';
			} else {
				return 'The database has been automatically locked ' .
					'while the slave database servers catch up to the master.';
			}
		}

		return false;
	}

	/**
	 * Disables/enables lag checks
	 * @param null|bool $mode
	 * @return bool
	 */
	public function allowLagged( $mode = null ) {
		if ( $mode === null ) {
			return $this->mAllowLagged;
		}
		$this->mAllowLagged = $mode;

		return $this->mAllowLagged;
	}

	/**
	 * @return bool
	 */
	public function pingAll() {
		$success = true;
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				/** @var DatabaseBase[] $conns3 */
				foreach ( $conns3 as $conn ) {
					if ( !$conn->ping() ) {
						$success = false;
					}
				}
			}
		}

		return $success;
	}

	/**
	 * Call a function with each open connection object
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachOpenConnection( $callback, array $params = [] ) {
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				foreach ( $conns3 as $conn ) {
					$mergedParams = array_merge( [ $conn ], $params );
					call_user_func_array( $callback, $mergedParams );
				}
			}
		}
	}

	/**
	 * Get the hostname and lag time of the most-lagged slave
	 *
	 * This is useful for maintenance scripts that need to throttle their updates.
	 * May attempt to open connections to slaves on the default DB. If there is
	 * no lag, the maximum lag will be reported as -1.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the default database
	 * @return array ( host, max lag, index of max lagged host )
	 */
	public function getMaxLag( $wiki = false ) {
		$maxLag = -1;
		$host = '';
		$maxIndex = 0;

		if ( $this->getServerCount() <= 1 ) {
			return [ $host, $maxLag, $maxIndex ]; // no replication = no lag
		}

		$lagTimes = $this->getLagTimes( $wiki );
		foreach ( $lagTimes as $i => $lag ) {
			if ( $lag > $maxLag ) {
				$maxLag = $lag;
				$host = $this->mServers[$i]['host'];
				$maxIndex = $i;
			}
		}

		return [ $host, $maxLag, $maxIndex ];
	}

	/**
	 * Get an estimate of replication lag (in seconds) for each server
	 *
	 * Results are cached for a short time in memcached/process cache
	 *
	 * Values may be "false" if replication is too broken to estimate
	 *
	 * @param string|bool $wiki
	 * @return int[] Map of (server index => float|int|bool)
	 */
	public function getLagTimes( $wiki = false ) {
		if ( $this->getServerCount() <= 1 ) {
			return [ 0 => 0 ]; // no replication = no lag
		}

		# Send the request to the load monitor
		return $this->getLoadMonitor()->getLagTimes( array_keys( $this->mServers ), $wiki );
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
	 */
	public function safeGetLag( IDatabase $conn ) {
		if ( $this->getServerCount() == 1 ) {
			return 0;
		} else {
			return $conn->getLag();
		}
	}

	/**
	 * Wait for a slave DB to reach a specified master position
	 *
	 * This will connect to the master to get an accurate position if $pos is not given
	 *
	 * @param IDatabase $conn Slave DB
	 * @param DBMasterPos|bool $pos Master position; default: current position
	 * @param integer $timeout Timeout in seconds
	 * @return bool Success
	 * @since 1.27
	 */
	public function safeWaitForMasterPos( IDatabase $conn, $pos = false, $timeout = 10 ) {
		if ( $this->getServerCount() == 1 || !$conn->getLBInfo( 'slave' ) ) {
			return true; // server is not a slave DB
		}

		$pos = $pos ?: $this->getConnection( DB_MASTER )->getMasterPos();
		if ( !$pos ) {
			return false; // something is misconfigured
		}

		$result = $conn->masterPosWait( $pos, $timeout );
		if ( $result == -1 || is_null( $result ) ) {
			$msg = __METHOD__ . ": Timed out waiting on {$conn->getServer()} pos {$pos}";
			wfDebugLog( 'replication', "$msg\n" );
			wfDebugLog( 'DBPerformance', "$msg:\n" . wfBacktrace( true ) );
			$ok = false;
		} else {
			wfDebugLog( 'replication', __METHOD__ . ": Done\n" );
			$ok = true;
		}

		return $ok;
	}

	/**
	 * Clear the cache for slag lag delay times
	 *
	 * This is only used for testing
	 */
	public function clearLagTimeCache() {
		$this->getLoadMonitor()->clearCaches();
	}
}
