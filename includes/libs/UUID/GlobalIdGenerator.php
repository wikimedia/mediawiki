<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\UUID;

use InvalidArgumentException;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Class for getting statistically unique IDs without a central coordinator
 *
 * @since 1.35
 */
class GlobalIdGenerator {
	/** @var callable Callback for running shell commands */
	protected $shellCallback;

	/** @var string Temporary directory */
	protected $tmpDir;
	/** @var string
	 * File prefix containing user ID to prevent collisions
	 * if multiple users run MediaWiki (T268420) and getmyuid() is enabled
	 */
	protected $uniqueFilePrefix;
	/** @var string Local file path */
	protected $nodeIdFile;
	/** @var string Node ID in binary (32 bits) */
	protected $nodeId32;
	/** @var string Node ID in binary (48 bits) */
	protected $nodeId48;

	/** @var bool Whether initialization completed */
	protected $loaded = false;
	/** @var string Local file path */
	protected $lockFile88;
	/** @var string Local file path */
	protected $lockFile128;
	/** @var string Local file path */
	protected $lockFileUUID;

	/** @var array Cached file handles */
	protected $fileHandles = [];

	/**
	 * Avoid using __CLASS__ so namespace separators aren't interpreted
	 * as path components on Windows (T259693)
	 */
	private const FILE_PREFIX = 'mw-GlobalIdGenerator';

	/** Key used in the serialized clock state map that is stored on disk */
	private const CLOCK_TIME = 'time';
	/** Key used in the serialized clock state map that is stored on disk */
	private const CLOCK_COUNTER = 'counter';
	/** Key used in the serialized clock state map that is stored on disk */
	private const CLOCK_SEQUENCE = 'clkSeq';
	/** Key used in the serialized clock state map that is stored on disk */
	private const CLOCK_OFFSET = 'offset';
	/** Key used in the serialized clock state map that is stored on disk */
	private const CLOCK_OFFSET_COUNTER = 'offsetCounter';

	/**
	 * @param string|bool $tempDirectory A writable temporary directory
	 * @param callback $shellCallback A callback that takes a shell command and returns the output
	 */
	public function __construct( $tempDirectory, $shellCallback ) {
		if ( func_num_args() >= 3 && !is_callable( $shellCallback ) ) {
			trigger_error(
				__CLASS__ . ' with a BagOStuff instance was deprecated in MediaWiki 1.37.',
				E_USER_DEPRECATED
			);
			$shellCallback = func_get_arg( 2 );
		}
		if ( $tempDirectory === false || $tempDirectory === '' ) {
			throw new InvalidArgumentException( "No temp directory provided" );
		}
		$this->tmpDir = $tempDirectory;
		// Include the UID in the filename (T268420, T358768)
		if ( function_exists( 'posix_geteuid' ) ) {
			$fileSuffix = posix_geteuid();
		} elseif ( function_exists( 'getmyuid' ) ) {
			$fileSuffix = getmyuid();
		} else {
			$fileSuffix = '';
		}
		$this->uniqueFilePrefix = self::FILE_PREFIX . $fileSuffix;
		$this->nodeIdFile = $tempDirectory . '/' . $this->uniqueFilePrefix . '-UID-nodeid';
		// If different processes run as different users, they may have different temp dirs.
		// This is dealt with by initializing the clock sequence number and counters randomly.
		$this->lockFile88 = $tempDirectory . '/' . $this->uniqueFilePrefix . '-UID-88';
		$this->lockFile128 = $tempDirectory . '/' . $this->uniqueFilePrefix . '-UID-128';
		$this->lockFileUUID = $tempDirectory . '/' . $this->uniqueFilePrefix . '-UUID-128';

		$this->shellCallback = $shellCallback;
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
	public function newTimestampedUID88( int $base = 10 ) {
		Assert::parameter( $base <= 36, '$base', 'must be <= 36' );
		Assert::parameter( $base >= 2, '$base', 'must be >= 2' );

		$info = $this->getTimeAndDelay( 'lockFile88', 1, 1024, 1024 );
		$info[self::CLOCK_OFFSET_COUNTER] %= 1024;

		return \Wikimedia\base_convert( $this->getTimestampedID88( $info ), 2, $base );
	}

	/**
	 * @param array $info result of GlobalIdGenerator::getTimeAndDelay()
	 * @return string 88 bits
	 * @throws RuntimeException
	 */
	protected function getTimestampedID88( array $info ) {
		$time = $info[self::CLOCK_TIME];
		$counter = $info[self::CLOCK_OFFSET_COUNTER];
		// Take the 46 LSBs of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $time );
		// Add a 10 bit counter resulting in 56 bits total
		$id_bin .= str_pad( decbin( $counter ), 10, '0', STR_PAD_LEFT );
		// Add the 32 bit node ID resulting in 88 bits total
		$id_bin .= $this->getNodeId32();
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
	public function newTimestampedUID128( int $base = 10 ) {
		Assert::parameter( $base <= 36, '$base', 'must be <= 36' );
		Assert::parameter( $base >= 2, '$base', 'must be >= 2' );

		$info = $this->getTimeAndDelay( 'lockFile128', 16384, 1_048_576, 1_048_576 );
		$info[self::CLOCK_OFFSET_COUNTER] %= 1_048_576;

		return \Wikimedia\base_convert( $this->getTimestampedID128( $info ), 2, $base );
	}

	/**
	 * @param array $info The result of GlobalIdGenerator::getTimeAndDelay()
	 * @return string 128 bits
	 * @throws RuntimeException
	 */
	protected function getTimestampedID128( array $info ) {
		$time = $info[self::CLOCK_TIME];
		$counter = $info[self::CLOCK_OFFSET_COUNTER];
		$clkSeq = $info[self::CLOCK_SEQUENCE];
		// Take the 46 LSBs of "milliseconds since epoch"
		$id_bin = $this->millisecondsSinceEpochBinary( $time );
		// Add a 20 bit counter resulting in 66 bits total
		$id_bin .= str_pad( decbin( $counter ), 20, '0', STR_PAD_LEFT );
		// Add a 14 bit clock sequence number resulting in 80 bits total
		$id_bin .= str_pad( decbin( $clkSeq ), 14, '0', STR_PAD_LEFT );
		// Add the 48 bit node ID resulting in 128 bits total
		$id_bin .= $this->getNodeId48();
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
	 */
	public function newUUIDv1() {
		// There can be up to 10000 intervals for the same millisecond timestamp.
		// [0,4999] counter + [0,5000] offset is in [0,9999] for the offset counter.
		// Add this onto the timestamp to allow making up to 5000 IDs per second.
		return $this->getUUIDv1( $this->getTimeAndDelay( 'lockFileUUID', 16384, 5000, 5001 ) );
	}

	/**
	 * @param array $info Result of GlobalIdGenerator::getTimeAndDelay()
	 * @return string 128 bits
	 */
	protected function getUUIDv1( array $info ) {
		$clkSeq_bin = \Wikimedia\base_convert( $info[self::CLOCK_SEQUENCE], 10, 2, 14 );
		$time_bin = $this->intervalsSinceGregorianBinary(
			$info[self::CLOCK_TIME],
			$info[self::CLOCK_OFFSET_COUNTER]
		);
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
		$id_bin .= $this->getNodeId48();
		// Convert to a 32 char hex string with dashes
		if ( strlen( $id_bin ) !== 128 ) {
			throw new RuntimeException( "Detected overflow for millisecond timestamp." );
		}
		$hex = \Wikimedia\base_convert( $id_bin, 2, 16, 32 );
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
	 * Return an RFC4122 compliant v1 UUID
	 *
	 * @return string 32 hex characters with no hyphens
	 * @throws RuntimeException
	 */
	public function newRawUUIDv1() {
		return str_replace( '-', '', $this->newUUIDv1() );
	}

	/**
	 * Return an RFC4122 compliant v4 UUID
	 *
	 * @return string
	 * @throws RuntimeException
	 */
	public function newUUIDv4() {
		$hex = bin2hex( random_bytes( 32 / 2 ) );

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
	 * @return string 32 hex characters with no hyphens
	 * @throws RuntimeException
	 */
	public function newRawUUIDv4() {
		return str_replace( '-', '', $this->newUUIDv4() );
	}

	/**
	 * Return an ID that is sequential *only* for this node and bucket
	 *
	 * These IDs are suitable for per-host sequence numbers, e.g. for some packet protocols.
	 *
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (<=48) of resulting numbers before wrap-around
	 * @return float Integer value as float
	 */
	public function newSequentialPerNodeID( $bucket, $bits = 48 ) {
		return current( $this->newSequentialPerNodeIDs( $bucket, $bits, 1 ) );
	}

	/**
	 * Return IDs that are sequential *only* for this node and bucket
	 *
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (16 to 48) of resulting numbers before wrap-around
	 * @param int $count Number of IDs to return
	 * @return array Ordered list of float integer values
	 * @see GlobalIdGenerator::newSequentialPerNodeID()
	 */
	public function newSequentialPerNodeIDs( $bucket, $bits, $count ) {
		return $this->getSequentialPerNodeIDs( $bucket, $bits, $count );
	}

	/**
	 * Get timestamp in a specified format from UUIDv1
	 *
	 * @param string $uuid the UUID to get the timestamp from
	 * @param int $format the format to convert the timestamp to. Default: TS_MW
	 * @return string|false timestamp in requested format or false
	 */
	public function getTimestampFromUUIDv1( string $uuid, int $format = TS_MW ) {
		$components = [];
		if ( !preg_match(
			'/^([0-9a-f]{8})-([0-9a-f]{4})-(1[0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/',
			$uuid,
			$components
		) ) {
			throw new InvalidArgumentException( "Invalid UUIDv1 {$uuid}" );
		}

		$timestamp = hexdec( substr( $components[3], 1 ) . $components[2] . $components[1] );
		// The 60 bit timestamp value is constructed from fields of this UUID.
		// The timestamp is measured in 100-nanosecond units since midnight, October 15, 1582 UTC.
		$unixTime = ( $timestamp - 0x01b21dd213814000 ) / 1e7;

		return ConvertibleTimestamp::convert( $format, $unixTime );
	}

	/**
	 * Return IDs that are sequential *only* for this node and bucket
	 *
	 * @param string $bucket Arbitrary bucket name (should be ASCII)
	 * @param int $bits Bit size (16 to 48) of resulting numbers before wrap-around
	 * @param int $count Number of IDs to return
	 * @return array Ordered list of float integer values
	 * @throws RuntimeException
	 * @see GlobalIdGenerator::newSequentialPerNodeID()
	 */
	protected function getSequentialPerNodeIDs( $bucket, $bits, $count ) {
		if ( $count <= 0 ) {
			return [];
		}
		if ( $bits < 16 || $bits > 48 ) {
			throw new RuntimeException( "Requested bit size ($bits) is out of range." );
		}

		$path = $this->tmpDir . '/' . $this->uniqueFilePrefix . '-' . rawurlencode( $bucket ) . '-48';
		// Get the UID lock file handle
		if ( isset( $this->fileHandles[$path] ) ) {
			$handle = $this->fileHandles[$path];
		} else {
			$handle = fopen( $path, 'cb+' );
			$this->fileHandles[$path] = $handle ?: null;
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

		// fetch as float
		$counter = floor( (float)trim( fgets( $handle ) ) ) + $count;

		// Write back the new counter value
		ftruncate( $handle, 0 );
		rewind( $handle );

		// Use fmod() to avoid "division by zero" on 32 bit machines
		// warp-around as needed
		fwrite( $handle, (string)fmod( $counter, 2 ** 48 ) );
		fflush( $handle );

		// Release the UID lock file
		flock( $handle, LOCK_UN );

		$ids = [];
		$divisor = 2 ** $bits;

		// pre-increment counter value
		$currentId = floor( $counter - $count );
		for ( $i = 0; $i < $count; ++$i ) {
			// Use fmod() to avoid "division by zero" on 32 bit machines
			$ids[] = fmod( ++$currentId, $divisor );
		}

		return $ids;
	}

	/**
	 * Get a (time,counter,clock sequence) where (time,counter) is higher
	 * than any previous (time,counter) value for the given clock sequence.
	 * This is useful for making UIDs sequential on a per-node basis.
	 *
	 * @param string $lockFile Name of a local lock file
	 * @param int $clockSeqSize The number of possible clock sequence values
	 * @param int $counterSize The number of possible counter values
	 * @param int $offsetSize The number of possible offset values
	 * @return array Array with the following keys:
	 *  - GlobalIdGenerator::CLOCK_TIME: (integer seconds, integer milliseconds) array
	 *  - GlobalIdGenerator::CLOCK_COUNTER: integer millisecond tie-breaking counter
	 *  - GlobalIdGenerator::CLOCK_SEQUENCE: integer clock identifier that is local to the node
	 *  - GlobalIdGenerator::CLOCK_OFFSET: integer offset for millisecond tie-breaking counter
	 *  - GlobalIdGenerator::CLOCK_OFFSET_COUNTER: integer; CLOCK_COUNTER with CLOCK_OFFSET applied
	 * @throws RuntimeException
	 */
	protected function getTimeAndDelay( $lockFile, $clockSeqSize, $counterSize, $offsetSize ) {
		// Get the UID lock file handle
		if ( isset( $this->fileHandles[$this->$lockFile] ) ) {
			$handle = $this->fileHandles[$this->$lockFile];
		} else {
			$handle = fopen( $this->$lockFile, 'cb+' );
			$this->fileHandles[$this->$lockFile] = $handle ?: null;
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
		// To orchestrate this between independent PHP processes on the same host,
		// we must have a common sense of time so that we only have to maintain
		// a single counter in a single lock file.
		//
		// Given that:
		// * The system clock can be observed via time(), without milliseconds.
		// * Some other clock can be observed via microtime(), which also offers
		//   millisecond precision.
		// * microtime() drifts in-process further and further away from the system
		//   clock the longer a process runs for.
		//   For example, on 2018-10-03 an HHVM 3.18 JobQueue process at WMF,
		//   that ran for 9 min 55 sec, microtime drifted by 7 seconds.
		//   time() does not have this problem. See https://bugs.php.net/bug.php?id=42659.
		//
		// We have two choices:
		//
		// 1. Use microtime() with the following caveats:
		//    - The last stored time may be in the future, or our current time may be in the
		//      past, in which case we'll frequently enter the slow timeWaitUntil() method to
		//      try and "sync" the current process with the previous process.
		//      We mustn't block for long though, max 10ms?
		//    - For any drift above 10ms, we pretend that the clock went backwards, and treat
		//      it the same way as after an NTP sync, by incrementing clock sequence instead.
		//      Given the sequence rolls over automatically, and silently, and is meant to be
		//      rare, this essentially sacrifices a reasonable guarantee of uniqueness.
		//    - For long running processes (e.g. longer than a few seconds) the drift can
		//      easily be more than 2 seconds. Because we only have a single lock file
		//      and don't want to keep too many counters and deal with clearing those,
		//      we fatal the user and refuse to make an ID.  (T94522)
		//    - This offers terrible service availability.
		// 2. Use time() instead, and expand the counter size by 1000x and use its
		//    digits as if they were the millisecond fraction of our timestamp.
		//    Known caveats or perf impact: None. We still need to read-write our
		//    lock file on each generation, so might as well make the most of it.
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
			$prevMsecCounter = (int)$data[2] % $msecCounterSize;
			$randOffset = (int)$data[3] % $counterSize;
			// If the system clock moved back or inter-process clock drift caused the last
			// writer process to record a higher time than the current process time, then
			// briefly wait for the current process clock to catch up.
			$sec = $this->timeWaitUntil( $prevSec );
			if ( $sec === false ) {
				// There was too much clock drift to wait. Bump the clock sequence number to
				// avoid collisions between new and already-generated IDs with the same time.
				$clkSeq = ( $clkSeq + 1 ) % $clockSeqSize;
				$sec = time();
				$msecCounter = 0;
				$randOffset = random_int( 0, $offsetSize - 1 );
				trigger_error( "Clock was set back; sequence number incremented." );
			} elseif ( $sec === $prevSec ) {
				// The time matches the last ID. Bump the tie-breaking counter.
				$msecCounter = $prevMsecCounter + 1;
				if ( $msecCounter >= $msecCounterSize ) {
					// More IDs generated with the same time than counterSize can accommodate
					flock( $handle, LOCK_UN );
					throw new RuntimeException( "Counter overflow for timestamp value." );
				}
			} else {
				// The time is higher than the last ID. Reset the tie-breaking counter.
				$msecCounter = 0;
			}
		} else {
			// Initialize UID lock file information
			$clkSeq = random_int( 0, $clockSeqSize - 1 );
			$sec = time();
			$msecCounter = 0;
			$randOffset = random_int( 0, $offsetSize - 1 );
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
			self::CLOCK_TIME     => [ $sec, $msec ],
			self::CLOCK_COUNTER  => $counter,
			self::CLOCK_SEQUENCE => $clkSeq,
			self::CLOCK_OFFSET   => $randOffset,
			self::CLOCK_OFFSET_COUNTER => $counter + $randOffset,
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
			// https://www.php.net/manual/en/language.operators.comparison.php
			if ( $ct >= $time ) {
				// current time is higher than or equal to than $time
				return $ct;
			}
			// up to 10ms
		} while ( ( microtime( true ) - $start ) <= 0.010 );

		return false;
	}

	/**
	 * @param array $time Array of second and millisecond integers
	 * @return string 46 LSBs of "milliseconds since epoch" in binary (rolls over in 4201)
	 * @throws RuntimeException
	 */
	protected function millisecondsSinceEpochBinary( array $time ) {
		[ $sec, $msec ] = $time;
		$ts = 1000 * $sec + $msec;
		if ( $ts > 2 ** 52 ) {
			throw new RuntimeException( __METHOD__ .
				': sorry, this function doesn\'t work after the year 144680' );
		}

		return substr( \Wikimedia\base_convert( (string)$ts, 10, 2, 46 ), -46 );
	}

	/**
	 * @param array $time Array of second and millisecond integers
	 * @param int $delta Number of intervals to add on to the timestamp
	 * @return string 60 bits of "100ns intervals since 15 October 1582" (rolls over in 3400)
	 * @throws RuntimeException
	 */
	protected function intervalsSinceGregorianBinary( array $time, $delta = 0 ) {
		[ $sec, $msec ] = $time;
		$offset = '122192928000000000';

		// 64 bit integers
		if ( PHP_INT_SIZE >= 8 ) {
			$ts = ( 1000 * $sec + $msec ) * 10000 + (int)$offset + $delta;
			$id_bin = str_pad( decbin( $ts % ( 2 ** 60 ) ), 60, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'gmp' ) ) {
			// ms
			$ts = gmp_add( gmp_mul( (string)$sec, '1000' ), (string)$msec );
			// 100ns intervals
			$ts = gmp_add( gmp_mul( $ts, '10000' ), $offset );
			$ts = gmp_add( $ts, (string)$delta );
			// wrap around
			$ts = gmp_mod( $ts, gmp_pow( '2', 60 ) );
			$id_bin = str_pad( gmp_strval( $ts, 2 ), 60, '0', STR_PAD_LEFT );
		} elseif ( extension_loaded( 'bcmath' ) ) {
			// ms
			$ts = bcadd( bcmul( $sec, '1000' ), $msec );
			// 100ns intervals
			$ts = bcadd( bcmul( $ts, '10000' ), $offset );
			$ts = bcadd( $ts, (string)$delta );
			// wrap around
			$ts = bcmod( $ts, bcpow( '2', '60' ) );
			$id_bin = \Wikimedia\base_convert( $ts, 10, 2, 60 );
		} else {
			throw new RuntimeException( 'bcmath or gmp extension required for 32 bit machines.' );
		}
		return $id_bin;
	}

	/**
	 * Load the node ID information
	 */
	private function load() {
		if ( $this->loaded ) {
			return;
		}

		$this->loaded = true;

		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$nodeId = @file_get_contents( $this->nodeIdFile ) ?: '';
		// Try to get some ID that uniquely identifies this machine (RFC 4122)...
		if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
			AtEase::suppressWarnings();
			if ( PHP_OS_FAMILY === 'Windows' ) {
				// https://technet.microsoft.com/en-us/library/bb490913.aspx
				$csv = trim( ( $this->shellCallback )( 'getmac /NH /FO CSV' ) );
				$line = substr( $csv, 0, strcspn( $csv, "\n" ) );
				$info = str_getcsv( $line, ",", "\"", "\\" );
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal False positive
				$nodeId = isset( $info[0] ) ? str_replace( '-', '', $info[0] ) : '';
			} elseif ( is_executable( '/sbin/ifconfig' ) ) {
				// Linux/BSD/Solaris/OS X
				// See https://linux.die.net/man/8/ifconfig
				$m = [];
				preg_match( '/\s([0-9a-f]{2}(?::[0-9a-f]{2}){5})\s/',
					( $this->shellCallback )( '/sbin/ifconfig -a' ), $m );
				$nodeId = isset( $m[1] ) ? str_replace( ':', '', $m[1] ) : '';
			}
			AtEase::restoreWarnings();
			if ( !preg_match( '/^[0-9a-f]{12}$/i', $nodeId ) ) {
				$nodeId = bin2hex( random_bytes( 12 / 2 ) );
				// set multicast bit
				$nodeId[1] = dechex( hexdec( $nodeId[1] ) | 0x1 );
			}
			file_put_contents( $this->nodeIdFile, $nodeId );
		}
		$this->nodeId32 = \Wikimedia\base_convert( substr( sha1( $nodeId ), 0, 8 ), 16, 2, 32 );
		$this->nodeId48 = \Wikimedia\base_convert( $nodeId, 16, 2, 48 );
	}

	/**
	 * @return string
	 */
	private function getNodeId32() {
		$this->load();

		return $this->nodeId32;
	}

	/**
	 * @return string
	 */
	private function getNodeId48() {
		$this->load();

		return $this->nodeId48;
	}

	/**
	 * Delete all cache files that have been created (T46850)
	 *
	 * This is a cleanup method primarily meant to be used from unit tests to
	 * avoid polluting the local filesystem. If used outside of a unit test
	 * environment it should be used with caution as it may destroy state saved
	 * in the files.
	 *
	 * @see unitTestTearDown
	 * @codeCoverageIgnore
	 */
	private function deleteCacheFiles() {
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
	 * Cleanup resources when tearing down after a unit test (T46850)
	 *
	 * This is a cleanup method primarily meant to be used from unit tests to
	 * avoid polluting the local filesystem. If used outside of a unit test
	 * environment it should be used with caution as it may destroy state saved
	 * in the files.
	 *
	 * @internal For use by unit tests
	 * @see deleteCacheFiles
	 * @codeCoverageIgnore
	 */
	public function unitTestTearDown() {
		$this->deleteCacheFiles();
	}

	public function __destruct() {
		// @phan-suppress-next-line PhanPluginUseReturnValueInternalKnown
		array_map( 'fclose', array_filter( $this->fileHandles ) );
	}
}
