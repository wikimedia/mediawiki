<?php
/**
 * @defgroup FileJournal File journal
 * @ingroup FileBackend
 */

/**
 * File operation journaling.
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
 * @ingroup FileJournal
 */

use Wikimedia\ObjectFactory;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @brief Class for handling file operation journaling.
 *
 * Subclasses should avoid throwing exceptions at all costs.
 *
 * @ingroup FileJournal
 * @since 1.20
 */
abstract class FileJournal {
	/** @var string */
	protected $backend;
	/** @var int|false */
	protected $ttlDays;

	/**
	 * Construct a new instance from configuration. Do not call this directly, use factory().
	 *
	 * @param array $config Includes:
	 *     'ttlDays' : days to keep log entries around (false means "forever")
	 */
	public function __construct( array $config ) {
		$this->ttlDays = $config['ttlDays'] ?? false;
	}

	/**
	 * Create an appropriate FileJournal object from config
	 *
	 * @param array $config
	 * @param string $backend A registered file backend name
	 * @throws Exception
	 * @return FileJournal
	 */
	final public static function factory( array $config, $backend ) {
		$jrn = ObjectFactory::getObjectFromSpec(
			$config,
			[ 'specIsArg' => true, 'assertClass' => __CLASS__ ]
		);
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
		$s = Wikimedia\base_convert( sha1( $s ), 16, 36, 31 );

		$timestamp = ConvertibleTimestamp::convert( TS_MW, time() );

		return substr( Wikimedia\base_convert( $timestamp, 10, 36, 9 ) . $s, 0, 31 );
	}

	/**
	 * Log changes made by a batch file operation.
	 *
	 * @param array $entries List of file operations (each an array of parameters) which contain:
	 *     op      : Basic operation name (create, update, delete)
	 *     path    : The storage path of the file
	 *     newSha1 : The final base 36 SHA-1 of the file
	 *   Note that 'false' should be used as the SHA-1 for non-existing files.
	 * @param string $batchId UUID string that identifies the operation batch
	 * @return StatusValue
	 */
	final public function logChangeBatch( array $entries, $batchId ) {
		if ( $entries === [] ) {
			return StatusValue::newGood();
		}

		return $this->doLogChangeBatch( $entries, $batchId );
	}

	/**
	 * @see FileJournal::logChangeBatch()
	 *
	 * @param array $entries List of file operations (each an array of parameters)
	 * @param string $batchId UUID string that identifies the operation batch
	 * @return StatusValue
	 */
	abstract protected function doLogChangeBatch( array $entries, $batchId );

	/**
	 * Get the position ID of the latest journal entry
	 *
	 * @return int|bool
	 */
	final public function getCurrentPosition() {
		return $this->doGetCurrentPosition();
	}

	/**
	 * @see FileJournal::getCurrentPosition()
	 * @return int|bool
	 */
	abstract protected function doGetCurrentPosition();

	/**
	 * Get the position ID of the latest journal entry at some point in time
	 *
	 * @param int|string $time Timestamp
	 * @return int|bool
	 */
	final public function getPositionAtTime( $time ) {
		return $this->doGetPositionAtTime( $time );
	}

	/**
	 * @see FileJournal::getPositionAtTime()
	 * @param int|string $time Timestamp
	 * @return int|bool
	 */
	abstract protected function doGetPositionAtTime( $time );

	/**
	 * Get an array of file change log entries.
	 * A starting change ID and/or limit can be specified.
	 *
	 * @param int|null $start Starting change ID or null
	 * @param int $limit Maximum number of items to return (0 = unlimited)
	 * @param string|null &$next Updated to the ID of the next entry.
	 * @return array List of associative arrays, each having:
	 *     id         : unique, monotonic, ID for this change
	 *     batch_uuid : UUID for an operation batch
	 *     backend    : the backend name
	 *     op         : primitive operation (create,update,delete,null)
	 *     path       : affected storage path
	 *     new_sha1   : base 36 sha1 of the new file had the operation succeeded
	 *     timestamp  : TS_MW timestamp of the batch change
	 *   Also, $next is updated to the ID of the next entry.
	 */
	final public function getChangeEntries( $start = null, $limit = 0, &$next = null ) {
		$entries = $this->doGetChangeEntries( $start, $limit ? $limit + 1 : 0 );
		if ( $limit && count( $entries ) > $limit ) {
			$last = array_pop( $entries ); // remove the extra entry
			$next = $last['id']; // update for next call
		} else {
			$next = null; // end of list
		}

		return $entries;
	}

	/**
	 * @see FileJournal::getChangeEntries()
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	abstract protected function doGetChangeEntries( $start, $limit );

	/**
	 * Purge any old log entries
	 *
	 * @return StatusValue
	 */
	final public function purgeOldLogs() {
		return $this->doPurgeOldLogs();
	}

	/**
	 * @see FileJournal::purgeOldLogs()
	 * @return StatusValue
	 */
	abstract protected function doPurgeOldLogs();
}
