<?php

namespace MediaWiki\Permissions\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetUserPermissionsErrorsExpensiveHook {
	/**
	 * Equal to getUserPermissionsErrors, but is
	 * called only if expensive checks are enabled. Add a permissions error when
	 * permissions errors are checked for. Return false if the user can't do it, and
	 * populate $result with the reason in the form of [ messagename, param1, param2,
	 * ... ] or a MessageSpecifier instance (you might want to use ApiMessage to
	 * provide machine-readable details for the API). For consistency, error messages
	 * should be plain text with no special coloring, bolding, etc. to show that
	 * they're errors; presenting them properly to the user as errors is done by the
	 * caller.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title object being checked against
	 * @param ?mixed $user Current user object
	 * @param ?mixed $action Action being checked
	 * @param ?mixed &$result User permissions error to add. If none, return true.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetUserPermissionsErrorsExpensive( $title, $user, $action,
		&$result
	);
}
