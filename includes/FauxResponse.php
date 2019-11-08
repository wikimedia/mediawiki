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
	 * @return array[]
	 */
	public function getCookies() {
		return $this->cookies;
	}

	/**
	 * @inheritDoc
	 */
	public function hasCookies() {
		return count( $this->cookies ) > 0;
	}
}
