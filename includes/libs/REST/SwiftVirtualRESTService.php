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

	/**
	 * @param array $params Key/value map
	 *   - swiftAuthUrl       : Swift authentication server URL
	 *   - swiftUser          : Swift user used by MediaWiki (account:username)
	 *   - swiftKey           : Swift authentication key for the above user
	 *   - swiftAuthTTL       : Swift authentication TTL (seconds)
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
	}

	public function needsAuthRequest() {
		if ( !$this->authCreds ) {
			return true;
		}
		if ( $this->authErrorTimestamp !== null ) {
			if ( ( time() - $this->authErrorTimestamp ) < 60 ) {
				return false; // failed last attempt; don't bother
			} else { // actually retry this time
				$this->authErrorTimestamp = null;
			}
		}
		// Session keys expire after a while, so we renew them periodically
		return !( ( time() - $this->authSessionTimestamp ) > $this->params['swiftAuthTTL'] );
	}

	public function getAuthRequest() {
		return array(
			'method'  => 'GET',
			'url'     => $this->params['swiftAuthUrl'] . "/v1.0",
			'headers' => array(
				'x-auth-user' => $this->params['swiftUser'],
				'x-auth-key'  => $this->params['swiftKey'] )
		);
	}

	public function applyAuthResponse( array $req ) {
		$this->authSessionTimestamp = 0;
		list( $rcode, $rdesc, $rhdrs, $rbody, $rerr ) = $req['response'];
		if ( $rcode >= 200 && $rcode <= 299 ) { // OK
			$this->authCreds = array(
				'auth_token'  => $rhdrs['x-auth-token'],
				'storage_url' => $rhdrs['x-storage-url']
			);
			$this->authSessionTimestamp = time();
			return true;
		} elseif ( $rcode === 401 ) {
			$this->authErrorTimestamp = time();
		} else {
			$this->authErrorTimestamp = time();
		}
		return false;
	}

	public function readyRequest( array $req, MultiHttpClient $http ) {
		if ( !$this->authCreds ) {
			throw new Exception( "Prior authentication failed!" );
		}
		// The default encoding treats the URL as a REST style path that uses
		// forward slash as a hierarchical delimiter (and never otherwise).
		// Subclasses can override this, and should be documented in any case.
		$parts = array_map( 'rawurlencode', explode( '/', $req['url'] ) );
		$req['url'] = $this->authCreds['storage_url'] . '/' . implode( '/', $parts );
		$req['headers']['x-auth-token'] = $this->authCreds['auth_token'];
		// @TODO: add ETag/Content-Length and such as needed
		return $req;
	}
}
