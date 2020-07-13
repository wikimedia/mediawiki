<?php

namespace MediaWiki\Hook;

use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PrefsEmailAuditHook {
	/**
	 * This hook is called when user changes their email address.
	 *
	 * @since 1.35
	 *
	 * @param User $user User (object) changing his email address
	 * @param string $oldaddr old email address (string)
	 * @param string $newaddr new email address (string)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPrefsEmailAudit( $user, $oldaddr, $newaddr );
}
