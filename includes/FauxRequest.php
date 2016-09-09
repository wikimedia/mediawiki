<?php
/**
 * Deal with importing all those nasty globals and things
 *
 * Copyright © 2003 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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

use MediaWiki\Session\SessionManager;

/**
 * WebRequest clone which takes values from a provided array.
 *
 * @ingroup HTTP
 */
class FauxRequest extends WebRequest {
	private $wasPosted = false;
	private $requestUrl;
	protected $cookies = [];

	/**
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param bool $wasPosted Whether to treat the data as POST
	 * @param MediaWiki\Session\Session|array|null $session Session, session
	 *  data array, or null
	 * @param string $protocol 'http' or 'https'
	 * @throws MWException
	 */
	public function __construct( $data = [], $wasPosted = false,
		$session = null, $protocol = 'http'
	) {
		$this->requestTime = microtime( true );

		if ( is_array( $data ) ) {
			$this->data = $data;
		} else {
			throw new MWException( "FauxRequest() got bogus data" );
		}
		$this->wasPosted = $wasPosted;
		if ( $session instanceof MediaWiki\Session\Session ) {
			$this->sessionId = $session->getSessionId();
		} elseif ( is_array( $session ) ) {
			$mwsession = SessionManager::singleton()->getEmptySession( $this );
			$this->sessionId = $mwsession->getSessionId();
			foreach ( $session as $key => $value ) {
				$mwsession->set( $key, $value );
			}
		} elseif ( $session !== null ) {
			throw new MWException( "FauxRequest() got bogus session" );
		}
		$this->protocol = $protocol;
	}

	/**
	 * Initialise the header list
	 */
	protected function initHeaders() {
		// Nothing to init
	}

	/**
	 * @param string $name
	 * @param string $default
	 * @return string
	 */
	public function getText( $name, $default = '' ) {
		# Override; don't recode since we're using internal data
		return (string)$this->getVal( $name, $default );
	}

	/**
	 * @return array
	 */
	public function getValues() {
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function getQueryValues() {
		if ( $this->wasPosted ) {
			return [];
		} else {
			return $this->data;
		}
	}

	public function getMethod() {
		return $this->wasPosted ? 'POST' : 'GET';
	}

	/**
	 * @return bool
	 */
	public function wasPosted() {
		return $this->wasPosted;
	}

	public function getCookie( $key, $prefix = null, $default = null ) {
		if ( $prefix === null ) {
			global $wgCookiePrefix;
			$prefix = $wgCookiePrefix;
		}
		$name = $prefix . $key;
		return isset( $this->cookies[$name] ) ? $this->cookies[$name] : $default;
	}

	/**
	 * @since 1.26
	 * @param string $name Unprefixed name of the cookie to set
	 * @param string|null $value Value of the cookie to set
	 * @param string|null $prefix Cookie prefix. Defaults to $wgCookiePrefix
	 */
	public function setCookie( $key, $value, $prefix = null ) {
		$this->setCookies( [ $key => $value ], $prefix );
	}

	/**
	 * @since 1.26
	 * @param array $cookies
	 * @param string|null $prefix Cookie prefix. Defaults to $wgCookiePrefix
	 */
	public function setCookies( $cookies, $prefix = null ) {
		if ( $prefix === null ) {
			global $wgCookiePrefix;
			$prefix = $wgCookiePrefix;
		}
		foreach ( $cookies as $key => $value ) {
			$name = $prefix . $key;
			$this->cookies[$name] = $value;
		}
	}

	/**
	 * @since 1.25
	 */
	public function setRequestURL( $url ) {
		$this->requestUrl = $url;
	}

	/**
	 * @since 1.25 MWException( "getRequestURL not implemented" )
	 * no longer thrown.
	 */
	public function getRequestURL() {
		if ( $this->requestUrl === null ) {
			throw new MWException( 'Request URL not set' );
		}
		return $this->requestUrl;
	}

	public function getProtocol() {
		return $this->protocol;
	}

	/**
	 * @param string $name
	 * @param string $val
	 */
	public function setHeader( $name, $val ) {
		$this->setHeaders( [ $name => $val ] );
	}

	/**
	 * @since 1.26
	 * @param array $headers
	 */
	public function setHeaders( $headers ) {
		foreach ( $headers as $name => $val ) {
			$name = strtoupper( $name );
			$this->headers[$name] = $val;
		}
	}

	/**
	 * @return array|null
	 */
	public function getSessionArray() {
		if ( $this->sessionId !== null ) {
			return iterator_to_array( $this->getSession() );
		}
		return null;
	}

	/**
	 * FauxRequests shouldn't depend on raw request data (but that could be implemented here)
	 * @return string
	 */
	public function getRawQueryString() {
		return '';
	}

	/**
	 * FauxRequests shouldn't depend on raw request data (but that could be implemented here)
	 * @return string
	 */
	public function getRawPostString() {
		return '';
	}

	/**
	 * FauxRequests shouldn't depend on raw request data (but that could be implemented here)
	 * @return string
	 */
	public function getRawInput() {
		return '';
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $extWhitelist
	 * @return bool
	 */
	public function checkUrlExtension( $extWhitelist = [] ) {
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	protected function getRawIP() {
		return '127.0.0.1';
	}
}
