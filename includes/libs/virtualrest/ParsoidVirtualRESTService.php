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
	 *  GET /local/v1/page/$title/html/$oldid
	 *   * $oldid is optional
	 *  POST /local/v1/transform/html/to/wikitext/$title/$oldid
	 *   * body: array( 'html' => ... )
	 *   * $title and $oldid are optional
	 *  POST /local/v1/transform/wikitext/to/html/$title
	 *   * body: array( 'wikitext' => ... ) or array( 'wikitext' => ..., 'body' => true/false )
	 *   * $title is optional
	 * @param array $params Key/value map
	 *   - url            : Parsoid server URL
	 *   - prefix         : Parsoid prefix for this wiki
	 *   - timeout        : Parsoid timeout (optional)
	 *   - forwardCookies : Cookies to forward to Parsoid, or false. (optional)
	 *   - HTTPProxy      : Parsoid HTTP proxy (optional)
	 */
	public function __construct( array $params ) {
		// for backwards compatibility:
		if ( isset( $params['URL'] ) ) {
			$params['url'] = $params['URL'];
			unset( $params['URL'] );
		}
		parent::__construct( $params );
	}

	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = array();
		foreach ( $reqs as $key => $req ) {
			$parts = explode( '/', $req['url'] );

			list(
				$targetWiki, // 'local'
				$version, // 'v1'
				$reqType // 'page' or 'transform'
			) = $parts;

			if ( $targetWiki !== 'local' ) {
				throw new Exception( "Only 'local' target wiki is currently supported" );
			} elseif ( $version !== 'v1' ) {
				throw new Exception( "Only version 1 exists" );
			} elseif ( $reqType !== 'page' && $reqType !== 'transform' ) {
				throw new Exception( "Request type must be either 'page' or 'transform'" );
			}

			$req['url'] = $this->params['url'] . '/' . urlencode( $this->params['prefix'] ) . '/';

			if ( $reqType === 'page' ) {
				$title = $parts[3];
				if ( $parts[4] !== 'html' ) {
					throw new Exception( "Only 'html' output format is currently supported" );
				}
				if ( isset( $parts[5] ) ) {
					$req['url'] .= $title . '?oldid=' . $parts[5];
				} else {
					$req['url'] .= $title;
				}
			} elseif ( $reqType === 'transform' ) {
				if ( $parts[4] !== 'to' ) {
					throw new Exception( "Part index 4 is not 'to'" );
				}

				if ( isset( $parts[6] ) ) {
					$req['url'] .= $parts[6];
				}

				if ( $parts[3] === 'html' & $parts[5] === 'wikitext' ) {
					if ( !isset( $req['body']['html'] ) ) {
						throw new Exception( "You must set an 'html' body key for this request" );
					}
					if ( isset( $parts[7] ) ) {
						$req['body']['oldid'] = $parts[7];
					}
				} elseif ( $parts[3] == 'wikitext' && $parts[5] == 'html' ) {
					if ( !isset( $req['body']['wikitext'] ) ) {
						throw new Exception( "You must set a 'wikitext' body key for this request" );
					}
					$req['body']['wt'] = $req['body']['wikitext'];
					unset( $req['body']['wikitext'] );
				} else {
					throw new Exception( "Transformation unsupported" );
				}
			}

			if ( isset( $this->params['HTTPProxy'] ) && $this->params['HTTPProxy'] ) {
				$req['proxy'] = $this->params['HTTPProxy'];
			}
			if ( isset( $this->params['timeout'] ) ) {
				$req['reqTimeout'] = $this->params['timeout'];
			}

			// Forward cookies
			if ( isset( $this->params['forwardCookies'] ) ) {
				$req['headers']['Cookie'] = $this->params['forwardCookies'];
			}

			$result[$key] = $req;
		}
		return $result;
	}
}
