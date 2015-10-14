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

	/** Used to select master DB for reading */
	const USE_MASTER = 1;

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
			return self::$cache->get ($pageId, $propertyName );
		}
		return false;
	}

	/**
	 * Save a property to the cache.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param string $propertyName name of property being queried
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
	 * Get all page property values.
	 * This function does not use the cache.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param int $flags use PageProps::USE_MASTER to use master DB
	 *
	 * @return string|bool property value array or false if not found
	 *
	 */
	public static function getProperties( $pageId, $flags = 0 ) {

		$db = ( $flags & self::USE_MASTER ) ? wfGetDB( DB_MASTER ) : wfGetDB( DB_SLAVE );
		$result = $db->select(
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

		return $pageProperties;
	}

	/**
	 * Get page property value for a given property name.
	 * If the USE_MASTER flag is set, the master DB will always be queried.
	 * Otherwise, the cache will be checked first, and if the property is not
	 * found, the slave DB will be queried.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param string $propertyName name of property being queried
	 * @param int $flags use PageProps::USE_MASTER to use master DB
	 *
	 * @return string|bool property value or false if not found
	 *
	 */
	public static function getProperty( $pageId, $propertyName, $flags = 0 ) {

		if ( $flags & self::USE_MASTER ) {
			$db = wfGetDB( DB_MASTER );
		} else {
			$propertyValue = self::getCachedProperty( $pageId, $propertyName );
			if ( $propertyValue !== false ) {
				return $propertyValue;
			}
			$db = wfGetDB( DB_SLAVE );
		}

		$row = $db->selectRow(
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
			self::cacheProperty( $pageId, $propertyName, $propertyValue );
			return $propertyValue;
		}

		return false;
	}

	/**
	 * Determines the sort key for the given property value.
	 * This will return $value if it is a float or int,
	 * 1 or resp. 0 if it is a bool, and null otherwise.
	 *
	 * @param mixed $value
	 *
	 * @return float|null
	 *
	 */
	private static function getPropertySortKeyValue( $value ) {
		if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
			return floatval( $value );
		}

		return null;
	}

	/**
	 * Set page property values.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param array mapping property name to property value
	 *
	 */
	public static function setProperties( $pageId, $properties ) {

		$rows = array();

		foreach ( $properties as $propertyName => $propertyValue ) {

			$row = array(
				'pp_page' => $pageId,
				'pp_propname' => $propertyName,
				'pp_value' => $propertyValue
			);

			global $wgPagePropsHaveSortkey;
			if ( $wgPagePropsHaveSortkey ) {
				$row['pp_sortkey'] =
					self::getPropertySortKeyValue( $propertyValue );
			}

			$rows[] = $row;

			self::cacheProperty( $pageId, $propertyName, $propertyValue );
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'page_props',
			array (
				array (
					'pp_page',
					'pp_propname'
				)
			),
			$rows,
			__METHOD__
		);
	}

	/**
	 * Set page property value.
	 *
	 * @param int $pageId page ID of page being queried
	 * @param string $propertyName name of property being set
	 * @param mixed $propertyValue value of property
	 *
	 */
	public static function setProperty( $pageId, $propertyName,
		$propertyValue ) {

		$properties = array();
		$properties[$propertyName] = $propertyValue;

		self::setProperties( $pageId, $properties );

	}

}
