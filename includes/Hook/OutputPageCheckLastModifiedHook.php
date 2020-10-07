<?php

namespace MediaWiki\Hook;

use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface OutputPageCheckLastModifiedHook {
	/**
	 * This hook is called when checking if the page has been modified
	 * since the last visit.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$modifiedTimes Array of timestamps.
	 *   The following keys are set: page, user, epoch.
	 * @param OutputPage $out since 1.28
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onOutputPageCheckLastModified( &$modifiedTimes, $out );
}
