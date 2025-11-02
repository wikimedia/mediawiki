<?php

namespace MediaWiki\User\Hook;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "AutopromoteCondition" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface AutopromoteConditionHook {
	/**
	 * Use this hook to check autopromote condition for user.
	 *
	 * @since 1.35
	 *
	 * @param string $type Condition type
	 * @param array $args Arguments
	 * @param User $user
	 * @param null|bool &$result Result of checking autopromote condition
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAutopromoteCondition( $type, $args, $user, &$result );
}
