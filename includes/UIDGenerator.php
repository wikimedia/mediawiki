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

	protected $uidLockFile64; // string; local file path
	protected $uidLockFile128; // string; local file path

	protected function __construct() {
		$this->uidLockFile64  = wfTempDir() . '/mw-UID-64.lock';
		$this->uidLockFile128 = wfTempDir() . '/mw-UID-128.lock';
	}

	/**
	 * @return UIDGenerator
	 */
	protected static function singleton() {
		if ( self::$instance == null ) {
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
		$handle = fopen( $generator->uidLockFile64, 'a' );
		if ( !$handle ) {
			throw new MWException( "Could not open '{$this->uidLockFile64}'." );
		} elseif ( !flock( $handle, LOCK_EX ) ) {
			fclose( $handle );
			throw new MWException( 'Could not acquire local UID64 lock.' );
		}
		// Generate a UID that is higher than the last one from this server
		try {
			$uid = $generator->newTimestampedID64();
			do { // delay so the next process on this server will generate higher UIDs
				$nextUid = $generator->newTimestampedID64();
			} while ( $generator->lessThanOrEqual64( $nextUid, $uid ) );
		} catch ( Exception $e ) {
			flock( $handle, LOCK_UN );
			fclose( $handle );
			throw $e;
		}
		// Release the UID lock file
		flock( $handle, LOCK_UN );
		fclose( $handle );

		return $uid;
	}

	/**
	 * Check if $a is <= $b, where $a and $b are 64-bit UIDs.
	 * Note that (2^64)/(10^20) = 0.184467441.
	 *
	 * @param $a string
	 * @param $b string
	 * @return bool
	 */
	protected function lessThanOrEqual64( $a, $b ) {
		return strcmp( // pad out to compare lexicographically
			str_pad( $a, 20, '0', STR_PAD_LEFT ),
			str_pad( $b, 20, '0', STR_PAD_LEFT ) ) <= 0;
	}

	/**
	 * @return string
	 */
	protected function newTimestampedID64() {
		$epoch = 1325376000; // "January 1, 2012"
		$time  = microtime( true ); // float
		$sec   = floor( $time ) - (int)$epoch; // start from epoch
		$msec  = floor( 1e3 * ( $time - floor( $time ) ) );
		$node  = substr( sha1( wfHostname() ), 0, 6 ); // 24 bit
		if ( PHP_INT_SIZE == 8 ) { // 64 bit integers
			# Take the 40 MSBs of "milliseconds since epoch" (rolls over in 2046)
			$id_bin = str_pad( decbin( 1e3 * $sec + $msec ), 40, '0', STR_PAD_LEFT );
			# Add the 24 bit node ID resulting in 64 bits total
			$id_bin .= str_pad( base_convert( $node, 16, 2 ), 24, '0', STR_PAD_LEFT );
		} else { // 32 bit integers
			# Create a 42 bit binary string from the time (rolls over in 2038).
			# Note that $sec has 32 bits and (10^3)/(2^10) = 0.9765625.
			$id_bin = wfBaseConvert( $sec, 10, 2, 32 ) . wfBaseConvert( $msec, 10, 2, 10 );
			# Add the 22 bit node ID resulting in 64 bits total
			$id_bin .= substr( wfBaseConvert( $node, 16, 2, 24 ), 0, 22 );
		}
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
		$handle = fopen( $generator->uidLockFile128, 'a' );
		if ( !$handle ) {
			throw new MWException( "Could not open '{$this->uidLockFile128}'." );
		} elseif ( !flock( $handle, LOCK_EX ) ) {
			fclose( $handle );
			throw new MWException( 'Could not acquire local UID128 lock.' );
		}
		// Generate a UID that is higher than the last one from this server
		try {
			$uid = $generator->newTimestampedID128();
			do { // delay so the next process on this server will generate higher UIDs
				$nextUid = $generator->newTimestampedID128();
			} while ( $generator->lessThanOrEqual128( $nextUid, $uid ) );
		} catch ( Exception $e ) {
			flock( $handle, LOCK_UN );
			fclose( $handle );
			throw $e;
		}
		// Release the UID lock file
		flock( $handle, LOCK_UN );
		fclose( $handle );

		return $uid;
	}

	/**
	 * Check if $a is <= $b, where $a and $b are 128-bit UIDs.
	 * Note that (2^128)/(10^39) = 0.340282367.
	 *
	 * @param $a string
	 * @param $b string
	 * @return bool
	 */
	protected function lessThanOrEqual128( $a, $b ) {
		return strcmp( // pad out to compare lexicographically
			str_pad( $a, 39, '0', STR_PAD_LEFT ),
			str_pad( $b, 39, '0', STR_PAD_LEFT ) ) <= 0;
	}

	/**
	 * @return string
	 */
	protected function newTimestampedID128() {
		$epoch = 1325376000; // "January 1, 2012"
		$time  = microtime( true ); // float
		$sec   = floor( $time ) - (int)$epoch; // start from epoch
		$usec  = floor( 1e6 * ( $time - floor( $time ) ) );
		$node  = substr( sha1( wfHostname() ), 0, 12 ); // 48 bit
		if ( PHP_INT_SIZE == 8 ) { // 64 bit integers
			# Take the 56 MSBs of "microseconds since epoch" (rolls over in 4354)
			$id_bin = str_pad( decbin( 1e6 * $sec + $usec ), 56, '0', STR_PAD_LEFT );
			# Add on 24 bits of randomness to make 80 bits total (2^24 = 16777216)
			$id_bin .= str_pad( decbin( mt_rand( 0, 16777215 ) ), 24, '0', STR_PAD_LEFT );
			# Add the 48 bit node ID resulting in 128 bits total
			$id_bin .= str_pad( base_convert( $node, 16, 2 ), 48, '0', STR_PAD_LEFT );
		} else { // 32 bit integers
			# Create a 52 bit binary string from the time (rolls over in 2038).
			# Note that (10^6)/(2^20) = 0.953674316
			$id_bin = wfBaseConvert( $sec, 10, 2, 32 ) . wfBaseConvert( $usec, 10, 2, 20 );
			# Add on 28 bits of randomness to make 80 bits total (2^28 = 268435456)
			$id_bin .= wfBaseConvert( mt_rand( 0, 268435455 ), 10, 2, 28 );
			# Add the 48 bit node ID resulting in 128 bits total
			$id_bin .= wfBaseConvert( $node, 16, 2, 48 );
		}
		# Convert to a 1-39 digit integer string
		if ( strlen( $id_bin ) != 128 ) {
			throw new MWException( "Detected overflow for microsecond timestamp." );
		}
		# Convert to a 1-39 digit integer string
		return wfBaseConvert( $id_bin, 2, 10 );
	}
}
