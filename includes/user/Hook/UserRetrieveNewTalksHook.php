<?php

namespace MediaWiki\User\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UserRetrieveNewTalksHook {
	/**
	 * Called when retrieving "You have new messages!"
	 * message(s).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user user retrieving new talks messages
	 * @param ?mixed &$talks array of new talks page(s)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUserRetrieveNewTalks( $user, &$talks );
}
