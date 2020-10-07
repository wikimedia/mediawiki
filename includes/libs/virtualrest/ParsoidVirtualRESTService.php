<?php
/**
 * Virtual HTTP service client for Parsoid
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
 */

/**
 * Virtual REST service for Parsoid
 * @since 1.25
 */
class ParsoidVirtualRESTService extends VirtualRESTService {
	/**
	 * Example Parsoid v3 requests:
	 *  GET /local/v3/page/html/$title/{$revision}
	 *   * $revision is optional
	 *  POST /local/v3/transform/html/to/wikitext/{$title}{/$revision}
	 *   * body: array( 'html' => ... )
	 *   * $title and $revision are optional
	 *  POST /local/v3/transform/wikitext/to/html/{$title}{/$revision}
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'body_only' => true/false )
	 *   * $title is optional
	 *   * $revision is optional
	 *
	 * @param array $params Key/value map
	 *   - url            : Parsoid server URL
	 *   - domain         : Wiki domain to use
	 *   - timeout        : Parsoid timeout (optional)
	 *   - forwardCookies : Cookies to forward to Parsoid, or false. (optional)
	 *   - HTTPProxy      : Parsoid HTTP proxy (optional)
	 *   - restbaseCompat : whether to parse URL as if they were meant for RESTBase
	 *                       boolean (optional)
	 */
	public function __construct( array $params ) {
		// for backwards compatibility:
		if ( isset( $params['URL'] ) ) {
			wfDeprecatedMsg(
				'Using all-caps URL parameter to $wgVirtualRestConfig ' .
				'was deprecated in MediaWiki 1.35', '1.35'
			);
			$params['url'] = $params['URL'];
			unset( $params['URL'] );
		}
		// set up defaults and merge them with the given params
		$defaultURL = wfExpandUrl( wfScript( 'rest' ), PROTO_CANONICAL );
		$mparams = array_merge( [
			'name' => 'parsoid',
			'url' => $defaultURL,
			'domain' => wfParseUrl( $defaultURL )['host'] ?? 'localhost',
			'timeout' => null,
			'forwardCookies' => false,
			'HTTPProxy' => null,
		], $params );
		// Ensure that the url parameter has a trailing slash.
		if ( substr( $mparams['url'], -1 ) !== '/' ) {
			$mparams['url'] .= '/';
		}
		// Ensure the correct domain format: strip protocol, port,
		// and trailing slash if present.  This lets us use
		// $wgCanonicalServer as a default value, which is very convenient.
		$mparams['domain'] = preg_replace(
			'/^((https?:)?\/\/)?([^\/:]+?)(:\d+)?\/?$/',
			'$3',
			$mparams['domain']
		);
		parent::__construct( $mparams );
	}

	/**
	 * @inheritDoc
	 * @phan-param array[] $reqs
	 */
	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = [];
		foreach ( $reqs as $key => $req ) {
			$parts = explode( '/', $req['url'] );

			list(
				$targetWiki, // 'local'
				$version, // 'v3' ('v1' for restbase compatibility)
				$reqType, // 'page' or 'transform'
				$format, // 'html' or 'wikitext'
				// $title (optional)
				// $revision (optional)
			) = $parts;

			if ( isset( $this->params['restbaseCompat'] ) && $this->params['restbaseCompat'] ) {
				if ( $version !== 'v1' ) {
					throw new Exception( "Only RESTBase v1 API is supported." );
				}
				# Map RESTBase v1 API to Parsoid v3 API (pretty easy)
				$req['url'] = preg_replace( '#^local/v1/#', 'local/v3/', $req['url'] );
			} elseif ( $version !== 'v3' ) {
				throw new Exception( "Only Parsoid v3 API is supported." );
			}
			if ( $targetWiki !== 'local' ) {
				throw new Exception( "Only 'local' target wiki is currently supported" );
			}
			if ( $reqType !== 'page' && $reqType !== 'transform' ) {
				throw new Exception( "Request action must be either 'page' or 'transform'" );
			}
			if ( $format !== 'html' && $format !== 'wikitext' && $format !== 'lint' ) {
				throw new Exception( "Request format must be 'html', 'wt' or 'lint'" );
			}
			// replace /local/ with the current domain
			$req['url'] = preg_replace( '#^local/#', $this->params['domain'] . '/', $req['url'] );
			// and prefix it with the service URL
			$req['url'] = $this->params['url'] . $req['url'];
			// set the appropriate proxy, timeout and headers
			if ( $this->params['HTTPProxy'] ) {
				$req['proxy'] = $this->params['HTTPProxy'];
			}
			if ( $this->params['timeout'] != null ) {
				$req['reqTimeout'] = $this->params['timeout'];
			}
			if ( $this->params['forwardCookies'] ) {
				$req['headers']['Cookie'] = $this->params['forwardCookies'];
			}
			// Parsoid/PHP is a MW instance, so it needs the Host header set,
			// otherwise the server replies with a 404, so apply it unconditionally
			// to all requests
			$req['headers']['Host'] = $this->params['domain'];
			$result[$key] = $req;
		}
		return $result;
	}

}
