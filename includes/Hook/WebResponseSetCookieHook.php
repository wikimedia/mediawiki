<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface WebResponseSetCookieHook {
	/**
	 * This hook is called when setting a cookie in WebResponse::setcookie().
	 *
	 * @since 1.35
	 *
	 * @param string &$name Cookie name passed to WebResponse::setcookie()
	 * @param string &$value Cookie value passed to WebResponse::setcookie()
	 * @param int|null &$expire Cookie expiration, as for PHP's setcookie()
	 * @param array &$options Options passed to WebResponse::setcookie()
	 * @return bool|void True or no return value to continue, or false to prevent setting of the cookie
	 */
	public function onWebResponseSetCookie( &$name, &$value, &$expire, &$options );
}
