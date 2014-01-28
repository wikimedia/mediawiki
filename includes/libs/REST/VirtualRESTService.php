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
	 * TODO: docs
	 *
	 * @param array $reqs Map of Virtual HTTP request arrays
	 * @return array Modified HTTP request array map
	 */
	public function replaceOutgoing( array $reqs ) {
		$result = array();
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

	// TODO: docs
	public function replaceIncoming( array $reqs ) {
		return $reqs;
	}
}
