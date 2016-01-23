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

	/** @var array Used to record set cookies, because PHP's setcookie() will
	 * happily send an identical Set-Cookie to the client.
	 */
	protected static $setCookies = array();

	/**
	 * Output an HTTP header, wrapper for PHP's header()
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
	 * Output an HTTP status code header
	 * @since 1.26
	 * @param int $code Status code
	 */
	public function statusHeader( $code ) {
		HttpStatus::header( $code );
	}

	/**
	 * Test if headers have been sent
	 * @since 1.27
	 * @return bool
	 */
	public function headersSent() {
		return headers_sent();
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
	public function setCookie( $name, $value, $expire = 0, $options = array() ) {
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
			$cookie = $options['prefix'] . $name;
			$data = array(
				'name' => (string)$cookie,
				'value' => (string)$value,
				'expire' => (int)$expire,
				'path' => (string)$options['path'],
				'domain' => (string)$options['domain'],
				'secure' => (bool)$options['secure'],
				'httpOnly' => (bool)$options['httpOnly'],
			);

			// Per RFC 6265, key is name + domain + path
			$key = "{$data['name']}\n{$data['domain']}\n{$date['path']}";

			// If this cookie name was in the request, fake an entry in
			// self::$setCookies for it so the deleting check works right.
			if ( isset( $_COOKIE[$cookie] ) && !array_key_exists( $key, self::$setCookies ) ) {
				self::$setCookies[$key] = array();
			}

			// PHP deletes if value is the empty string; also, a past expiry is deleting
			$deleting = ( $data['value'] === '' || $data['expire'] > 0 && $data['expire'] <= time() );

			if ( $deleting && !isset( self::$setCookies[$key] ) ) { // isset( null ) is false
				wfDebugLog( 'cookie', 'already deleted ' . $func . ': "' . implode( '", "', $data ) . '"' );
			} elseif ( !$deleting && isset( self::$setCookies[$key] ) &&
				self::$setCookies[$key] === array( $func, $data )
			) {
				wfDebugLog( 'cookie', 'already set ' . $func . ': "' . implode( '", "', $data ) . '"' );
			} else {
				wfDebugLog( 'cookie', $func . ': "' . implode( '", "', $data ) . '"' );
				if ( call_user_func_array( $func, array_values( $data ) ) ) {
					self::$setCookies[$key] = $deleting ? null : array( $func, $data );
				}
			}
		}
	}

	/**
	 * Unset a browser cookie.
	 * This sets the cookie with an empty value and an expiry set to a time in the past,
	 * which will cause the browser to remove any cookie with the given name, domain and
	 * path from its cookie store. Options other than these (and prefix) have no effect.
	 * @param string $name Cookie name
	 * @param array $options Cookie options, see {@link setCookie()}
	 * @since 1.27
	 */
	public function clearCookie( $name, $options = array() ) {
		$this->setCookie( $name, '', time() - 31536000 /* 1 year */, $options );
	}
}

/**
 * @ingroup HTTP
 */
class FauxResponse extends WebResponse {
	private $headers;
	private $cookies = array();
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
	 * @since 1.26
	 * @param int $code Status code
	 */
	public function statusHeader( $code ) {
		$this->code = intval( $code );
	}

	public function headersSent() {
		return false;
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
	public function setCookie( $name, $value, $expire = 0, $options = array() ) {
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

		$this->cookies[$options['prefix'] . $name] = array(
			'value' => (string)$value,
			'expire' => (int)$expire,
			'path' => (string)$options['path'],
			'domain' => (string)$options['domain'],
			'secure' => (bool)$options['secure'],
			'httpOnly' => (bool)$options['httpOnly'],
			'raw' => (bool)$options['raw'],
		);
	}

	/**
	 * @param string $name
	 * @return string|null
	 */
	public function getCookie( $name ) {
		if ( isset( $this->cookies[$name] ) ) {
			return $this->cookies[$name]['value'];
		}
		return null;
	}

	/**
	 * @param string $name
	 * @return array|null
	 */
	public function getCookieData( $name ) {
		if ( isset( $this->cookies[$name] ) ) {
			return $this->cookies[$name];
		}
		return null;
	}

	/**
	 * @return array
	 */
	public function getCookies() {
		return $this->cookies;
	}
}
