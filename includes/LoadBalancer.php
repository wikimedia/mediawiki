<?php
# Database load balancing object

class LoadBalancer {
	/* private */ var $mServers, $mConnections, $mLoads;
	/* private */ var $mUser, $mPassword, $mDbName, $mFailFunction;
	/* private */ var $mForce, $mReadIndex;

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
		$rand = mt_rand() / RAND_MAX * $sum;
		
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
					$i = pickRandom( $loads );
					if ( $i !== false ) {
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
		return $this->mConnections[$i];
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
		if ( !$c->isOpen() ) {
			reportConnectionError( $conn );
			$c = false;
		}
		return $c;
	}

	function force( $i )
	{
		$this->mForce = $i;
	}
}
