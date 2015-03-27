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
	 * Output a HTTP header, wrapper for PHP's header()
	 * @param string $string Header to output
	 * @param bool $replace Replace current similar header
	 * @param null|int $http_response_code Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		header( $string, $replace, $http_response_code );
	}

	/**
	 * Get a response header
	 * @param string $key The name of the header to get (case insensitive).
	 * @return string|null The header value (if set); null otherwise.
	 * @since 1.25
	 */
	public function getHeader( $key ) {
		foreach ( headers_list() as $header ) {
			list( $name, $val ) = explode( ':', $header, 2 );
			if ( !strcasecmp( $name, $key ) ) {
				return trim( $val );
			}
		}
		return null;
	}

	/**
	 * Set the browser cookie
	 * @param string $name The name of the cookie.
	 * @param string $value The value to be stored in the cookie.
	 * @param int|null $expire Unix timestamp (in seconds) when the cookie should expire.
	 *        0 (the default) causes it to expire $wgCookieExpiration seconds from now.
	 *        null causes it to be a session cookie.
	 * @param array $options Assoc of additional cookie options:
	 *     prefix: string, name prefix ($wgCookiePrefix)
	 *     domain: string, cookie domain ($wgCookieDomain)
	 *     path: string, cookie path ($wgCookiePath)
	 *     secure: bool, secure attribute ($wgCookieSecure)
	 *     httpOnly: bool, httpOnly attribute ($wgCookieHttpOnly)
	 *     raw: bool, if true uses PHP's setrawcookie() instead of setcookie()
	 *   For backwards compatibility, if $options is not an array then it and
	 *   the following two parameters will be interpreted as values for
	 *   'prefix', 'domain', and 'secure'
	 * @since 1.22 Replaced $prefix, $domain, and $forceSecure with $options
	 */
	public function setcookie( $name, $value, $expire = 0, $options = array() ) {
		global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain;
		global $wgCookieSecure, $wgCookieExpiration, $wgCookieHttpOnly;

		if ( !is_array( $options ) ) {
			// Backwards compatibility
			$options = array( 'prefix' => $options );
			if ( func_num_args() >= 5 ) {
				$options['domain'] = func_get_arg( 4 );
			}
			if ( func_num_args() >= 6 ) {
				$options['secure'] = func_get_arg( 5 );
			}
		}
		$options = array_filter( $options, function ( $a ) {
			return $a !== null;
		} ) + array(
			'prefix' => $wgCookiePrefix,
			'domain' => $wgCookieDomain,
			'path' => $wgCookiePath,
			'secure' => $wgCookieSecure,
			'httpOnly' => $wgCookieHttpOnly,
			'raw' => false,
		);

		if ( $expire === null ) {
			$expire = 0; // Session cookie
		} elseif ( $expire == 0 && $wgCookieExpiration != 0 ) {
			$expire = time() + $wgCookieExpiration;
		}

		$func = $options['raw'] ? 'setrawcookie' : 'setcookie';

		if ( Hooks::run( 'WebResponseSetCookie', array( &$name, &$value, &$expire, $options ) ) ) {
			wfDebugLog( 'cookie',
				$func . ': "' . implode( '", "',
					array(
						$options['prefix'] . $name,
						$value,
						$expire,
						$options['path'],
						$options['domain'],
						$options['secure'],
						$options['httpOnly'] ) ) . '"' );

			call_user_func( $func,
				$options['prefix'] . $name,
				$value,
				$expire,
				$options['path'],
				$options['domain'],
				$options['secure'],
				$options['httpOnly'] );
		}
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
	 * @param string $string Header to output
	 * @param bool $replace Replace current similar header
	 * @param null|int $http_response_code Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		if ( substr( $string, 0, 5 ) == 'HTTP/' ) {
			$parts = explode( ' ', $string, 3 );
			$this->code = intval( $parts[1] );
		} else {
			list( $key, $val ) = array_map( 'trim', explode( ":", $string, 2 ) );

			$key = strtoupper( $key );

			if ( $replace || !isset( $this->headers[$key] ) ) {
				$this->headers[$key] = $val;
			}
		}

		if ( $http_response_code !== null ) {
			$this->code = intval( $http_response_code );
		}
	}

	/**
	 * @param string $key The name of the header to get (case insensitive).
	 * @return string|null The header value (if set); null otherwise.
	 */
	public function getHeader( $key ) {
		$key = strtoupper( $key );

		if ( isset( $this->headers[$key] ) ) {
			return $this->headers[$key];
		}
		return null;
	}

	/**
	 * Get the HTTP response code, null if not set
	 *
	 * @return int|null
	 */
	public function getStatusCode() {
		return $this->code;
	}

	/**
	 * @param string $name The name of the cookie.
	 * @param string $value The value to be stored in the cookie.
	 * @param int|null $expire Ignored in this faux subclass.
	 * @param array $options Ignored in this faux subclass.
	 */
	public function setcookie( $name, $value, $expire = 0, $options = array() ) {
		$this->cookies[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return string|null
	 */
	public function getcookie( $name ) {
		if ( isset( $this->cookies[$name] ) ) {
			return $this->cookies[$name];
		}
		return null;
	}
}
