<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetDefaultOptionsHook {
	/**
	 * After fetching the core default, this hook is run right
	 * before returning the options to the caller. Warning: This hook is called for
	 * every call to User::getDefaultOptions(), which means it's potentially called
	 * dozens or hundreds of times. You may want to cache the results of non-trivial
	 * operations in your hook function for this reason.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$defaultOptions Array of preference keys and their default values.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetDefaultOptions( &$defaultOptions );
}
