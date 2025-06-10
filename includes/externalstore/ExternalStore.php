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

use MediaWiki\MediaWikiServices;

/**
 * @ingroup ExternalStorage
 * @deprecated since 1.34 Use the ExternalStoreAccess service instead.
 */
class ExternalStore {
	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * @param string $proto Type of external storage, should be a value in $wgExternalStores
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return ExternalStoreMedium|bool The store class or false on error
	 * @deprecated since 1.34
	 */
	public static function getStoreObject( $proto, array $params = [] ) {
		try {
			return MediaWikiServices::getInstance()
				->getExternalStoreFactory()
				->getStore( $proto, $params );
		} catch ( ExternalStoreException ) {
			return false;
		}
	}

	/**
	 * Fetch data from given URL
	 *
	 * @param string $url The URL of the text to get
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The text stored or false on error
	 * @deprecated since 1.34
	 */
	public static function fetchFromURL( $url, array $params = [] ) {
		try {
			return MediaWikiServices::getInstance()
				->getExternalStoreAccess()
				->fetchFromURL( $url, $params );
		} catch ( ExternalStoreException ) {
			return false;
		}
	}

	/**
	 * Store a data item to an external store, identified by a partial URL
	 * The protocol part is used to identify the class, the rest is passed to the
	 * class itself as a parameter.
	 *
	 * @param string $url A partial external store URL ("<store type>://<location>")
	 * @param string $data
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @deprecated since 1.34
	 */
	public static function insert( $url, $data, array $params = [] ) {
		try {
			$esFactory = MediaWikiServices::getInstance()->getExternalStoreFactory();
			$location = $esFactory->getStoreLocationFromUrl( $url );

			return $esFactory->getStoreForUrl( $url, $params )->store( $location, $data );
		} catch ( ExternalStoreException ) {
			return false;
		}
	}

	/**
	 * Fetch data from multiple URLs with a minimum of round trips
	 *
	 * @param array $urls The URLs of the text to get
	 * @return array Map from url to its data.  Data is either string when found
	 *     or false on failure.
	 * @throws ExternalStoreException
	 * @deprecated since 1.34
	 */
	public static function batchFetchFromURLs( array $urls ) {
		return MediaWikiServices::getInstance()->getExternalStoreAccess()->fetchFromURLs( $urls );
	}

	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters
	 * provided by $wgDefaultExternalStore.
	 *
	 * @param string $data
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string The URL of the stored data item
	 * @throws ExternalStoreException
	 * @deprecated since 1.34
	 */
	public static function insertToDefault( $data, array $params = [] ) {
		return MediaWikiServices::getInstance()->getExternalStoreAccess()->insert( $data, $params );
	}

	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters
	 * as provided in the first parameter.
	 *
	 * @param array $tryStores Refer to $wgDefaultExternalStore
	 * @param string $data
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string The URL of the stored data item
	 * @throws ExternalStoreException
	 * @deprecated since 1.34
	 */
	public static function insertWithFallback( array $tryStores, $data, array $params = [] ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreAccess()
			->insert( $data, $params, $tryStores );
	}

	/**
	 * @param string $data
	 * @param string $wiki
	 * @return string The URL of the stored data item
	 * @throws ExternalStoreException
	 * @deprecated since 1.34 Use insertToDefault() with 'wiki' set
	 */
	public static function insertToForeignDefault( $data, $wiki ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreAccess()
			->insert( $data, [ 'domain' => $wiki ] );
	}
}
