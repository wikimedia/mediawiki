<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface CanIPUseHTTPSHook {
	/**
	 * Determine whether the client at a given source IP is likely
	 * to be able to access the wiki via HTTPS.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $ip The IP address in human-readable form
	 * @param ?mixed &$canDo This reference should be set to false if the client may not be able
	 *   to use HTTPS
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCanIPUseHTTPS( $ip, &$canDo );
}
