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
	 * @param array $params Key/value map
	 *   - parsoidURL            : Parsoid server URL
	 *   - parsoidTimeout        : Parsoid timeout
	 *   - parsoidForwardCookies : Whether to forward cookies to Parsoid or not
	 *   - parsoidHTTPProxy      : Parsoid HTTP proxy
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
	}

	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = array();
		foreach ( $reqs as $key => $req ) {
			$parts = explode( '/', $req['url'] );
			list( $prefix, $reqType /* 'page' or 'convert' */ ) = $parts;

			$req['url'] = $this->params['parsoidURL'] . '/' . $prefix . '/';

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
					throw new MWException( 'Output type not supported.' );
				}
			}

			if ( isset( $this->params['parsoidHTTPProxy'] ) && $this->params['parsoidHTTPProxy'] ) {
				$req['proxy'] = $this->params['parsoidHTTPProxy'];
			} else {
				$req['noproxy'] = true;
			}
			$req['timeout'] = $this->params['parsoidTimeout'];

			// Forward cookies, but only if configured to do so and if there are read restrictions
			if ( $this->params['parsoidForwardCookies']
				&& !User::isEveryoneAllowed( 'read' )
			) {
				$req['headers']['Cookie'] = RequestContext::getMain()->getRequest()->getHeader( 'Cookie' );
			}

			$result[$key] = $req;
		}
		return $result;
	}
}
