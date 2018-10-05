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
 */
use Wikimedia\Assert\Assert;
use MediaWiki\MediaWikiServices;

/**
 * Class for getting statistically unique IDs
 *
 * @since 1.21
 */
class UIDGenerator {
	/** @var UIDGenerator */
	protected static $instance = null;
	/** @var string Local file path */
	protected $nodeIdFile;
	/** @var string Node ID in binary (32 bits) */
	protected $nodeId32;
	/** @var string Node ID in binary (48 bits) */
	protected $nodeId48;

	/** @var string Local file path */
	protected $lockFile88;
	/** @var string Local file path */
	protected $lockFile128;
	/** @var string Local file path */
	protected $lockFileUUID;

	/** @var array Cached file handles */
	protected $fileHandles = []; // cached file handles

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
			Wikimedia\suppressWarnings();
			if ( wfIsWindows() ) {
				// https://technet.microsoft.com/en-us/library/bb490913.aspx
				$csv = trim( wfShellExec( 'getmac /NH /FO CSV' ) );
				$line = substr( $csv, 0, strcspn( $csv, "\n" ) );
				$info = str_getcsv( $line );
				$nodeId = isset( $info[0] ) ? str_replace( '-', '', $info[0] ) : '';
			} elseif ( is_executable( '/sbin/ifconfig' ) ) { // Linux/BSD/Solaris/OS X
				// See https://linux.die.net/man/8/ifconfig
				$m = [];
				preg_match( '/\s([0-9a-f]{2}(:[0-9a-f]{2}){5})\s/',
					wfShellExec( '/sbin/ifconfig -a' ), $m );
				$nodeId = isset( $m[1] ) ? str_replace( ':', '', $m[1] ) : '';
			}
			Wikimedia\restoreWarnings();
			if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
				$nodeId = MWCryptRand::generateHex( 12, true );
				$nodeId[1] = dechex( hexdec( $nodeId[1] ) | 0x1 ); // set multicast bit
			}
			file_put_contents( $this->nodeIdFile, $nodeId ); // cache
		}
		$this->nodeId32 = Wikimedia\base_convert( substr( sha1( $nodeId ), 0, 8 ), 16, 2, 32 );
		$this->nodeId48 = Wikimedia\base_convert( $nodeId, 16, 2, 48 );
		// If different processes run as different users, they may have different temp dirs.
		// This is dealt with by initializing the clock sequence number and counters randomly.
		$this->lockFile88 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-88';
		$this->lockFile128 = wfTempDir() . '/mw-' . __CLASS__ . '-UID-128';
		$this->lockFileUUID = wfTempDir() . '/mw-' . __CLASS__ . '-UUID-128';
	}

	/**
	 * @todo move to MW-specific factory class and inject temp dir
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
	 * @throws RuntimeException
	 */
	public static function newTimestampedUID88( $base = 10 ) {
		Assert::parameterType( 'integer', $base, '$base' );
		Assert::parameter( $base <= 36, '$base', 'must be <= 36' );
		Assert::parameter( $base >= 2, '$base', 'must be >= 2' );

		$gen = self::singleton();
		$info = $gen->getTimeAndDelay( 'lockFile88', 1, 1024, 1024 );
		$info['offsetCounter'] = $info['offsetCounter'] % 1024;
		return Wikimedia\base_convert( $gen->getTimestampedID88( $info ), 2, $base );
	}

	/**
	 * @param array $info result of UIDGenerator::getTimeAndDelay(), or
	 *  for sub classes, a seqencial array like (time, offsetCounter).
	 * @return string 88 bits
	 * @throws RuntimeException
	 */
	protected function getTimestampedID88( array $info ) {
		if ( isset( $info['time'] ) ) {
			$time = $info['time'];
			$counter = $info['offsetCounter'];
		} else {
			$time = $info[0];
			$counter = $info[1];
		}
		// Take the 46 LSBs of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $time );
		// Add a 10 bit counter resulting in 56 bits total
		$id_bin .= str_pad( decbin( $counter ), 10, '0', STR_PAD_LEFT );
		// Add the 32 bit node ID resulting in 88 bits total
		$id_bin .= $this->nodeId32;
		// Convert to a 1-27 digit integer string
		if ( strlen( $id_bin ) !== 88 ) {
			throw new RuntimeException( "Detected overflow for millisecond timestamp." );
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
	 * @throws RuntimeException
	 */
	public static function newTimestampedUID128( $base = 10 ) {
		Assert::parameterType( 'integer', $base, '$base' );
		Assert::parameter( $base <= 36, '$base', 'must be <= 36' );
		Assert::parameter( $base >= 2, '$base', 'must be >= 2' );

		$gen = self::singleton();
		$info = $gen->getTimeAndDelay( 'lockFile128', 16384, 1048576, 1048576 );
		$info['offsetCounter'] = $info['offsetCounter'] % 1048576;

		return Wikimedia\base_convert( $gen->getTimestampedID128( $info ), 2, $base );
	}

	/**
	 * @param array $info The result of UIDGenerator::getTimeAndDelay(),
	 *  for sub classes, a seqencial array like (time, offsetCounter, clkSeq).
	 * @return string 128 bits
	 * @throws RuntimeException
	 */
	protected function getTimestampedID128( array $info ) {
		if ( isset( $info['time'] ) ) {
			$time = $info['time'];
			$counter = $info['offsetCounter'];
			$clkSeq = $info['clkSeq'];
		} else {
			$time = $info[0];
			$counter = $info[1];
			$clkSeq = $info[2];
		}
		// Take the 46 LSBs of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $time );
		// Add a 20 bit counter resulting in 66 bits total
		$id_bin .= str_pad( decbin( $counter ), 20, '0', STR_PAD_LEFT );
		// Add a 14 bit clock sequence number resulting in 80 bits total
		$id_bin .= str_pad( decbin( $clkSeq ), 14, '0', STR_PAD_LEFT );
		// Add the 48 bit node ID resulting in 128 bits total
		$id_bin .= $this->nodeId48;
		// Convert to a 1-39 digit integer string
		if ( strlen( $id_bin ) !== 128 ) {
			throw new RuntimeException( "Detected overflow for millisecond timestamp." );
		}

		return $id_bin;
	}

	/**
	 * Return an RFC4122 compliant v1 UUID
	 *
	 * @return string
	 * @throws RuntimeException
	 * @since 1.27
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
	 * @throws RuntimeException
	 * @since 1.27
	 */
	public static function newRawUUIDv1() {
		return str_replace( '-', '', self::newUUIDv1() );
	}

	/**
	 * @param array $info Result of UIDGenerator::getTimeAndDelay()
	 * @return string 128 bits
	 */
	protected function getUUIDv1( array $info ) {
		$clkSeq_bin = Wikimedia\base_convert( $info['clkSeq'], 10, 2, 14 );
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
			throw new RuntimeException( "Detected overflow for millisecond timestamp." );
		}
		$hex = Wikimedia\base_convert( $id_bin, 2, 16, 32 );
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
	 * @param int $flags Bitfield (supports UIDGenerator::QUICK_RAND)
	 * @return string
	 * @throws RuntimeException
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
	 * @param int $flags Bitfield (supports UIDGenerator::QUICK_RAND)
	 * @return string 32 hex characters with no hyphens
	 * @throws RuntimeException
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
	 * @param int $count Number of IDs to return
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
	 * @param int $count Number of IDs to return
	 * @param int $flags (supports UIDGenerator::QUICK_VOLATILE)
	 * @return array Ordered list of float integer values
	 * @throws RuntimeException
	 */
	protected function getSequentialPerNodeIDs( $bucket, $bits, $count, $flags ) {
		if ( $count <= 0 ) {
			return []; // nothing to do
		}
		if ( $bits < 16 || $bits > 48 ) {
			throw new RuntimeException( "Requested bit size ($bits) is out of range." );
		}

		$counter = null; // post-increment persistent counter value

		// Use APC/etc if requested, available, and not in CLI mode;
		// Counter values would not survive across script instances in CLI mode.
		$cache = null;
		if ( ( $flags & self::QUICK_VOLATILE ) && !wfIsCLI() ) {
			$cache = MediaWikiServices::getInstance()->getLocalServerObjectCache();
		}
		if ( $cache ) {
			$counter = $cache->incrWithInit( $bucket, $cache::TTL_INDEFINITE, $count, $count );
			if ( $counter === false ) {
				throw new RuntimeException( 'Unable to set value to ' . get_class( $cache ) );
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
				throw new RuntimeException( "Could not open '{$path}'." );
			}
			if ( !flock( $handle, LOCK_EX ) ) {
				fclose( $handle );
				throw new RuntimeException( "Could not acquire '{$path}'." );
			}
			// Fetch the counter value and increment it...
			rewind( $handle );
			$counter = floor( trim( fgets( $handle ) ) ) + $count; // fetch as float
			// Write back the new counter value
			ftruncate( $handle, 0 );
			rewind( $handle );
			fwrite( $handle, fmod( $counter, 2 ** 48 ) ); // warp-around as needed
			fflush( $handle );
			// Release the UID lock file
			flock( $handle, LOCK_UN );
		}

		$ids = [];
		$divisor = 2 ** $bits;
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
	 * @param int $offsetSize The number of possible offset values
	 * @return array Array with the following keys:
	 *  - array 'time': array of seconds int and milliseconds int.
	 *  - int 'counter'.
	 *  - int 'clkSeq'.
	 *  - int 'offset': .
	 *  - int 'offsetCounter'.
	 * @throws RuntimeException
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
			throw new RuntimeException( "Could not open '{$this->$lockFile}'." );
		}
		if ( !flock( $handle, LOCK_EX ) ) {
			fclose( $handle );
			throw new RuntimeException( "Could not acquire '{$this->$lockFile}'." );
		}

		// The formatters that use this method expect a timestamp with millisecond
		// precision and a counter upto a certain size. When more IDs than the counter
		// size are generated during the same timestamp, an exception is thrown as we
		// cannot increment further, because the formatted ID would not have enough
		// bits to fit the counter.
		//
		// To orchestrate this between independant PHP processes on the same hosts,
		// we must have a common sense of time so that we only have to maintain
		// a single counter in a single lock file.
		//
		// Given that:
		// * The system clock can be observed via time(), without milliseconds.
		// * Some other clock can be observed via microtime(), which also offers
		//   millisecond precision.
		// * microtime() drifts in-process further and further away from the system
		//   clock the longer the longer the process runs for.
		//   For example, on 2018-10-03 an HHVM 3.18 JobQueue process at WMF,
		//   that ran for 9 min 55 sec, drifted 7 seconds.
		//   The drift is immediate for processes running while the system clock changes.
		//   time() does not have this problem. See https://bugs.php.net/bug.php?id=42659.
		//
		// We have two choices:
		//
		// 1. Use microtime() with the following caveats:
		//    - The last stored time may be in the future, or our current time
		//    may be in the past, in which case we'll frequently enter the slow
		//    timeWaitUntil() method to try and "sync" the current process with
		//    the previous process. We mustn't block for long though, max 10ms?
		//    - For any drift above 10ms, we pretend that the clock went backwards,
		//    and treat it the same way as after an NTP sync, by incrementing clock
		//    sequence instead. Given this rolls over automatically and silently
		//    and is meant to be rare, this is essentially sacrifices a reasonable
		//    guarantee of uniqueness.
		//    - For long running processes (e.g. longer than a few seconds) the drift
		//    can easily be more than 2 seconds. Because we only have a single lock
		//    file and don't want to keep too many counters and deal with clearing
		//    those, we fatal the user and refuse to make an ID. (T94522)
		// 2. Use time() and expand the counter by 1000x and use the first digits
		//    as if they are the millisecond fraction of the timestamp.
		//    Known caveats or perf impact: None.
		//
		// We choose the latter.
		$msecCounterSize = $counterSize * 1000;

		rewind( $handle );
		// Format of lock file contents:
		// "<clk seq> <sec> <msec counter> <rand offset>"
		$data = explode( ' ', fgets( $handle ) );

		if ( count( $data ) === 4 ) {
			// The UID lock file was already initialized
			$clkSeq = (int)$data[0] % $clockSeqSize;
			$prevSec = (int)$data[1];
			// Counter for UIDs with the same timestamp,
			$msecCounter = 0;
			$randOffset = (int)$data[3] % $counterSize;

			// If the system clock moved backwards by an NTP sync,
			// or if the last writer process had its clock drift ahead,
			// Try to catch up if the gap is small, so that we can keep a single
			// monotonic logic file.
			$sec = $this->timeWaitUntil( $prevSec );
			if ( $sec === false ) {
				// Gap is too big. Looks like the clock got moved back significantly.
				// Start a new clock sequence, and re-randomize the extra offset,
				// which is useful for UIDs that do not include the clock sequence number.
				$clkSeq = ( $clkSeq + 1 ) % $clockSeqSize;
				$sec = time();
				$randOffset = mt_rand( 0, $offsetSize - 1 );
				trigger_error( "Clock was set back; sequence number incremented." );
			} elseif ( $sec === $prevSec ) {
				// Sanity check, only keep remainder if a previous writer wrote
				// something here that we don't accept.
				$msecCounter = (int)$data[2] % $msecCounterSize;
				// Bump the counter if the time has not changed yet
				if ( ++$msecCounter >= $msecCounterSize ) {
					// More IDs generated with the same time than counterSize can accomodate
					flock( $handle, LOCK_UN );
					throw new RuntimeException( "Counter overflow for timestamp value." );
				}
			}
		} else {
			// Initialize UID lock file information
			$clkSeq = mt_rand( 0, $clockSeqSize - 1 );
			$sec = time();
			$msecCounter = 0;
			$randOffset = mt_rand( 0, $offsetSize - 1 );
		}

		// Update and release the UID lock file
		ftruncate( $handle, 0 );
		rewind( $handle );
		fwrite( $handle, "{$clkSeq} {$sec} {$msecCounter} {$randOffset}" );
		fflush( $handle );
		flock( $handle, LOCK_UN );

		// Split msecCounter back into msec and counter
		$msec = (int)( $msecCounter / 1000 );
		$counter = $msecCounter % 1000;

		return [
			'time'          => [ $sec, $msec ],
			'counter'       => $counter,
			'clkSeq'        => $clkSeq,
			'offset'        => $randOffset,
			'offsetCounter' => $counter + $randOffset,
		];
	}

	/**
	 * Wait till the current timestamp reaches $time and return the current
	 * timestamp. This returns false if it would have to wait more than 10ms.
	 *
	 * @param int $time Result of time()
	 * @return int|bool Timestamp or false
	 */
	protected function timeWaitUntil( $time ) {
		$start = microtime( true );
		do {
			$ct = time();
			// https://secure.php.net/manual/en/language.operators.comparison.php
			if ( $ct >= $time ) {
				// current time is higher than or equal to than $time
				return $ct;
			}
		} while ( ( microtime( true ) - $start ) <= 0.010 ); // upto 10ms

		return false;
	}

	/**
	 * @param array $time Array of second and millisecond integers
	 * @return string 46 LSBs of "milliseconds since epoch" in binary (rolls over in 4201)
	 * @throws RuntimeException
	 */
	protected function millisecondsSinceEpochBinary( array $time ) {
		list( $sec, $msec ) = $time;
		$ts = 1000 * $sec + $msec;
		if ( $ts > 2 ** 52 ) {
			throw new RuntimeException( __METHOD__ .
				': sorry, this function doesn\'t work after the year 144680' );
		}

		return substr( Wikimedia\base_convert( $ts, 10, 2, 46 ), -46 );
	}

	/**
	 * @param array $time Array of second and millisecond integers
	 * @param int $delta Number of intervals to add on to the timestamp
	 * @return string 60 bits of "100ns intervals since 15 October 1582" (rolls over in 3400)
	 * @throws RuntimeException
	 */
	protected function intervalsSinceGregorianBinary( array $time, $delta = 0 ) {
		list( $sec, $msec ) = $time;
		$offset = '122192928000000000';
		if ( PHP_INT_SIZE >= 8 ) { // 64 bit integers
			$ts = ( 1000 * $sec + $msec ) * 10000 + (int)$offset + $delta;
			$id_bin = str_pad( decbin( $ts % ( 2 ** 60 ) ), 60, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'gmp' ) ) {
			$ts = gmp_add( gmp_mul( (string)$sec, '1000' ), (string)$msec ); // ms
			$ts = gmp_add( gmp_mul( $ts, '10000' ), $offset ); // 100ns intervals
			$ts = gmp_add( $ts, (string)$delta );
			$ts = gmp_mod( $ts, gmp_pow( '2', '60' ) ); // wrap around
			$id_bin = str_pad( gmp_strval( $ts, 2 ), 60, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'bcmath' ) ) {
			$ts = bcadd( bcmul( $sec, 1000 ), $msec ); // ms
			$ts = bcadd( bcmul( $ts, 10000 ), $offset ); // 100ns intervals
			$ts = bcadd( $ts, $delta );
			$ts = bcmod( $ts, bcpow( 2, 60 ) ); // wrap around
			$id_bin = Wikimedia\base_convert( $ts, 10, 2, 60 );
		} else {
			throw new RuntimeException( 'bcmath or gmp extension required for 32 bit machines.' );
		}
		return $id_bin;
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
	 * @codeCoverageIgnore
	 */
	private function deleteCacheFiles() {
		// T46850
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
	 * @internal For use by unit tests
	 * @see deleteCacheFiles
	 * @since 1.23
	 * @codeCoverageIgnore
	 */
	public static function unitTestTearDown() {
		// T46850
		$gen = self::singleton();
		$gen->deleteCacheFiles();
	}

	function __destruct() {
		array_map( 'fclose', array_filter( $this->fileHandles ) );
	}
}
