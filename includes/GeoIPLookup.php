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
 */

namespace MediaWiki;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Model\Asn;
use GeoIp2\Model\City;

/**
 * Wrapper around GeoIP2 libraries
 *
 * @since 1.30
 */
class GeoIPLookup {

	/**
	 * Directory where data files are
	 *
	 * @var string
	 */
	private $dataDirectory;

	/**
	 * @var Reader|null
	 */
	private $cityDb;

	/**
	 * @var Reader|null
	 */
	private $asnDb;

	public static function isEnabled() {
		global $wgGeoIPDataDirectory;
		return $wgGeoIPDataDirectory !== false && class_exists( Reader::class );
	}

	/**
	 * @param string $dataDirectory Directory where data files are or false if disabled
	 */
	public function __construct( $dataDirectory ) {
		$this->dataDirectory = $dataDirectory;
	}

	/**
	 * Get an IP address's city information
	 *
	 * @param string $ip IP address
	 * @return bool|City false if address wasn't found in the database
	 */
	public function getCityInfo( $ip ) {
		if ( !$this->cityDb ) {
			$this->cityDb = new Reader( "{$this->dataDirectory}/GeoLite2-City.mmdb" );
		}

		try {
			return $this->cityDb->city( $ip );
		} catch ( AddressNotFoundException $e ) {
			return false;
		}
	}

	/**
	 * Get an IP address's ASN information
	 *
	 * @param string $ip IP address
	 * @return bool|Asn false if the address wasn't found in the database
	 */
	public function getASNInfo( $ip ) {
		if ( !$this->asnDb ) {
			$this->asnDb = new Reader( "{$this->dataDirectory}/GeoLite2-ASN.mmdb" );
		}

		try {
			return $this->asnDb->asn( $ip );
		} catch ( AddressNotFoundException $e ) {
			return false;
		}
	}
}
