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
	 * @param $string String: header to output
	 * @param $replace Bool: replace current similar header
	 * @param $http_response_code null|int Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		header( $string, $replace, $http_response_code );
	}

	/**
	 * Set the browser cookie
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

/**
 * @ingroup HTTP
 */
class FauxResponse extends WebResponse {
	private $headers;
	private $cookies;

	/**
	 * Stores a HTTP header
	 * @param $string String: header to output
	 * @param $replace Bool: replace current similar header
	 * @param $http_response_code null|int Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		list( $key, $val ) = explode( ":", $string, 2 );

		if( $replace || !isset( $this->headers[$key] ) ) {
			$this->headers[$key] = $val;
		}
	}

	/**
	 * @param $key string
	 * @return string
	 */
	public function getheader( $key ) {
		return $this->headers[$key];
	}

	/**
	 * @param $name String: name of cookie
	 * @param $value String: value to give cookie
	 * @param $expire Int: number of seconds til cookie expires
	 */
	public function setcookie( $name, $value, $expire = 0 ) {
		$this->cookies[$name] = $value;
	}

	/**
	 * @param $name string
	 * @return string
	 */
	public function getcookie( $name )  {
		if ( isset( $this->cookies[$name] ) ) {
			return $this->cookies[$name];
		}
		return null;
	}
}
