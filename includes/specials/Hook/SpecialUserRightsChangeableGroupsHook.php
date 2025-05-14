<?php

namespace MediaWiki\Hook;

use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialUserRightsChangeableGroups" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialUserRightsChangeableGroupsHook {
	/**
	 * This hook is called on checking changeable groups in SpecialUserRights.
	 *
	 * Allow extensions to specify groups that the performing user may not add for this target user,
	 * which they would usually be allowed to add, according to the AddGroups config.
	 *
	 * @since 1.45
	 *
	 * @param Authority $authority The user performing the group change
	 * @param UserIdentity $target The target user whose groups may be changed. This could be a user
	 *   from an external wiki.
	 * @param array $addableGroups Array Names of groups that the performer is allowed to add
	 * @param array &$unaddableGroups Map of groups that may not be added to message keys explaining
	 *   why they may not be added, e.g. [ 'sysop' => 'sysop-requirements-not-met' ]. This only contains
	 *   groups that the performer would otherwise be allowed to add.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onSpecialUserRightsChangeableGroups(
		Authority $authority,
		UserIdentity $target,
		array $addableGroups,
		array &$unaddableGroups
	): void;
}
