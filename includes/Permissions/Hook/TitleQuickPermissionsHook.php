<?php

namespace MediaWiki\Permissions\Hook;

use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleQuickPermissionsHook {
	/**
	 * This hook is called from Title::checkQuickPermissions to add to
	 * or override the quick permissions check.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title being accessed
	 * @param User $user User performing the action
	 * @param string $action Action being performed
	 * @param array &$errors Array of errors
	 * @param bool $doExpensiveQueries Whether to do expensive database queries
	 * @param bool $short Whether to return immediately on first error
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleQuickPermissions( $title, $user, $action, &$errors,
		$doExpensiveQueries, $short
	);
}
