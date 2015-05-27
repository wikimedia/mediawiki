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
	 * Example requests:
	 *  GET /v2/local/html/$title/{$revision}
	 *   * $revision is optional
	 *  POST /v2/local/wt/{$title}/{$revision}
	 *   * body: array( 'html' => ... )
	 *   * $title and $revision are optional
	 *  POST /v2/local/html/{$title}/{$revision}
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'body' => true/false )
	 *   * $title is optional
	 *   * $revision is optional
	 * There are also deprecated "v1" requests; see onParsoid1Request
	 * for details.
	 * @param array $params Key/value map
	 *   - url            : Parsoid server URL
	 *   - domain         : Wiki domain to use
	 *   - timeout        : Parsoid timeout (optional)
	 *   - forwardCookies : Cookies to forward to Parsoid, or false. (optional)
	 *   - HTTPProxy      : Parsoid HTTP proxy (optional)
	 */
	public function __construct( array $params ) {
		global $wgCanonicalServer;
		// for backwards compatibility:
		if ( isset( $params['URL'] ) ) {
			$params['url'] = $params['URL'];
			unset( $params['URL'] );
		}
		// set up defaults and merge them with the given params
		$mparams = array_merge( array(
			'url' => 'http://localhost:8000/',
			'prefix' => 'localhost',
			'domain' => $wgCanonicalServer,
			'forwardCookies' => false,
			'HTTPProxy' => null,
		), $params );
		// Ensure the correct url format.
		$mparams['url'] = preg_replace(
			'#/?$#',
			'/',
			$mparams['url']
		);
		// Ensure the correct domain format.
		$mparams['domain'] = preg_replace(
			'/^(https?:\/\/)?([^\/:]+?)(\/|:\d+\/?)?$/',
			'$2',
			$mparams['domain']
		);
		parent::__construct( $mparams );
	}

	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = array();
		foreach ( $reqs as $key => $req ) {
			$parts = explode( '/', $req['url'] );

			list(
				$version, // 'v1' or 'v2'
				$targetWiki, // 'local'
				$format, // 'html' or 'wt'
				// $title (optional)
				// $revision (optional)
			) = $parts;

			if ( $version !== 'v2' ) {
				$result[$key] = $this->onParsoid1Request( $req, $idGeneratorFunc );
				continue;
			}
			if ( $targetWiki !== 'local' ) {

				throw new Exception( "Only 'local' target wiki is currently supported" );
			}
			if ( $format !== 'html' && $format !== 'wt' ) {
				throw new Exception( "Request format must be either 'html' or 'wt'" );
			}
			// replace /local/ with the current domain
			$req['url'] = preg_replace( '#^v2/local/#', 'v2/' . $this->params['domain'] . '/', $req['url'] );
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
	 * Remap a Parsoid v1 request to a Parsoid v2 path
	 *
	 * Example requests:
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
	 * Visual Editor "pretends" the V1 API is like.  A previous version of
	 * ParsoidVirtualRESTService translated these to the "real" Parsoid v1
	 * API.  We now translate these to the "real" Parsoid v2 API.
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
		} elseif ( $reqType !== 'page' && $reqType !== 'transform' ) {
			throw new Exception( "Request type must be either 'page' or 'transform'" );
		}
		$req['url'] = $this->params['url'] . 'v2/' . $this->params['domain'] . '/';
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
			if ( $parts[5] === 'wikitext' ) {
				// but in parsoid v2 api, "wikitext" is called "wt"
				$req['url'] .= 'wt';
				// and the response is JSON, not wikitext (text/plain)
				$req['FIXUPv1'] = true;
			} else {
				$req['url'] .= $parts[5];
			}
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

	public function onResponses( array $reqs, Closure $idGeneratorFunc ) {
		// Look for requests with 'FIXUPv1' set (these are v1 html2wt
		// requests) and tweak the response.
		$result = array();
		foreach ( $reqs as $key => $req ) {
			if ( isset( $req['FIXUPv1'] ) && $req['response']['code'] === 200 ) {
				// Decode the JSON response and fake a v1 API response.
				$body = FormatJson::parse( $req['response']['body'], FormatJson::FORCE_ASSOC );
				if ( $body->isGood() ) {
					$resp = $body->getValue()['wikitext'];
					$req['response']['headers']['content-type'] = $resp['headers']['content-type'];
					$req['response']['body'] = $resp['body'];
				} else {
					// JSON parsing didn't succeed, something is wrong.
					$req['response']['code'] = 500;
				}
			}
			$result[$key] = $req;
		}
		return $result;
	}
}
