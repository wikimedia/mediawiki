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
namespace MediaWiki\Http;

use CurlHttpRequest;
use GuzzleHttpRequest;
use Http;
use MediaWiki\Logger\LoggerFactory;
use MWHttpRequest;
use PhpHttpRequest;
use Profiler;
use RuntimeException;
use Status;

/**
 * Factory creating MWHttpRequest objects.
 */
class HttpRequestFactory {
	/**
	 * Generate a new MWHttpRequest object
	 * @param string $url Url to use
	 * @param array $options Possible keys for the array:
	 *    - timeout             Timeout length in seconds or 'default'
	 *    - connectTimeout      Timeout for connection, in seconds (curl only) or 'default'
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
	 * @codingStandardsIgnoreStart
	 * @phan-param array{timeout?:int|string,connectTimeout?:int|string,postData?:array,proxy?:string,noProxy?:bool,sslVerifyHost?:bool,sslVerifyCert?:bool,caInfo?:string,maxRedirects?:int,followRedirects?:bool,userAgent?:string,method?:string,logger?:\Psr\Log\LoggerInterface,username?:string,password?:string,originalRequest?:\WebRequest|array{ip:string,userAgent:string}} $options
	 * @codingStandardsIgnoreEnd
	 * @param string $caller The method making this request, for profiling
	 * @throws RuntimeException
	 * @return MWHttpRequest
	 * @see MWHttpRequest::__construct
	 */
	public function create( $url, array $options = [], $caller = __METHOD__ ) {
		if ( !Http::$httpEngine ) {
			Http::$httpEngine = 'guzzle';
		}

		if ( !isset( $options['logger'] ) ) {
			$options['logger'] = LoggerFactory::getInstance( 'http' );
		}

		switch ( Http::$httpEngine ) {
			case 'guzzle':
				return new GuzzleHttpRequest( $url, $options, $caller, Profiler::instance() );
			case 'curl':
				return new CurlHttpRequest( $url, $options, $caller, Profiler::instance() );
			case 'php':
				return new PhpHttpRequest( $url, $options, $caller, Profiler::instance() );
			default:
				throw new RuntimeException( __METHOD__ . ': The requested engine is not valid.' );
		}
	}

	/**
	 * Simple function to test if we can make any sort of requests at all, using
	 * cURL or fopen()
	 * @return bool
	 */
	public function canMakeRequests() {
		return function_exists( 'curl_init' ) || wfIniGetBool( 'allow_url_fopen' );
	}

	/**
	 * Perform an HTTP request
	 *
	 * @since 1.34
	 * @param string $method HTTP method. Usually GET/POST
	 * @param string $url Full URL to act on. If protocol-relative, will be expanded to an http://
	 *  URL
	 * @param array $options See HttpRequestFactory::create
	 * @param string $caller The method making this request, for profiling
	 * @return string|null null on failure or a string on success
	 */
	public function request( $method, $url, array $options = [], $caller = __METHOD__ ) {
		$logger = LoggerFactory::getInstance( 'http' );
		$logger->debug( "$method: $url" );

		$options['method'] = strtoupper( $method );

		if ( !isset( $options['timeout'] ) ) {
			$options['timeout'] = 'default';
		}
		if ( !isset( $options['connectTimeout'] ) ) {
			$options['connectTimeout'] = 'default';
		}

		$req = $this->create( $url, $options, $caller );
		$status = $req->execute();

		if ( $status->isOK() ) {
			return $req->getContent();
		} else {
			$errors = $status->getErrorsByType( 'error' );
			$logger->warning( Status::wrap( $status )->getWikiText( false, false, 'en' ),
				[ 'error' => $errors, 'caller' => $caller, 'content' => $req->getContent() ] );
			return null;
		}
	}

	/**
	 * Simple wrapper for request( 'GET' ), parameters have same meaning as for request()
	 *
	 * @since 1.34
	 * @param string $url
	 * @param array $options
	 * @param string $caller
	 * @return string|null
	 */
	public function get( $url, array $options = [], $caller = __METHOD__ ) {
		return $this->request( 'GET', $url, $options, $caller );
	}

	/**
	 * Simple wrapper for request( 'POST' ), parameters have same meaning as for request()
	 *
	 * @since 1.34
	 * @param string $url
	 * @param array $options
	 * @param string $caller
	 * @return string|null
	 */
	public function post( $url, array $options = [], $caller = __METHOD__ ) {
		return $this->request( 'POST', $url, $options, $caller );
	}

	/**
	 * @return string
	 */
	public function getUserAgent() {
		global $wgVersion;

		return "MediaWiki/$wgVersion";
	}
}
