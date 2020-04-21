<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface WebResponseSetCookieHook {
	/**
	 * when setting a cookie in WebResponse::setcookie().
	 * Return false to prevent setting of the cookie.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$name Cookie name passed to WebResponse::setcookie()
	 * @param ?mixed &$value Cookie value passed to WebResponse::setcookie()
	 * @param ?mixed &$expire Cookie expiration, as for PHP's setcookie()
	 * @param ?mixed &$options Options passed to WebResponse::setcookie()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onWebResponseSetCookie( &$name, &$value, &$expire, &$options );
}
