<?php
/*
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ReadPrivateUserRequirementsCondition" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ReadPrivateUserRequirementsConditionHook {

	/**
	 * This hook is called when the value of private user requirement conditions is revealed to another user. It allows
	 * the extensions to perform logging if they wish to record when some condition-related data was given to user.
	 *
	 * This usually happens when a performer submits Special:UserRights and tries to assign another user to a group,
	 * which is restricted ({@see $wgRestrictedGroups}) and uses a private condition
	 * ({@see $wgUserRequirementsPrivateConditions}). The values for conditions are not shown directly, but knowledge
	 * if user can or cannot be added to a group may imply what is the actual value for such condition.
	 *
	 * This hook is called regardless of whether the action involving checking conditions failed or succeeded.
	 * For instance, in case of Special:UserRights, it may happen that a public 'rights' log is added and another
	 * extension-managed log entry is created for the same action.
	 *
	 * @since 1.46
	 *
	 * @param UserIdentity $performer The user who performed the action revealing the condition value
	 * @param UserIdentity $target The user for whom the condition was evaluated
	 * @param list<mixed> $conditions A list of values, referring to private conditions that were evaluated.
	 *     The values will correspond to constants such as APCOND_BLOCKED
	 */
	public function onReadPrivateUserRequirementsCondition(
		UserIdentity $performer,
		UserIdentity $target,
		array $conditions
	): void;
}
