<?php
/**
 * External storage in PHP process memory for testing.
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
 * Process memory based external objects for testing.
 *
 * In this system, each store "location" is separate PHP array.
 * URLs are of the form "memory://location/id". The id/value pairs
 * at each location are segregated by DB domain ID.
 *
 * @ingroup ExternalStorage
 * @since 1.33
 */
class ExternalStoreMemory extends ExternalStoreMedium {
	/** @var array[] Map of (location => DB domain => id => value) */
	private static $data = [];
	/** @var int */
	private static $nextId = 0;

	public function __construct( array $params ) {
		parent::__construct( $params );
	}

	public function fetchFromURL( $url ) {
		list( $location, $id ) = self::getURLComponents( $url );
		if ( $id === null ) {
			throw new UnexpectedValueException( "Missing ID in URL component." );
		}

		return self::$data[$location][$this->dbDomain][$id] ?? false;
	}

	public function batchFetchFromURLs( array $urls ) {
		$blobs = [];
		foreach ( $urls as $url ) {
			$blob = $this->fetchFromURL( $url );
			if ( $blob !== false ) {
				$blobs[$url] = $blob;
			}
		}

		return $blobs;
	}

	public function store( $location, $data ) {
		$index = ++self::$nextId;
		self::$data[$location][$this->dbDomain][$index] = $data;

		return "memory://$location/$index";
	}

	/**
	 * Remove all data from memory for this domain
	 */
	public function clear() {
		foreach ( self::$data as &$dataForLocation ) {
			unset( $dataForLocation[$this->dbDomain] );
		}
		unset( $dataForLocation );
		self::$data = array_filter( self::$data, 'count' );
		self::$nextId = 0;
	}

	/**
	 * @param string $url
	 * @return array (location, ID or null)
	 */
	private function getURLComponents( $url ) {
		list( $proto, $path ) = explode( '://', $url, 2 ) + [ null, null ];
		if ( $proto !== 'memory' ) {
			throw new UnexpectedValueException( "Got URL of protocol '$proto', not 'memory'." );
		} elseif ( $path === null ) {
			throw new UnexpectedValueException( "URL is missing path component." );
		}

		$parts = explode( '/', $path );
		if ( count( $parts ) > 2 ) {
			throw new UnexpectedValueException( "Too components in URL '$path'." );
		}

		return [ $parts[0], $parts[1] ?? null ];
	}
}
