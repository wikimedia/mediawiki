<?php
/**
 * This file deals with UID generation.
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
 * @author Aaron Schulz
 */

/**
 * Class for getting statistically unique IDs
 *
 * @since 1.20
 */
class UIDGenerator {
	/** @var UIDGenerator */
	protected static $instance = null;

	protected $nodeIdBinary; // string; node ID in binary (48 bits)
	protected $threadId; // integer; thread ID or random integer
	protected $lockFile64; // string; local file path
	protected $lockFile128; // string; local file path

	const EPOCH64  = 1325376000; // "January 1, 2012"
	const EPOCH128 = 1325376000; // "January 1, 2012"

	protected function __construct() {
		$id_bin = ''; // string to identify this machine
		if ( !wfIsWindows() ) { // *nix
			$hostId = trim( `hostid` );
			if ( preg_match( '/^[0-9a-f]{8}|[0-9a-f]{12}$/', $hostId ) ) {
				$id_bin = wfBaseConvert( $hostId, 16, 2, 48 );
			}
		}
		if ( !strlen( $id_bin ) ) { // fallback to host name
			$id_bin = wfBaseConvert( substr( sha1( wfHostname() ), 0, 12 ), 16, 2, 48 );
		}
		$this->nodeIdBinary = $id_bin;
		# UID128 should be unique without having to check. The flock() calls may not be effective
		# on operating systems when PHP is running in a threaded server model. In such cases, the
		# Zend Thread Safety extension should be installed per php.net guidelines. This extension
		# gives us zend_thread_id(), which we include in the UID to avoid collisions.
		# See http://www.php.net/manual/en/install.unix.apache2.php.
		$this->threadId    = function_exists( 'zend_thread_id' ) ? zend_thread_id() : 0;
		$this->lockFile64  = wfTempDir() . '/mw-' . __CLASS__ . '-UID-64.lock';
		$this->lockFile128 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-128.lock';
	}

	/**
	 * @return UIDGenerator
	 */
	protected static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get a statistically unique 64-bit unsigned integer ID string.
	 * The bits of the UID are prefixed with the time (down to the millisecond).
	 *
	 * These IDs are suitable as values for the shard column of sharded DB tables.
	 * If a column uses these as values, it should be declared UNIQUE to handle collisions.
	 * New rows almost always have higher UIDs, which makes B-TREE updates on INSERT fast.
	 * They can also be stored efficiently as a "BIGINT UNSIGNED" in MySQL.
	 *
	 * UID generation is serialized on each server (since the node ID is the hostname).
	 *
	 * @return string
	 * @throws MWException
	 */
	public static function timestampedUID64() {
		$generator = self::singleton();

		// Acquire the UID lock file
		$handle = fopen( $generator->lockFile64, 'a' );
		if ( $handle === false ) {
			throw new MWException( "Could not open '{$this->lockFile64}'." );
		} elseif ( !flock( $handle, LOCK_EX ) ) {
			fclose( $handle ); // bail out
			throw new MWException( 'Could not acquire local UID64 lock.' );
		}
		// Get the current UNIX timestamp on this server
		$time = self::millitime(); // millisecond precision
		// Delay so the next process on this server will generate higher UIDs
		while ( self::lessThanOrEqualTime( self::millitime(), $time ) );
		// Release the UID lock file
		flock( $handle, LOCK_UN );
		fclose( $handle );

		// Generate a UID that is higher than the last one from this server
		return $generator->newTimestampedID64( $time );
	}

	/**
	 * @param $time array Result of UIDGenerator::millitime()
	 * @return string
	 */
	protected function newTimestampedID64( array $time ) {
		list( $sec, $msec ) = $time;
		$sec = $sec - self::EPOCH64; // start from epoch
		if ( PHP_INT_SIZE === 8 ) { // 64 bit integers
			# Take the 40 MSBs of "milliseconds since epoch" (rolls over in 2046)
			$id_bin = str_pad( decbin( 1e3 * $sec + $msec ), 40, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'bcmath' ) ) { // 32 bit integers
			# Take the 40 MSBs of "milliseconds since epoch" (rolls over in 2046)
			$id_bin = wfBaseConvert( bcadd( bcmul( $sec, 1e3 ), $msec ), 10, 2, 40 );
		} else {
			throw new MWException( 'bcmath extension required for 32 bit machines.' );
		}
		# Add the 24 bit node ID resulting in 64 bits total
		$id_bin .= substr( $this->nodeIdBinary, 0, 24 );
		# Convert to a 1-20 digit integer string
		if ( strlen( $id_bin ) != 64 ) {
			throw new MWException( "Detected overflow for millisecond timestamp." );
		}
		return wfBaseConvert( $id_bin, 2, 10 );
	}

	/**
	 * Get a statistically unique 128-bit unsigned integer ID string.
	 * The bits of the UID are prefixed with the time (down to the microsecond).
	 *
	 * Unlike timestampedUID64(), these IDs are suitable as UUIDs, without any enforced
	 * uniqueness checks in the storage medium, such as a DB. Thus, this can be used to
	 * make unique values of DB table column where the DBMS does not enforce uniqueness.
	 * New rows almost always have higher UIDs, which makes B-TREE updates on INSERT fast.
	 * They can also be stored reasonably as a "DECIMAL(39) UNSIGNED" in MySQL.
	 *
	 * UID generation is serialized on each server (since the node ID is the hostname).
	 *
	 * @return string
	 * @throws MWException
	 */
	public static function timestampedUID128() {
		$generator = self::singleton();

		// Acquire the UID lock file
		$handle = fopen( $generator->lockFile128, 'a' );
		if ( $handle === false ) {
			throw new MWException( "Could not open '{$this->lockFile128}'." );
		} elseif ( !flock( $handle, LOCK_EX ) ) {
			fclose( $handle ); // bail out
			throw new MWException( 'Could not acquire local UID128 lock.' );
		}
		// Get the current UNIX timestamp on this server
		$time = self::microtime(); // microsecond precision
		// Delay so the next process on this server will generate higher UIDs
		while ( self::lessThanOrEqualTime( self::microtime(), $time ) );
		// Release the UID lock file
		flock( $handle, LOCK_UN );
		fclose( $handle );

		// Generate a UID that is higher than the last one from this server
		return $generator->newTimestampedID128( $time );
	}

	/**
	 * @param $time array Result of UIDGenerator::microtime()
	 * @return string
	 */
	protected function newTimestampedID128( array $time ) {
		list( $sec, $usec ) = $time;
		$sec  = $sec - self::EPOCH128; // start from epoch
		$rand = $this->threadId ? $this->threadId : mt_rand();
		if ( PHP_INT_SIZE === 8 ) { // 64 bit integers
			# Take the 56 MSBs of "microseconds since epoch" (rolls over in 4354)
			$id_bin = str_pad( decbin( 1e6 * $sec + $usec ), 56, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'bcmath' ) ) { // 32 bit integers
			# Take the 56 MSBs of "microseconds since epoch" (rolls over in 4354)
			$id_bin = wfBaseConvert( bcadd( bcmul( $sec, 1e6 ), $usec ), 10, 2, 56 );
		} else {
			throw new MWException( 'bcmath extension required for 32 bit machines.' );
		}
		# Add on 24 bits of randomness to make 80 bits total (2^24 = 16777216)
		$id_bin .= str_pad( decbin( $rand % 16777216 ), 24, '0', STR_PAD_LEFT );
		# Add the 48 bit node ID resulting in 128 bits total
		$id_bin .= substr( $this->nodeIdBinary, 0, 48 );
		# Convert to a 1-39 digit integer string
		if ( strlen( $id_bin ) != 128 ) {
			throw new MWException( "Detected overflow for microsecond timestamp." );
		}
		# Convert to a 1-39 digit integer string
		return wfBaseConvert( $id_bin, 2, 10 );
	}

	/**
	 * @return array (current time in seconds, milliseconds since then)
	 */
	protected static function millitime() {
		list( $usec, $sec ) = explode( ' ', microtime() );
		return array( (int)$sec, (int)( $usec * 1e3 ) );
	}

	/**
	 * @return array (current time in seconds, microseconds since then)
	 */
	protected static function microtime() {
		list( $usec, $sec ) = explode( ' ', microtime() );
		return array( (int)$sec, (int)( $usec * 1e6 ) );
	}

	/**
	 * Returns true if time $t1 is <= time $t2
	 *
	 * @param $t1 array Result of UIDGenerator::microtime() or UIDGenerator::millitime()
	 * @param $t2 array Result of UIDGenerator::microtime() or UIDGenerator::millitime()
	 */
	protected static function lessThanOrEqualTime( array $t1, array $t2 ) {
		return ( $t1[0] < $t2[0] || ( $t1[0] == $t2[0] && $t1[1] <= $t2[1] ) );
	}
}
