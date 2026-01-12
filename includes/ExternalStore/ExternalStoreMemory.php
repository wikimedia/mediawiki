<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ExternalStore;

use UnexpectedValueException;

/**
 * External storage in PHP process memory for testing.
 *
 * In this system, each store "location" is separate PHP array.
 * URLs are of the form "memory://location/id". The id/value pairs
 * at each location are segregated by DB domain ID.
 *
 * @see ExternalStoreAccess
 * @ingroup ExternalStorage
 * @since 1.33
 */
class ExternalStoreMemory extends ExternalStoreMedium {
	/** @var array[] Map of (location => DB domain => id => value) */
	private static $data = [];
	/** @var int */
	private static $nextId = 0;

	/** @inheritDoc */
	public function fetchFromURL( $url ) {
		[ $location, $id ] = self::getURLComponents( $url );
		if ( $id === null ) {
			throw new UnexpectedValueException( "Missing ID in URL component." );
		}

		return self::$data[$location][$this->dbDomain][$id] ?? false;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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
		// @phan-suppress-next-line PhanSuspiciousBinaryAddLists It's intentional
		[ $proto, $path ] = explode( '://', $url, 2 ) + [ null, null ];
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

/** @deprecated class alias since 1.46 */
class_alias( ExternalStoreMemory::class, 'ExternalStoreMemory' );
