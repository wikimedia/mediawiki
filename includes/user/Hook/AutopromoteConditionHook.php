<?php

namespace MediaWiki\User\Hook;

use User;

/**
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
	 * @param array &$result Result of checking autopromote condition
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAutopromoteCondition( $type, $args, $user, &$result );
}
