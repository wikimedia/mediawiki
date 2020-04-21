<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserGetReservedNamesHook {
	/**
	 * Allows to modify $wgReservedUsernames at run time.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$reservedUsernames $wgReservedUsernames
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetReservedNames( &$reservedUsernames );
}
