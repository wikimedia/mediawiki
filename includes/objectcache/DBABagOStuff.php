<?php

/**
 * Cache that uses DBA as a backend.
 * Slow due to the need to constantly open and close the file to avoid holding
 * writer locks. Intended for development use only,  as a memcached workalike
 * for systems that don't have it.
 *
 * On construction you can pass array( 'dir' => '/some/path' ); as a parameter
 * to override the default DBA files directory (wgTmpDirectory).
 *
 * @ingroup Cache
 */
class DBABagOStuff extends BagOStuff {
	var $mHandler, $mFile, $mReader, $mWriter, $mDisabled;

	public function __construct( $params ) {
		global $wgDBAhandler;

		if ( !isset( $params['dir'] ) ) {
			global $wgTmpDirectory;
			$params['dir'] = $wgTmpDirectory;
		}

		$this->mFile = $params['dir']."/mw-cache-" . wfWikiID();
		$this->mFile .= '.db';
		wfDebug( __CLASS__ . ": using cache file {$this->mFile}\n" );
		$this->mHandler = $wgDBAhandler;
	}

	/**
	 * Encode value and expiry for storage
	 * @param $value
	 * @param $expiry
	 *
	 * @return string
	 */
	function encode( $value, $expiry ) {
		# Convert to absolute time
		$expiry = $this->convertExpiry( $expiry );

		return sprintf( '%010u', intval( $expiry ) ) . ' ' . serialize( $value );
	}

	/**
	 * @return array list containing value first and expiry second
	 */
	function decode( $blob ) {
		if ( !is_string( $blob ) ) {
			return array( null, 0 );
		} else {
			return array(
				unserialize( substr( $blob, 11 ) ),
				intval( substr( $blob, 0, 10 ) )
			);
		}
	}

	function getReader() {
		if ( file_exists( $this->mFile ) ) {
			$handle = dba_open( $this->mFile, 'rl', $this->mHandler );
		} else {
			$handle = $this->getWriter();
		}

		if ( !$handle ) {
			wfDebug( "Unable to open DBA cache file {$this->mFile}\n" );
		}

		return $handle;
	}

	function getWriter() {
		$handle = dba_open( $this->mFile, 'cl', $this->mHandler );

		if ( !$handle ) {
			wfDebug( "Unable to open DBA cache file {$this->mFile}\n" );
		}

		return $handle;
	}

	function get( $key ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$handle = $this->getReader();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return null;
		}

		$val = dba_fetch( $key, $handle );
		list( $val, $expiry ) = $this->decode( $val );

		# Must close ASAP because locks are held
		dba_close( $handle );

		if ( !is_null( $val ) && $expiry && $expiry < time() ) {
			# Key is expired, delete it
			$handle = $this->getWriter();
			dba_delete( $key, $handle );
			dba_close( $handle );
			wfDebug( __METHOD__ . ": $key expired\n" );
			$val = null;
		}

		wfProfileOut( __METHOD__ );
		return $val;
	}

	function set( $key, $value, $exptime = 0 ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$blob = $this->encode( $value, $exptime );

		$handle = $this->getWriter();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = dba_replace( $key, $blob, $handle );
		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	function delete( $key, $time = 0 ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$handle = $this->getWriter();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = dba_delete( $key, $handle );
		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	function add( $key, $value, $exptime = 0 ) {
		wfProfileIn( __METHOD__ );

		$blob = $this->encode( $value, $exptime );

		$handle = $this->getWriter();

		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = dba_insert( $key, $blob, $handle );

		# Insert failed, check to see if it failed due to an expired key
		if ( !$ret ) {
			list( $value, $expiry ) = $this->decode( dba_fetch( $key, $handle ) );

			if ( $expiry < time() ) {
				# Yes expired, delete and try again
				dba_delete( $key, $handle );
				$ret = dba_insert( $key, $blob, $handle );
				# This time if it failed then it will be handled by the caller like any other race
			}
		}

		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	function keys() {
		$reader = $this->getReader();
		$k1 = dba_firstkey( $reader );

		if ( !$k1 ) {
			return array();
		}

		$result[] = $k1;

		while ( $key = dba_nextkey( $reader ) ) {
			$result[] = $key;
		}

		return $result;
	}
}

