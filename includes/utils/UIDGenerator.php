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
use Wikimedia\Assert\Assert;

/**
 * Class for getting statistically unique IDs
 *
 * @since 1.21
 */
class UIDGenerator {
	/** @var UIDGenerator */
	protected static $instance = null;

	protected $nodeIdFile; // string; local file path
	protected $nodeId32; // string; node ID in binary (32 bits)
	protected $nodeId48; // string; node ID in binary (48 bits)

	protected $lockFile88; // string; local file path
	protected $lockFile128; // string; local file path

	/** @var array */
	protected $fileHandles = array(); // cache file handles

	const QUICK_RAND = 1; // get randomness from fast and insecure sources
	const QUICK_VOLATILE = 2; // use an APC like in-memory counter if available

	protected function __construct() {
		$this->nodeIdFile = wfTempDir() . '/mw-' . __CLASS__ . '-UID-nodeid';
		$nodeId = '';
		if ( is_file( $this->nodeIdFile ) ) {
			$nodeId = file_get_contents( $this->nodeIdFile );
		}
		// Try to get some ID that uniquely identifies this machine (RFC 4122)...
		if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
			MediaWiki\suppressWarnings();
			if ( wfIsWindows() ) {
				// http://technet.microsoft.com/en-us/library/bb490913.aspx
				$csv = trim( wfShellExec( 'getmac /NH /FO CSV' ) );
				$line = substr( $csv, 0, strcspn( $csv, "\n" ) );
				$info = str_getcsv( $line );
				$nodeId = isset( $info[0] ) ? str_replace( '-', '', $info[0] ) : '';
			} elseif ( is_executable( '/sbin/ifconfig' ) ) { // Linux/BSD/Solaris/OS X
				// See http://linux.die.net/man/8/ifconfig
				$m = array();
				preg_match( '/\s([0-9a-f]{2}(:[0-9a-f]{2}){5})\s/',
					wfShellExec( '/sbin/ifconfig -a' ), $m );
				$nodeId = isset( $m[1] ) ? str_replace( ':', '', $m[1] ) : '';
			}
			MediaWiki\restoreWarnings();
			if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
				$nodeId = MWCryptRand::generateHex( 12, true );
				$nodeId[1] = dechex( hexdec( $nodeId[1] ) | 0x1 ); // set multicast bit
			}
			file_put_contents( $this->nodeIdFile, $nodeId ); // cache
		}
		$this->nodeId32 = wfBaseConvert( substr( sha1( $nodeId ), 0, 8 ), 16, 2, 32 );
		$this->nodeId48 = wfBaseConvert( $nodeId, 16, 2, 48 );
		// If different processes run as different users, they may have different temp dirs.
		// This is dealt with by initializing the clock sequence number and counters randomly.
		$this->lockFile88 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-88';
		$this->lockFile128 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-128';
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
	 * Get a statistically unique 88-bit unsigned integer ID string.
	 * The bits of the UID are prefixed with the time (down to the millisecond).
	 *
	 * These IDs are suitable as values for the shard key of distributed data.
	 * If a column uses these as values, it should be declared UNIQUE to handle collisions.
	 * New rows almost always have higher UIDs, which makes B-TREE updates on INSERT fast.
	 * They can also be stored "DECIMAL(27) UNSIGNED" or BINARY(11) in MySQL.
	 *
	 * UID generation is serialized on each server (as the node ID is for the whole machine).
	 *
	 * @param int $base Specifies a base other than 10
	 * @return string Number
	 * @throws MWException
	 */
	public static function newTimestampedUID88( $base = 10 ) {
		Assert::parameterType( 'integer', $base, '$base' );
		Assert::parameter( $base <= 36, '$base', 'must be <= 36' );
		Assert::parameter( $base >= 2, '$base', 'must be >= 2' );

		$gen = self::singleton();
		$time = $gen->getTimestampAndDelay( 'lockFile88', 1, 1024 );

		return wfBaseConvert( $gen->getTimestampedID88( $time ), 2, $base );
	}

	/**
	 * @param array $info (UIDGenerator::millitime(), counter, clock sequence)
	 * @return string 88 bits
	 * @throws MWException
	 */
	protected function getTimestampedID88( array $info ) {
		list( $time, $counter ) = $info;
		// Take the 46 MSBs of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $time );
		// Add a 10 bit counter resulting in 56 bits total
		$id_bin .= str_pad( decbin( $counter ), 10, '0', STR_PAD_LEFT );
		// Add the 32 bit node ID resulting in 88 bits total
		$id_bin .= $this->nodeId32;
		// Convert to a 1-27 digit integer string
		if ( strlen( $id_bin ) !== 88 ) {
			throw new MWException( "Detected overflow for millisecond timestamp." );
		}

		return $id_bin;
	}

	/**
	 * Get a statistically unique 128-bit unsigned integer ID string.
	 * The bits of the UID are prefixed with the time (down to the millisecond).
	 *
	 * These IDs are suitable as globally unique IDs, without any enforced uniqueness.
	 * New rows almost always have higher UIDs, which makes B-TREE updates on INSERT fast.
	 * They can also be stored as "DECIMAL(39) UNSIGNED" or BINARY(16) in MySQL.
	 *
	 * UID generation is serialized on each server (as the node ID is for the whole machine).
	 *
	 * @param int $base Specifies a base other than 10
	 * @return string Number
	 * @throws MWException
	 */
	public static function newTimestampedUID128( $base = 10 ) {
		Assert::parameterType( 'integer', $base, '$base' );
		Assert::parameter( $base <= 36, '$base', 'must be <= 36' );
		Assert::parameter( $base >= 2, '$base', 'must be >= 2' );

		$gen = self::singleton();
		$time = $gen->getTimestampAndDelay( 'lockFile128', 16384, 1048576 );

		return wfBaseConvert( $gen->getTimestampedID128( $time ), 2, $base );
	}

	/**
	 * @param array $info (UIDGenerator::millitime(), counter, clock sequence)
	 * @return string 128 bits
	 * @throws MWException
	 */
	protected function getTimestampedID128( array $info ) {
		list( $time, $counter, $clkSeq ) = $info;
		// Take the 46 MSBs of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $time );
		// Add a 20 bit counter resulting in 66 bits total
		$id_bin .= str_pad( decbin( $counter ), 20, '0', STR_PAD_LEFT );
		// Add a 14 bit clock sequence number resulting in 80 bits total
		$id_bin .= str_pad( decbin( $clkSeq ), 14, '0', STR_PAD_LEFT );
		// Add the 48 bit node ID resulting in 128 bits total
		$id_bin .= $this->nodeId48;
		// Convert to a 1-39 digit integer string
		if ( strlen( $id_bin ) !== 128 ) {
			throw new MWException( "Detected overflow for millisecond timestamp." );
		}

		return $id_bin;
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @param int $flags Bitfield (supports UIDGenerator::QUICK_RAND)
	 * @return string
	 * @throws MWException
	 */
	public static function newUUIDv4( $flags = 0 ) {
		$hex = ( $flags & self::QUICK_RAND )
			? wfRandomString( 31 )
			: MWCryptRand::generateHex( 31 );

		return sprintf( '%s-%s-%s-%s-%s',
			// "time_low" (32 bits)
			substr( $hex, 0, 8 ),
			// "time_mid" (16 bits)
			substr( $hex, 8, 4 ),
			// "time_hi_and_version" (16 bits)
			'4' . substr( $hex, 12, 3 ),
			// "clk_seq_hi_res (8 bits, variant is binary 10x) and "clk_seq_low" (8 bits)
			dechex( 0x8 | ( hexdec( $hex[15] ) & 0x3 ) ) . $hex[16] . substr( $hex, 17, 2 ),
			// "node" (48 bits)
			substr( $hex, 19, 12 )
		);
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @param int $flags Bitfield (supports UIDGenerator::QUICK_RAND)
	 * @return string 32 hex characters with no hyphens
	 * @throws MWException
	 */
	public static function newRawUUIDv4( $flags = 0 ) {
		return str_replace( '-', '', self::newUUIDv4( $flags ) );
	}

	/**
	 * Return an ID that is sequential *only* for this node and bucket
	 *
	 * These IDs are suitable for per-host sequence numbers, e.g. for some packet protocols.
	 * If UIDGenerator::QUICK_VOLATILE is used the counter might reset on server restart.
	 *
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (<=48) of resulting numbers before wrap-around
	 * @param int $flags (supports UIDGenerator::QUICK_VOLATILE)
	 * @return float Integer value as float
	 * @since 1.23
	 */
	public static function newSequentialPerNodeID( $bucket, $bits = 48, $flags = 0 ) {
		return current( self::newSequentialPerNodeIDs( $bucket, $bits, 1, $flags ) );
	}

	/**
	 * Return IDs that are sequential *only* for this node and bucket
	 *
	 * @see UIDGenerator::newSequentialPerNodeID()
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (16 to 48) of resulting numbers before wrap-around
	 * @param int $count Number of IDs to return (1 to 10000)
	 * @param int $flags (supports UIDGenerator::QUICK_VOLATILE)
	 * @return array Ordered list of float integer values
	 * @since 1.23
	 */
	public static function newSequentialPerNodeIDs( $bucket, $bits, $count, $flags = 0 ) {
		$gen = self::singleton();
		return $gen->getSequentialPerNodeIDs( $bucket, $bits, $count, $flags );
	}

	/**
	 * Return IDs that are sequential *only* for this node and bucket
	 *
	 * @see UIDGenerator::newSequentialPerNodeID()
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (16 to 48) of resulting numbers before wrap-around
	 * @param int $count Number of IDs to return (1 to 10000)
	 * @param int $flags (supports UIDGenerator::QUICK_VOLATILE)
	 * @return array Ordered list of float integer values
	 * @throws MWException
	 */
	protected function getSequentialPerNodeIDs( $bucket, $bits, $count, $flags ) {
		if ( $count <= 0 ) {
			return array(); // nothing to do
		} elseif ( $count > 10000 ) {
			throw new MWException( "Number of requested IDs ($count) is too high." );
		} elseif ( $bits < 16 || $bits > 48 ) {
			throw new MWException( "Requested bit size ($bits) is out of range." );
		}

		$counter = null; // post-increment persistent counter value

		// Use APC/eAccelerator/xcache if requested, available, and not in CLI mode;
		// Counter values would not survive accross script instances in CLI mode.
		$cache = null;
		if ( ( $flags & self::QUICK_VOLATILE ) && PHP_SAPI !== 'cli' ) {
			try {
				$cache = ObjectCache::newAccelerator();
			} catch ( Exception $e ) {
				// not supported
			}
		}
		if ( $cache ) {
			$counter = $cache->incr( $bucket, $count );
			if ( $counter === false ) {
				if ( !$cache->add( $bucket, (int)$count ) ) {
					throw new MWException( 'Unable to set value to ' . get_class( $cache ) );
				}
				$counter = $count;
			}
		}

		// Note: use of fmod() avoids "division by zero" on 32 bit machines
		if ( $counter === null ) {
			$path = wfTempDir() . '/mw-' . __CLASS__ . '-' . rawurlencode( $bucket ) . '-48';
			// Get the UID lock file handle
			if ( isset( $this->fileHandles[$path] ) ) {
				$handle = $this->fileHandles[$path];
			} else {
				$handle = fopen( $path, 'cb+' );
				$this->fileHandles[$path] = $handle ?: null; // cache
			}
			// Acquire the UID lock file
			if ( $handle === false ) {
				throw new MWException( "Could not open '{$path}'." );
			} elseif ( !flock( $handle, LOCK_EX ) ) {
				fclose( $handle );
				throw new MWException( "Could not acquire '{$path}'." );
			}
			// Fetch the counter value and increment it...
			rewind( $handle );
			$counter = floor( trim( fgets( $handle ) ) ) + $count; // fetch as float
			// Write back the new counter value
			ftruncate( $handle, 0 );
			rewind( $handle );
			fwrite( $handle, fmod( $counter, pow( 2, 48 ) ) ); // warp-around as needed
			fflush( $handle );
			// Release the UID lock file
			flock( $handle, LOCK_UN );
		}

		$ids = array();
		$divisor = pow( 2, $bits );
		$currentId = floor( $counter - $count ); // pre-increment counter value
		for ( $i = 0; $i < $count; ++$i ) {
			$ids[] = fmod( ++$currentId, $divisor );
		}

		return $ids;
	}

	/**
	 * Get a (time,counter,clock sequence) where (time,counter) is higher
	 * than any previous (time,counter) value for the given clock sequence.
	 * This is useful for making UIDs sequential on a per-node bases.
	 *
	 * @param string $lockFile Name of a local lock file
	 * @param int $clockSeqSize The number of possible clock sequence values
	 * @param int $counterSize The number of possible counter values
	 * @return array (result of UIDGenerator::millitime(), counter, clock sequence)
	 * @throws MWException
	 */
	protected function getTimestampAndDelay( $lockFile, $clockSeqSize, $counterSize ) {
		// Get the UID lock file handle
		$path = $this->$lockFile;
		if ( isset( $this->fileHandles[$path] ) ) {
			$handle = $this->fileHandles[$path];
		} else {
			$handle = fopen( $path, 'cb+' );
			$this->fileHandles[$path] = $handle ?: null; // cache
		}
		// Acquire the UID lock file
		if ( $handle === false ) {
			throw new MWException( "Could not open '{$this->$lockFile}'." );
		} elseif ( !flock( $handle, LOCK_EX ) ) {
			fclose( $handle );
			throw new MWException( "Could not acquire '{$this->$lockFile}'." );
		}
		// Get the current timestamp, clock sequence number, last time, and counter
		rewind( $handle );
		$data = explode( ' ', fgets( $handle ) ); // "<clk seq> <sec> <msec> <counter> <offset>"
		$clockChanged = false; // clock set back significantly?
		if ( count( $data ) == 5 ) { // last UID info already initialized
			$clkSeq = (int)$data[0] % $clockSeqSize;
			$prevTime = array( (int)$data[1], (int)$data[2] );
			$offset = (int)$data[4] % $counterSize; // random counter offset
			$counter = 0; // counter for UIDs with the same timestamp
			// Delay until the clock reaches the time of the last ID.
			// This detects any microtime() drift among processes.
			$time = $this->timeWaitUntil( $prevTime );
			if ( !$time ) { // too long to delay?
				$clockChanged = true; // bump clock sequence number
				$time = self::millitime();
			} elseif ( $time == $prevTime ) {
				// Bump the counter if there are timestamp collisions
				$counter = (int)$data[3] % $counterSize;
				if ( ++$counter >= $counterSize ) { // sanity (starts at 0)
					flock( $handle, LOCK_UN ); // abort
					throw new MWException( "Counter overflow for timestamp value." );
				}
			}
		} else { // last UID info not initialized
			$clkSeq = mt_rand( 0, $clockSeqSize - 1 );
			$counter = 0;
			$offset = mt_rand( 0, $counterSize - 1 );
			$time = self::millitime();
		}
		// microtime() and gettimeofday() can drift from time() at least on Windows.
		// The drift is immediate for processes running while the system clock changes.
		// time() does not have this problem. See https://bugs.php.net/bug.php?id=42659.
		if ( abs( time() - $time[0] ) >= 2 ) {
			// We don't want processes using too high or low timestamps to avoid duplicate
			// UIDs and clock sequence number churn. This process should just be restarted.
			flock( $handle, LOCK_UN ); // abort
			throw new MWException( "Process clock is outdated or drifted." );
		}
		// If microtime() is synced and a clock change was detected, then the clock went back
		if ( $clockChanged ) {
			// Bump the clock sequence number and also randomize the counter offset,
			// which is useful for UIDs that do not include the clock sequence number.
			$clkSeq = ( $clkSeq + 1 ) % $clockSeqSize;
			$offset = mt_rand( 0, $counterSize - 1 );
			trigger_error( "Clock was set back; sequence number incremented." );
		}
		// Update the (clock sequence number, timestamp, counter)
		ftruncate( $handle, 0 );
		rewind( $handle );
		fwrite( $handle, "{$clkSeq} {$time[0]} {$time[1]} {$counter} {$offset}" );
		fflush( $handle );
		// Release the UID lock file
		flock( $handle, LOCK_UN );

		return array( $time, ( $counter + $offset ) % $counterSize, $clkSeq );
	}

	/**
	 * Wait till the current timestamp reaches $time and return the current
	 * timestamp. This returns false if it would have to wait more than 10ms.
	 *
	 * @param array $time Result of UIDGenerator::millitime()
	 * @return array|bool UIDGenerator::millitime() result or false
	 */
	protected function timeWaitUntil( array $time ) {
		do {
			$ct = self::millitime();
			if ( $ct >= $time ) { // http://php.net/manual/en/language.operators.comparison.php
				return $ct; // current timestamp is higher than $time
			}
		} while ( ( ( $time[0] - $ct[0] ) * 1000 + ( $time[1] - $ct[1] ) ) <= 10 );

		return false;
	}

	/**
	 * @param array $time Result of UIDGenerator::millitime()
	 * @return string 46 MSBs of "milliseconds since epoch" in binary (rolls over in 4201)
	 * @throws MWException
	 */
	protected function millisecondsSinceEpochBinary( array $time ) {
		list( $sec, $msec ) = $time;
		$ts = 1000 * $sec + $msec;
		if ( $ts > pow( 2, 52 ) ) {
			throw new MWException( __METHOD__ .
				': sorry, this function doesn\'t work after the year 144680' );
		}

		return substr( wfBaseConvert( $ts, 10, 2, 46 ), -46 );
	}

	/**
	 * @return array (current time in seconds, milliseconds since then)
	 */
	protected static function millitime() {
		list( $msec, $sec ) = explode( ' ', microtime() );

		return array( (int)$sec, (int)( $msec * 1000 ) );
	}

	/**
	 * Delete all cache files that have been created.
	 *
	 * This is a cleanup method primarily meant to be used from unit tests to
	 * avoid poluting the local filesystem. If used outside of a unit test
	 * environment it should be used with caution as it may destroy state saved
	 * in the files.
	 *
	 * @see unitTestTearDown
	 * @since 1.23
	 */
	protected function deleteCacheFiles() {
		// Bug: 44850
		foreach ( $this->fileHandles as $path => $handle ) {
			if ( $handle !== null ) {
				fclose( $handle );
			}
			if ( is_file( $path ) ) {
				unlink( $path );
			}
			unset( $this->fileHandles[$path] );
		}
		if ( is_file( $this->nodeIdFile ) ) {
			unlink( $this->nodeIdFile );
		}
	}

	/**
	 * Cleanup resources when tearing down after a unit test.
	 *
	 * This is a cleanup method primarily meant to be used from unit tests to
	 * avoid poluting the local filesystem. If used outside of a unit test
	 * environment it should be used with caution as it may destroy state saved
	 * in the files.
	 *
	 * @see deleteCacheFiles
	 * @since 1.23
	 */
	public static function unitTestTearDown() {
		// Bug: 44850
		$gen = self::singleton();
		$gen->deleteCacheFiles();
	}

	function __destruct() {
		array_map( 'fclose', array_filter( $this->fileHandles ) );
	}
}
