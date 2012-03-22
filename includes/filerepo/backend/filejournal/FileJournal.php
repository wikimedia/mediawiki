<?php
/**
 * @defgroup FileJournal File journal
 * @ingroup FileBackend
 */

/**
 * @file
 * @ingroup FileJournal
 * @author Aaron Schulz
 */

/**
 * @brief Class for handling file operation journaling.
 *
 * Subclasses should avoid throwing exceptions at all costs.
 *
 * @ingroup FileJournal
 * @since 1.20
 */
abstract class FileJournal {
	protected $backend; // string
	protected $ttlDays; // integer

	/**
	 * Construct a new instance from configuration.
	 * $config includes:
	 *     'ttlDays' : days to keep log entries around (false means "forever")
	 * 
	 * @param $config Array
	 */
	protected function __construct( array $config ) {
		$this->ttlDays = isset( $config['ttlDays'] ) ? $config['ttlDays'] : false;
	}

	/**
	 * Create an appropriate FileJournal object from config
	 * 
	 * @param $config Array
	 * @param $backend string A registered file backend name
	 * @return FileJournal
	 */
	final public static function factory( array $config, $backend ) {
		$class = $config['class'];
		$jrn = new $class( $config );
		if ( !$jrn instanceof self ) {
			throw new MWException( "Class given is not an instance of FileJournal." );
		}
		$jrn->backend = $backend;
		return $jrn;
	}

	/**
	 * Get a statistically unique ID string
	 * 
	 * @return string <9 char TS_MW timestamp in base 36><22 random base 36 chars>
	 */
	final public function getTimestampedUUID() {
		$s = '';
		for ( $i = 0; $i < 5; $i++ ) {
			$s .= mt_rand( 0, 2147483647 );
		}
		$s = wfBaseConvert( sha1( $s ), 16, 36, 31 );
		return substr( wfBaseConvert( wfTimestamp( TS_MW ), 10, 36, 9 ) . $s, 0, 31 );
	}

	/**
	 * Log changes made by a batch file operation.
	 * $entries is an array of log entries, each of which contains:
	 *     op      : Basic operation name (create, store, copy, delete)
	 *     path    : The storage path of the file
	 *     newSha1 : The final base 36 SHA-1 of the file
	 * Note that 'false' should be used as the SHA-1 for non-existing files.
	 * 
	 * @param $entries Array List of file operations (each an array of parameters)
	 * @param $batchId string UUID string that identifies the operation batch
	 * @return Status
	 */
	final public function logChangeBatch( array $entries, $batchId ) {
		if ( !count( $entries ) ) {
			return Status::newGood();
		}
		return $this->doLogChangeBatch( $entries, $batchId );
	}

	/**
	 * @see FileJournal::logChangeBatch()
	 * 
	 * @param $entries Array List of file operations (each an array of parameters)
	 * @param $batchId string UUID string that identifies the operation batch
	 * @return Status
	 */
	abstract protected function doLogChangeBatch( array $entries, $batchId );

	/**
	 * Purge any old log entries
	 * 
	 * @return Status 
	 */
	final public function purgeOldLogs() {
		return $this->doPurgeOldLogs();
	}

	/**
	 * @see FileJournal::purgeOldLogs()
	 * @return Status
	 */
	abstract protected function doPurgeOldLogs();
}

/**
 * Simple version of FileJournal that does nothing
 * @since 1.20
 */
class NullFileJournal extends FileJournal {
	/**
	 * @see FileJournal::logChangeBatch()
	 * @return Status 
	 */
	protected function doLogChangeBatch( array $entries, $batchId ) {
		return Status::newGood();
	}

	/**
	 * @see FileJournal::purgeOldLogs()
	 * @return Status
	 */
	protected function doPurgeOldLogs() {
		return Status::newGood();
	}
}
