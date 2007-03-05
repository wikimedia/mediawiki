<?php
/**
 *
 */


/**
 * Database load balancing object
 *
 * @todo document
 */
class LoadBalancer {
	/* private */ var $mServers, $mConnections, $mLoads, $mGroupLoads;
	/* private */ var $mFailFunction, $mErrorConnection;
	/* private */ var $mForce, $mReadIndex, $mLastIndex, $mAllowLagged;
	/* private */ var $mWaitForFile, $mWaitForPos, $mWaitTimeout;
	/* private */ var $mLaggedSlaveMode, $mLastError = 'Unknown error';

	/**
	 * Scale polling time so that under overload conditions, the database server
	 * receives a SHOW STATUS query at an average interval of this many microseconds
	 */
	const AVG_STATUS_POLL = 2000;

	function __construct( $servers, $failFunction = false, $waitTimeout = 10, $waitForMasterNow = false )
	{
		$this->mServers = $servers;
		$this->mFailFunction = $failFunction;
		$this->mReadIndex = -1;
		$this->mWriteIndex = -1;
		$this->mForce = -1;
		$this->mConnections = array();
		$this->mLastIndex = -1;
		$this->mLoads = array();
		$this->mWaitForFile = false;
		$this->mWaitForPos = false;
		$this->mWaitTimeout = $waitTimeout;
		$this->mLaggedSlaveMode = false;
		$this->mErrorConnection = false;
		$this->mAllowLag = false;

		foreach( $servers as $i => $server ) {
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
		if ( $waitForMasterNow ) {
			$this->loadMasterPos();
		}
	}

	static function newFromParams( $servers, $failFunction = false, $waitTimeout = 10 )
	{
		return new LoadBalancer( $servers, $failFunction, $waitTimeout );
	}

	/**
	 * Given an array of non-normalised probabilities, this function will select
	 * an element and return the appropriate key
	 */
	function pickRandom( $weights )
	{
		if ( !is_array( $weights ) || count( $weights ) == 0 ) {
			return false;
		}

		$sum = array_sum( $weights );
		if ( $sum == 0 ) {
			# No loads on any of them
			# In previous versions, this triggered an unweighted random selection,
			# but this feature has been removed as of April 2006 to allow for strict 
			# separation of query groups. 
			return false;
		}
		$max = mt_getrandmax();
		$rand = mt_rand(0, $max) / $max * $sum;

		$sum = 0;
		foreach ( $weights as $i => $w ) {
			$sum += $w;
			if ( $sum >= $rand ) {
				break;
			}
		}
		return $i;
	}

	function getRandomNonLagged( $loads ) {
		# Unset excessively lagged servers
		$lags = $this->getLagTimes();
		foreach ( $lags as $i => $lag ) {
			if ( isset( $this->mServers[$i]['max lag'] ) && $lag > $this->mServers[$i]['max lag'] ) {
				unset( $loads[$i] );
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
		return $this->pickRandom( $loads );
	}

	/**
	 * Get the index of the reader connection, which may be a slave
	 * This takes into account load ratios and lag times. It should
	 * always return a consistent index during a given invocation
	 *
	 * Side effect: opens connections to databases
	 */
	function getReaderIndex() {
		global $wgReadOnly, $wgDBClusterTimeout;

		$fname = 'LoadBalancer::getReaderIndex';
		wfProfileIn( $fname );

		$i = false;
		if ( $this->mForce >= 0 ) {
			$i = $this->mForce;
		} elseif ( count( $this->mServers ) == 1 )  {
			# Skip the load balancing if there's only one server
			$i = 0;
		} else {
			if ( $this->mReadIndex >= 0 ) {
				$i = $this->mReadIndex;
			} else {
				# $loads is $this->mLoads except with elements knocked out if they
				# don't work
				$loads = $this->mLoads;
				$done = false;
				$totalElapsed = 0;
				do {
					if ( $wgReadOnly or $this->mAllowLagged ) {
						$i = $this->pickRandom( $loads );
					} else {
						$i = $this->getRandomNonLagged( $loads );
						if ( $i === false && count( $loads ) != 0 )  {
							# All slaves lagged. Switch to read-only mode
							$wgReadOnly = wfMsgNoDBForContent( 'readonly_lag' );
							$i = $this->pickRandom( $loads );
						}
					}
					$serverIndex = $i;
					if ( $i !== false ) {
						wfDebugLog( 'connect', "$fname: Using reader #$i: {$this->mServers[$i]['host']}...\n" );
						$this->openConnection( $i );

						if ( !$this->isOpen( $i ) ) {
							wfDebug( "$fname: Failed\n" );
							unset( $loads[$i] );
							$sleepTime = 0;
						} else {
							if ( isset( $this->mServers[$i]['max threads'] ) ) {
							    $status = $this->mConnections[$i]->getStatus("Thread%");
							    if ( $status['Threads_running'] > $this->mServers[$i]['max threads'] ) {
								# Too much load, back off and wait for a while.
								# The sleep time is scaled by the number of threads connected,
								# to produce a roughly constant global poll rate.
								$sleepTime = self::AVG_STATUS_POLL * $status['Threads_connected'];

								# If we reach the timeout and exit the loop, don't use it
								$i = false;
							    } else {
								$done = true;
								$sleepTime = 0;
							    }
							} else {
							    $done = true;
							    $sleepTime = 0;
							}
						}
					} else {
						$sleepTime = 500000;
					}
					if ( $sleepTime ) {
							$totalElapsed += $sleepTime;
							$x = "{$this->mServers[$serverIndex]['host']} [$serverIndex]";
							wfProfileIn( "$fname-sleep $x" );
							usleep( $sleepTime );
							wfProfileOut( "$fname-sleep $x" );
					}
				} while ( count( $loads ) && !$done && $totalElapsed / 1e6 < $wgDBClusterTimeout );

				if ( $totalElapsed / 1e6 >= $wgDBClusterTimeout ) {
					$this->mErrorConnection = false;
					$this->mLastError = 'All servers busy';
				}

				if ( $i !== false && $this->isOpen( $i ) ) {
					# Wait for the session master pos for a short time
					if ( $this->mWaitForFile ) {
						if ( !$this->doWait( $i ) ) {
							$this->mServers[$i]['slave pos'] = $this->mConnections[$i]->getSlavePos();
						}
					}
					if ( $i !== false ) {
						$this->mReadIndex = $i;
					}
				} else {
					$i = false;
				}
			}
		}
		wfProfileOut( $fname );
		return $i;
	}

	/**
	 * Get a random server to use in a query group
	 */
	function getGroupIndex( $group ) {
		if ( isset( $this->mGroupLoads[$group] ) ) {
			$i = $this->pickRandom( $this->mGroupLoads[$group] );
		} else {
			$i = false;
		}
		wfDebug( "Query group $group => $i\n" );
		return $i;
	}

	/**
	 * Get a connection to a foreign DB. Will use the following types of 
	 * connection in order of precedence:
	 *    * The alternate master, if there is one and DB_MASTER is given
	 *    * The query group
	 *    * The alternate master, if there is one
	 *    * The default writer, if DB_MASTER was specified
	 *    * The default reader. 
	 * 
	 * Connection error will *not* lead to progression down this list. Selection
	 * of a group depends only on configuration.
	 *
	 * @param string $dbName The database name
	 * @param mixed $group The query group, or DB_MASTER for the foreign master
	 * @return Database object with the database selected
	 */
	function getForeignConnection( $dbName, $group = false ) {
		global $wgAlternateMaster;

		// Try cache
		if ( isset( $this->mForeignConnections[$dbName][$group] ) ) {
			return $this->mForeignConnections[$dbName][$group];
		}

		// Try the precedence list described in the function description
		if ( $group === DB_MASTER && isset( $wgAlternateMaster[$dbName] ) ) {
			$index = $this->mServers[$wgAlternateMaster[$dbName]];
		} if ( $group && $group !== DB_MASTER ) {
			$index = $this->getGroupIndex( $group );
		} elseif ( isset( $wgAlternateMaster[$dbName] ) ) {
			$index = $this->mServers[$wgAlternateMaster[$dbName]];
		} elseif ( $group === DB_MASTER ) {
			$index = $this->getWriterIndex();
		} else {
			$index = $this->getReaderIndex();
		}

		if ( $index === false || !isset( $this->mServers[$index] ) ) {
			throw new MWException( "No configured server available for foreign connection to $dbName" );
		}

		$dbInfo = $this->mServers[$index];
		$dbc = $this->reallyOpenConnection( $dbInfo, $dbName );
		$this->mForeignConnections[$dbName][$group] = $dbc;
		return $dbc;
	}

	/**
	 * Set the master wait position
	 * If a DB_SLAVE connection has been opened already, waits
	 * Otherwise sets a variable telling it to wait if such a connection is opened
	 */
	function waitFor( $file, $pos ) {
		$fname = 'LoadBalancer::waitFor';
		wfProfileIn( $fname );

		wfDebug( "User master pos: $file $pos\n" );
		$this->mWaitForFile = false;
		$this->mWaitForPos = false;

		if ( count( $this->mServers ) > 1 ) {
			$this->mWaitForFile = $file;
			$this->mWaitForPos = $pos;
			$i = $this->mReadIndex;

			if ( $i > 0 ) {
				if ( !$this->doWait( $i ) ) {
					$this->mServers[$i]['slave pos'] = $this->mConnections[$i]->getSlavePos();
					$this->mLaggedSlaveMode = true;
				}
			}
		}
		wfProfileOut( $fname );
	}

	/**
	 * Wait for a given slave to catch up to the master pos stored in $this
	 */
	function doWait( $index ) {
		global $wgMemc;

		$retVal = false;

		# Debugging hacks
		if ( isset( $this->mServers[$index]['lagged slave'] ) ) {
			return false;
		} elseif ( isset( $this->mServers[$index]['fake slave'] ) ) {
			return true;
		}

		$key = 'masterpos:' . $index;
		$memcPos = $wgMemc->get( $key );
		if ( $memcPos ) {
			list( $file, $pos ) = explode( ' ', $memcPos );
			# If the saved position is later than the requested position, return now
			if ( $file == $this->mWaitForFile && $this->mWaitForPos <= $pos ) {
				$retVal = true;
			}
		}

		if ( !$retVal && $this->isOpen( $index ) ) {
			$conn =& $this->mConnections[$index];
			wfDebug( "Waiting for slave #$index to catch up...\n" );
			$result = $conn->masterPosWait( $this->mWaitForFile, $this->mWaitForPos, $this->mWaitTimeout );

			if ( $result == -1 || is_null( $result ) ) {
				# Timed out waiting for slave, use master instead
				wfDebug( "Timed out waiting for slave #$index pos {$this->mWaitForFile} {$this->mWaitForPos}\n" );
				$retVal = false;
			} else {
				$retVal = true;
				wfDebug( "Done\n" );
			}
		}
		return $retVal;
	}

	/**
	 * Get a connection by index
	 */
	function &getConnection( $i, $fail = true, $groups = array() )
	{
		global $wgDBtype;
		$fname = 'LoadBalancer::getConnection';
		wfProfileIn( $fname );


		# Query groups
		if ( !is_array( $groups ) ) {
			$groupIndex = $this->getGroupIndex( $groups, $i );
			if ( $groupIndex !== false ) {
				$i = $groupIndex;
			}
		} else {
			foreach ( $groups as $group ) {
				$groupIndex = $this->getGroupIndex( $group, $i );
				if ( $groupIndex !== false ) {
					$i = $groupIndex;
					break;
				}
			}
		}

		# For now, only go through all this for mysql databases
		if ($wgDBtype != 'mysql') {
			$i = $this->getWriterIndex();
		}
		# Operation-based index
		elseif ( $i == DB_SLAVE ) {
			$i = $this->getReaderIndex();
		} elseif ( $i == DB_MASTER ) {
			$i = $this->getWriterIndex();
		} elseif ( $i == DB_LAST ) {
			# Just use $this->mLastIndex, which should already be set
			$i = $this->mLastIndex;
			if ( $i === -1 ) {
				# Oh dear, not set, best to use the writer for safety
				wfDebug( "Warning: DB_LAST used when there was no previous index\n" );
				$i = $this->getWriterIndex();
			}
		}
		# Couldn't find a working server in getReaderIndex()?
		if ( $i === false ) {
			$this->reportConnectionError( $this->mErrorConnection );
		}
		# Now we have an explicit index into the servers array
		$this->openConnection( $i, $fail );

		wfProfileOut( $fname );
		return $this->mConnections[$i];
	}

	/**
	 * Open a connection to the server given by the specified index
	 * Index must be an actual index into the array
	 * Returns success
	 * @access private
	 */
	function openConnection( $i, $fail = false ) {
		$fname = 'LoadBalancer::openConnection';
		wfProfileIn( $fname );
		$success = true;

		if ( !$this->isOpen( $i ) ) {
			$this->mConnections[$i] = $this->reallyOpenConnection( $this->mServers[$i] );
		}

		if ( !$this->isOpen( $i ) ) {
			wfDebug( "Failed to connect to database $i at {$this->mServers[$i]['host']}\n" );
			if ( $fail ) {
				$this->reportConnectionError( $this->mConnections[$i] );
			}
			$this->mErrorConnection = $this->mConnections[$i];
			$this->mConnections[$i] = false;
			$success = false;
		}
		$this->mLastIndex = $i;
		wfProfileOut( $fname );
		return $success;
	}

	/**
	 * Test if the specified index represents an open connection
	 * @access private
	 */
	function isOpen( $index ) {
		if( !is_integer( $index ) ) {
			return false;
		}
		if ( array_key_exists( $index, $this->mConnections ) && is_object( $this->mConnections[$index] ) &&
		  $this->mConnections[$index]->isOpen() )
		{
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Really opens a connection
	 * @access private
	 */
	function reallyOpenConnection( &$server, $overrideDBname = false ) {
		if( !is_array( $server ) ) {
			throw new MWException( 'You must update your load-balancing configuration. See DefaultSettings.php entry for $wgDBservers.' );
		}

		extract( $server );
		if ( $overrideDBname ) {
			$dbname = $overrideDBname;
		}
		# Get class for this database type
		$class = 'Database' . ucfirst( $type );

		# Create object
		$db = new $class( $host, $user, $password, $dbname, 1, $flags );
		$db->setLBInfo( $server );
		return $db;
	}

	function reportConnectionError( &$conn )
	{
		$fname = 'LoadBalancer::reportConnectionError';
		wfProfileIn( $fname );
		# Prevent infinite recursion

		static $reporting = false;
		if ( !$reporting ) {
			$reporting = true;
			if ( !is_object( $conn ) ) {
				// No last connection, probably due to all servers being too busy
				$conn = new Database;
				if ( $this->mFailFunction ) {
					$conn->failFunction( $this->mFailFunction );
					$conn->reportConnectionError( $this->mLastError );
				} else {
					// If all servers were busy, mLastError will contain something sensible
					throw new DBConnectionError( $conn, $this->mLastError );
				}
			} else {
				if ( $this->mFailFunction ) {
					$conn->failFunction( $this->mFailFunction );
				} else {
					$conn->failFunction( false );
				}
				$server = $conn->getProperty( 'mServer' );
				$conn->reportConnectionError( "{$this->mLastError} ({$server})" );
			}
			$reporting = false;
		}
		wfProfileOut( $fname );
	}

	function getWriterIndex() {
		return 0;
	}

	/**
	 * Force subsequent calls to getConnection(DB_SLAVE) to return the 
	 * given index. Set to -1 to restore the original load balancing
	 * behaviour. I thought this was a good idea when I originally 
	 * wrote this class, but it has never been used.
	 */
	function force( $i ) {
		$this->mForce = $i;
	}

	/**
	 * Returns true if the specified index is a valid server index
	 */
	function haveIndex( $i ) {
		return array_key_exists( $i, $this->mServers );
	}

	/**
	 * Returns true if the specified index is valid and has non-zero load
	 */
	function isNonZeroLoad( $i ) {
		return array_key_exists( $i, $this->mServers ) && $this->mLoads[$i] != 0;
	}

	/**
	 * Get the number of defined servers (not the number of open connections)
	 */
	function getServerCount() {
		return count( $this->mServers );
	}

	/**
	 * Save master pos to the session and to memcached, if the session exists
	 */
	function saveMasterPos() {
		if ( session_id() != '' && count( $this->mServers ) > 1 ) {
			# If this entire request was served from a slave without opening a connection to the
			# master (however unlikely that may be), then we can fetch the position from the slave.
			if ( empty( $this->mConnections[0] ) ) {
				$conn =& $this->getConnection( DB_SLAVE );
				list( $file, $pos ) = $conn->getSlavePos();
				wfDebug( "Saving master pos fetched from slave: $file $pos\n" );
			} else {
				$conn =& $this->getConnection( 0 );
				list( $file, $pos ) = $conn->getMasterPos();
				wfDebug( "Saving master pos: $file $pos\n" );
			}
			if ( $file !== false ) {
				$_SESSION['master_log_file'] = $file;
				$_SESSION['master_pos'] = $pos;
			}
		}
	}

	/**
	 * Loads the master pos from the session, waits for it if necessary
	 */
	function loadMasterPos() {
		if ( isset( $_SESSION['master_log_file'] ) && isset( $_SESSION['master_pos'] ) ) {
			$this->waitFor( $_SESSION['master_log_file'], $_SESSION['master_pos'] );
		}
	}

	/**
	 * Close all open connections
	 */
	function closeAll() {
		foreach( $this->mConnections as $i => $conn ) {
			if ( $this->isOpen( $i ) ) {
				// Need to use this syntax because $conn is a copy not a reference
				$this->mConnections[$i]->close();
			}
		}
	}

	function commitAll() {
		foreach( $this->mConnections as $i => $conn ) {
			if ( $this->isOpen( $i ) ) {
				// Need to use this syntax because $conn is a copy not a reference
				$this->mConnections[$i]->immediateCommit();
			}
		}
	}

	function waitTimeout( $value = NULL ) {
		return wfSetVar( $this->mWaitTimeout, $value );
	}

	function getLaggedSlaveMode() {
		return $this->mLaggedSlaveMode;
	}

	/* Disables/enables lag checks */
	function allowLagged($mode=null) {
		if ($mode===null)
			return $this->mAllowLagged;
		$this->mAllowLagged=$mode;
	}

	function pingAll() {
		$success = true;
		foreach ( $this->mConnections as $i => $conn ) {
			if ( $this->isOpen( $i ) ) {
				if ( !$this->mConnections[$i]->ping() ) {
					$success = false;
				}
			}
		}
		return $success;
	}

	/**
	 * Get the hostname and lag time of the most-lagged slave
	 * This is useful for maintenance scripts that need to throttle their updates
	 */
	function getMaxLag() {
		$maxLag = -1;
		$host = '';
		foreach ( $this->mServers as $i => $conn ) {
			if ( $this->openConnection( $i ) ) {
				$lag = $this->mConnections[$i]->getLag();
				if ( $lag > $maxLag ) {
					$maxLag = $lag;
					$host = $this->mServers[$i]['host'];
				}
			}
		}
		return array( $host, $maxLag );
	}

	/**
	 * Get lag time for each DB
	 * Results are cached for a short time in memcached
	 */
	function getLagTimes() {
		wfProfileIn( __METHOD__ );
		$expiry = 5;
		$requestRate = 10;

		global $wgMemc;
		$times = $wgMemc->get( wfMemcKey( 'lag_times' ) );
		if ( $times ) {
			# Randomly recache with probability rising over $expiry
			$elapsed = time() - $times['timestamp'];
			$chance = max( 0, ( $expiry - $elapsed ) * $requestRate );
			if ( mt_rand( 0, $chance ) != 0 ) {
				unset( $times['timestamp'] );
				wfProfileOut( __METHOD__ );
				return $times;
			}
			wfIncrStats( 'lag_cache_miss_expired' );
		} else {
			wfIncrStats( 'lag_cache_miss_absent' );
		}

		# Cache key missing or expired

		$times = array();
		foreach ( $this->mServers as $i => $conn ) {
			if ($i==0) { # Master
				$times[$i] = 0;
			} elseif ( $this->openConnection( $i ) ) {
				$times[$i] = $this->mConnections[$i]->getLag();
			}
		}

		# Add a timestamp key so we know when it was cached
		$times['timestamp'] = time();
		$wgMemc->set( wfMemcKey( 'lag_times' ), $times, $expiry );

		# But don't give the timestamp to the caller
		unset($times['timestamp']);
		wfProfileOut( __METHOD__ );
		return $times;
	}
}

?>
