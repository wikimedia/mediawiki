<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SetupAfterCacheHook {
	/**
	 * This hook is called in Setup.php, after cache objects are set.
	 *
	 * @since 1.35
	 *
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSetupAfterCache();
}
