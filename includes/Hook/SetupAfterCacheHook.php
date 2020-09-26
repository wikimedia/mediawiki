<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SetupAfterCache" to register handlers implementing this interface.
 *
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
