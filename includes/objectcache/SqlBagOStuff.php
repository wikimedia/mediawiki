<?php

/**
 * Class to store objects in the database
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends BagOStuff {
	var $lb, $db, $serverInfo;
	var $lastExpireAll = 0;

	/**
	 * Constructor. Parameters are:
	 *   - server:   A server info structure in the format required by each 
	 *               element in $wgDBServers.
	 */
	public function __construct( $params ) {
		if ( isset( $params['server'] ) ) {
			$this->serverInfo = $params['server'];
			$this->serverInfo['load'] = 1;
		}
	}

	protected function getDB() {
		if ( !isset( $this->db ) ) {
			# If server connection info was given, use that
			if ( $this->serverInfo ) {
				$this->lb = new LoadBalancer( array(
					'servers' => array( $this->serverInfo ) ) );
				$this->db = $this->lb->getConnection( DB_MASTER );
				$this->db->clearFlag( DBO_TRX );
			} else {
				# We must keep a separate connection to MySQL in order to avoid deadlocks
				# However, SQLite has an opposite behaviour.
				# @todo Investigate behaviour for other databases
				if ( wfGetDB( DB_MASTER )->getType() == 'sqlite' ) {
					$this->db = wfGetDB( DB_MASTER );
				} else {
					$this->lb = wfGetLBFactory()->newMainLB();
					$this->db = $this->lb->getConnection( DB_MASTER );
					$this->db->clearFlag( DBO_TRX );
				}
			}
		}

		return $this->db;
	}

	public function get( $key ) {
		# expire old entries if any
		$this->garbageCollect();
		$db = $this->getDB();
		$row = $db->selectRow( 'objectcache', array( 'value', 'exptime' ),
			array( 'keyname' => $key ), __METHOD__ );

		if ( !$row ) {
			$this->debug( 'get: no matching rows' );
			return false;
		}

		$this->debug( "get: retrieved data; expiry time is " . $row->exptime );

		if ( $this->isExpired( $row->exptime ) ) {
			$this->debug( "get: key has expired, deleting" );
			try {
				$db->begin();
				# Put the expiry time in the WHERE condition to avoid deleting a
				# newly-inserted value
				$db->delete( 'objectcache',
					array(
						'keyname' => $key,
						'exptime' => $row->exptime
					), __METHOD__ );
				$db->commit();
			} catch ( DBQueryError $e ) {
				$this->handleWriteError( $e );
			}

			return false;
		}

		return $this->unserialize( $db->decodeBlob( $row->value ) );
	}

	public function set( $key, $value, $exptime = 0 ) {
		$db = $this->getDB();
		$exptime = intval( $exptime );

		if ( $exptime < 0 ) {
			$exptime = 0;
		}

		if ( $exptime == 0 ) {
			$encExpiry = $this->getMaxDateTime();
		} else {
			if ( $exptime < 3.16e8 ) { # ~10 years
				$exptime += time();
			}

			$encExpiry = $db->timestamp( $exptime );
		}
		try {
			$db->begin();
			// (bug 24425) use a replace if the db supports it instead of
			// delete/insert to avoid clashes with conflicting keynames
			$db->replace( 'objectcache', array( 'keyname' ),
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $value ) ),
					'exptime' => $encExpiry
				), __METHOD__ );
			$db->commit();
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e );

			return false;
		}

		return true;
	}

	public function delete( $key, $time = 0 ) {
		$db = $this->getDB();

		try {
			$db->begin();
			$db->delete( 'objectcache', array( 'keyname' => $key ), __METHOD__ );
			$db->commit();
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e );

			return false;
		}

		return true;
	}

	public function incr( $key, $step = 1 ) {
		$db = $this->getDB();
		$step = intval( $step );

		try {
			$db->begin();
			$row = $db->selectRow( 'objectcache', array( 'value', 'exptime' ),
				array( 'keyname' => $key ), __METHOD__, array( 'FOR UPDATE' ) );
			if ( $row === false ) {
				// Missing
				$db->commit();

				return null;
			}
			$db->delete( 'objectcache', array( 'keyname' => $key ), __METHOD__ );
			if ( $this->isExpired( $row->exptime ) ) {
				// Expired, do not reinsert
				$db->commit();

				return null;
			}

			$oldValue = intval( $this->unserialize( $db->decodeBlob( $row->value ) ) );
			$newValue = $oldValue + $step;
			$db->insert( 'objectcache',
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $newValue ) ),
					'exptime' => $row->exptime
				), __METHOD__ );
			$db->commit();
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e );

			return null;
		}

		return $newValue;
	}

	public function keys() {
		$db = $this->getDB();
		$res = $db->select( 'objectcache', array( 'keyname' ), false, __METHOD__ );
		$result = array();

		foreach ( $res as $row ) {
			$result[] = $row->keyname;
		}

		return $result;
	}

	protected function isExpired( $exptime ) {
		return $exptime != $this->getMaxDateTime() && wfTimestamp( TS_UNIX, $exptime ) < time();
	}

	protected function getMaxDateTime() {
		if ( time() > 0x7fffffff ) {
			return $this->getDB()->timestamp( 1 << 62 );
		} else {
			return $this->getDB()->timestamp( 0x7fffffff );
		}
	}

	protected function garbageCollect() {
		/* Ignore 99% of requests */
		if ( !mt_rand( 0, 100 ) ) {
			$now = time();
			/* Avoid repeating the delete within a few seconds */
			if ( $now > ( $this->lastExpireAll + 1 ) ) {
				$this->lastExpireAll = $now;
				$this->expireAll();
			}
		}
	}

	public function expireAll() {
		$db = $this->getDB();
		$now = $db->timestamp();

		try {
			$db->begin();
			$db->delete( 'objectcache', array( 'exptime < ' . $db->addQuotes( $now ) ), __METHOD__ );
			$db->commit();
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e );
		}
	}

	public function deleteAll() {
		$db = $this->getDB();

		try {
			$db->begin();
			$db->delete( 'objectcache', '*', __METHOD__ );
			$db->commit();
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e );
		}
	}

	/**
	 * Serialize an object and, if possible, compress the representation.
	 * On typical message and page data, this can provide a 3X decrease
	 * in storage requirements.
	 *
	 * @param $data mixed
	 * @return string
	 */
	protected function serialize( &$data ) {
		$serial = serialize( $data );

		if ( function_exists( 'gzdeflate' ) ) {
			return gzdeflate( $serial );
		} else {
			return $serial;
		}
	}

	/**
	 * Unserialize and, if necessary, decompress an object.
	 * @param $serial string
	 * @return mixed
	 */
	protected function unserialize( $serial ) {
		if ( function_exists( 'gzinflate' ) ) {
			$decomp = @gzinflate( $serial );

			if ( false !== $decomp ) {
				$serial = $decomp;
			}
		}

		$ret = unserialize( $serial );

		return $ret;
	}

	/**
	 * Handle a DBQueryError which occurred during a write operation.
	 * Ignore errors which are due to a read-only database, rethrow others.
	 */
	protected function handleWriteError( $exception ) {
		$db = $this->getDB();

		if ( !$db->wasReadOnlyError() ) {
			throw $exception;
		}

		try {
			$db->rollback();
		} catch ( DBQueryError $e ) {
		}

		wfDebug( __METHOD__ . ": ignoring query error\n" );
		$db->ignoreErrors( false );
	}
}

/**
 * Backwards compatibility alias
 */
class MediaWikiBagOStuff extends SqlBagOStuff { }

