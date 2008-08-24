<?php
/**
 * Allow programs to request this object from WebRequest::response()
 * and handle all outputting (or lack of outputting) via it.
 */
class WebResponse {

	/** 
	 * Output a HTTP header, wrapper for PHP's
	 * header()
	 * @param string $string Header to output
	 * @param bool $replace Replace current similar header
	 */
	public function header($string, $replace=true) {
		header($string,$replace);
	}

	/** Set the browser cookie
	 * @param string $name Name of cookie
	 * @param string $value Value to give cookie
	 * @param int $expire Number of seconds til cookie expires
	 */
	public function setcookie( $name, $value, $expire = 0 ) {
		global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain;
		global $wgCookieSecure,$wgCookieExpiration, $wgCookieHttpOnly;
		if ( $expire == 0 ) {
			$expire = time() + $wgCookieExpiration;
		}
		$httpOnlySafe = wfHttpOnlySafe();
		wfDebugLog( 'cookie',
			'setcookie: "' . implode( '", "',
				array(
					$wgCookiePrefix . $name,
					$value,
					$expire,
					$wgCookiePath,
					$wgCookieDomain,
					$wgCookieSecure,
					$httpOnlySafe && $wgCookieHttpOnly ) ) . '"' );
		if( $httpOnlySafe && isset( $wgCookieHttpOnly ) ) {
			setcookie( $wgCookiePrefix . $name,
				$value,
				$expire,
				$wgCookiePath,
				$wgCookieDomain,
				$wgCookieSecure,
				$wgCookieHttpOnly );
		} else {
			// setcookie() fails on PHP 5.1 if you give it future-compat paramters.
			// stab stab!
			setcookie( $wgCookiePrefix . $name,
				$value,
				$expire,
				$wgCookiePath,
				$wgCookieDomain,
				$wgCookieSecure );
		}
	}
}
