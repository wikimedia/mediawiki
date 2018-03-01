<?php
/**
 * @defgroup ExternalStorage ExternalStorage
 */

use MediaWiki\MediaWikiServices;

/**
 * Interface for data storage in external repositories.
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
 * Constructor class for key/value blob data kept in external repositories.
 *
 * Objects in external stores are defined by a special URL. The URL is of
 * the form "<store protocol>://<location>/<object name>". The protocol is used
 * to determine what ExternalStoreMedium class is used. The location identifies
 * particular storage instances or database clusters for store class to use.
 *
 * When an object is inserted into a store, the calling code uses a partial URL of
 * the form "<store protocol>://<location>" and receives the full object URL on success.
 * This is useful since object names can be sequential IDs, UUIDs, or hashes.
 * Callers are not responsible for unique name generation.
 *
 * External repositories might be populated by maintenance/async
 * scripts, thus partial moving of data may be possible, as well
 * as the possibility to have any storage format (i.e. for archives).
 *
 * @ingroup ExternalStorage
 * @deprecated 1.31 Use ExternalStoreFactory directly instead
 */
class ExternalStore {
	/**
	 * Get an external store object of the given type, with the given parameters
	 *
	 * @param string $proto Type of external storage, should be a value in $wgExternalStores
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return ExternalStoreMedium|bool The store class or false on error
	 * @deprecated 1.31
	 */
	public static function getStoreObject( $proto, array $params = [] ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->getStoreObject( $proto, $params );
	}

	/**
	 * Fetch data from given URL
	 *
	 * @param string $url The URL of the text to get
	 * @param array $params Associative array of ExternalStoreMedium parameters
	 * @return string|bool The text stored or false on error
	 * @throws MWException
	 * @deprecated 1.31
	 */
	public static function fetchFromURL( $url, array $params = [] ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->fetchFromURL( $url, $params );
	}

	/**
	 * Fetch data from multiple URLs with a minimum of round trips
	 *
	 * @param array $urls The URLs of the text to get
	 * @return array Map from url to its data.  Data is either string when found
	 *     or false on failure.
	 * @throws MWException
	 * @deprecated 1.31
	 */
	public static function batchFetchFromURLs( array $urls ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->batchFetchFromURLs( $urls );
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
	 * @throws MWException
	 * @deprecated 1.31
	 */
	public static function insert( $url, $data, array $params = [] ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->insert( $url, $data, $params );
	}

	/**
	 * Like insert() above, but does more of the work for us.
	 * This function does not need a url param, it builds it by
	 * itself. It also fails-over to the next possible clusters
	 * provided by $wgDefaultExternalStore.
	 *
	 * @param string $data
	 * @param array $params Map of ExternalStoreMedium::__construct context parameters
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 * @deprecated 1.31
	 */
	public static function insertToDefault( $data, array $params = [] ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->insertToDefault( $data, $params );
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
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 * @deprecated 1.31
	 */
	public static function insertWithFallback( array $tryStores, $data, array $params = [] ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->insertWithFallback( $tryStores, $data, $params );
	}

	/**
	 * @param string $data
	 * @param string $wiki
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 * @deprecated 1.31 Use insertToDefault() with 'wiki' set
	 */
	public static function insertToForeignDefault( $data, $wiki ) {
		return MediaWikiServices::getInstance()
			->getExternalStoreFactory()
			->insertToDefault( $data, [ 'wiki' => $wiki ] );
	}
}
