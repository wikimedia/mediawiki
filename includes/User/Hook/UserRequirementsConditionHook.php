<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\UserIdentity;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UserRequirementsCondition" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UserRequirementsConditionHook {
	/**
	 * Use this hook to check user requirements condition for a user.
	 *
	 * @since 1.46
	 *
	 * @param string|int $type The evaluated condition
	 * @param array $args Arguments to the condition
	 * @param UserIdentity $user The user who's subject to the condition check. This user may or may not be the
	 *   one who is performing the current request. Furthermore, they may or may not be from the local wiki.
	 * @param bool $isPerformingRequest True if $user is the one performing the current request. If false,
	 *   the hook handler should avoid comparing the user against any session- or request-related data, but
	 *   should still set the $result to either true or false, to indicate that the condition was recognized.
	 * @param null|bool &$result Result of checking the condition
	 */
	public function onUserRequirementsCondition(
		$type,
		array $args,
		UserIdentity $user,
		bool $isPerformingRequest,
		?bool &$result
	): void;
}
