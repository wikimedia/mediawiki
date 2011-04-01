<?php
/**
 * Classes used to send headers and cookies back to the user
 *
 * @file
 */

/**
 * Allow programs to request this object from WebRequest::response()
 * and handle all outputting (or lack of outputting) via it.
 * @ingroup HTTP
 */
class WebResponse {

	/**
	 * Output a HTTP header, wrapper for PHP's
	 * header()
	 * @param $string String: header to output
	 * @param $replace Bool: replace current similar header
	 */
	public function header($string, $replace=true) {
		header($string,$replace);
	}

	/** Set the browser cookie
	 * @param $name String: name of cookie
	 * @param $value String: value to give cookie
	 * @param $expire Int: number of seconds til cookie expires
	 */
	public function setcookie( $name, $value, $expire = 0 ) {
		global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain;
		global $wgCookieSecure,$wgCookieExpiration, $wgCookieHttpOnly;
		if ( $expire == 0 ) {
			$expire = time() + $wgCookieExpiration;
		}
		$httpOnlySafe = wfHttpOnlySafe() && $wgCookieHttpOnly;
		wfDebugLog( 'cookie',
			'setcookie: "' . implode( '", "',
				array(
					$wgCookiePrefix . $name,
					$value,
					$expire,
					$wgCookiePath,
					$wgCookieDomain,
					$wgCookieSecure,
					$httpOnlySafe ) ) . '"' );
		setcookie( $wgCookiePrefix . $name,
			$value,
			$expire,
			$wgCookiePath,
			$wgCookieDomain,
			$wgCookieSecure,
			$httpOnlySafe );
	}
}


class FauxResponse extends WebResponse {
	private $headers;
	private $cookies;

	public function header($string, $replace=true) {
		list($key, $val) = explode(":", $string, 2);

		if($replace || !isset($this->headers[$key])) {
			$this->headers[$key] = $val;
		}
	}

	public function getheader($key) {
		return $this->headers[$key];
	}

	public function setcookie( $name, $value, $expire = 0 ) {
		$this->cookies[$name] = $value;
	}

	public function getcookie( $name )  {
		if ( isset($this->cookies[$name]) ) {
			return $this->cookies[$name];
		}
	}
}
