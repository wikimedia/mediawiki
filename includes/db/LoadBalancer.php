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
	private $mServers, $mConns, $mLoads, $mGroupLoads;

	/** @var bool|DatabaseBase Database connection that caused a problem */
	private $mErrorConnection;
	private $mReadIndex, $mAllowLagged;

	/** @var bool|DBMasterPos False if not set */
	private $mWaitForPos;

	private $mWaitTimeout;
	private $mLaggedSlaveMode, $mLastError = 'Unknown error';
	private $mParentInfo, $mLagTimes;
	private $mLoadMonitorClass, $mLoadMonitor;

	/**
	 * @param array $params with keys:
	 *   servers           Required. Array of server info structures.
	 *   masterWaitTimeout Replication lag wait timeout
	 *   loadMonitor       Name of a class used to fetch server lag and load.
	 * @throws MWException
	 */
	function __construct( $params ) {
		if ( !isset( $params['servers'] ) ) {
			throw new MWException( __CLASS__ . ': missing servers parameter' );
		}
		$this->mServers = $params['servers'];

		if ( isset( $params['waitTimeout'] ) ) {
			$this->mWaitTimeout = $params['waitTimeout'];
		} else {
			$this->mWaitTimeout = 10;
		}

		$this->mReadIndex = -1;
		$this->mWriteIndex = -1;
		$this->mConns = array(
			'local' => array(),
			'foreignUsed' => array(),
			'foreignFree' => array() );
		$this->mLoads = array();
		$this->mWaitForPos = false;
		$this->mLaggedSlaveMode = false;
		$this->mErrorConnection = false;
		$this->mAllowLagged = false;

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
						$this->mGroupLoads[$group] = array();
					}
					$this->mGroupLoads[$group][$i] = $ratio;
				}
			}
		}
	}

	/**
	 * Get a LoadMonitor instance
	 *
	 * @return LoadMonitor
	 */
	function getLoadMonitor() {
		if ( !isset( $this->mLoadMonitor ) ) {
			$class = $this->mLoadMonitorClass;
			$this->mLoadMonitor = new $class( $this );
		}

		return $this->mLoadMonitor;
	}

	/**
	 * Get or set arbitrary data used by the parent object, usually an LBFactory
	 * @param $x
	 * @return mixed
	 */
	function parentInfo( $x = null ) {
		return wfSetVar( $this->mParentInfo, $x );
	}

	/**
	 * Given an array of non-normalised probabilities, this function will select
	 * an element and return the appropriate key
	 *
	 * @deprecated since 1.21, use ArrayUtils::pickRandom()
	 *
	 * @param array $weights
	 * @return bool|int|string
	 */
	function pickRandom( $weights ) {
		return ArrayUtils::pickRandom( $weights );
	}

	/**
	 * @param array $loads
	 * @param bool|string $wiki Wiki to get non-lagged for
	 * @return bool|int|string
	 */
	function getRandomNonLagged( $loads, $wiki = false ) {
		# Unset excessively lagged servers
		$lags = $this->getLagTimes( $wiki );
		foreach ( $lags as $i => $lag ) {
			if ( $i != 0 ) {
				if ( $lag === false ) {
					wfDebugLog( 'replication', "Server #$i is not replicating" );
					unset( $loads[$i] );
				} elseif ( isset( $this->mServers[$i]['max lag'] ) && $lag > $this->mServers[$i]['max lag'] ) {
					wfDebugLog( 'replication', "Server #$i is excessively lagged ($lag seconds)" );
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

		#wfDebugLog( 'connect', var_export( $loads, true ) );

		# Return a random representative of the remainder
		return ArrayUtils::pickRandom( $loads );
	}

	/**
	 * Get the index of the reader connection, which may be a slave
	 * This takes into account load ratios and lag times. It should
	 * always return a consistent index during a given invocation
	 *
	 * Side effect: opens connections to databases
	 * @param bool|string $group
	 * @param bool|string $wiki
	 * @throws MWException
	 * @return bool|int|string
	 */
	function getReaderIndex( $group = false, $wiki = false ) {
		global $wgReadOnly, $wgDBtype;

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

		$section = new ProfileSection( __METHOD__ );

		# Find the relevant load array
		if ( $group !== false ) {
			if ( isset( $this->mGroupLoads[$group] ) ) {
				$nonErrorLoads = $this->mGroupLoads[$group];
			} else {
				# No loads for this group, return false and the caller can use some other group
				wfDebug( __METHOD__ . ": no loads for group $group\n" );

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
		# First try quickly looking through the available servers for a server that
		# meets our criteria
		$currentLoads = $nonErrorLoads;
		while ( count( $currentLoads ) ) {
			if ( $wgReadOnly || $this->mAllowLagged || $laggedSlaveMode ) {
				$i = ArrayUtils::pickRandom( $currentLoads );
			} else {
				$i = $this->getRandomNonLagged( $currentLoads, $wiki );
				if ( $i === false && count( $currentLoads ) != 0 ) {
					# All slaves lagged. Switch to read-only mode
					wfDebugLog( 'replication', "All slaves lagged. Switch to read-only mode" );
					$wgReadOnly = 'The database has been automatically locked ' .
						'while the slave database servers catch up to the master';
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

			wfDebugLog( 'connect', __METHOD__ .
				": Using reader #$i: {$this->mServers[$i]['host']}..." );

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
			if ( $this->mReadIndex <= 0 && $this->mLoads[$i] > 0 && $group !== false ) {
				$this->mReadIndex = $i;
			}
		}

		return $i;
	}

	/**
	 * Wait for a specified number of microseconds, and return the period waited
	 * @param int $t
	 * @return int
	 */
	function sleep( $t ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . ": waiting $t us\n" );
		usleep( $t );
		wfProfileOut( __METHOD__ );

		return $t;
	}

	/**
	 * Set the master wait position
	 * If a DB_SLAVE connection has been opened already, waits
	 * Otherwise sets a variable telling it to wait if such a connection is opened
	 * @param DBMasterPos $pos
	 */
	public function waitFor( $pos ) {
		wfProfileIn( __METHOD__ );
		$this->mWaitForPos = $pos;
		$i = $this->mReadIndex;

		if ( $i > 0 ) {
			if ( !$this->doWait( $i ) ) {
				$this->mServers[$i]['slave pos'] = $this->getAnyOpenConnection( $i )->getSlavePos();
				$this->mLaggedSlaveMode = true;
			}
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Set the master wait position and wait for ALL slaves to catch up to it
	 * @param DBMasterPos $pos
	 */
	public function waitForAll( $pos ) {
		wfProfileIn( __METHOD__ );
		$this->mWaitForPos = $pos;
		$serverCount = count( $this->mServers );
		for ( $i = 1; $i < $serverCount; $i++ ) {
			if ( $this->mLoads[$i] > 0 ) {
				$this->doWait( $i, true );
			}
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get any open connection to a given server index, local or foreign
	 * Returns false if there is no connection open
	 *
	 * @param int $i
	 * @return DatabaseBase|bool False on failure
	 */
	function getAnyOpenConnection( $i ) {
		foreach ( $this->mConns as $conns ) {
			if ( !empty( $conns[$i] ) ) {
				return reset( $conns[$i] );
			}
		}

		return false;
	}

	/**
	 * Wait for a given slave to catch up to the master pos stored in $this
	 * @param $index
	 * @param $open bool
	 * @return bool
	 */
	protected function doWait( $index, $open = false ) {
		# Find a connection to wait on
		$conn = $this->getAnyOpenConnection( $index );
		if ( !$conn ) {
			if ( !$open ) {
				wfDebug( __METHOD__ . ": no connection open\n" );

				return false;
			} else {
				$conn = $this->openConnection( $index, '' );
				if ( !$conn ) {
					wfDebug( __METHOD__ . ": failed to open connection\n" );

					return false;
				}
			}
		}

		wfDebug( __METHOD__ . ": Waiting for slave #$index to catch up...\n" );
		$result = $conn->masterPosWait( $this->mWaitForPos, $this->mWaitTimeout );

		if ( $result == -1 || is_null( $result ) ) {
			# Timed out waiting for slave, use master instead
			wfDebug( __METHOD__ . ": Timed out waiting for slave #$index pos {$this->mWaitForPos}\n" );

			return false;
		} else {
			wfDebug( __METHOD__ . ": Done\n" );

			return true;
		}
	}

	/**
	 * Get a connection by index
	 * This is the main entry point for this class.
	 *
	 * @param $i Integer: server index
	 * @param array $groups query groups
	 * @param bool|string $wiki Wiki ID
	 *
	 * @throws MWException
	 * @return DatabaseBase
	 */
	public function &getConnection( $i, $groups = array(), $wiki = false ) {
		wfProfileIn( __METHOD__ );

		if ( $i == DB_LAST ) {
			wfProfileOut( __METHOD__ );
			throw new MWException( 'Attempt to call ' . __METHOD__ .
				' with deprecated server index DB_LAST' );
		} elseif ( $i === null || $i === false ) {
			wfProfileOut( __METHOD__ );
			throw new MWException( 'Attempt to call ' . __METHOD__ .
				' with invalid server index' );
		}

		if ( $wiki === wfWikiID() ) {
			$wiki = false;
		}

		# Query groups
		if ( $i == DB_MASTER ) {
			$i = $this->getWriterIndex();
		} elseif ( !is_array( $groups ) ) {
			$groupIndex = $this->getReaderIndex( $groups, $wiki );
			if ( $groupIndex !== false ) {
				$serverName = $this->getServerName( $groupIndex );
				wfDebug( __METHOD__ . ": using server $serverName for group $groups\n" );
				$i = $groupIndex;
			}
		} else {
			foreach ( $groups as $group ) {
				$groupIndex = $this->getReaderIndex( $group, $wiki );
				if ( $groupIndex !== false ) {
					$serverName = $this->getServerName( $groupIndex );
					wfDebug( __METHOD__ . ": using server $serverName for group $group\n" );
					$i = $groupIndex;
					break;
				}
			}
		}

		# Operation-based index
		if ( $i == DB_SLAVE ) {
			$this->mLastError = 'Unknown error'; // reset error string
			$i = $this->getReaderIndex( false, $wiki );
			# Couldn't find a working server in getReaderIndex()?
			if ( $i === false ) {
				$this->mLastError = 'No working slave server: ' . $this->mLastError;
				wfProfileOut( __METHOD__ );

				return $this->reportConnectionError();
			}
		}

		# Now we have an explicit index into the servers array
		$conn = $this->openConnection( $i, $wiki );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );

			return $this->reportConnectionError();
		}

		wfProfileOut( __METHOD__ );

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
	 * @param integer $db
	 * @param mixed $groups
	 * @param bool|string $wiki
	 * @return DBConnRef
	 */
	public function getConnectionRef( $db, $groups = array(), $wiki = false ) {
		return new DBConnRef( $this, $this->getConnection( $db, $groups, $wiki ) );
	}

	/**
	 * Get a database connection handle reference without connecting yet
	 *
	 * The handle's methods wrap simply wrap those of a DatabaseBase handle
	 *
	 * @see LoadBalancer::getConnection() for parameter information
	 *
	 * @param integer $db
	 * @param mixed $groups
	 * @param bool|string $wiki
	 * @return DBConnRef
	 */
	public function getLazyConnectionRef( $db, $groups = array(), $wiki = false ) {
		return new DBConnRef( $this, array( $db, $groups, $wiki ) );
	}

	/**
	 * Open a connection to the server given by the specified index
	 * Index must be an actual index into the array.
	 * If the server is already open, returns it.
	 *
	 * On error, returns false, and the connection which caused the
	 * error will be available via $this->mErrorConnection.
	 *
	 * @param $i Integer server index
	 * @param bool|string $wiki wiki ID to open
	 * @return DatabaseBase
	 *
	 * @access private
	 */
	function openConnection( $i, $wiki = false ) {
		wfProfileIn( __METHOD__ );
		if ( $wiki !== false ) {
			$conn = $this->openForeignConnection( $i, $wiki );
			wfProfileOut( __METHOD__ );

			return $conn;
		}
		if ( isset( $this->mConns['local'][$i][0] ) ) {
			$conn = $this->mConns['local'][$i][0];
		} else {
			$server = $this->mServers[$i];
			$server['serverIndex'] = $i;
			$conn = $this->reallyOpenConnection( $server, false );
			if ( $conn->isOpen() ) {
				wfDebug( "Connected to database $i at {$this->mServers[$i]['host']}\n" );
				$this->mConns['local'][$i][0] = $conn;
			} else {
				wfDebug( "Failed to connect to database $i at {$this->mServers[$i]['host']}\n" );
				$this->mErrorConnection = $conn;
				$conn = false;
			}
		}
		wfProfileOut( __METHOD__ );

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
	 * @param $i Integer: server index
	 * @param string $wiki wiki ID to open
	 * @return DatabaseBase
	 */
	function openForeignConnection( $i, $wiki ) {
		wfProfileIn( __METHOD__ );
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

			if ( !$conn->selectDB( $dbName ) ) {
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
		wfProfileOut( __METHOD__ );

		return $conn;
	}

	/**
	 * Test if the specified index represents an open connection
	 *
	 * @param $index Integer: server index
	 * @access private
	 * @return bool
	 */
	function isOpen( $index ) {
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
	 * @param $server
	 * @param $dbNameOverride bool
	 * @throws MWException
	 * @return DatabaseBase
	 */
	function reallyOpenConnection( $server, $dbNameOverride = false ) {
		if ( !is_array( $server ) ) {
			throw new MWException( 'You must update your load-balancing configuration. ' .
				'See DefaultSettings.php entry for $wgDBservers.' );
		}

		if ( $dbNameOverride !== false ) {
			$server['dbname'] = $dbNameOverride;
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
		if ( isset( $server['fakeSlaveLag'] ) ) {
			$db->setFakeSlaveLag( $server['fakeSlaveLag'] );
		}
		if ( isset( $server['fakeMaster'] ) ) {
			$db->setFakeMaster( true );
		}

		return $db;
	}

	/**
	 * @throws DBConnectionError
	 * @return bool
	 */
	private function reportConnectionError() {
		$conn = $this->mErrorConnection; // The connection which caused the error

		if ( !is_object( $conn ) ) {
			// No last connection, probably due to all servers being too busy
			wfLogDBError( "LB failure with no last connection. Connection error: {$this->mLastError}" );

			// If all servers were busy, mLastError will contain something sensible
			throw new DBConnectionError( null, $this->mLastError );
		} else {
			$server = $conn->getProperty( 'mServer' );
			wfLogDBError( "Connection error: {$this->mLastError} ({$server})" );
			$conn->reportConnectionError( "{$this->mLastError} ({$server})" ); // throws DBConnectionError
		}

		return false; /* not reached */
	}

	/**
	 * @return int
	 */
	function getWriterIndex() {
		return 0;
	}

	/**
	 * Returns true if the specified index is a valid server index
	 *
	 * @param string $i
	 * @return bool
	 */
	function haveIndex( $i ) {
		return array_key_exists( $i, $this->mServers );
	}

	/**
	 * Returns true if the specified index is valid and has non-zero load
	 *
	 * @param string $i
	 * @return bool
	 */
	function isNonZeroLoad( $i ) {
		return array_key_exists( $i, $this->mServers ) && $this->mLoads[$i] != 0;
	}

	/**
	 * Get the number of defined servers (not the number of open connections)
	 *
	 * @return int
	 */
	function getServerCount() {
		return count( $this->mServers );
	}

	/**
	 * Get the host name or IP address of the server with the specified index
	 * Prefer a readable name if available.
	 * @param string $i
	 * @return string
	 */
	function getServerName( $i ) {
		if ( isset( $this->mServers[$i]['hostName'] ) ) {
			return $this->mServers[$i]['hostName'];
		} elseif ( isset( $this->mServers[$i]['host'] ) ) {
			return $this->mServers[$i]['host'];
		} else {
			return '';
		}
	}

	/**
	 * Return the server info structure for a given index, or false if the index is invalid.
	 * @param $i
	 * @return bool
	 */
	function getServerInfo( $i ) {
		if ( isset( $this->mServers[$i] ) ) {
			return $this->mServers[$i];
		} else {
			return false;
		}
	}

	/**
	 * Sets the server info structure for the given index. Entry at index $i
	 * is created if it doesn't exist
	 * @param $i
	 * @param $serverInfo
	 */
	function setServerInfo( $i, $serverInfo ) {
		$this->mServers[$i] = $serverInfo;
	}

	/**
	 * Get the current master position for chronology control purposes
	 * @return mixed
	 */
	function getMasterPos() {
		# If this entire request was served from a slave without opening a connection to the
		# master (however unlikely that may be), then we can fetch the position from the slave.
		$masterConn = $this->getAnyOpenConnection( 0 );
		if ( !$masterConn ) {
			$serverCount = count( $this->mServers );
			for ( $i = 1; $i < $serverCount; $i++ ) {
				$conn = $this->getAnyOpenConnection( $i );
				if ( $conn ) {
					wfDebug( "Master pos fetched from slave\n" );

					return $conn->getSlavePos();
				}
			}
		} else {
			wfDebug( "Master pos fetched from master\n" );

			return $masterConn->getMasterPos();
		}

		return false;
	}

	/**
	 * Close all open connections
	 */
	function closeAll() {
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				/** @var DatabaseBase $conn */
				foreach ( $conns3 as $conn ) {
					$conn->close();
				}
			}
		}
		$this->mConns = array(
			'local' => array(),
			'foreignFree' => array(),
			'foreignUsed' => array(),
		);
	}

	/**
	 * Deprecated function, typo in function name
	 *
	 * @deprecated in 1.18
	 * @param DatabaseBase $conn
	 */
	function closeConnecton( $conn ) {
		wfDeprecated( __METHOD__, '1.18' );
		$this->closeConnection( $conn );
	}

	/**
	 * Close a connection
	 * Using this function makes sure the LoadBalancer knows the connection is closed.
	 * If you use $conn->close() directly, the load balancer won't update its state.
	 * @param DatabaseBase $conn
	 */
	function closeConnection( $conn ) {
		$done = false;
		foreach ( $this->mConns as $i1 => $conns2 ) {
			foreach ( $conns2 as $i2 => $conns3 ) {
				foreach ( $conns3 as $i3 => $candidateConn ) {
					if ( $conn === $candidateConn ) {
						$conn->close();
						unset( $this->mConns[$i1][$i2][$i3] );
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
	 */
	function commitAll() {
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				/** @var DatabaseBase[] $conns3 */
				foreach ( $conns3 as $conn ) {
					if ( $conn->trxLevel() ) {
						$conn->commit( __METHOD__, 'flush' );
					}
				}
			}
		}
	}

	/**
	 *  Issue COMMIT only on master, only if queries were done on connection
	 */
	function commitMasterChanges() {
		// Always 0, but who knows.. :)
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				if ( $conn->trxLevel() && $conn->writesOrCallbacksPending() ) {
					$conn->commit( __METHOD__, 'flush' );
				}
			}
		}
	}

	/**
	 * Issue ROLLBACK only on master, only if queries were done on connection
	 * @since 1.23
	 */
	function rollbackMasterChanges() {
		// Always 0, but who knows.. :)
		$masterIndex = $this->getWriterIndex();
		foreach ( $this->mConns as $conns2 ) {
			if ( empty( $conns2[$masterIndex] ) ) {
				continue;
			}
			/** @var DatabaseBase $conn */
			foreach ( $conns2[$masterIndex] as $conn ) {
				if ( $conn->trxLevel() && $conn->writesOrCallbacksPending() ) {
					$conn->rollback( __METHOD__, 'flush' );
				}
			}
		}
	}

	/**
	 * Determine if there are any pending changes that need to be rolled back
	 * or committed.
	 * @since 1.23
	 * @return bool
	 */
	function hasMasterChanges() {
		// Always 0, but who knows.. :)
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
	 * @param $value null
	 * @return Mixed
	 */
	function waitTimeout( $value = null ) {
		return wfSetVar( $this->mWaitTimeout, $value );
	}

	/**
	 * @return bool
	 */
	function getLaggedSlaveMode() {
		return $this->mLaggedSlaveMode;
	}

	/**
	 * Disables/enables lag checks
	 * @param null|bool $mode
	 * @return bool
	 */
	function allowLagged( $mode = null ) {
		if ( $mode === null ) {
			return $this->mAllowLagged;
		}
		$this->mAllowLagged = $mode;

		return $this->mAllowLagged;
	}

	/**
	 * @return bool
	 */
	function pingAll() {
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
	function forEachOpenConnection( $callback, $params = array() ) {
		foreach ( $this->mConns as $conns2 ) {
			foreach ( $conns2 as $conns3 ) {
				foreach ( $conns3 as $conn ) {
					$mergedParams = array_merge( array( $conn ), $params );
					call_user_func_array( $callback, $mergedParams );
				}
			}
		}
	}

	/**
	 * Get the hostname and lag time of the most-lagged slave.
	 * This is useful for maintenance scripts that need to throttle their updates.
	 * May attempt to open connections to slaves on the default DB. If there is
	 * no lag, the maximum lag will be reported as -1.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the default database
	 * @return array ( host, max lag, index of max lagged host )
	 */
	function getMaxLag( $wiki = false ) {
		$maxLag = -1;
		$host = '';
		$maxIndex = 0;
		if ( $this->getServerCount() > 1 ) { // no replication = no lag
			foreach ( $this->mServers as $i => $conn ) {
				$conn = false;
				if ( $wiki === false ) {
					$conn = $this->getAnyOpenConnection( $i );
				}
				if ( !$conn ) {
					$conn = $this->openConnection( $i, $wiki );
				}
				if ( !$conn ) {
					continue;
				}
				$lag = $conn->getLag();
				if ( $lag > $maxLag ) {
					$maxLag = $lag;
					$host = $this->mServers[$i]['host'];
					$maxIndex = $i;
				}
			}
		}

		return array( $host, $maxLag, $maxIndex );
	}

	/**
	 * Get lag time for each server
	 * Results are cached for a short time in memcached, and indefinitely in the process cache
	 *
	 * @param string|bool $wiki
	 * @return array
	 */
	function getLagTimes( $wiki = false ) {
		# Try process cache
		if ( isset( $this->mLagTimes ) ) {
			return $this->mLagTimes;
		}
		if ( $this->getServerCount() == 1 ) {
			# No replication
			$this->mLagTimes = array( 0 => 0 );
		} else {
			# Send the request to the load monitor
			$this->mLagTimes = $this->getLoadMonitor()->getLagTimes(
				array_keys( $this->mServers ), $wiki );
		}

		return $this->mLagTimes;
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
	 * @param DatabaseBase $conn
	 * @return int
	 */
	function safeGetLag( $conn ) {
		if ( $this->getServerCount() == 1 ) {
			return 0;
		} else {
			return $conn->getLag();
		}
	}

	/**
	 * Clear the cache for getLagTimes
	 */
	function clearLagTimeCache() {
		$this->mLagTimes = null;
	}
}

/**
 * Helper class to handle automatically marking connectons as reusable (via RAII pattern)
 * as well handling deferring the actual network connection until the handle is used
 *
 * @ingroup Database
 * @since 1.22
 */
class DBConnRef implements IDatabase {
	/** @var LoadBalancer */
	protected $lb;

	/** @var DatabaseBase|null */
	protected $conn;

	/** @var array|null */
	protected $params;

	/**
	 * @param LoadBalancer $lb
	 * @param DatabaseBase|array $conn Connection or (server index, group, wiki ID) array
	 */
	public function __construct( LoadBalancer $lb, $conn ) {
		$this->lb = $lb;
		if ( $conn instanceof DatabaseBase ) {
			$this->conn = $conn;
		} else {
			$this->params = $conn;
		}
	}

	public function __call( $name, $arguments ) {
		if ( $this->conn === null ) {
			list( $db, $groups, $wiki ) = $this->params;
			$this->conn = $this->lb->getConnection( $db, $groups, $wiki );
		}

		return call_user_func_array( array( $this->conn, $name ), $arguments );
	}

	function __destruct() {
		if ( $this->conn !== null ) {
			$this->lb->reuseConnection( $this->conn );
		}
	}
}
