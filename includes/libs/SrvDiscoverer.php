<?php
/**
 * Service discovery using DNS SRV records
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
 * @since 1.29
 */
class SrvDiscoverer {

	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @param string $domain
	 */
	public function __construct( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * Fetch the servers with a DNS SRV request
	 *
	 * @return array
	 */
	public function getServers() {
		$records = dns_get_record( $this->domain, DNS_SRV );
		$result = [];
		foreach ( $records as $record ) {
			$result[] = [
				'target' => $record['target'],
				'port' => $record['port'],
				'pri' => $record['pri'],
				'weight' => $record['weight'],
			];
		}

		return $result;
	}

	/**
	 * Pick a server according to the priority fields.
	 * Note that weight is currently ignored.
	 *
	 * @param array $servers from getServers
	 * @return array|bool
	 */
	public function pickServer( array $servers ) {
		if ( !$servers ) {
			return false;
		}
		$by_prio = [];
		foreach ( $servers as $server ) {
			$by_prio[$server['pri']][] = $server;
		}

		$min = min( array_keys( $by_prio ) );
		if ( count( $by_prio[$min] ) == 1 ) {
			return $by_prio[$min][0];
		} else {
			// Choose randomly
			$rand = mt_rand( 0, count( $by_prio[$min] )-1 );
			return $by_prio[$min][$rand];
		}
	}
}
