<?php

namespace MediaWiki\User\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserGetReservedNames" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetReservedNamesHook {
	/**
	 * Use this hook to modify $wgReservedUsernames at run time.
	 *
	 * @since 1.35
	 *
	 * @param array &$reservedUsernames $wgReservedUsernames
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetReservedNames( &$reservedUsernames );
}
