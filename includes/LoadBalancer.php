<?php
/**
 *
 * @package MediaWiki
 */

/**
 * Depends on the database object
 */
require_once( 'Database.php' );

# Valid database indexes
# Operation-based indexes
define( 'DB_SLAVE', -1 );     # Read from the slave (or only server)
define( 'DB_MASTER', -2 );    # Write to master (or only server)
define( 'DB_LAST', -3 );     # Whatever database was used last

# Obsolete aliases
define( 'DB_READ', -1 );
define( 'DB_WRITE', -2 );

# Task-based indexes
# ***NOT USED YET, EXPERIMENTAL***
# These may be defined in $wgDBservers. If they aren't, the default reader or writer will be used
# Even numbers are always readers, odd numbers are writers
define( 'DB_TASK_FIRST', 1000 );  # First in list
define( 'DB_SEARCH_R', 1000 );    # Search read
define( 'DB_SEARCH_W', 1001 );    # Search write
define( 'DB_ASKSQL_R', 1002 );    # Special:Asksql read
define( 'DB_WATCHLIST_R', 1004 ); # Watchlist read
define( 'DB_TASK_LAST', 1004) ;   # Last in list

define( 'MASTER_WAIT_TIMEOUT', 15 ); # Time to wait for a slave to synchronise

/**
 * Database load balancing object
 *
 * @todo document
 * @package MediaWiki
 */
class LoadBalancer {
	/* private */ var $mServers, $mConnections, $mLoads;
	/* private */ var $mFailFunction;
	/* private */ var $mForce, $mReadIndex, $mLastIndex;
	/* private */ var $mWaitForFile, $mWaitForPos;

	function LoadBalancer()
	{
		$this->mServers = array();
		$this->mConnections = array();
		$this->mFailFunction = false;
		$this->mReadIndex = -1;
		$this->mForce = -1;
		$this->mLastIndex = -1;
	}

	function newFromParams( $servers, $failFunction = false )
	{
		$lb = new LoadBalancer;
		$lb->initialise( $servers, $failFunction = false );
		return $lb;
	}

	function initialise( $servers, $failFunction = false )
	{
		$this->mServers = $servers;
		$this->mFailFunction = $failFunction;
		$this->mReadIndex = -1;
		$this->mWriteIndex = -1;
		$this->mForce = -1;
		$this->mConnections = array();
		$this->mLastIndex = 1;
		$this->mLoads = array();
		$this->mWaitForFile = false;
		$this->mWaitForPos = false;

		foreach( $servers as $i => $server ) {
			$this->mLoads[$i] = $server['load'];
		}
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

		$sum = 0;
		foreach ( $weights as $w ) {
			$sum += $w;
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

	function getReaderIndex()
	{
		$fname = 'LoadBalancer::getReaderIndex';
		wfProfileIn( $fname );

		$i = false;
		if ( $this->mForce >= 0 ) {
			$i = $this->mForce;
		} else {
			if ( $this->mReadIndex >= 0 ) {
				$i = $this->mReadIndex;
			} else {
				# $loads is $this->mLoads except with elements knocked out if they
				# don't work
				$loads = $this->mLoads;
				do {
					$i = $this->pickRandom( $loads );
					if ( $i !== false ) {
						wfDebug( "Using reader #$i: {$this->mServers[$i]['host']}\n" );

						$this->openConnection( $i );

						if ( !$this->isOpen( $i ) ) {
							unset( $loads[$i] );
						}
					}
				} while ( $i !== false && !$this->isOpen( $i ) );

				if ( $this->isOpen( $i ) ) {
					$this->mReadIndex = $i;
				} else {
					$i = false;
				}
			}
		}
		wfProfileOut( $fname );
		return $i;
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

			if ( $this->mReadIndex > 0 ) {
				if ( !$this->doWait( $this->mReadIndex ) ) {
					# Use master instead
					$this->mReadIndex = 0;
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
			$conn =& $this->mConnections( $index );
			wfDebug( "Waiting for slave #$index to catch up...\n" );
			$result = $conn->masterPosWait( $this->mWaitForFile, $this->mWaitForPos, MASTER_WAIT_TIMEOUT );

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
	function &getConnection( $i, $fail = true )
	{
		$fname = 'LoadBalancer::getConnection';
		wfProfileIn( $fname );
		/*
		# Task-based index
		if ( $i >= DB_TASK_FIRST && $i < DB_TASK_LAST ) {
			if ( $i % 2 ) {
				# Odd index use writer
				$i = DB_MASTER;
			} else {
				# Even index use reader
				$i = DB_SLAVE;
			}
		}*/

		# Operation-based index
		if ( $i == DB_SLAVE ) {	
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
		# Now we have an explicit index into the servers array
		$this->openConnection( $i, $fail );
		wfProfileOut( $fname );
		return $this->mConnections[$i];
	}

	/**
	 * Open a connection to the server given by the specified index
	 * Index must be an actual index into the array
	 * @private
	 */
	function openConnection( $i, $fail = false ) {
		$fname = 'LoadBalancer::openConnection';
		wfProfileIn( $fname );

		if ( !$this->isOpen( $i ) ) {
			$this->mConnections[$i] = $this->reallyOpenConnection( $this->mServers[$i] );
			
			if ( $i != 0 && $this->mWaitForFile ) {
				if ( !$this->doWait( $i ) ) {
					# Error waiting for this slave, use master instead
					$this->mReadIndex = 0;
					$i = 0;
					if ( !$this->isOpen( 0 ) ) {
						$this->mConnections[0] = $this->reallyOpenConnection( $this->mServers[0] );
					}
					wfDebug( "Failed over to {$this->mConnections[0]->mServer}\n" );
				}
			}
		}
		if ( !$this->isOpen( $i ) ) {
			wfDebug( "Failed to connect to database $i at {$this->mServers[$i]['host']}\n" );
			if ( $fail ) {
				$this->reportConnectionError( $this->mConnections[$i] );
			}
			$this->mConnections[$i] = false;
		}
		$this->mLastIndex = $i;
		wfProfileOut( $fname );
	}

	/**
	 * Test if the specified index represents an open connection
	 * @private
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
	 * @private
	 */
	function reallyOpenConnection( &$server ) {
			extract( $server );
			# Get class for this database type
			$class = 'Database' . ucfirst( $type );
			if ( !class_exists( $class ) ) {
				require_once( "$class.php" );
			}

			# Create object
			return new $class( $host, $user, $password, $dbname, 1, $flags );
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
				$conn = new Database;
			}
			if ( $this->mFailFunction ) {
				$conn->failFunction( $this->mFailFunction );
			} else {
				$conn->failFunction( 'wfEmergencyAbort' );
			}
			$conn->reportConnectionError();
			$reporting = false;
		}
		wfProfileOut( $fname );
	}
	
	function getWriterIndex()
	{
		return 0;
	}

	function force( $i )
	{
		$this->mForce = $i;
	}

	function haveIndex( $i )
	{
		return array_key_exists( $i, $this->mServers );
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
		global $wgSessionStarted;
		if ( $wgSessionStarted && count( $this->mServers ) > 1 ) {
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
				$conn->close();
			}
		}
	}

	function commitAll() {
		foreach( $this->mConnections as $i => $conn ) {
			if ( $this->isOpen( $i ) ) {
				$conn->immediateCommit();
			}
		}
	}
}
