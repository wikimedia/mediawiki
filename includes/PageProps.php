<?php
/**
 * Access to properties of a page.
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
 * Gives access to properties of a page.
 *
 * @since 1.27
 *
 */
class PageProps {

	/** @var int $pageId page ID of the page being queried */
	private $pageId;

	/**
	 * Create a PageProps object
	 *
	 * @param Title $title
	 *
	 */
	public function __construct( Title $title ) {
		$this->pageId = $title->getArticleID();
	}

	/**
	 * Get page property value for a given property name.
	 *
	 * @param string $propertyName
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	public function getProperty( $propertyName ) {

		if ( $this->pageId < 1 ) {
			return false;
		}

		$propertyValue = self::getCachedProperty( $this->pageId, $propertyName );
		if ( $propertyValue !== false ) {
			return $propertyValue;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow(
			'page_props',
			array( 'pp_value' ),
			array(
				'pp_page' => $this->pageId,
				'pp_propname' => $propertyName
			),
			__METHOD__
		);

		if ( $row !== false ) {
			$propertyValue = $row->pp_value;
			self::cacheProperty( $this->pageId, $propertyName, $propertyValue );
			return $propertyValue;
		}

		return false;
	}

	/**
	 * Get all page property values.
	 *
	 * @return array property value array
	 *
	 */
	public function getProperties() {

		if ( $this->pageId < 1 ) {
			return array();
		}

		$pageProperties = self::getCachedProperties( $this->pageId );
		if ( $pageProperties !== false ) {
			return $pageProperties;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			'page_props',
			array(
				'pp_propname',
				'pp_value'
			),
			array(
				'pp_page' => $this->pageId
			),
			__METHOD__
		);

		$pageProperties = array();

		foreach ( $result as $row ) {
			$pageProperties[$row->pp_propname] = $row->pp_value;
		}

		self::cacheProperties( $this->pageId, $pageProperties );

		return $pageProperties;
	}

	/** Cache parameters */
	const CACHE_TTL = 10; // integer; TTL in seconds
	const CACHE_SIZE = 100; // integer; max cached pages

	/** Property cache */
	private static $cache = null;

	/**
	 * Get a property from the cache.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param string $propertyName name of property being queried
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	private static function getCachedProperty( $pageId, $propertyName ) {
		if ( is_null( self::$cache ) ) {
			return false;
		}
		if ( self::$cache->has( $pageId, $propertyName, self::CACHE_TTL ) ) {
			return self::$cache->get( $pageId, $propertyName );
		}
		if ( self::$cache->has( 0, $pageId, self::CACHE_TTL ) ) {
			$pageProperties = self::$cache->get( 0, $pageId );
			if ( isset( $pageProperties[$propertyName] ) ) {
				return $pageProperties[$propertyName];
			}
		}
		return false;
	}

	/**
	 * Get properties from the cache.
	 *
	 * @param int $pageId page ID of page being queried
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	private static function getCachedProperties( $pageId ) {
		if ( is_null( self::$cache ) ) {
			return false;
		}
		if ( self::$cache->has( 0, $pageId, self::CACHE_TTL ) ) {
			return self::$cache->get( 0, $pageId );
		}
		return false;
	}

	/**
	 * Save a property to the cache.
	 *
	 * @param int $pageId page ID of page being cached
	 * @param string $propertyName name of property being cached
	 * @param mixed $propertyValue value of property
	 *
	 */
	private static function cacheProperty( $pageId, $propertyName,
		$propertyValue ) {
		if ( is_null( self::$cache ) ) {
			self::$cache = new ProcessCacheLRU( self::CACHE_SIZE );
		}
		self::$cache->set( $pageId, $propertyName, $propertyValue );
	}

	/**
	 * Save properties to the cache.
	 *
	 * @param int $pageId page ID of page being cached
	 * @param array $pageProperties associative array of page properties to be cached
	 *
	 */
	private static function cacheProperties( $pageId, $pageProperties ) {
		if ( is_null( self::$cache ) ) {
			self::$cache = new ProcessCacheLRU( self::CACHE_SIZE );
		}
		self::$cache->clear( $pageId );
		self::$cache->set( 0, $pageId, $pageProperties );
	}
}
