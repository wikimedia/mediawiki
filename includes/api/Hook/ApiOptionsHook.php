<?php

namespace MediaWiki\Api\Hook;

use ApiOptions;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiOptionsHook {
	/**
	 * This hook is called by action=options before applying changes to user
	 * preferences.
	 *
	 * @since 1.35
	 *
	 * @param ApiOptions $apiModule Calling ApiOptions object
	 * @param User $user User object whose preferences are being changed
	 * @param array $changes Associative array of preference name => value
	 * @param string[] $resetKinds Array of strings specifying which options kinds to reset
	 *   See User::resetOptions() and User::getOptionKinds() for possible values.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiOptions( $apiModule, $user, $changes, $resetKinds );
}
