<?php
# Database load balancing object

# Valid database indexes
# Operation-based indexes
define( "DB_READ", -1 );     # Read from the slave (or only server)
define( "DB_WRITE", -2 );    # Write to master (or only server)
define( "DB_LAST", -3 );     # Whatever database was used last

# Task-based indexes
# ***NOT USED YET, EXPERIMENTAL***
# These may be defined in $wgDBservers. If they aren't, the default reader or writer will be used
# Even numbers are always readers, odd numbers are writers
define( "DB_TASK_FIRST", 1000 );  # First in list
define( "DB_SEARCH_R", 1000 );    # Search read
define( "DB_SEARCH_W", 1001 );    # Search write
define( "DB_ASKSQL_R", 1002 );    # Special:Asksql read
define( "DB_WATCHLIST_R", 1004 ); # Watchlist read
define( "DB_TASK_LAST", 1004) ;   # Last in list

class LoadBalancer {
	/* private */ var $mServers, $mConnections, $mLoads;
	/* private */ var $mUser, $mPassword, $mDbName, $mFailFunction;
	/* private */ var $mForce, $mReadIndex, $mLastConn;

	function LoadBalancer()
	{
		$this->mServers = array();
		$this->mLoads = array();
		$this->mConnections = array();
		$this->mUser = false;
		$this->mPassword = false;
		$this->mDbName = false;
		$this->mFailFunction = false;
		$this->mReadIndex = -1;
		$this->mForce = -1;
		$this->mLastConn = false;
	}

	function newFromParams( $servers, $loads, $user, $password, $dbName, $failFunction = false )
	{
		$lb = new LoadBalancer;
		$lb->initialise( $servers, $loads, $user, $password, $dbName, $failFunction = false );
		return $lb;
	}

	function initialise( $servers, $loads, $user, $password, $dbName, $failFunction = false )
	{
		$this->mServers = $servers;
		$this->mLoads = $loads;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDbName = $dbName;
		$this->mFailFunction = $failFunction;
		$this->mReadIndex = -1;
		$this->mWriteIndex = -1;
		$this->mForce = -1;
		$this->mConnections = array();
		$this->mLastConn = false;
		wfSeedRandom();
	}
	
	# Given an array of non-normalised probabilities, this function will select
	# an element and return the appropriate key
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

	function &getReader()
	{
		if ( $this->mForce >= 0 ) {
			$conn =& $this->getConnection( $this->mForce );
		} else {
			if ( $this->mReadIndex >= 0 ) {
				$conn =& $this->getConnection( $this->mReadIndex );
			} else {
				# $loads is $this->mLoads except with elements knocked out if they
				# don't work
				$loads = $this->mLoads;
				do {
					$i = $this->pickRandom( $loads );
					if ( $i !== false ) {
						wfDebug( "Using reader #$i: {$this->mServers[$i]}\n" );

						$conn =& $this->getConnection( $i );
						if ( !$conn->isOpen() ) {
							unset( $loads[$i] );
						}
					}
				} while ( $i !== false && !$conn->isOpen() );
				if ( $conn->isOpen() ) {
					$this->mReadIndex = $i;
				}
			}
		}
		if ( $conn === false || !$conn->isOpen() ) {
			$this->reportConnectionError( $conn );
			$conn = false;
		}
		return $conn;
	}

	function &getConnection( $i, $fail = false )
	{
		/*
		# Task-based index
		if ( $i >= DB_TASK_FIRST && $i < DB_TASK_LAST ) {
			if ( $i % 2 ) {
				# Odd index use writer
				$i = DB_WRITE;
			} else {
				# Even index use reader
				$i = DB_READ;
			}
		}*/

		# Operation-based index
		# Note, getReader() and getWriter() will re-enter this function
		if ( $i == DB_READ ) {
			$this->mLastConn =& $this->getReader();
		} elseif ( $i == DB_WRITE ) {
			$this->mLastConn =& $this->getWriter();
		} elseif ( $i == DB_LAST ) {
			# Just use $this->mLastConn, which should already be set
			if ( $this->mLastConn === false ) {
				# Oh dear, not set, best to use the writer for safety
				$this->mLastConn =& $this->getWriter();
			}
		} else {
			# Explicit index
			if ( !array_key_exists( $i, $this->mConnections) || !$this->mConnections[$i]->isOpen() ) {
				$this->mConnections[$i] = Database::newFromParams( $this->mServers[$i], $this->mUser, 
				  $this->mPassword, $this->mDbName, 1 );
			}
			if ( !$this->mConnections[$i]->isOpen() ) {
				wfDebug( "Failed to connect to database $i at {$this->mServers[$i]}\n" );
				if ( $fail ) {
					$this->reportConnectionError( $this->mConnections[$i] );
				}
				$this->mConnections[$i] = false;
			}
			$this->mLastConn =& $this->mConnections[$i];
		}
		return $this->mLastConn;
	}

	function reportConnectionError( &$conn )
	{
		if ( !is_object( $conn ) ) {
			$conn = new Database;
		}
		if ( $this->mFailFunction ) {
			$conn->setFailFunction( $this->mFailFunction );
		} else {
			$conn->setFailFunction( "wfEmergencyAbort" );
		}
		$conn->reportConnectionError();
	}
	
	function &getWriter()
	{
		$c =& $this->getConnection( 0 );
		if ( $c === false || !$c->isOpen() ) {
			$this->reportConnectionError( $c );
			$c = false;
		}
		return $c;
	}

	function force( $i )
	{
		$this->mForce = $i;
	}

	function haveIndex( $i )
	{
		return array_key_exists( $i, $this->mServers );
	}
}
