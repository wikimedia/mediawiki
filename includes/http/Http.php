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

/**
 * Various HTTP related functions
 * @ingroup HTTP
 */
class Http {
	static public $httpEngine = false;

	/**
	 * Perform an HTTP request
	 *
	 * @param string $method HTTP method. Usually GET/POST
	 * @param string $url Full URL to act on. If protocol-relative, will be expanded to an http:// URL
	 * @param array $options Options to pass to MWHttpRequest object.
	 *	Possible keys for the array:
	 *    - timeout             Timeout length in seconds
	 *    - connectTimeout      Timeout for connection, in seconds (curl only)
	 *    - postData            An array of key-value pairs or a url-encoded form data
	 *    - proxy               The proxy to use.
	 *                          Otherwise it will use $wgHTTPProxy (if set)
	 *                          Otherwise it will use the environment variable "http_proxy" (if set)
	 *    - noProxy             Don't use any proxy at all. Takes precedence over proxy value(s).
	 *    - sslVerifyHost       Verify hostname against certificate
	 *    - sslVerifyCert       Verify SSL certificate
	 *    - caInfo              Provide CA information
	 *    - maxRedirects        Maximum number of redirects to follow (defaults to 5)
	 *    - followRedirects     Whether to follow redirects (defaults to false).
	 *                          Note: this should only be used when the target URL is trusted,
	 *                          to avoid attacks on intranet services accessible by HTTP.
	 *    - userAgent           A user agent, if you want to override the default
	 *                          MediaWiki/$wgVersion
	 *    - logger              A \Psr\Logger\LoggerInterface instance for debug logging
	 *    - username            Username for HTTP Basic Authentication
	 *    - password            Password for HTTP Basic Authentication
	 *    - originalRequest     Information about the original request (as a WebRequest object or
	 *                          an associative array with 'ip' and 'userAgent').
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool (bool)false on failure or a string on success
	 */
	public static function request( $method, $url, $options = [], $caller = __METHOD__ ) {
		wfDebug( "HTTP: $method: $url\n" );

		$options['method'] = strtoupper( $method );

		if ( !isset( $options['timeout'] ) ) {
			$options['timeout'] = 'default';
		}
		if ( !isset( $options['connectTimeout'] ) ) {
			$options['connectTimeout'] = 'default';
		}

		$req = MWHttpRequest::factory( $url, $options, $caller );
		$status = $req->execute();

		if ( $status->isOK() ) {
			return $req->getContent();
		} else {
			$errors = $status->getErrorsByType( 'error' );
			$logger = LoggerFactory::getInstance( 'http' );
			$logger->warning( Status::wrap( $status )->getWikiText( false, false, 'en' ),
				[ 'error' => $errors, 'caller' => $caller, 'content' => $req->getContent() ] );
			return false;
		}
	}

	/**
	 * Simple wrapper for Http::request( 'GET' )
	 * @see Http::request()
	 * @since 1.25 Second parameter $timeout removed. Second parameter
	 * is now $options which can be given a 'timeout'
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool false on error
	 */
	public static function get( $url, $options = [], $caller = __METHOD__ ) {
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
		return Http::request( 'GET', $url, $options, $caller );
	}

	/**
	 * Simple wrapper for Http::request( 'POST' )
	 * @see Http::request()
	 *
	 * @param string $url
	 * @param array $options
	 * @param string $caller The method making this request, for profiling
	 * @return string|bool false on error
	 */
	public static function post( $url, $options = [], $caller = __METHOD__ ) {
		return Http::request( 'POST', $url, $options, $caller );
	}

	/**
	 * A standard user-agent we can use for external requests.
	 * @return string
	 */
	public static function userAgent() {
		global $wgVersion;
		return "MediaWiki/$wgVersion";
	}

	/**
	 * Checks that the given URI is a valid one. Hardcoding the
	 * protocols, because we only want protocols that both cURL
	 * and php support.
	 *
	 * file:// should not be allowed here for security purpose (r67684)
	 *
	 * @todo FIXME this is wildly inaccurate and fails to actually check most stuff
	 *
	 * @param string $uri URI to check for validity
	 * @return bool
	 */
	public static function isValidURI( $uri ) {
		return (bool)preg_match(
			'/^https?:\/\/[^\/\s]\S*$/D',
			$uri
		);
	}

	/**
	 * Gets the relevant proxy from $wgHTTPProxy
	 *
	 * @return mixed The proxy address or an empty string if not set.
	 */
	public static function getProxy() {
		global $wgHTTPProxy;

		if ( $wgHTTPProxy ) {
			return $wgHTTPProxy;
		}

		return "";
	}
}
