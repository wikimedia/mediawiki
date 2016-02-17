<?php
/**
 * Virtual HTTP service client for Swift
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
 * Example virtual rest service for OpenStack Swift
 * @TODO: caching support (APC/memcached)
 * @since 1.23
 */
class SwiftVirtualRESTService extends VirtualRESTService {
	/** @var array */
	protected $authCreds;
	/** @var int UNIX timestamp */
	protected $authSessionTimestamp = 0;
	/** @var int UNIX timestamp */
	protected $authErrorTimestamp = null;
	/** @var int */
	protected $authCachedStatus = null;
	/** @var string */
	protected $authCachedReason = null;

	/**
	 * @param array $params Key/value map
	 *   - swiftAuthUrl       : Swift authentication server URL
	 *   - swiftUser          : Swift user used by MediaWiki (account:username)
	 *   - swiftKey           : Swift authentication key for the above user
	 *   - swiftAuthTTL       : Swift authentication TTL (seconds)
	 */
	public function __construct( array $params ) {
		// set up defaults and merge them with the given params
		$mparams = array_merge( [
			'name' => 'swift'
		], $params );
		parent::__construct( $mparams );
	}

	/**
	 * @return int|bool HTTP status on cached failure
	 */
	protected function needsAuthRequest() {
		if ( !$this->authCreds ) {
			return true;
		}
		if ( $this->authErrorTimestamp !== null ) {
			if ( ( time() - $this->authErrorTimestamp ) < 60 ) {
				return $this->authCachedStatus; // failed last attempt; don't bother
			} else { // actually retry this time
				$this->authErrorTimestamp = null;
			}
		}
		// Session keys expire after a while, so we renew them periodically
		return ( ( time() - $this->authSessionTimestamp ) > $this->params['swiftAuthTTL'] );
	}

	protected function applyAuthResponse( array $req ) {
		$this->authSessionTimestamp = 0;
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req['response'];
		if ( $rcode >= 200 && $rcode <= 299 ) { // OK
			$this->authCreds = [
				'auth_token'  => $rhdrs['x-auth-token'],
				'storage_url' => $rhdrs['x-storage-url']
			];
			$this->authSessionTimestamp = time();
			return true;
		} elseif ( $rcode === 403 ) {
			$this->authCachedStatus = 401;
			$this->authCachedReason = 'Authorization Required';
			$this->authErrorTimestamp = time();
			return false;
		} else {
			$this->authCachedStatus = $rcode;
			$this->authCachedReason = $rdesc;
			$this->authErrorTimestamp = time();
			return null;
		}
	}

	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = [];
		$firstReq = reset( $reqs );
		if ( $firstReq && count( $reqs ) == 1 && isset( $firstReq['isAuth'] ) ) {
			// This was an authentication request for work requests...
			$result = $reqs; // no change
		} else {
			// These are actual work requests...
			$needsAuth = $this->needsAuthRequest();
			if ( $needsAuth === true ) {
				// These are work requests and we don't have any token to use.
				// Replace the work requests with an authentication request.
				$result = [
					$idGeneratorFunc() => [
						'method'  => 'GET',
						'url'     => $this->params['swiftAuthUrl'] . "/v1.0",
						'headers' => [
							'x-auth-user' => $this->params['swiftUser'],
							'x-auth-key'  => $this->params['swiftKey'] ],
						'isAuth'  => true,
						'chain'   => $reqs
					]
				];
			} elseif ( $needsAuth !== false ) {
				// These are work requests and authentication has previously failed.
				// It is most efficient to just give failed pseudo responses back for
				// the original work requests.
				foreach ( $reqs as $key => $req ) {
					$req['response'] = [
						'code'     => $this->authCachedStatus,
						'reason'   => $this->authCachedReason,
						'headers'  => [],
						'body'     => '',
						'error'    => ''
					];
					$result[$key] = $req;
				}
			} else {
				// These are work requests and we have a token already.
				// Go through and mangle each request to include a token.
				foreach ( $reqs as $key => $req ) {
					// The default encoding treats the URL as a REST style path that uses
					// forward slash as a hierarchical delimiter (and never otherwise).
					// Subclasses can override this, and should be documented in any case.
					$parts = array_map( 'rawurlencode', explode( '/', $req['url'] ) );
					$req['url'] = $this->authCreds['storage_url'] . '/' . implode( '/', $parts );
					$req['headers']['x-auth-token'] = $this->authCreds['auth_token'];
					$result[$key] = $req;
					// @TODO: add ETag/Content-Length and such as needed
				}
			}
		}
		return $result;
	}

	public function onResponses( array $reqs, Closure $idGeneratorFunc ) {
		$firstReq = reset( $reqs );
		if ( $firstReq && count( $reqs ) == 1 && isset( $firstReq['isAuth'] ) ) {
			$result = [];
			// This was an authentication request for work requests...
			if ( $this->applyAuthResponse( $firstReq ) ) {
				// If it succeeded, we can subsitute the work requests back.
				// Call this recursively in order to munge and add headers.
				$result = $this->onRequests( $firstReq['chain'], $idGeneratorFunc );
			} else {
				// If it failed, it is most efficient to just give failing
				// pseudo-responses back for the actual work requests.
				foreach ( $firstReq['chain'] as $key => $req ) {
					$req['response'] = [
						'code'     => $this->authCachedStatus,
						'reason'   => $this->authCachedReason,
						'headers'  => [],
						'body'     => '',
						'error'    => ''
					];
					$result[$key] = $req;
				}
			}
		} else {
			$result = $reqs; // no change
		}
		return $result;
	}
}
