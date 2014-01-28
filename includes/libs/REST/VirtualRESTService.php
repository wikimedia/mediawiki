<?php
/**
 * Virtual HTTP service client
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
 * Virtual HTTP service instance that can be mounted on to a VirtualRESTService
 *
 * Sub-classes manage the logic of either:
 *   - a) Munging virtual HTTP request arrays to have qualified URLs and auth headers
 *   - b) Emulating the execution of virtual HTTP requests (e.g. brokering)
 *
 * Authentication information can be cached in instances of the class for performance.
 * Such information should also be cached locally on the server and auth requests should
 * have reasonable timeouts.
 *
 * @since 1.23
 */
abstract class VirtualRESTService {
	/** @var array Key/value map */
	protected $params = array();

	/**
	 * @param array $params Key/value map
	 */
	public function __construct( array $params ) {
		$this->params = $params;
	}

	/**
	 * Whether getAuthRequest()/applyAuthResponse() should be called
	 *
	 * If this returns true, then getAuthRequest() will be called,
	 * that request will be executed, and the response information
	 * will be passed back to applyAuthResponse().
	 *
	 * @return bool
	 */
	public function needsAuthRequest() {
		return false;
	}

	/**
	 * @return array HTTP request for MultiHttpClient
	 * @throws Exception
	 */
	public function getAuthRequest() {
		throw new Exception( __METHOD__ . " has no implementation." );
	}

	/**
	 * @return array HTTP request (with response) from MultiHttpClient
	 * @throws Exception
	 */
	public function applyAuthResponse( array $req ) {
		throw new Exception( __METHOD__ . " has no implementation." );
	}

	/**
	 * Prepare a virtual HTTP(S) request (for this service) for execution
	 *
	 * This method should mangle any of the $req fields as needed:
	 *   - url      : munge the URL to have an absolute URL with a protocol
	 *                and encode path components as needed by the backend [required]
	 *   - query    : include any authentication signatures/parameters [as needed]
	 *   - headers  : include any authentication tokens/headers [as needed]
	 *
	 * The incoming URL parameter will be relative to the service mount point.
	 *
	 * @param array $req Virtual HTTP request array
	 * @param MultiHttpClient $http HTTP Client which can be used for auth requests
	 * @return array Modified HTTP request array
	 */
	public function readyRequest( array $req, MultiHttpClient $http ) {
		// The default encoding treats the URL as a REST style path that uses
		// forward slash as a hierarchical delimiter (and never otherwise).
		// Subclasses can override this, and should be documented in any case.
		$parts = array_map( 'rawurlencode', explode( '/', $req['url'] ) );
		$req['url'] = $this->params['baseUrl'] . '/' . implode( '/', $parts );
		return $req;
	}

	/**
	 * Whether this service handles requests itself via runEmulatedMulti()
	 *
	 * @see VirtualRESTService::runEmulatedMulti()
	 *
	 * @return boolean
	 */
	public function isEmulated() {
		return false;
	}

	/**
	 * Execute a set of virtual HTTP(s) requests (for this service)
	 *
	 * The maps are returned by this method with the 'response' field set to a map of:
	 *   - code    : HTTP response code or 0 if there was a serious cURL error
	 *   - reason  : HTTP response reason (empty if there was a serious cURL error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - err     : Any internal error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 *	<code>
	 *		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req;
	 *  </code>
	 * All headers in the 'headers' field are normalized to use lower case names.
	 * This is true for the request headers and the response headers.
	 *
	 * @see VirtualRESTService::isEmulated()
	 *
	 * @param array $req Map of Virtual HTTP request arrays
	 * @return array $reqs With response array populated for each
	 * @throws Exception
	 */
	public function runEmulatedMulti( array $reqs ) {
		throw new Exception( __METHOD__ . " has no implementation." );
	}
}
