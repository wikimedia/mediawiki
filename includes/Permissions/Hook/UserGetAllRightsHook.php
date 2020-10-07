<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UserGetAllRightsHook {
	/**
	 * This hook is called after calculating a list of all available rights.
	 *
	 * @since 1.35
	 *
	 * @param string[] &$rights Array of rights, which may be added to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserGetAllRights( &$rights );
}
