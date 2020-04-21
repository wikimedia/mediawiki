<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleQuickPermissionsHook {
	/**
	 * Called from Title::checkQuickPermissions to add to
	 * or override the quick permissions check.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title The Title object being accessed
	 * @param ?mixed $user The User performing the action
	 * @param ?mixed $action Action being performed
	 * @param ?mixed &$errors Array of errors
	 * @param ?mixed $doExpensiveQueries Whether to do expensive DB queries
	 * @param ?mixed $short Whether to return immediately on first error
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleQuickPermissions( $title, $user, $action, &$errors,
		$doExpensiveQueries, $short
	);
}
