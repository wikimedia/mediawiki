<?php
namespace MediaWiki\Permissions\Hook;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md
 * Use the hook name "PermissionErrorAudit" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface PermissionErrorAuditHook {
	/**
	 * This hook is called from PermissionManager::getPermissionErrorsInternal()
	 * to collect internal permission errors and make them available to consumers.
	 *
	 * @param LinkTarget $title Page in question
	 * @param UserIdentity $user User to check
	 * @param string $action Action being checked
	 * @param string $rigor One of PermissionManager::RIGOR_ constants
	 * @param array[] $errors Array of arrays of the arguments to wfMessage to explain permissions problems.
	 * @return void This hook must not abort, it must return no value
	 *
	 * @since 1.39
	 */
	public function onPermissionErrorAudit(
		LinkTarget $title,
		UserIdentity $user,
		string $action,
		string $rigor,
		array $errors
	): void;
}
