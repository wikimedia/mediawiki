<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface IsTrustedProxyHook {
	/**
	 * Override the result of ProxyLookup::isTrustedProxy()
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $ip IP being check
	 * @param ?mixed &$result Change this value to override the result of ProxyLookup::isTrustedProxy()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onIsTrustedProxy( $ip, &$result );
}
