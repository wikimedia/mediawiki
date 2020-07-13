<?php

namespace MediaWiki\Auth\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ExemptFromAccountCreationThrottleHook {
	/**
	 * Use this hook to add an exemption from the account creation throttle.
	 *
	 * @since 1.35
	 *
	 * @param string $ip IP address of the user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onExemptFromAccountCreationThrottle( $ip );
}
