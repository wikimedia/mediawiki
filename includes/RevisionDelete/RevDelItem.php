<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup RevisionDelete
 */

use MediaWiki\Api\ApiResult;
use MediaWiki\RevisionList\RevisionItemBase;
use MediaWiki\Status\Status;

/**
 * Abstract base class for deletable items
 */
abstract class RevDelItem extends RevisionItemBase {
	/**
	 * Returns true if the item is "current", and the operation to set the given
	 * bits can't be executed for that reason
	 * STUB
	 * @param int $newBits
	 * @return bool
	 */
	public function isHideCurrentOp( $newBits ) {
		return false;
	}

	/**
	 * Get the current deletion bitfield value
	 *
	 * @return int
	 */
	abstract public function getBits();

	/**
	 * Set the visibility of the item. This should do any necessary DB queries.
	 *
	 * The DB update query should have a condition which forces it to only update
	 * if the value in the DB matches the value fetched earlier with the SELECT.
	 * If the update fails because it did not match, the function should return
	 * false. This prevents concurrency problems.
	 *
	 * @param int $newBits
	 * @return bool Success
	 */
	abstract public function setBits( $newBits );

	/**
	 * Get the return information about the revision for the API
	 * @since 1.23
	 * @param ApiResult $result
	 * @return array Data for the API result
	 */
	abstract public function getApiData( ApiResult $result );

	/**
	 * Lock the item against changes outside of the DB
	 * @return Status
	 * @since 1.28
	 */
	public function lock() {
		return Status::newGood();
	}

	/**
	 * Unlock the item against changes outside of the DB
	 * @return Status
	 * @since 1.28
	 */
	public function unlock() {
		return Status::newGood();
	}
}
