<?php

namespace MediaWiki\Hook;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface CanIPUseHTTPSHook {
	/**
	 * Use this hook to determine whether the client at a given source IP is likely
	 * to be able to access the wiki via HTTPS.
	 *
	 * @deprecated since 1.35 This feature will be removed. All clients should use HTTPS.
	 *
	 * @since 1.35
	 *
	 * @param string $ip IP address in human-readable form
	 * @param bool &$canDo Set to false if the client may not be able to use HTTPS
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onCanIPUseHTTPS( $ip, &$canDo );
}
