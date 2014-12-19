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
 * Virtual HTTP request maps are arrays that use the following format:
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
 * Request maps can use integer index 0 instead of 'method' and 1 instead of 'url'.
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
	 *   - error   : Any cURL error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 *	<code>
	 *		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $client->run( $req );
	 *  </code>
	 * @param array $req Virtual HTTP request maps
	 * @return array Response array for request
	 */
	public function run( array $req ) {
		$responses = $this->runMulti( array( $req ) );
		return $responses[0];
	}

	/**
	 * Execute a set of virtual HTTP(S) requests concurrently
	 *
	 * A map of requests keys to response maps is returned. Each response map has:
	 *   - code    : HTTP response code or 0 if there was a serious cURL error
	 *   - reason  : HTTP response reason (empty if there was a serious cURL error)
	 *   - headers : <header name/value associative array>
	 *   - body    : HTTP response body or resource (if "stream" was set)
	 *   - error   : Any cURL error string
	 * The map also stores integer-indexed copies of these values. This lets callers do:
	 *    <code>
	 *        list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $responses[0];
	 *  </code>
	 *
	 * @param array $reqs Map of Virtual HTTP request maps
	 * @return array $reqs Map of corresponding response values with the same keys/order
	 * @throws Exception
	 */
	public function runMulti( array $reqs ) {
		foreach ( $reqs as $index => &$req ) {
			if ( isset( $req[0] ) ) {
				$req['method'] = $req[0]; // short-form
				unset( $req[0] );
			}
			if ( isset( $req[1] ) ) {
				$req['url'] = $req[1]; // short-form
				unset( $req[1] );
			}
			$req['chain'] = array(); // chain or list of replaced requests
		}
		unset( $req ); // don't assign over this by accident

		$curUniqueId = 0;
		$armoredIndexMap = array(); // (original index => new index)

		$doneReqs = array(); // (index => request)
		$executeReqs = array(); // (index => request)
		$replaceReqsByService = array(); // (prefix => index => request)
		$origPending = array(); // (index => 1) for original requests

		foreach ( $reqs as $origIndex => $req ) {
			// Re-index keys to consecutive integers (they will be swapped back later)
			$index = $curUniqueId++;
			$armoredIndexMap[$origIndex] = $index;
			$origPending[$index] = 1;
			if ( preg_match( '#^(http|ftp)s?://#', $req['url'] ) ) {
				// Absolute FTP/HTTP(S) URL, run it as normal
				$executeReqs[$index] = $req;
			} else {
				// Must be a virtual HTTP URL; resolve it
				list( $prefix, $service ) = $this->getMountAndService( $req['url'] );
				if ( !$service ) {
					throw new UnexpectedValueException( "Path '{$req['url']}' has no service." );
				}
				// Set the URL to the mount-relative portion
				$req['url'] = substr( $req['url'], strlen( $prefix ) );
				$replaceReqsByService[$prefix][$index] = $req;
			}
		}

		// Function to get IDs that won't collide with keys in $armoredIndexMap
		$idFunc = function() use ( &$curUniqueId ) {
			return $curUniqueId++;
		};

		$rounds = 0;
		do {
			if ( ++$rounds > 5 ) { // sanity
				throw new Exception( "Too many replacement rounds detected. Aborting." );
			}
			// Track requests executed this round that have a prefix/service.
			// Note that this also includes requests where 'response' was forced.
			$checkReqIndexesByPrefix = array();
			// Resolve the virtual URLs valid and qualified HTTP(S) URLs
			// and add any required authentication headers for the backend.
			// Services can also replace requests with new ones, either to
			// defer the original or to set a proxy response to the original.
			$newReplaceReqsByService = array();
			foreach ( $replaceReqsByService as $prefix => $servReqs ) {
				$service = $this->instances[$prefix];
				foreach ( $service->onRequests( $servReqs, $idFunc ) as $index => $req ) {
					// Services use unique IDs for replacement requests
					if ( isset( $servReqs[$index] ) || isset( $origPending[$index] ) ) {
						// A current or original request which was not modified
					} else {
						// Replacement request that will convert to original requests
						$newReplaceReqsByService[$prefix][$index] = $req;
					}
					if ( isset( $req['response'] ) ) {
						// Replacement requests with pre-set responses should not execute
						unset( $executeReqs[$index] );
						unset( $origPending[$index] );
						$doneReqs[$index] = $req;
					} else {
						// Original or mangled request included
						$executeReqs[$index] = $req;
					}
					$checkReqIndexesByPrefix[$prefix][$index] = 1;
				}
			}
			// Update index of requests to inspect for replacement
			$replaceReqsByService = $newReplaceReqsByService;
			// Run the actual work HTTP requests
			foreach ( $this->http->runMulti( $executeReqs ) as $index => $ranReq ) {
				$doneReqs[$index] = $ranReq;
				unset( $origPending[$index] );
			}
			$executeReqs = array();
			// Services can also replace requests with new ones, either to
			// defer the original or to set a proxy response to the original.
			// Any replacement requests executed above will need to be replaced
			// with new requests (eventually the original). The responses can be
			// forced by setting 'response' rather than actually be sent over the wire.
			$newReplaceReqsByService = array();
			foreach ( $checkReqIndexesByPrefix as $prefix => $servReqIndexes ) {
				$service = $this->instances[$prefix];
				// $doneReqs actually has the requests (with 'response' set)
				$servReqs = array_intersect_key( $doneReqs, $servReqIndexes );
				foreach ( $service->onResponses( $servReqs, $idFunc ) as $index => $req ) {
					// Services use unique IDs for replacement requests
					if ( isset( $servReqs[$index] ) || isset( $origPending[$index] ) ) {
						// A current or original request which was not modified
					} else {
						// Replacement requests with pre-set responses should not execute
						$newReplaceReqsByService[$prefix][$index] = $req;
					}
					if ( isset( $req['response'] ) ) {
						// Replacement requests with pre-set responses should not execute
						unset( $origPending[$index] );
						$doneReqs[$index] = $req;
					} else {
						// Update the request in case it was mangled
						$executeReqs[$index] = $req;
					}
				}
			}
			// Update index of requests to inspect for replacement
			$replaceReqsByService = $newReplaceReqsByService;
		} while ( count( $origPending ) );

		$responses = array();
		// Update $reqs to include 'response' and normalized request 'headers'.
		// This maintains the original order of $reqs.
		foreach ( $reqs as $origIndex => $req ) {
			$index = $armoredIndexMap[$origIndex];
			if ( !isset( $doneReqs[$index] ) ) {
				throw new UnexpectedValueException( "Response for request '$index' is NULL." );
			}
			$responses[$origIndex] = $doneReqs[$index]['response'];
		}

		return $responses;
	}
}
