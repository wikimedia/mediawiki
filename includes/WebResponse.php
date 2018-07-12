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
	protected static $setCookies = [];

	/** @var bool Used to disable setters before running jobs post-request (T191537) */
	protected static $disableForPostSend = false;

	/**
	 * Disable setters for post-send processing
	 *
	 * After this call, self::setCookie(), self::header(), and
	 * self::statusHeader() will log a warning and return without
	 * setting cookies or headers.
	 *
	 * @since 1.32
	 */
	public static function disableForPostSend() {
		self::$disableForPostSend = true;
	}

	/**
	 * Output an HTTP header, wrapper for PHP's header()
	 * @param string $string Header to output
	 * @param bool $replace Replace current similar header
	 * @param null|int $http_response_code Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		if ( self::$disableForPostSend ) {
			wfDebugLog( 'header', 'ignored post-send header {header}', 'all', [
				'header' => $string,
				'replace' => $replace,
				'http_response_code' => $http_response_code,
				'exception' => new RuntimeException( 'Ignored post-send header' ),
			] );
			return;
		}

		\MediaWiki\HeaderCallback::warnIfHeadersSent();
		if ( $http_response_code ) {
			header( $string, $replace, $http_response_code );
		} else {
			header( $string, $replace );
		}
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
		if ( self::$disableForPostSend ) {
			wfDebugLog( 'header', 'ignored post-send status header {code}', 'all', [
				'code' => $code,
				'exception' => new RuntimeException( 'Ignored post-send status header' ),
			] );
			return;
		}

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
	 * @since 1.22 Replaced $prefix, $domain, and $forceSecure with $options
	 */
	public function setCookie( $name, $value, $expire = 0, $options = [] ) {
		global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain;
		global $wgCookieSecure, $wgCookieExpiration, $wgCookieHttpOnly;

		$options = array_filter( $options, function ( $a ) {
			return $a !== null;
		} ) + [
			'prefix' => $wgCookiePrefix,
			'domain' => $wgCookieDomain,
			'path' => $wgCookiePath,
			'secure' => $wgCookieSecure,
			'httpOnly' => $wgCookieHttpOnly,
			'raw' => false,
		];

		if ( $expire === null ) {
			$expire = 0; // Session cookie
		} elseif ( $expire == 0 && $wgCookieExpiration != 0 ) {
			$expire = time() + $wgCookieExpiration;
		}

		if ( self::$disableForPostSend ) {
			$cookie = $options['prefix'] . $name;
			wfDebugLog( 'cookie', 'ignored post-send cookie {cookie}', 'all', [
				'cookie' => $cookie,
				'data' => [
					'name' => (string)$cookie,
					'value' => (string)$value,
					'expire' => (int)$expire,
					'path' => (string)$options['path'],
					'domain' => (string)$options['domain'],
					'secure' => (bool)$options['secure'],
					'httpOnly' => (bool)$options['httpOnly'],
				],
				'exception' => new RuntimeException( 'Ignored post-send cookie' ),
			] );
			return;
		}

		$func = $options['raw'] ? 'setrawcookie' : 'setcookie';

		if ( Hooks::run( 'WebResponseSetCookie', [ &$name, &$value, &$expire, &$options ] ) ) {
			// Note: Don't try to move this earlier to reuse it for self::$disableForPostSend,
			// we need to use the altered values from the hook here. (T198525)
			$cookie = $options['prefix'] . $name;
			$data = [
				'name' => (string)$cookie,
				'value' => (string)$value,
				'expire' => (int)$expire,
				'path' => (string)$options['path'],
				'domain' => (string)$options['domain'],
				'secure' => (bool)$options['secure'],
				'httpOnly' => (bool)$options['httpOnly'],
			];

			// Per RFC 6265, key is name + domain + path
			$key = "{$data['name']}\n{$data['domain']}\n{$data['path']}";

			// If this cookie name was in the request, fake an entry in
			// self::$setCookies for it so the deleting check works right.
			if ( isset( $_COOKIE[$cookie] ) && !array_key_exists( $key, self::$setCookies ) ) {
				self::$setCookies[$key] = [];
			}

			// PHP deletes if value is the empty string; also, a past expiry is deleting
			$deleting = ( $data['value'] === '' || $data['expire'] > 0 && $data['expire'] <= time() );

			if ( $deleting && !isset( self::$setCookies[$key] ) ) { // isset( null ) is false
				wfDebugLog( 'cookie', 'already deleted ' . $func . ': "' . implode( '", "', $data ) . '"' );
			} elseif ( !$deleting && isset( self::$setCookies[$key] ) &&
				self::$setCookies[$key] === [ $func, $data ]
			) {
				wfDebugLog( 'cookie', 'already set ' . $func . ': "' . implode( '", "', $data ) . '"' );
			} else {
				wfDebugLog( 'cookie', $func . ': "' . implode( '", "', $data ) . '"' );
				if ( call_user_func_array( $func, array_values( $data ) ) ) {
					self::$setCookies[$key] = $deleting ? null : [ $func, $data ];
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
	public function clearCookie( $name, $options = [] ) {
		$this->setCookie( $name, '', time() - 31536000 /* 1 year */, $options );
	}

	/**
	 * Checks whether this request is performing cookie operations
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function hasCookies() {
		return (bool)self::$setCookies;
	}
}

/**
 * @ingroup HTTP
 */
class FauxResponse extends WebResponse {
	private $headers;
	private $cookies = [];
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

		return $this->headers[$key] ?? null;
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
	public function setCookie( $name, $value, $expire = 0, $options = [] ) {
		global $wgCookiePath, $wgCookiePrefix, $wgCookieDomain;
		global $wgCookieSecure, $wgCookieExpiration, $wgCookieHttpOnly;

		$options = array_filter( $options, function ( $a ) {
			return $a !== null;
		} ) + [
			'prefix' => $wgCookiePrefix,
			'domain' => $wgCookieDomain,
			'path' => $wgCookiePath,
			'secure' => $wgCookieSecure,
			'httpOnly' => $wgCookieHttpOnly,
			'raw' => false,
		];

		if ( $expire === null ) {
			$expire = 0; // Session cookie
		} elseif ( $expire == 0 && $wgCookieExpiration != 0 ) {
			$expire = time() + $wgCookieExpiration;
		}

		$this->cookies[$options['prefix'] . $name] = [
			'value' => (string)$value,
			'expire' => (int)$expire,
			'path' => (string)$options['path'],
			'domain' => (string)$options['domain'],
			'secure' => (bool)$options['secure'],
			'httpOnly' => (bool)$options['httpOnly'],
			'raw' => (bool)$options['raw'],
		];
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
		return $this->cookies[$name] ?? null;
	}

	/**
	 * @return array
	 */
	public function getCookies() {
		return $this->cookies;
	}
}
