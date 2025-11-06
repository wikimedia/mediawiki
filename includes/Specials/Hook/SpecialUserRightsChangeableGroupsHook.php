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
	 * @param array &$restrictedGroups Map of groups that require some condition to be met in order to
	 *   be added. The hook handlers are expected to evaluate the relevant conditions, and determine if
	 *   they are met and if the performer is able to ignore the condition not being met. Don't include
	 *   groups here that the performer is not allowed to add at all (i.e. that are not in `$addableGroups`).
	 *   The expected format is:
	 *   `[ 'groupname' => [ 'condition-met' => bool, 'ignore-condition' => bool, 'message' => string ] ]`,
	 *   where 'message' is a message key that will be shown if the condition is not met, regardless of whether
	 *   it's ignored or not.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onSpecialUserRightsChangeableGroups(
		Authority $authority,
		UserIdentity $target,
		array $addableGroups,
		array &$restrictedGroups
	): void;
}
