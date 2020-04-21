<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface GetAutoPromoteGroupsHook {
	/**
	 * When determining which autopromote groups a user is
	 * entitled to be in.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user to promote.
	 * @param ?mixed &$promote groups that will be added.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onGetAutoPromoteGroups( $user, &$promote );
}
