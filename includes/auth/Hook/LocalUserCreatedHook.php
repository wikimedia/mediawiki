<?php

namespace MediaWiki\Auth\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LocalUserCreatedHook {
	/**
	 * Called when a local user has been created
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $user User object for the created user
	 * @param ?mixed $autocreated Boolean, whether this was an auto-creation
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalUserCreated( $user, $autocreated );
}
