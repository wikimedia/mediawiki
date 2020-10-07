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
	protected $params = [];

	/**
	 * @param array $params Key/value map
	 */
	public function __construct( array $params ) {
		$this->params = $params;
	}

	/**
	 * Return the name of this service, in a form suitable for error
	 * reporting or debugging.
	 *
	 * @return string The name of the service behind this VRS object.
	 */
	public function getName() {
		return $this->params['name'] ?? static::class;
	}

	/**
	 * Prepare virtual HTTP(S) requests (for this service) for execution
	 *
	 * This method should mangle any of the $reqs entry fields as needed:
	 *   - url      : munge the URL to have an absolute URL with a protocol
	 *                and encode path components as needed by the backend [required]
	 *   - query    : include any authentication signatures/parameters [as needed]
	 *   - headers  : include any authentication tokens/headers [as needed]
	 *
	 * The incoming URL parameter will be relative to the service mount point.
	 *
	 * This method can also remove some of the requests as well as add new ones
	 * (using $idGenerator to set each of the entries' array keys). For any existing
	 * or added request, the 'response' array can be filled in, which will prevent the
	 * client from executing it. If an original request is removed, at some point it
	 * must be added back (with the same key) in onRequests() or onResponses();
	 * it's reponse may be filled in as with other requests.
	 *
	 * @param array[] $reqs Map of Virtual HTTP request arrays
	 * @param Closure $idGeneratorFunc Method to generate unique keys for new requests
	 * @return array[] Modified HTTP request array map
	 */
	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = [];
		foreach ( $reqs as $key => $req ) {
			// The default encoding treats the URL as a REST style path that uses
			// forward slash as a hierarchical delimiter (and never otherwise).
			// Subclasses can override this, and should be documented in any case.
			$parts = array_map( 'rawurlencode', explode( '/', $req['url'] ) );
			$req['url'] = $this->params['baseUrl'] . '/' . implode( '/', $parts );
			$result[$key] = $req;
		}
		return $result;
	}

	/**
	 * Mangle or replace virtual HTTP(S) requests which have been responded to
	 *
	 * This method may mangle any of the $reqs entry 'response' fields as needed:
	 *   - code    : perform any code normalization [as needed]
	 *   - reason  : perform any reason normalization [as needed]
	 *   - headers : perform any header normalization [as needed]
	 *
	 * This method can also remove some of the requests as well as add new ones
	 * (using $idGenerator to set each of the entries' array keys). For any existing
	 * or added request, the 'response' array can be filled in, which will prevent the
	 * client from executing it. If an original request is removed, at some point it
	 * must be added back (with the same key) in onRequests() or onResponses();
	 * it's reponse may be filled in as with other requests. All requests added to $reqs
	 * will be passed through onRequests() to handle any munging required as normal.
	 *
	 * The incoming URL parameter will be relative to the service mount point.
	 *
	 * @param array $reqs Map of Virtual HTTP request arrays with 'response' set
	 * @param Closure $idGeneratorFunc Method to generate unique keys for new requests
	 * @return array Modified HTTP request array map
	 */
	public function onResponses( array $reqs, Closure $idGeneratorFunc ) {
		return $reqs;
	}
}
