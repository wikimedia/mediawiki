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
	 *  GET /$wgDBname/page/$title/html/$oldid
	 *  POST /$wgDBname/convert/$title (with the body being either oldid and html or
	 *                                  wt and possibly body)
	 * @param array $params Key/value map
	 *   - URL            : Parsoid server URL
	 *   - timeout        : Parsoid timeout
	 *   - forwardCookies : Cookies to forward to Parsoid, or false.
	 *   - HTTPProxy      : Parsoid HTTP proxy
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
	}

	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = array();
		foreach ( $reqs as $key => $req ) {
			$parts = explode( '/', $req['url'] );
			list( $prefix, $reqType /* 'page' or 'convert' */ ) = $parts;

			$req['url'] = $this->params['URL'] . '/' . $prefix . '/';

			if ( $reqType === 'page' ) {
				list(
					$title,
					$outType, // 'html' or 'data-mw'
					$oldid
				) = array_slice( $parts, 2 );
				if ( $outType === 'html' ) {
					if ( $oldid ) {
						$req['url'] .= $title . '?oldid=' . $oldid;
					} else {
						$req['url'] .= $title;
					}
				} else { // 'data-mw'
					throw new Exception( 'Output type not supported.' );
				}
			} elseif ( $reqType === 'convert' && isset( $parts[2] ) ) {
				$req['url'] .= $parts[2];
			}

			if ( isset( $this->params['HTTPProxy'] ) && $this->params['HTTPProxy'] ) {
				$req['proxy'] = $this->params['HTTPProxy'];
			}
			$req['reqTimeout'] = $this->params['timeout'];

			// Forward cookies
			if ( isset( $this->params['forwardCookies'] ) ) {
				$req['headers']['Cookie'] = $this->params['forwardCookies'];
			}

			$result[$key] = $req;
		}
		return $result;
	}
}
