<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface PrefsEmailAuditHook {
	/**
	 * Called when user changes their email address.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User (object) changing his email address
	 * @param ?mixed $oldaddr old email address (string)
	 * @param ?mixed $newaddr new email address (string)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onPrefsEmailAudit( $user, $oldaddr, $newaddr );
}
