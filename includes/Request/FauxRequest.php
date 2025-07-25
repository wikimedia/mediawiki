<?php
/**
 * Deal with importing all those nasty globals and things
 *
 * Copyright © 2003 Brooke Vibber <bvibber@wikimedia.org>
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

namespace MediaWiki\Request;

use InvalidArgumentException;
use MediaWiki;
use MediaWiki\Exception\MWException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\SessionManager;

/**
 * WebRequest clone which takes values from a provided array.
 *
 * @newable
 *
 * @ingroup HTTP
 */
class FauxRequest extends WebRequest {
	private bool $wasPosted;
	private ?string $requestUrl = null;
	private array $serverInfo;

	protected array $cookies = [];
	private array $uploadData = [];

	/**
	 * @stable to call
	 *
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param bool $wasPosted Whether to treat the data as POST
	 * @param MediaWiki\Session\Session|array|null $session Session, session
	 *  data array, or null
	 * @param string $protocol 'http' or 'https'
	 */
	public function __construct( array $data = [], $wasPosted = false,
								$session = null, $protocol = 'http'
	) {
		$this->requestTime = microtime( true );
		$this->serverInfo = $_SERVER;

		$this->data = $data;
		$this->wasPosted = $wasPosted;
		if ( $session instanceof MediaWiki\Session\Session ) {
			$this->session = $session;
			$this->sessionId = $session->getSessionId();
		} elseif ( is_array( $session ) ) {
			$mwsession = SessionManager::singleton()->getEmptySession( $this );
			$this->session = $mwsession;
			$this->sessionId = $mwsession->getSessionId();
			foreach ( $session as $key => $value ) {
				$mwsession->set( $key, $value );
			}
		} elseif ( $session !== null ) {
			throw new InvalidArgumentException( "MediaWiki\Request\FauxRequest() got bogus session" );
		}
		$this->protocol = $protocol;
	}

	public function response(): FauxResponse {
		/* Lazy initialization of response object for this request */
		if ( !$this->response ) {
			$this->response = new FauxResponse();
		}
		return $this->response;
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
	public function getQueryValues() {
		if ( $this->wasPosted ) {
			return [];
		} else {
			return $this->data;
		}
	}

	public function getQueryValuesOnly() {
		return $this->getQueryValues();
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
			$cookiePrefix = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::CookiePrefix );
			$prefix = $cookiePrefix;
		}
		$name = $prefix . $key;
		return $this->cookies[$name] ?? $default;
	}

	/**
	 * @param string $key Unprefixed name of the cookie to set
	 * @param string|null $value Value of the cookie to set
	 * @param string|null $prefix Cookie prefix. Defaults to $wgCookiePrefix
	 * @since 1.26
	 */
	public function setCookie( $key, $value, $prefix = null ) {
		$this->setCookies( [ $key => $value ], $prefix );
	}

	/**
	 * @param array $cookies
	 * @param string|null $prefix Cookie prefix. Defaults to $wgCookiePrefix
	 * @since 1.26
	 */
	public function setCookies( $cookies, $prefix = null ) {
		if ( $prefix === null ) {
			$cookiePrefix = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::CookiePrefix );
			$prefix = $cookiePrefix;
		}
		foreach ( $cookies as $key => $value ) {
			$name = $prefix . $key;
			$this->cookies[$name] = $value;
		}
	}

	/**
	 * Set fake upload data for all files
	 *
	 * @param (array|WebRequestUpload)[] $uploadData
	 * @since 1.37
	 */
	public function setUploadData( $uploadData ) {
		foreach ( $uploadData as $key => $data ) {
			$this->setUpload( $key, $data );
		}
	}

	/**
	 * Set fake upload data for one file with specific key
	 *
	 * @param string $key
	 * @param array|WebRequestUpload $data
	 * @since 1.37
	 */
	public function setUpload( $key, $data ) {
		if ( $data instanceof WebRequestUpload ) {
			// cannot reuse MediaWiki\Request\WebRequestUpload, because it contains the original web request object
			$data = [
				'name' => $data->getName(),
				'type' => $data->getType(),
				'tmp_name' => $data->getTempName(),
				'size' => $data->getSize(),
				'error' => $data->getError(),
			];
		}
		// Check if everything is provided
		if ( !is_array( $data ) ||
			array_diff( WebRequestUpload::REQUIRED_FILEINFO_KEYS, array_keys( $data ) ) !== []
		) {
			throw new InvalidArgumentException( __METHOD__ . ' got bogus data' );
		}
		$this->uploadData[$key] = $data;
	}

	/**
	 * Return a MediaWiki\Request\FauxRequestUpload object corresponding to the key
	 *
	 * @param string $key
	 * @return FauxRequestUpload
	 */
	public function getUpload( $key ) {
		return new FauxRequestUpload( $this->uploadData, $this, $key );
	}

	/**
	 * @param string $url
	 *
	 * @since 1.25
	 */
	public function setRequestURL( string $url ) {
		$this->requestUrl = $url;

		if ( preg_match( '@^(.*)://@', $url, $m ) ) {
			$this->protocol = $m[1];
		}
	}

	/**
	 * @since 1.42
	 * @return bool
	 */
	public function hasRequestURL(): bool {
		return $this->requestUrl !== null;
	}

	protected function getServerInfo( $name, $default = null ): ?string {
		return $this->serverInfo[$name] ?? $default;
	}

	/**
	 * @see $_SERVER
	 * @param array $info
	 */
	public function setServerInfo( array $info ) {
		$this->serverInfo = $info;
	}

	/**
	 * @return string
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
	 * @param array $headers
	 * @since 1.26
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

	public function getPostValues() {
		return $this->wasPosted ? $this->data : [];
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
	 * @return string
	 */
	protected function getRawIP() {
		return '127.0.0.1';
	}
}
