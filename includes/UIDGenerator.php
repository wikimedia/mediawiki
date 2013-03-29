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
 * @since 1.21
 */
class UIDGenerator {
	/** @var UIDGenerator */
	protected static $instance = null;

	protected $nodeId32; // string; node ID in binary (32 bits)
	protected $nodeId48; // string; node ID in binary (48 bits)

	protected $lockFile88; // string; local file path
	protected $lockFile128; // string; local file path
	protected $lockFileUUID; // string; local file path

	/** @var Array */
	protected $fileHandles = array(); // cache file handles

	const QUICK_RAND = 1; // get randomness from fast and insecure sources

	protected function __construct() {
		$idFile = wfTempDir() . '/mw-' . __CLASS__ . '-UID-nodeid';
		$nodeId = is_file( $idFile ) ? file_get_contents( $idFile ) : '';
		// Try to get some ID that uniquely identifies this machine (RFC 4122)...
		if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
			wfSuppressWarnings();
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
			wfRestoreWarnings();
			if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
				$nodeId = MWCryptRand::generateHex( 12, true );
				$nodeId[1] = dechex( hexdec( $nodeId[1] ) | 0x1 ); // set multicast bit
			}
			file_put_contents( $idFile, $nodeId ); // cache
		}
		$this->nodeId32 = wfBaseConvert( substr( sha1( $nodeId ), 0, 8 ), 16, 2, 32 );
		$this->nodeId48 = wfBaseConvert( $nodeId, 16, 2, 48 );
		// If different processes run as different users, they may have different temp dirs.
		// This is dealt with by initializing the clock sequence number and counters randomly.
		$this->lockFile88 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-88';
		$this->lockFile128 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-128';
		$this->lockFileUUID = wfTempDir() . '/mw-' . __CLASS__ . '-UUID-128';
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
	 * @param $base integer Specifies a base other than 10
	 * @return string Number
	 * @throws MWException
	 */
	public static function newTimestampedUID88( $base = 10 ) {
		if ( !is_integer( $base ) || $base > 36 || $base < 2 ) {
			throw new MWException( "Base must an integer be between 2 and 36" );
		}
		$gen = self::singleton();
		$info = $gen->getTimeAndDelay( 'lockFile88', 1, 1024, 1024 );
		$info['offsetCounter'] = $info['offsetCounter'] % 1024;
		return wfBaseConvert( $gen->getTimestampedID88( $info ), 2, $base );
	}

	/**
	 * @param array $info Result of UIDGenerator::getTimeAndDelay()
	 * @return string 88 bits
	 */
	protected function getTimestampedID88( array $info ) {
		// Take the 46 bits of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $info['time'] );
		// Add a 10 bit counter resulting in 56 bits total
		$id_bin .= str_pad( decbin( $info['offsetCounter'] ), 10, '0', STR_PAD_LEFT );
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
	 * @param $base integer Specifies a base other than 10
	 * @return string Number
	 * @throws MWException
	 */
	public static function newTimestampedUID128( $base = 10 ) {
		if ( !is_integer( $base ) || $base > 36 || $base < 2 ) {
			throw new MWException( "Base must be an integer between 2 and 36" );
		}
		$gen = self::singleton();
		$info = $gen->getTimeAndDelay( 'lockFile128', 16384, 1048576, 1048576 );
		$info['offsetCounter'] = $info['offsetCounter'] % 1048576;
		return wfBaseConvert( $gen->getTimestampedID128( $info ), 2, $base );
	}

	/**
	 * @param array $info Result of UIDGenerator::getTimeAndDelay()
	 * @return string 128 bits
	 */
	protected function getTimestampedID128( array $info ) {
		// Take the 46 bits of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $info['time'] );
		// Add a 20 bit counter resulting in 66 bits total
		$id_bin .= str_pad( decbin( $info['offsetCounter'] ), 20, '0', STR_PAD_LEFT );
		// Add a 14 bit clock sequence number resulting in 80 bits total
		$id_bin .= str_pad( decbin( $info['clkSeq'] ), 14, '0', STR_PAD_LEFT );
		// Add the 48 bit node ID resulting in 128 bits total
		$id_bin .= $this->nodeId48;
		// Convert to a 1-39 digit integer string
		if ( strlen( $id_bin ) !== 128 ) {
			throw new MWException( "Detected overflow for millisecond timestamp." );
		}
		return $id_bin;
	}

	/**
	 * Return an RFC4122 compliant v1 UUID
	 *
	 * @return string
	 * @throws MWException
	 * @since 1.22
	 */
	public static function newUUIDv1() {
		$gen = self::singleton();
		// There can be up to 10000 intervals for the same millisecond timestamp.
		// [0,4999] counter + [0,5000] offset is in [0,9999] for the offset counter.
		// Add this onto the timestamp to allow making up to 5000 IDs per second.
		return $gen->getUUIDv1( $gen->getTimeAndDelay( 'lockFileUUID', 16384, 5000, 5001 ) );
	}

	/**
	 * Return an RFC4122 compliant v1 UUID
	 *
	 * @return string 32 hex characters with no hyphens
	 * @throws MWException
	 * @since 1.22
	 */
	public static function newRawUUIDv1() {
		return str_replace( '-', '', self::newUUIDv1() );
	}

	/**
	 * @param array $info Result of UIDGenerator::getTimeAndDelay()
	 * @return string 128 bits
	 */
	protected function getUUIDv1( array $info ) {
		$clkSeq_bin = wfBaseConvert( $info['clkSeq'], 10, 2, 14 );
		$time_bin = $this->intervalsSinceGregorianBinary( $info['time'], $info['offsetCounter'] );
		// Take the 32 bits of "time low"
		$id_bin = substr( $time_bin, 28, 32 );
		// Add 16 bits of "time mid" resulting in 48 bits total
		$id_bin .= substr( $time_bin, 12, 16 );
		// Add 4 bit version resulting in 52 bits total
		$id_bin .= '0001';
		// Add 12 bits of "time high" resulting in 64 bits total
		$id_bin .= substr( $time_bin, 0, 12 );
		// Add 2 bits of "variant" resulting in 66 bits total
		$id_bin .= '10';
		// Add 6 bits of "clock seq high" resulting in 72 bits total
		$id_bin .= substr( $clkSeq_bin, 0, 6 );
		// Add 8 bits of "clock seq low" resulting in 80 bits total
		$id_bin .= substr( $clkSeq_bin, 6, 8 );
		// Add the 48 bit node ID resulting in 128 bits total
		$id_bin .= $this->nodeId48;
		// Convert to a 32 char hex string with dashes
		if ( strlen( $id_bin ) !== 128 ) {
			throw new MWException( "Detected overflow for millisecond timestamp." );
		}
		$hex = wfBaseConvert( $id_bin, 2, 16, 32 );
		return sprintf( '%s-%s-%s-%s-%s',
			// "time_low" (32 bits)
			substr( $hex, 0, 8 ),
			// "time_mid" (16 bits)
			substr( $hex, 8, 4 ),
			// "time_hi_and_version" (16 bits)
			substr( $hex, 12, 4 ),
			// "clk_seq_hi_res" (8 bits) and "clk_seq_low" (8 bits)
			substr( $hex, 16, 4 ),
			// "node" (48 bits)
			substr( $hex, 20, 12 )
		);
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @param $flags integer Bitfield (supports UIDGenerator::QUICK_RAND)
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
			// "clk_seq_hi_res" (8 bits, variant is binary 10x) and "clk_seq_low" (8 bits)
			dechex( 0x8 | ( hexdec( $hex[15] ) & 0x3 ) ) . $hex[16] . substr( $hex, 17, 2 ),
			// "node" (48 bits)
			substr( $hex, 19, 12 )
		);
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @param $flags integer Bitfield (supports UIDGenerator::QUICK_RAND)
	 * @return string 32 hex characters with no hyphens
	 * @throws MWException
	 */
	public static function newRawUUIDv4( $flags = 0 ) {
		return str_replace( '-', '', self::newUUIDv4( $flags ) );
	}

	/**
	 * Get a (time,counter,clock sequence, offset counter) where (time,counter)
	 * is higher than any previous (time,counter) value for the given clock sequence.
	 * This is useful for making UIDs sequential on a per-node bases. The offset counter
	 * is the counter offset by a randomly initialized amount specific to the clock sequence.
	 *
	 * @param string $lockFile Name of a local lock file
	 * @param $clockSeqSize integer The number of possible clock sequence values
	 * @param $counterSize integer The number of possible counter values
	 * @param $offsetSize integer The number of possible offset values
	 * @return Array
	 * @throws MWException
	 */
	protected function getTimeAndDelay( $lockFile, $clockSeqSize, $counterSize, $offsetSize ) {
		// Get the UID lock file handle
		if ( isset( $this->fileHandles[$lockFile] ) ) {
			$handle = $this->fileHandles[$lockFile];
		} else {
			$handle = fopen( $this->$lockFile, 'cb+' );
			$this->fileHandles[$lockFile] = $handle ?: null; // cache
		}
		// Acquire the UID lock file
		if ( $handle === false ) {
			throw new MWException( "Could not open '{$this->$lockFile}'." );
		} elseif ( !flock( $handle, LOCK_EX ) ) {
			throw new MWException( "Could not acquire '{$this->$lockFile}'." );
		}
		// Get the current timestamp, clock sequence number, last time, and counter
		rewind( $handle );
		$data = explode( ' ', fgets( $handle ) ); // "<clk seq> <sec> <msec> <counter> <offset>"
		$clockChanged = false; // clock set back significantly?
		if ( count( $data ) == 5 ) { // last UID info already initialized
			$clkSeq = (int) $data[0] % $clockSeqSize;
			$prevTime = array( (int) $data[1], (int) $data[2] );
			$offset = (int) $data[4] % $offsetSize; // random counter offset
			$counter = 0; // counter for UIDs with the same timestamp
			// Delay until the clock reaches the time of the last ID.
			// This detects any microtime() drift among processes.
			$time = $this->timeWaitUntil( $prevTime );
			if ( !$time ) { // too long to delay?
				$clockChanged = true; // bump clock sequence number
				$time = self::millitime();
			} elseif ( $time == $prevTime ) {
				// Bump the counter if there are timestamp collisions
				$counter = (int) $data[3] % $counterSize;
				if ( ++$counter >= $counterSize ) { // sanity (starts at 0)
					flock( $handle, LOCK_UN ); // abort
					throw new MWException( "Counter overflow for timestamp value." );
				}
			}
		} else { // last UID info not initialized
			$clkSeq = mt_rand( 0, $clockSeqSize - 1 );
			$counter = 0;
			$offset = mt_rand( 0, $offsetSize - 1 );
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
			$offset = mt_rand( 0, $offsetSize - 1 );
			trigger_error( "Clock was set back; sequence number incremented." );
		}
		// Update the (clock sequence number, timestamp, counter)
		ftruncate( $handle, 0 );
		rewind( $handle );
		fwrite( $handle, "{$clkSeq} {$time[0]} {$time[1]} {$counter} {$offset}" );
		fflush( $handle );
		// Release the UID lock file
		flock( $handle, LOCK_UN );

		return array(
			'time'          => $time,
			'counter'       => $counter,
			'clkSeq'        => $clkSeq,
			'offset'        => $offset,
			'offsetCounter' => $counter + $offset
		);
	}

	/**
	 * Wait till the current timestamp reaches $time and return the current
	 * timestamp. This returns false if it would have to wait more than 10ms.
	 *
	 * @param array $time Result of UIDGenerator::millitime()
	 * @return Array|bool UIDGenerator::millitime() result or false
	 */
	protected function timeWaitUntil( array $time ) {
		do {
			$ct = self::millitime();
			if ( $ct >= $time ) { // http://php.net/manual/en/language.operators.comparison.php
				return $ct;
			}
		} while ( ( ( $time[0] - $ct[0] )*1000 + ( $time[1] - $ct[1] ) ) <= 10 );

		return false;
	}

	/**
	 * @param array $time Result of UIDGenerator::millitime()
	 * @return string 46 bits of "milliseconds since epoch" in binary (rolls over in 4201)
	 */
	protected function millisecondsSinceEpochBinary( array $time ) {
		list( $sec, $msec ) = $time;
		if ( PHP_INT_SIZE >= 8 ) { // 64 bit integers
			$ts = ( 1000 * $sec + $msec );
			$id_bin = str_pad( decbin( $ts % pow( 2, 46 ) ), 46, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'gmp' ) ) {
			$ts = gmp_mod( // wrap around
				gmp_add( gmp_mul( (string) $sec, '1000' ), (string) $msec ),
				gmp_pow( '2', '46' )
			);
			$id_bin = str_pad( gmp_strval( $ts, 2 ), 46, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'bcmath' ) ) {
			$ts = bcmod( // wrap around
				bcadd( bcmul( $sec, 1000 ), $msec ),
				bcpow( 2, 46 )
			);
			$id_bin = wfBaseConvert( $ts, 10, 2, 46 );
		} else {
			throw new MWException( 'bcmath or gmp extension required for 32 bit machines.' );
		}
		return $id_bin;
	}

	/**
	 * @param array $time Result of UIDGenerator::millitime()
	 * @param integer $delta Number of intervals to add on to the timestamp
	 * @return string 60 bits of "100ns intervals since 15 October 1582" (rolls over in 3400)
	 */
	protected function intervalsSinceGregorianBinary( array $time, $delta = 0 ) {
		list( $sec, $msec ) = $time;
		$offset = '122192928000000000';
		if ( PHP_INT_SIZE >= 8 ) { // 64 bit integers
			$ts = ( 1000 * $sec + $msec ) * 10000 + $offset + $delta;
			$id_bin = str_pad( decbin( $ts % pow( 2, 60 ) ), 60, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'gmp' ) ) {
			$ts = gmp_add( gmp_mul( (string) $sec, '1000' ), (string) $msec ); // ms
			$ts = gmp_add( gmp_mul( $ts, '10000' ), $offset ); // 100ns intervals
			$ts = gmp_add( $ts, (string) $delta );
			$ts = gmp_mod( $ts, gmp_pow( '2', '60' ) ); // wrap around
			$id_bin = str_pad( gmp_strval( $ts, 2 ), 60, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'bcmath' ) ) {
			$ts = bcadd( bcmul( $sec, 1000 ), $msec ); // ms
			$ts = bcadd( bcmul( $ts, 10000 ), $offset ); // 100ns intervals
			$ts = bcadd( $ts, $delta );
			$ts = bcmod( $ts, bcpow( 2, 60 ) ); // wrap around
			$id_bin = wfBaseConvert( $ts, 10, 2, 60 );
		} else {
			throw new MWException( 'bcmath or gmp extension required for 32 bit machines.' );
		}
		return $id_bin;
	}

	/**
	 * @return Array (current time in seconds, milliseconds since then)
	 */
	protected static function millitime() {
		list( $msec, $sec ) = explode( ' ', microtime() );
		return array( (int) $sec, (int) ( $msec * 1000 ) );
	}

	function __destruct() {
		array_map( 'fclose', $this->fileHandles );
	}
}
