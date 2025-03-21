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

namespace MediaWiki\Request;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use RuntimeException;
use Wikimedia\Http\HttpStatus;

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
	protected $disableForPostSend = false;

	/**
	 * Disable setters for post-send processing
	 *
	 * After this call, self::setCookie(), self::header(), and
	 * self::statusHeader() will log a warning and return without
	 * setting cookies or headers.
	 *
	 * @since 1.32 (non-static since 1.42)
	 */
	public function disableForPostSend() {
		$this->disableForPostSend = true;
	}

	/**
	 * Output an HTTP header, wrapper for PHP's header()
	 * @param string $string Header to output
	 * @param bool $replace Replace current similar header
	 * @param null|int $http_response_code Forces the HTTP response code to the specified value.
	 */
	public function header( $string, $replace = true, $http_response_code = null ) {
		if ( $this->disableForPostSend ) {
			wfDebugLog( 'header', 'ignored post-send header {header}', 'all', [
				'header' => $string,
				'replace' => $replace,
				'http_response_code' => $http_response_code,
				'exception' => new RuntimeException( 'Ignored post-send header' ),
			] );
			return;
		}

		\MediaWiki\Request\HeaderCallback::warnIfHeadersSent();
		if ( $http_response_code ) {
			header( $string, $replace, $http_response_code );
		} else {
			header( $string, $replace );
		}
	}

	/**
	 * @see http_response_code
	 * @return int|bool
	 * @since 1.42
	 */
	public function getStatusCode() {
		return http_response_code();
	}

	/**
	 * Get a response header
	 * @param string $key The name of the header to get (case insensitive).
	 * @return string|null The header value (if set); null otherwise.
	 * @since 1.25
	 */
	public function getHeader( $key ) {
		foreach ( headers_list() as $header ) {
			[ $name, $val ] = explode( ':', $header, 2 );
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
		if ( $this->disableForPostSend ) {
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
	 *
	 * @param string $name The name of the cookie.
	 * @param string $value The value to be stored in the cookie.
	 * @param int|null $expire Unix timestamp (in seconds) when the cookie should expire.
	 *   - 0 (the default) causes it to expire $wgCookieExpiration seconds from now.
	 *   - null causes it to be a session cookie.
	 * @param array $options Assoc of additional cookie options:
	 *   - prefix: string, name prefix ($wgCookiePrefix)
	 *   - domain: string, cookie domain ($wgCookieDomain)
	 *   - path: string, cookie path ($wgCookiePath)
	 *   - secure: bool, secure attribute ($wgCookieSecure)
	 *   - httpOnly: bool, httpOnly attribute ($wgCookieHttpOnly)
	 *   - raw: bool, true to suppress encoding of the value
	 *   - sameSite: string|null, SameSite attribute. May be "strict", "lax",
	 *     "none", or null or "" for no attribute. (default absent)
	 *   - sameSiteLegacy: bool|null, this option is now ignored
	 * @since 1.22 Replaced $prefix, $domain, and $forceSecure with $options
	 */
	public function setCookie( $name, $value, $expire = 0, $options = [] ) {
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$cookiePath = $mainConfig->get( MainConfigNames::CookiePath );
		$cookiePrefix = $mainConfig->get( MainConfigNames::CookiePrefix );
		$cookieDomain = $mainConfig->get( MainConfigNames::CookieDomain );
		$cookieSecure = $mainConfig->get( MainConfigNames::CookieSecure );
		$cookieExpiration = $mainConfig->get( MainConfigNames::CookieExpiration );
		$cookieHttpOnly = $mainConfig->get( MainConfigNames::CookieHttpOnly );
		$options = array_filter( $options, static function ( $a ) {
			return $a !== null;
		} ) + [
			'prefix' => $cookiePrefix,
			'domain' => $cookieDomain,
			'path' => $cookiePath,
			'secure' => $cookieSecure,
			'httpOnly' => $cookieHttpOnly,
			'raw' => false,
			'sameSite' => '',
		];

		if ( $expire === null ) {
			$expire = 0; // Session cookie
		} elseif ( $expire == 0 && $cookieExpiration != 0 ) {
			$expire = time() + $cookieExpiration;
		}

		if ( $this->disableForPostSend ) {
			$prefixedName = $options['prefix'] . $name;
			wfDebugLog( 'cookie', 'ignored post-send cookie {cookie}', 'all', [
				'cookie' => $prefixedName,
				'data' => [
					'name' => $prefixedName,
					'value' => (string)$value,
					'expire' => (int)$expire,
					'path' => (string)$options['path'],
					'domain' => (string)$options['domain'],
					'secure' => (bool)$options['secure'],
					'httpOnly' => (bool)$options['httpOnly'],
					'sameSite' => (string)$options['sameSite']
				],
				'exception' => new RuntimeException( 'Ignored post-send cookie' ),
			] );
			return;
		}

		$hookRunner = new HookRunner( $services->getHookContainer() );
		if ( !$hookRunner->onWebResponseSetCookie( $name, $value, $expire, $options ) ) {
			return;
		}

		// Note: Don't try to move this earlier to reuse it for $this->disableForPostSend,
		// we need to use the altered values from the hook here. (T198525)
		$prefixedName = $options['prefix'] . $name;
		$value = (string)$value;
		$func = $options['raw'] ? 'setrawcookie' : 'setcookie';
		$setOptions = [
			'expires' => (int)$expire,
			'path' => (string)$options['path'],
			'domain' => (string)$options['domain'],
			'secure' => (bool)$options['secure'],
			'httponly' => (bool)$options['httpOnly'],
			'samesite' => (string)$options['sameSite'],
		];

		// Per RFC 6265, key is name + domain + path
		$key = "{$prefixedName}\n{$setOptions['domain']}\n{$setOptions['path']}";

		// If this cookie name was in the request, fake an entry in
		// self::$setCookies for it so the deleting check works right.
		if ( isset( $_COOKIE[$prefixedName] ) && !array_key_exists( $key, self::$setCookies ) ) {
			self::$setCookies[$key] = [];
		}

		// PHP deletes if value is the empty string; also, a past expiry is deleting
		$deleting = ( $value === '' || ( $setOptions['expires'] > 0 && $setOptions['expires'] <= time() ) );

		$logDesc = "$func: \"$prefixedName\", \"$value\", \"" .
			implode( '", "', array_map( 'strval', $setOptions ) ) . '"';
		$optionsForDeduplication = [ $func, $prefixedName, $value, $setOptions ];

		if ( $deleting && !isset( self::$setCookies[$key] ) ) { // isset( null ) is false
			wfDebugLog( 'cookie', "already deleted $logDesc" );
			return;
		} elseif ( !$deleting && isset( self::$setCookies[$key] ) &&
			self::$setCookies[$key] === $optionsForDeduplication
		) {
			wfDebugLog( 'cookie', "already set $logDesc" );
			return;
		}

		wfDebugLog( 'cookie', $logDesc );
		if ( $func === 'setrawcookie' ) {
			setrawcookie( $prefixedName, $value, $setOptions );
		} else {
			setcookie( $prefixedName, $value, $setOptions );
		}
		self::$setCookies[$key] = $deleting ? null : $optionsForDeduplication;
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
		$this->setCookie( $name, '', time() - 31_536_000 /* 1 year */, $options );
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
