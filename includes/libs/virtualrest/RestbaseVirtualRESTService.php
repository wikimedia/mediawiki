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
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'bodyOnly' => true/false )
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
	 */
	public function __construct( array $params ) {
		global $wgCanonicalServer;
		// set up defaults and merge them with the given params
		$mparams = array_merge( array(
			'url' => 'http://localhost:7231/',
			'domain' => $wgCanonicalServer,
			'timeout' => 100,
			'forwardCookies' => false,
			'HTTPProxy' => null,
			'parsoidCompat' => false
		), $params );
		// Ensure that the url parameter has a trailing slash.
		$mparams['url'] = preg_replace(
			'#/?$#',
			'/',
			$mparams['url']
		);
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

		$result = array();
		foreach ( $reqs as $key => $req ) {
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
			$result[$key] = $req;
		}

		return $result;

	}

	/**
	 * Remaps Parsoid v1/v2 requests to RESTBase v1 requests.
	 */
	public function onParsoidRequests( array $reqs, Closure $idGeneratorFunc ) {

		$result = array();
		foreach ( $reqs as $key => $req ) {
			$parts = explode( '/', $req['url'] );
			if ( $parts[0] === 'v2' ) {
				$result[$key] = $this->onParsoid2Request( $req, $idGeneratorFunc );
			} elseif ( $parts[1] === 'v1' ) {
				$result[$key] = $this->onParsoid1Request( $req, $idGeneratorFunc );
			} else {
				throw new Exception( "Only v1 and v2 are supported." );
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
	 */
	public function onParsoid1Request( array $req, Closure $idGeneratorFunc ) {
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
					$req['body']['bodyOnly'] = $req['body']['body'];
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
	 * Remap a Parsoid v2 request to a RESTBase v1 request.
	 *
	 * Example Parsoid v2 requests:
	 *  GET /v2/local/html/$title/{$revision}
	 *   * $revision is optional
	 *  POST /v2/local/wt/{$title}/{$revision}
	 *   * body: array( 'html' => ... )
	 *   * $title and $revision are optional
	 *  POST /v2/local/html/{$title}/{$revision}
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'body' => true/false )
	 *   * $title is optional
	 *   * $revision is optional
	 */
	public function onParsoid2Request( array $req, Closure $idGeneratorFunc ) {

		$parts = explode( '/', $req['url'] );
		list(
			$version, // 'v2'
			$targetWiki, // 'local'
			$format, // 'html' or 'wt'
			// $title, // optional
			// $revision, // optional
		) = $parts;
		$title = isset( $parts[3] ) ? $parts[3] : null;
		$revision = isset( $parts[4] ) ? $parts[4] : null;
		if ( $targetWiki !== 'local' ) {
			throw new Exception( "Only 'local' target wiki is currently supported" );
		} elseif ( $version !== 'v2' ) {
			throw new Exception( "Version mismatch: should not happen." );
		}
		$req['url'] = $this->params['url'] . $this->params['domain'] . '/v1/';
		if ( $req['method'] === 'GET' ) {
			$req['url'] .= 'page/' . $format . '/' . $title;
			if ( $revision !== null ) {
				$req['url'] .= '/' . $revision;
			}
		} elseif ( $format === 'html' ) {
			$req['url'] .= 'transform/wikitext/to/html';
			if ( $title !== null ) {
				$req['url'] .= '/' . $title;
				if ( $revision !== null ) {
					$req['url'] .= '/' . $revision;
				}
			}
		} elseif ( $format === 'wt' ) {
			$req['url'] .= 'transform/html/to/wikitext';
			if ( $title !== null ) {
				$req['url'] .= '/' . $title;
				if ( $revision !== null ) {
					$req['url'] .= '/' . $revision;
				}
			}
			if ( isset( $req['body']['body'] ) ) {
				$req['body']['bodyOnly'] = $req['body']['body'];
				unset( $req['body']['body'] );
			}
			// Response will be JSON, but RESTBase expects wikitext
			$req['FIXUPv2'] = true;
		} else {
			throw new Exception( "Request format must be either 'html' or 'wt'" );
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

	public function onResponses( array $reqs, Closure $idGeneratorFunc ) {
		// Look for requests with 'FIXUPv2' set (these are Parsoid v2 html2wt
		// requests) and tweak the response.
		$result = array();
		foreach ( $reqs as $key => $req ) {
			if ( isset( $req['FIXUPv2'] ) && $req['response']['code'] === 200 ) {
				// Fake a v2 API response by making a JSON response.
				$json = FormatJson::encode(
					array(
						'wikitext' => array(
							'headers' => array(
								'content-type' => $req['response']['headers']['content-type'],
							),
							'body'  => $req['response']['body'],
						),
					), FormatJson::ALL_OK );
				if ( $json === false ) {
					// JSON encoding didn't succeed, something is wrong.
					$req['response']['code'] = 500;
				} else {
					$req['response']['body'] = $json;
					$req['response']['headers']['content-type'] = 'application/json';
				}
			}
			$result[$key] = $req;
		}
		return $result;
	}
}
