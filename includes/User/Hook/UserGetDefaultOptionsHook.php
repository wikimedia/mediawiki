<?php

namespace MediaWiki\User\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserGetDefaultOptions" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetDefaultOptionsHook {
	/**
	 * This hook is called after fetching core default user options but before returning the options
	 *
	 * Warning: This hook is called for every call to UserOptionsLookup::getDefaultOptions(), which means
	 * it's potentially called dozens or hundreds of times. You may want to cache the results
	 * of non-trivial operations in your hook function for this reason.
	 *
	 * @since 1.35
	 *
	 * @param array &$defaultOptions Array of preference keys and their default values.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetDefaultOptions( &$defaultOptions );
}
