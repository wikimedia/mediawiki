<?php
/**
 * Virtual REST service for Electron
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
 * Virtual REST service for Electron
 * @since 1.30
 */
class ElectronVirtualRestService extends VirtualRESTService {
	/**
	 * Example Electron requests:
	 *  GET /pdf/{$URL}
	 *  POST /pdf
	 *    (with the HTML as the POST body)
	 *
	 * @param array $params Key/value map
	 *   - url            : Electron server URL
	 *   - options        : Electron options, see https://github.com/wikimedia/mediawiki-services-electron-render#get-pdf---render-pdf
	 *   - timeout        : Request timeout (optional)
	 *   - HTTPProxy      : Use HTTP proxy (optional)
	 */
	public function __construct( array $params ) {
		// set up defaults and merge them with the given params
		$mparams = array_merge( [
			'name' => 'electron',
			'url' => 'http://localhost:3000/',
			'options' => [],
			'forwardCookies' => false,
			'timeout' => null,
			'HTTPProxy' => null,
		], $params );
		$mparams['options'] = array_merge( [
			'accessKey' => 'secret',
		], $mparams['options'] );
		// Ensure that the url parameter has a trailing slash.
		if ( substr( $mparams['url'], -1 ) !== '/' ) {
			$mparams['url'] .= '/';
		}
		parent::__construct( $mparams );
	}

	/**
	 * @inheritdoc
	 */
	public function onRequests( array $reqs, Closure $idGeneratorFunc ) {
		$result = [];
		foreach ( $reqs as $key => $req ) {
			$req['url'] = $this->params['url'] . $req['url'];
			if ( $this->params['options'] ) {
				$parts = wfParseUrl( $req['url'] );
				$parts['query'] += $this->params['options'];
				$req['url'] = wfAssembleUrl( $parts );
			}
			if ( $this->params['timeout'] != null ) {
				$req['reqTimeout'] = $this->params['timeout'];
			}
			if ( $this->params['HTTPProxy'] ) {
				$req['proxy'] = $this->params['HTTPProxy'];
			}
			$result[$key] = $req;
		}
		return $result;
	}
}
