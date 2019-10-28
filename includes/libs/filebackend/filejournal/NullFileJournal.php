<?php
/**
 * Simple version of FileJournal that does nothing
 * @since 1.20
 */
class NullFileJournal extends FileJournal {
	public function __construct() {
		// No-op
	}

	/**
	 * @see FileJournal::doLogChangeBatch()
	 * @param array $entries
	 * @param string $batchId
	 * @return StatusValue
	 */
	protected function doLogChangeBatch( array $entries, $batchId ) {
		return StatusValue::newGood();
	}

	/**
	 * @see FileJournal::doGetCurrentPosition()
	 * @return int|bool
	 */
	protected function doGetCurrentPosition() {
		return false;
	}

	/**
	 * @see FileJournal::doGetPositionAtTime()
	 * @param int|string $time Timestamp
	 * @return int|bool
	 */
	protected function doGetPositionAtTime( $time ) {
		return false;
	}

	/**
	 * @see FileJournal::doGetChangeEntries()
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	protected function doGetChangeEntries( $start, $limit ) {
		return [];
	}

	/**
	 * @see FileJournal::doPurgeOldLogs()
	 * @return StatusValue
	 */
	protected function doPurgeOldLogs() {
		return StatusValue::newGood();
	}
}
