<?php

namespace MediaWiki\Specials\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "GetLogTypesOnUser" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface GetLogTypesOnUserHook {
	/**
	 * Use this hook to add log types where the target is a user page.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$types Array of log types
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetLogTypesOnUser( &$types );
}

/** @deprecated class alias since 1.46 */
class_alias( GetLogTypesOnUserHook::class, 'MediaWiki\\Hook\\GetLogTypesOnUserHook' );
