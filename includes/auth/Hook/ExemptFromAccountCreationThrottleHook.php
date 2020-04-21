<?php

namespace MediaWiki\Auth\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ExemptFromAccountCreationThrottleHook {
	/**
	 * Exemption from the account creation
	 * throttle.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $ip The ip address of the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onExemptFromAccountCreationThrottle( $ip );
}
