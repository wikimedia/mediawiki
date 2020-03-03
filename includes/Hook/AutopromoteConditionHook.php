<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface AutopromoteConditionHook {
	/**
	 * Check autopromote condition for user.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $type condition type
	 * @param ?mixed $args arguments
	 * @param ?mixed $user user
	 * @param ?mixed &$result result of checking autopromote condition
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onAutopromoteCondition( $type, $args, $user, &$result );
}
