<?php

namespace MediaWiki\Diff\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface DifferenceEngineMarkPatrolledRCIDHook {
	/**
	 * Allows extensions to possibly change the
	 * rcid parameter. For example the rcid might be set to zero due to the user being
	 * the same as the performer of the change but an extension might still want to
	 * show it under certain conditions.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$rcid rc_id (int) of the change or 0
	 * @param ?mixed $differenceEngine DifferenceEngine object
	 * @param ?mixed $change RecentChange object
	 * @param ?mixed $user User object representing the current user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onDifferenceEngineMarkPatrolledRCID( &$rcid, $differenceEngine,
		$change, $user
	);
}
