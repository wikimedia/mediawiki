<?php
/**
 * Classes used to send headers and cookies back to the user
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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
	 * @param string $string header to output
	 * @param bool $replace replace current similar header
	 * @param $http_response_code null|int Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		header( $string, $replace, $http_response_code );
	}

	/**
	 * Set the browser cookie
	 * @param string $name name of cookie
	 * @param string $value value to give cookie
	 * @param int $expire Unix timestamp (in seconds) when the cookie should expire.
	 *        0 (the default) causes it to expire $wgCookieExpiration seconds from now.
	 * @param string $prefix Prefix to use, if not $wgCookiePrefix (use '' for no prefix)
	 * @param string $domain Cookie domain to use, if not $wgCookieDomain
	 * @param $forceSecure Bool:
	 *   true: force the cookie to be set with the secure attribute
	 *   false: force the cookie to be set without the secure attribute
	 *   null: use the value from $wgCookieSecure
	 */
	public function setcookie( $name, $value, $expire = 0, $prefix = null, $domain = null, $forceSecure = null ) {
		global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain;
		global $wgCookieSecure, $wgCookieExpiration, $wgCookieHttpOnly;
		if ( $expire == 0 ) {
			$expire = time() + $wgCookieExpiration;
		}
		if( $prefix === null ) {
			$prefix = $wgCookiePrefix;
		}
		if( $domain === null ) {
			$domain = $wgCookieDomain;
		}

		if ( is_null( $forceSecure ) ) {
			$secureCookie = $wgCookieSecure;
		} else {
			$secureCookie = $forceSecure;
		}

		// Mark the cookie as httpOnly if $wgCookieHttpOnly is true,
		// unless the requesting user-agent is known to have trouble with
		// httpOnly cookies.
		$httpOnlySafe = $wgCookieHttpOnly && wfHttpOnlySafe();

		wfDebugLog( 'cookie',
			'setcookie: "' . implode( '", "',
				array(
					$prefix . $name,
					$value,
					$expire,
					$wgCookiePath,
					$domain,
					$secureCookie,
					$httpOnlySafe ) ) . '"' );
		setcookie( $prefix . $name,
			$value,
			$expire,
			$wgCookiePath,
			$domain,
			$secureCookie,
			$httpOnlySafe );
	}
}

/**
 * @ingroup HTTP
 */
class FauxResponse extends WebResponse {
	private $headers;
	private $cookies;
	private $code;

	/**
	 * Stores a HTTP header
	 * @param string $string header to output
	 * @param bool $replace replace current similar header
	 * @param $http_response_code null|int Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		if ( substr( $string, 0, 5 ) == 'HTTP/' ) {
			$parts = explode( ' ', $string, 3 );
			$this->code = intval( $parts[1] );
		} else {
			list( $key, $val ) = array_map( 'trim', explode( ":", $string, 2 ) );

			if( $replace || !isset( $this->headers[$key] ) ) {
				$this->headers[$key] = $val;
			}
		}

		if ( $http_response_code !== null ) {
			$this->code = intval( $http_response_code );
		}
	}

	/**
	 * @param $key string
	 * @return string
	 */
	public function getheader( $key ) {
		if ( isset( $this->headers[$key] ) ) {
			return $this->headers[$key];
		}
		return null;
	}

	/**
	 * Get the HTTP response code, null if not set
	 *
	 * @return Int or null
	 */
	public function getStatusCode() {
		return $this->code;
	}

	/**
	 * @todo document. It just ignore optional parameters.
	 *
	 * @param string $name name of cookie
	 * @param string $value value to give cookie
	 * @param int $expire number of seconds til cookie expires (Default: 0)
	 * @param $prefix TODO DOCUMENT (Default: null)
	 * @param $domain TODO DOCUMENT (Default: null)
	 * @param $forceSecure TODO DOCUMENT (Default: null)
	 */
	public function setcookie( $name, $value, $expire = 0, $prefix = null, $domain = null, $forceSecure = null ) {
		$this->cookies[$name] = $value;
	}

	/**
	 * @param $name string
	 * @return string
	 */
	public function getcookie( $name ) {
		if ( isset( $this->cookies[$name] ) ) {
			return $this->cookies[$name];
		}
		return null;
	}
}
