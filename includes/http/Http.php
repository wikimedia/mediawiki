<?php
/**
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Various HTTP related functions
 * @deprecated since 1.34
 * @ingroup HTTP
 */
class Http {
	/** @deprecated since 1.34, just use the default engine */
	public static $httpEngine = null;

	/**
	 * Perform an HTTP request
	 *
	 * @deprecated since 1.34, use HttpRequestFactory::request()
	 *
	 * @param string $method HTTP method. Usually GET/POST
	 * @param string $url Full URL to act on. If protocol-relative, will be expanded to an http:// URL
	 * @param array $options Options to pass to MWHttpRequest object. See HttpRequestFactory::create
	 *  docs
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool (bool)false on failure or a string on success
	 */
	public static function request( $method, $url, array $options = [], $caller = __METHOD__ ) {
		$ret = MediaWikiServices::getInstance()->getHttpRequestFactory()->request(
			$method, $url, $options, $caller );
		return is_string( $ret ) ? $ret : false;
	}

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 *
	 * @deprecated since 1.34, use HttpRequestFactory::get()
	 *
	 * @since 1.25 Second parameter $timeout removed. Second parameter
	 * is now $options which can be given a 'timeout'
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool false on error
	 */
	public static function get( $url, array $options = [], $caller = __METHOD__ ) {
		$args = func_get_args();
		if ( isset( $args[1] ) && ( is_string( $args[1] ) || is_numeric( $args[1] ) ) ) {
			// Second was used to be the timeout
			// And third parameter used to be $options
			wfWarn( "Second parameter should not be a timeout.", 2 );
			$options = isset( $args[2] ) && is_array( $args[2] ) ?
				$args[2] : [];
			$options['timeout'] = $args[1];
			$caller = __METHOD__;
		}
		return self::request( 'GET', $url, $options, $caller );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 *
	 * @deprecated since 1.34, use HttpRequestFactory::post()
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool false on error
	 */
	public static function post( $url, array $options = [], $caller = __METHOD__ ) {
		return self::request( 'POST', $url, $options, $caller );
	}

	/**
	 * A standard user-agent we can use for external requests.
	 *
	 * @deprecated since 1.34, use HttpRequestFactory::getUserAgent()
	 * @return string
	 */
	public static function userAgent() {
		return MediaWikiServices::getInstance()->getHttpRequestFactory()->getUserAgent();
	}

	/**
	 * Check that the given URI is a valid one.
	 *
	 * This hardcodes a small set of protocols only, because we want to
	 * deterministically reject protocols not supported by all HTTP-transport
	 * methods.
	 *
	 * "file://" specifically must not be allowed, for security purpose
	 * (see <https://www.mediawiki.org/wiki/Special:Code/MediaWiki/r67684>).
	 *
	 * @todo FIXME this is wildly inaccurate and fails to actually check most stuff
	 *
	 * @deprecated since 1.34, use MWHttpRequest::isValidURI
	 * @param string $uri URI to check for validity
	 * @return bool
	 */
	public static function isValidURI( $uri ) {
		return MWHttpRequest::isValidURI( $uri );
	}

	/**
	 * Gets the relevant proxy from $wgHTTPProxy
	 *
	 * @deprecated since 1.34, use $wgHTTPProxy directly
	 * @return string The proxy address or an empty string if not set.
	 */
	public static function getProxy() {
		wfDeprecated( __METHOD__, '1.34' );

		global $wgHTTPProxy;
		return (string)$wgHTTPProxy;
	}

	/**
	 * Get a configured MultiHttpClient
	 *
	 * @deprecated since 1.34, construct it directly
	 * @param array $options
	 * @return MultiHttpClient
	 */
	public static function createMultiClient( array $options = [] ) {
		wfDeprecated( __METHOD__, '1.34' );

		global $wgHTTPConnectTimeout, $wgHTTPTimeout, $wgHTTPProxy;

		return new MultiHttpClient( $options + [
			'connTimeout' => $wgHTTPConnectTimeout,
			'reqTimeout' => $wgHTTPTimeout,
			'userAgent' => self::userAgent(),
			'proxy' => $wgHTTPProxy,
			'logger' => LoggerFactory::getInstance( 'http' )
		] );
	}
}
