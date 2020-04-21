<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiOptionsHook {
	/**
	 * Called by action=options before applying changes to user
	 * preferences.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $apiModule Calling ApiOptions object
	 * @param ?mixed $user User object whose preferences are being changed
	 * @param ?mixed $changes Associative array of preference name => value
	 * @param ?mixed $resetKinds Array of strings specifying which options kinds to reset.
	 *   See User::resetOptions() and User::getOptionKinds() for possible
	 *   values.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiOptions( $apiModule, $user, $changes, $resetKinds );
}
