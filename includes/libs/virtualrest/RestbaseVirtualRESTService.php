<?php
/**
 * Virtual HTTP service client for RESTBase
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
 * Virtual REST service for RESTBase
 * @since 1.25
 */
class RestbaseVirtualRESTService extends VirtualRESTService {
	/**
	 * Example RESTBase v1 requests:
	 *  GET /local/v1/page/html/{title}{/revision}
	 *  POST /local/v1/transform/html/to/wikitext{/title}{/revision}
	 *   * body: array( 'html' => ... )
	 *  POST /local/v1/transform/wikitext/to/html{/title}{/revision}
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'body_only' => true/false )
	 *
	 * @param array $params Key/value map
	 *   - url            : RESTBase server URL
	 *   - domain         : Wiki domain to use
	 *   - timeout        : request timeout in seconds (optional)
	 *   - forwardCookies : cookies to forward to RESTBase/Parsoid (as a Cookie
	 *                       header string) or false (optional)
	 *                       Note: forwardCookies will in the future be a boolean
	 *                       only, signifing request cookies should be forwarded
	 *                       to the service; the current state is due to the way
	 *                       VE handles this particular parameter
	 *   - HTTPProxy      : HTTP proxy to use (optional)
	 *   - parsoidCompat  : whether to parse URL as if they were meant for Parsoid
	 *                       boolean (optional)
	 *   - fixedUrl       : Do not append domain to the url. For example to use
	 *                       English Wikipedia restbase, you would this to true
	 *                       and url to https://en.wikipedia.org/api/rest_#version#
	 */
	public function __construct( array $params ) {
		// set up defaults and merge them with the given params
		$mparams = array_merge( [
			'name' => 'restbase',
			'url' => 'http://localhost:7231/',
			'domain' => 'localhost',
			'timeout' => 100,
			'forwardCookies' => false,
			'HTTPProxy' => null,
			'parsoidCompat' => false,
			'fixedUrl' => false,
		], $params );
		// Ensure that the url parameter has a trailing slash.
		if ( substr( $mparams['url'], -1 ) !== '/' ) {
			$mparams['url'] .= '/';
		}
		// Ensure the correct domain format: strip protocol, port,
		// and trailing slash if present.  This lets us use
		// $wgCanonicalServer as a default value, which is very convenient.
		$mparams['domain'] = preg_replace(
			'/^(https?:\/\/)?([^\/:]+?)(:\d+)?\/?$/',
			'$2',
			$mparams['domain']
		);
		parent::__construct( $mparams );
	}

	public function onRequests( array $reqs, Closure $idGenFunc ) {
		if ( $this->params['parsoidCompat'] ) {
			return $this->onParsoidRequests( $reqs, $idGenFunc );
		}

		$result = [];
		foreach ( $reqs as $key => $req ) {
			if ( $this->params['fixedUrl'] ) {
				$version = explode( '/', $req['url'] )[1];
				$req['url'] =
					str_replace( '#version#', $version, $this->params['url'] ) .
					preg_replace( '#^local/v./#', '', $req['url'] );
			} else {
				// replace /local/ with the current domain
				$req['url'] = preg_replace( '#^local/#', $this->params['domain'] . '/', $req['url'] );
				// and prefix it with the service URL
				$req['url'] = $this->params['url'] . $req['url'];
			}

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
			$result[$key] = $req;
		}

		return $result;
	}

	/**
	 * Remaps Parsoid v1/v3 requests to RESTBase v1 requests.
	 * @param array $reqs
	 * @param Closure $idGeneratorFunc
	 * @return array
	 * @throws Exception
	 */
	public function onParsoidRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = [];
		foreach ( $reqs as $key => $req ) {
			$version = explode( '/', $req['url'] )[1];
			if ( $version === 'v3' ) {
				$result[$key] = $this->onParsoid3Request( $req, $idGeneratorFunc );
			} elseif ( $version === 'v1' ) {
				$result[$key] = $this->onParsoid1Request( $req, $idGeneratorFunc );
			} else {
				throw new Exception( "Only v1 and v3 are supported." );
			}
		}

		return $result;
	}

	/**
	 * Remap a Parsoid v1 request to a RESTBase v1 request.
	 *
	 * Example Parsoid v1 requests:
	 *  GET /local/v1/page/$title/html/$oldid
	 *   * $oldid is optional
	 *  POST /local/v1/transform/html/to/wikitext/$title/$oldid
	 *   * body: array( 'html' => ... )
	 *   * $title and $oldid are optional
	 *  POST /local/v1/transform/wikitext/to/html/$title
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'body' => true/false )
	 *   * $title is optional
	 *
	 * NOTE: the POST APIs aren't "real" Parsoid v1 APIs, they are just what
	 * Visual Editor "pretends" the V1 API is like.  (See
	 * ParsoidVirtualRESTService.)
	 * @param array $req
	 * @param Closure $idGeneratorFunc
	 * @return array
	 * @throws Exception
	 * @deprecated since 1.26, upgrade your client to issue v3 requests.
	 */
	public function onParsoid1Request( array $req, Closure $idGeneratorFunc ) {
		wfDeprecated( __METHOD__, '1.26' );
		$parts = explode( '/', $req['url'] );
		list(
			$targetWiki, // 'local'
			$version, // 'v1'
			$reqType // 'page' or 'transform'
		) = $parts;
		if ( $targetWiki !== 'local' ) {
			throw new Exception( "Only 'local' target wiki is currently supported" );
		} elseif ( $version !== 'v1' ) {
			throw new Exception( "Version mismatch: should not happen." );
		} elseif ( $reqType !== 'page' && $reqType !== 'transform' ) {
			throw new Exception( "Request type must be either 'page' or 'transform'" );
		}
		$req['url'] = $this->params['url'] . $this->params['domain'] . '/v1/' . $reqType . '/';
		if ( $reqType === 'page' ) {
			$title = $parts[3];
			if ( $parts[4] !== 'html' ) {
				throw new Exception( "Only 'html' output format is currently supported" );
			}
			$req['url'] .= 'html/' . $title;
			if ( isset( $parts[5] ) ) {
				$req['url'] .= '/' . $parts[5];
			} elseif ( isset( $req['query']['oldid'] ) && $req['query']['oldid'] ) {
				$req['url'] .= '/' . $req['query']['oldid'];
				unset( $req['query']['oldid'] );
			}
		} elseif ( $reqType === 'transform' ) {
			// from / to transform
			$req['url'] .= $parts[3] . '/to/' . $parts[5];
			// the title
			if ( isset( $parts[6] ) ) {
				$req['url'] .= '/' . $parts[6];
			}
			// revision id
			if ( isset( $parts[7] ) ) {
				$req['url'] .= '/' . $parts[7];
			} elseif ( isset( $req['body']['oldid'] ) && $req['body']['oldid'] ) {
				$req['url'] .= '/' . $req['body']['oldid'];
				unset( $req['body']['oldid'] );
			}
			if ( $parts[4] !== 'to' ) {
				throw new Exception( "Part index 4 is not 'to'" );
			}
			if ( $parts[3] === 'html' && $parts[5] === 'wikitext' ) {
				if ( !isset( $req['body']['html'] ) ) {
					throw new Exception( "You must set an 'html' body key for this request" );
				}
			} elseif ( $parts[3] == 'wikitext' && $parts[5] == 'html' ) {
				if ( !isset( $req['body']['wikitext'] ) ) {
					throw new Exception( "You must set a 'wikitext' body key for this request" );
				}
				if ( isset( $req['body']['body'] ) ) {
					$req['body']['body_only'] = $req['body']['body'];
					unset( $req['body']['body'] );
				}
			} else {
				throw new Exception( "Transformation unsupported" );
			}
		}
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

		return $req;
	}

	/**
	 * Remap a Parsoid v3 request to a RESTBase v1 request.
	 *
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
	 * @param array $req
	 * @param Closure $idGeneratorFunc
	 * @return array
	 * @throws Exception
	 */
	public function onParsoid3Request( array $req, Closure $idGeneratorFunc ) {
		$parts = explode( '/', $req['url'] );
		list(
			$targetWiki, // 'local'
			$version, // 'v3'
			$action, // 'transform' or 'page'
			$format, // 'html' or 'wikitext'
			// $title, // optional
			// $revision, // optional
		) = $parts;
		if ( $targetWiki !== 'local' ) {
			throw new Exception( "Only 'local' target wiki is currently supported" );
		} elseif ( $version !== 'v3' ) {
			throw new Exception( "Version mismatch: should not happen." );
		}
		// replace /local/ with the current domain, change v3 to v1,
		$req['url'] = preg_replace( '#^local/v3/#', $this->params['domain'] . '/v1/', $req['url'] );
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

		return $req;
	}

}
