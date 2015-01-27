<?php
/**
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
 * @author Aaron Schulz
 */

/**
 * Relayed that uses the mcrelaypushd server
 */
class EventRelayerMCRD extends EventRelayer {
	/** @var MultiHttpClient */
	protected $http;
	/** @var string */
	protected $baseUrl;

	public function __construct( array $params ) {
		parent::__construct( $params );

		$this->baseUrl = $params['mcrdConfig']['url'];

		$this->http = new MultiHttpClient( array() );
	}

	protected function doNotify( $channel, array $events ) {
		if ( !count( $events ) ) {
			return true;
		}

		$response = $this->http->run( array(
			'url'     => "{$this->baseUrl}/relayer/api/v1.0/" . rawurlencode( $channel ),
			'method'  => 'POST',
			'body'    => json_encode( array( 'events' => $events ) ),
			'headers' => array( 'content-type' => 'application/json' )
		) );

		return $response['code'] == 201;
	}
}
