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

	/**
	 * @var PageProps
	 */
	private static $instance;

	/**
	 * @return PageProps
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/** Cache parameters */
	const CACHE_TTL = 10; // integer; TTL in seconds
	const CACHE_SIZE = 100; // integer; max cached pages

	/** Property cache */
	private $cache = null;

	/**
	 * Create a PageProps object
	 */
	private function __construct() {
		$this->cache = new ProcessCacheLRU( self::CACHE_SIZE );
	}

	/**
	 * Get page property value for a given property name.
	 *
	 * @param Title $title
	 * @param string $propertyName
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	public function getProperty( Title $title, $propertyName ) {

		$pageId = $title->getArticleID();
		if ( $pageId < 1 ) {
			return false;
		}

		$propertyValue = $this->getCachedProperty( $pageId, $propertyName );
		if ( $propertyValue !== false ) {
			return $propertyValue;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow(
			'page_props',
			array( 'pp_value' ),
			array(
				'pp_page' => $pageId,
				'pp_propname' => $propertyName
			),
			__METHOD__
		);

		if ( $row !== false ) {
			$propertyValue = $row->pp_value;
			$this->cacheProperty( $pageId, $propertyName, $propertyValue );
			return $propertyValue;
		}

		return false;
	}

	/**
	 * Get all page property values.
	 *
	 * @param Title $title
	 * @return array property value array
	 *
	 */
	public function getProperties( Title $title ) {

		$pageId = $title->getArticleID();
		if ( $pageId < 1 ) {
			return array();
		}

		$pageProperties = $this->getCachedProperties( $pageId );
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
				'pp_page' => $pageId
			),
			__METHOD__
		);

		$pageProperties = array();

		foreach ( $result as $row ) {
			$pageProperties[$row->pp_propname] = $row->pp_value;
		}

		$this->cacheProperties( $pageId, $pageProperties );

		return $pageProperties;
	}

	/**
	 * Get a property from the cache.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param string $propertyName name of property being queried
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	private function getCachedProperty( $pageId, $propertyName ) {
		if ( $this->cache->has( $pageId, $propertyName, self::CACHE_TTL ) ) {
			return $this->cache->get( $pageId, $propertyName );
		}
		if ( $this->cache->has( 0, $pageId, self::CACHE_TTL ) ) {
			$pageProperties = $this->cache->get( 0, $pageId );
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
	private function getCachedProperties( $pageId ) {
		if ( $this->cache->has( 0, $pageId, self::CACHE_TTL ) ) {
			return $this->cache->get( 0, $pageId );
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
	private function cacheProperty( $pageId, $propertyName, $propertyValue ) {
		$this->cache->set( $pageId, $propertyName, $propertyValue );
	}

	/**
	 * Save properties to the cache.
	 *
	 * @param int $pageId page ID of page being cached
	 * @param array $pageProperties associative array of page properties to be cached
	 *
	 */
	private function cacheProperties( $pageId, $pageProperties ) {
		$this->cache->clear( $pageId );
		$this->cache->set( 0, $pageId, $pageProperties );
	}
}
