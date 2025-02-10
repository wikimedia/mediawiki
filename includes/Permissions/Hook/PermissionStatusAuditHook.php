<?php
namespace MediaWiki\Permissions\Hook;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md
 * Use the hook name "PermissionStatusAudit" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PermissionStatusAuditHook {
	/**
	 * This hook is called from PermissionManager::getPermissionStatus()
	 * to make any permission status (even if it has no errors) available to consumers.
	 *
	 * @param LinkTarget $title Page in question
	 * @param UserIdentity $user User to check
	 * @param string $action Action being checked
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 * @param PermissionStatus $status Permission check status. This is a read-only copy
	 *   and can't be modified, use the 'GetUserPermissionsErrors' hook to report permission errors.
	 * @return void This hook must not abort, it must return no value
	 *
	 * @since 1.44
	 */
	public function onPermissionStatusAudit(
		LinkTarget $title,
		UserIdentity $user,
		string $action,
		string $rigor,
		PermissionStatus $status
	): void;
}
