<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OutputPageCheckLastModifiedHook {
	/**
	 * when checking if the page has been modified
	 * since the last visit.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$modifiedTimes array of timestamps.
	 *   The following keys are set: page, user, epoch
	 * @param ?mixed $out OutputPage object (since 1.28)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageCheckLastModified( &$modifiedTimes, $out );
}
