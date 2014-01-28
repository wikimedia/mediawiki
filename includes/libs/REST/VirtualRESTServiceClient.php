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
 * Virtual HTTP service client loosely styled after a Virtual File System
 *
 * Services can be mounted on path prefixes so that virtual HTTP operations
 * against sub-paths will map to those services. Operations can actually be
 * done using HTTP messages over the wire or may simple be emulated locally.
 *
 * Virtual HTTP request maps use the following format:
 *   - method   : GET/HEAD/PUT/POST/DELETE
 *   - url      : HTTP/HTTPS URL or virtual service path with a registered prefix
 *   - query    : <query parameter field/value associative array> (uses RFC 3986)
 *   - headers  : <header name/value associative array>
 *   - body     : source to get the HTTP request body from;
 *                this can simply be a string (always), a resource for
 *                PUT requests, and a field/value array for POST request;
 *                array bodies are encoded as multipart/form-data and strings
 *                use application/x-www-form-urlencoded (headers sent automatically)
 *   - stream   : resource to stream the HTTP response body to
 *
 * @author Aaron Schulz
 * @since 1.23
 */
class VirtualRESTServiceClient {
	/** @var MultiHttpClient */
	protected $http;
	/** @var Array Map of (prefix => VirtualRESTService) */
	protected $instances = array();

	const VALID_MOUNT_REGEX = '#^/[0-9a-z]+/([0-9a-z]+/)*$#';

	/**
	 * @param MultiHttpClient $http
	 */
	public function __construct( MultiHttpClient $http ) {
		$this->http = $http;
	}

	/**
	 * Map a prefix to service handler
	 *
	 * @param string $prefix Virtual path
	 * @param VirtualRESTService $instance
	 */
	public function mount( $prefix, VirtualRESTService $instance ) {
		if ( !preg_match( self::VALID_MOUNT_REGEX, $prefix ) ) {
			throw new UnexpectedValueException( "Invalid service mount point '$prefix'." );
		} elseif ( isset( $this->instances[$prefix] ) ) {
			throw new UnexpectedValueException( "A service is already mounted on '$prefix'." );
		}
		$this->instances[$prefix] = $instance;
	}

	/**
	 * Unmap a prefix to service handler
	 *
	 * @param string $prefix Virtual path
	 */
	public function unmount( $prefix ) {
		if ( !preg_match( self::VALID_MOUNT_REGEX, $prefix ) ) {
			throw new UnexpectedValueException( "Invalid service mount point '$prefix'." );
		} elseif ( !isset( $this->instances[$prefix] ) ) {
			throw new UnexpectedValueException( "No service is mounted on '$prefix'." );
		}
		unset( $this->instances[$prefix] );
	}

	/**
	 * Get the prefix and service that a virtual path is serviced by
	 *
	 * @param string $path
	 * @return array (prefix,VirtualRESTService) or (null,null) if none found
	 */
	public function getMountAndService( $path ) {
		$cmpFunc = function( $a, $b ) {
			$al = substr_count( $a, '/' );
			$bl = substr_count( $b, '/' );
			if ( $al === $bl ) {
				return 0; // should not actually happen
			}
			return ( $al < $bl ) ? 1 : -1; // largest prefix first
		};

		$matches = array(); // matching prefixes (mount points)
		foreach ( $this->instances as $prefix => $service ) {
			if ( strpos( $path, $prefix ) === 0 ) {
				$matches[] = $prefix;
			}
		}
		usort( $matches, $cmpFunc );

		// Return the most specific prefix and corresponding service
		return isset( $matches[0] )
			? array( $matches[0], $this->instances[$matches[0]] )
			: array( null, null );
	}

	/**
	 * Execute a virtual HTTP(S) request
	 *
	 * This method returns a response map of:
	 *   - code    : HTTP response code or 0 if there was a serious cURL error
	 *   - reason  : HTTP response reason (empty if there was a serious cURL error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - err     : Any cURL error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 *	<code>
	 *		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req;
	 *  </code>
	 * @param array $req Virtual HTTP request array
	 * @return array Response array for request
	 */
	public function run( array $req ) {
		$req = $this->runMulti( array( $req ) );
		return $req[0]['response'];
	}

	/**
	 * Execute a set of virtual HTTP(S) requests concurrently
	 *
	 * The maps are returned by this method with the 'response' field set to a map of:
	 *   - code    : HTTP response code or 0 if there was a serious cURL error
	 *   - reason  : HTTP response reason (empty if there was a serious cURL error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - err     : Any cURL error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 *	<code>
	 *		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req;
	 *  </code>
	 * All headers in the 'headers' field are normalized to use lower case names.
	 * This is true for the request headers and the response headers.
	 *
	 * @param array $req Map of Virtual HTTP request arrays
	 * @return array $reqs With response array populated for each
	 */
	public function runMulti( array $reqs ) {
		$performReqs = array(); // (index => request)

		$authReqsByService = array(); // (prefix => request)
		$workReqsByService = array(); // (prefix => index => request)
		$emulReqsByService = array(); // (prefix => index => request)
		foreach ( $reqs as $index => $req ) {
			if ( preg_match( '#^(http|ftp)s?://#', $req['url'] ) ) {
				// Absolute FTP/HTTP(S) URL, run it as normal
				$performReqs[$index] = $req;
			} else {
				// Must be a virtual HTTP URL; resolve it
				list( $prefix, $service ) = $this->getMountAndService( $req['url'] );
				if ( !$service ) {
					throw new UnexpectedValueException( "Path '{$req['url']}' has no service." );
				}
				// Set the URL to the mount-relative portion
				$req['url'] = substr( $req['url'], strlen( $prefix ) );
				// Check if this service only fakes HTTP
				if ( $service->isEmulated() ) {
					// Collate the request by service to get partial concurrency
					$emulReqsByService[$prefix][$index] = $req;
				} else {
					$workReqsByService[$prefix][$index] = $req;
					// @TODO: a way to short-circuit on repeated auth failure
					if ( $service->needsAuthRequest() && !isset( $authReqsByService[$prefix] ) ) {
						$authReqsByService[$prefix] = $service->getAuthRequest();
					}
				}
			}
		}

		// Run the authentication HTTP request round
		$authReqsByService = $this->http->runMulti( $authReqsByService );
		// Apply the response to the services so they can create token headers
		foreach ( $authReqsByService as $prefix => $req ) {
			// @TODO: a way to short-circuit on auth failure
			$this->instances[$prefix]->applyAuthResponse( $req );
		}
		// Resolve the virtual URLs valid and qualified HTTP(S) URLs
		// and add any required authentication headers for the backend
		foreach ( $workReqsByService as $prefix => $workReqs ) {
			foreach ( $workReqs as $index => $req ) {
				$performReqs[$index] = $service->readyRequest( $req, $this->http );
			}
		}

		// Run the actual work HTTP requests
		$performedReqs = $this->http->runMulti( $performReqs );

		// Run the emulated work HTTP requests
		foreach ( $emulReqsByService as $prefix => $serviceReqs ) {
			$service = $this->instances[$prefix];
			$serviceReqs = $service->runEmulatedMulti( $serviceReqs );
			foreach ( $serviceReqs as $index => $serviceReq ) {
				// Make sure the headers are normalized in the emulated case
				// to be consistent with going through MultiHttpClient::runMulti()
				$headers = array(); // normalized headers
				if ( isset( $serviceReq['headers'] ) ) {
					foreach ( $serviceReq['headers'] as $name => $value ) {
						$headers[strtolower( $name )] = $value;
					}
				}
				$serviceReq['headers'] = $headers;
				$performedReqs[$index] = $serviceReq;
			}
		}

		// Update $reqs to include 'response' and normalized request 'headers'.
		// This maintains the original order of $reqs.
		foreach ( $reqs as $index => &$req ) {
			$req['response'] = $performedReqs[$index]['response'];
			$req['headers'] = $performedReqs[$index]['headers'];
		}

		return $reqs;
	}
}
