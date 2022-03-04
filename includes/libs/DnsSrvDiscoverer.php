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
class DnsSrvDiscoverer {
	/**
	 * @var string
	 */
	private $service;

	/**
	 * @var string
	 */
	private $protocol;

	/**
	 * @var string|null
	 */
	private $domain;

	/**
	 * @var callable
	 */
	private $resolver;

	/**
	 * Construct a new discoverer for the given domain, service, and protocol.
	 *
	 * @param string $service Name of the service to discover.
	 * @param string $protocol Service protocol. Defaults to 'tcp'
	 * @param ?string $domain The hostname/domain on which to perform discovery
	 *  of the given service and protocol. Defaults to null which effectively
	 *  performs a query relative to the host's configured search domain.
	 * @param ?callable $resolver Resolver function. Defaults to using
	 *  dns_get_record. Primarily useful in testing.
	 */
	public function __construct(
		string $service,
		string $protocol = 'tcp',
		?string $domain = null,
		?callable $resolver = null
	) {
		$this->service = $service;
		$this->protocol = $protocol;
		$this->domain = $domain;

		$this->resolver = $resolver ?? static function ( $srv ) {
			return dns_get_record( $srv, DNS_SRV );
		};
	}

	/**
	 * Queries the resolver for an SRV resource record matching the service,
	 * protocol, and domain and returns all target/port/priority/weight
	 * records.
	 *
	 * @return array
	 */
	public function getRecords() {
		$result = [];

		$records = ( $this->resolver )( $this->getSrvName() );

		// Respect RFC 2782 with regard to a single '.' entry denoting a valid
		// empty response
		if (
			!$records
			|| ( count( $records ) === 1 && $records[0]['target'] === '.' )
		) {
			return $result;
		}

		foreach ( $records as $record ) {
			$result[] = [
				'target' => $record['target'],
				'port' => (int)$record['port'],
				'pri' => (int)$record['pri'],
				'weight' => (int)$record['weight'],
			];
		}

		return $result;
	}

	/**
	 * Performs discovery for the domain, service, and protocol, and returns a
	 * list of resolved server name/ip and port number pairs sorted by each
	 * record's priority, with servers of the same priority randomly shuffled.
	 *
	 * @return array
	 */
	public function getServers() {
		$records = $this->getRecords();

		usort( $records, static function ( $a, $b ) {
			if ( $a['pri'] === $b['pri'] ) {
				return mt_rand( 0, 1 ) ? 1 : -1;
			}

			return $a['pri'] - $b['pri'];
		} );

		$serversAndPorts = [];

		foreach ( $records as $record ) {
			$serversAndPorts[] = [ $record['target'], $record['port'] ];
		}

		return $serversAndPorts;
	}

	/**
	 * Returns the SRV resource record name.
	 *
	 * @return string
	 */
	public function getSrvName(): string {
		$srv = "_{$this->service}._{$this->protocol}";

		if ( $this->domain === null || $this->domain === '' ) {
			return $srv;
		}

		return "$srv.{$this->domain}";
	}
}
