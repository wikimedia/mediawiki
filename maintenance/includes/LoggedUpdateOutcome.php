<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Maintenance;

/**
 * Enum representing the outcome of LoggedUpdateMaintenance::doDbUpdates().
 */
enum LoggedUpdateOutcome {

	/**
	 * Return value for doDBUpdates() indicating that the update was completed
	 * and does not need to be run again in the future.
	 */
	case COMPLETE;

	/**
	 * Return value for doDBUpdates() indicating that the update failed and
	 * needs to be re-tried later.
	 */
	case FAILED;

	/**
	 * Return value for doDBUpdates() indicating a successful "dry run":
	 * The update was simulated but still needs to be applied in the future.
	 */
	case SIMULATED;
}
