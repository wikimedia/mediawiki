<?php
/**
 * Classes to cache objects in PHP accelerators, SQL database or DBA files
 *
 * Copyright © 2003-2004 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * @ingroup Cache
 */

/**
 * @defgroup Cache Cache
 */

/**
 * interface is intended to be more or less compatible with
 * the PHP memcached client.
 *
 * backends for local hash array and SQL table included:
 * <code>
 *   $bag = new HashBagOStuff();
 *   $bag = new SqlBagOStuff(); # connect to db first
 * </code>
 *
 * @ingroup Cache
 */
abstract class BagOStuff {
	var $debugMode = false;

	public function set_debug( $bool ) {
		$this->debugMode = $bool;
	}

	/* *** THE GUTS OF THE OPERATION *** */
	/* Override these with functional things in subclasses */

	/**
	 * Get an item with the given key. Returns false if it does not exist.
	 * @param $key string
	 */
	abstract public function get( $key );

	/**
	 * Set an item.
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int Either an interval in seconds or a unix timestamp for expiry
	 */
	abstract public function set( $key, $value, $exptime = 0 );

	/*
	 * Delete an item.
	 * @param $key string
	 * @param $time int Amount of time to delay the operation (mostly memcached-specific)
	 */
	abstract public function delete( $key, $time = 0 );

	public function lock( $key, $timeout = 0 ) {
		/* stub */
		return true;
	}

	public function unlock( $key ) {
		/* stub */
		return true;
	}

	public function keys() {
		/* stub */
		return array();
	}

	/* *** Emulated functions *** */
	/* Better performance can likely be got with custom written versions */
	public function get_multi( $keys ) {
		$out = array();

		foreach ( $keys as $key ) {
			$out[$key] = $this->get( $key );
		}

		return $out;
	}

	public function set_multi( $hash, $exptime = 0 ) {
		foreach ( $hash as $key => $value ) {
			$this->set( $key, $value, $exptime );
		}
	}

	public function add( $key, $value, $exptime = 0 ) {
		if ( !$this->get( $key ) ) {
			$this->set( $key, $value, $exptime );

			return true;
		}
	}

	public function add_multi( $hash, $exptime = 0 ) {
		foreach ( $hash as $key => $value ) {
			$this->add( $key, $value, $exptime );
		}
	}

	public function delete_multi( $keys, $time = 0 ) {
		foreach ( $keys as $key ) {
			$this->delete( $key, $time );
		}
	}

	public function replace( $key, $value, $exptime = 0 ) {
		if ( $this->get( $key ) !== false ) {
			$this->set( $key, $value, $exptime );
		}
	}

	public function incr( $key, $value = 1 ) {
		if ( !$this->lock( $key ) ) {
			return false;
		}

		$value = intval( $value );

		$n = false;
		if ( ( $n = $this->get( $key ) ) !== false ) {
			$n += $value;
			$this->set( $key, $n ); // exptime?
		}
		$this->unlock( $key );

		return $n;
	}

	public function decr( $key, $value = 1 ) {
		return $this->incr( $key, - $value );
	}

	public function debug( $text ) {
		if ( $this->debugMode ) {
			wfDebug( "BagOStuff debug: $text\n" );
		}
	}

	/**
	 * Convert an optionally relative time to an absolute time
	 */
	protected function convertExpiry( $exptime ) {
		if ( ( $exptime != 0 ) && ( $exptime < 86400 * 3650 /* 10 years */ ) ) {
			return time() + $exptime;
		} else {
			return $exptime;
		}
	}
}

/**
 * Functional versions!
 * This is a test of the interface, mainly. It stores things in an associative
 * array, which is not going to persist between program runs.
 *
 * @ingroup Cache
 */
class HashBagOStuff extends BagOStuff {
	var $bag;

	function __construct() {
		$this->bag = array();
	}

	protected function expire( $key ) {
		$et = $this->bag[$key][1];

		if ( ( $et == 0 ) || ( $et > time() ) ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	function get( $key ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		if ( $this->expire( $key ) ) {
			return false;
		}

		return $this->bag[$key][0];
	}

	function set( $key, $value, $exptime = 0 ) {
		$this->bag[$key] = array( $value, $this->convertExpiry( $exptime ) );
	}

	function delete( $key, $time = 0 ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		unset( $this->bag[$key] );

		return true;
	}

	function keys() {
		return array_keys( $this->bag );
	}
}

/**
 * Class to store objects in the database
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends BagOStuff {
	var $lb, $db;
	var $lastExpireAll = 0;

	protected function getDB() {
		global $wgDBtype;

		if ( !isset( $this->db ) ) {
			/* We must keep a separate connection to MySQL in order to avoid deadlocks
			 * However, SQLite has an opposite behaviour.
			 * @todo Investigate behaviour for other databases
			 */
			if ( $wgDBtype == 'sqlite' ) {
				$this->db = wfGetDB( DB_MASTER );
			} else {
				$this->lb = wfGetLBFactory()->newMainLB();
				$this->db = $this->lb->getConnection( DB_MASTER );
				$this->db->clearFlag( DBO_TRX );
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

/**
 * This is a wrapper for APC's shared memory functions
 *
 * @ingroup Cache
 */
class APCBagOStuff extends BagOStuff {
	public function get( $key ) {
		$val = apc_fetch( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	public function set( $key, $value, $exptime = 0 ) {
		apc_store( $key, serialize( $value ), $exptime );

		return true;
	}

	public function delete( $key, $time = 0 ) {
		apc_delete( $key );

		return true;
	}

	public function keys() {
		$info = apc_cache_info( 'user' );
		$list = $info['cache_list'];
		$keys = array();

		foreach ( $list as $entry ) {
			$keys[] = $entry['info'];
		}

		return $keys;
	}
}

/**
 * This is a wrapper for eAccelerator's shared memory functions.
 *
 * This is basically identical to the deceased Turck MMCache version,
 * mostly because eAccelerator is based on Turck MMCache.
 *
 * @ingroup Cache
 */
class eAccelBagOStuff extends BagOStuff {
	public function get( $key ) {
		$val = eaccelerator_get( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	public function set( $key, $value, $exptime = 0 ) {
		eaccelerator_put( $key, serialize( $value ), $exptime );

		return true;
	}

	public function delete( $key, $time = 0 ) {
		eaccelerator_rm( $key );

		return true;
	}

	public function lock( $key, $waitTimeout = 0 ) {
		eaccelerator_lock( $key );

		return true;
	}

	public function unlock( $key ) {
		eaccelerator_unlock( $key );

		return true;
	}
}

/**
 * Wrapper for XCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class XCacheBagOStuff extends BagOStuff {
	/**
	 * Get a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @return mixed
	 */
	public function get( $key ) {
		$val = xcache_get( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	/**
	 * Store a value in the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		xcache_set( $key, serialize( $value ), $expire );

		return true;
	}

	/**
	 * Remove a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $time Int: not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		xcache_unset( $key );

		return true;
	}
}

/**
 * Cache that uses DBA as a backend.
 * Slow due to the need to constantly open and close the file to avoid holding
 * writer locks. Intended for development use only,  as a memcached workalike
 * for systems that don't have it.
 *
 * @ingroup Cache
 */
class DBABagOStuff extends BagOStuff {
	var $mHandler, $mFile, $mReader, $mWriter, $mDisabled;

	public function __construct( $dir = false ) {
		global $wgDBAhandler;

		if ( $dir === false ) {
			global $wgTmpDirectory;
			$dir = $wgTmpDirectory;
		}

		$this->mFile = "$dir/mw-cache-" . wfWikiID();
		$this->mFile .= '.db';
		wfDebug( __CLASS__ . ": using cache file {$this->mFile}\n" );
		$this->mHandler = $wgDBAhandler;
	}

	/**
	 * Encode value and expiry for storage
	 */
	function encode( $value, $expiry ) {
		# Convert to absolute time
		$expiry = $this->convertExpiry( $expiry );

		return sprintf( '%010u', intval( $expiry ) ) . ' ' . serialize( $value );
	}

	/**
	 * @return list containing value first and expiry second
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

/**
 * Wrapper for WinCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class WinCacheBagOStuff extends BagOStuff {

	/**
	 * Get a value from the WinCache object cache
	 *
	 * @param $key String: cache key
	 * @return mixed
	 */
	public function get( $key ) {
		$val = wincache_ucache_get( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	/**
	 * Store a value in the WinCache object cache
	 *
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		wincache_ucache_set( $key, serialize( $value ), $expire );

		return true;
	}

	/**
	 * Remove a value from the WinCache object cache
	 *
	 * @param $key String: cache key
	 * @param $time Int: not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		wincache_ucache_delete( $key );

		return true;
	}

	public function keys() {
		$info = wincache_ucache_info();
		$list = $info['ucache_entries'];
		$keys = array();

		foreach ( $list as $entry ) {
			$keys[] = $entry['key_name'];
		}

		return $keys;
	}
}
