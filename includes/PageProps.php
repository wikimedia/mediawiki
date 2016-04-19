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
	 * Overrides the default instance of this class
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * If this method is used it MUST also be called with null after a test to ensure a new
	 * default instance is created next time getInstance is called.
	 *
	 * @since 1.27
	 *
	 * @param PageProps|null $store
	 *
	 * @return ScopedCallback to reset the overridden value
	 * @throws MWException
	 */
	public static function overrideInstance( PageProps $store = null ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException(
				'Cannot override ' . __CLASS__ . 'default instance in operation.'
			);
		}
		$previousValue = self::$instance;
		self::$instance = $store;
		return new ScopedCallback( function() use ( $previousValue ) {
			self::$instance = $previousValue;
		} );
	}

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
	 * Given one or more Titles and one or more names of properties,
	 * returns an associative array mapping page ID to property value.
	 * Pages in the provided set of Titles that do not have a value for
	 * the given properties will not appear in the returned array. If a
	 * single Title is provided, it does not need to be passed in an array,
	 * but an array will always be returned. If a single property name is
	 * provided, it does not need to be passed in an array. In that case,
	 * an associtive array mapping page ID to property value will be
	 * returned; otherwise, an associative array mapping page ID to
	 * an associative array mapping property name to property value will be
	 * returned. An empty array will be returned if no matching properties
	 * were found.
	 *
	 * @param Title[]|Title $titles
	 * @param string[]|string $propertyNames
	 * @return array associative array mapping page ID to property value
	 */
	public function getProperties( $titles, $propertyNames ) {
		if ( is_array( $propertyNames ) ) {
			$gotArray = true;
		} else {
			$propertyNames = [ $propertyNames ];
			$gotArray = false;
		}

		$values = [];
		$goodIDs = $this->getGoodIDs( $titles );
		$queryIDs = [];
		foreach ( $goodIDs as $pageID ) {
			foreach ( $propertyNames as $propertyName ) {
				$propertyValue = $this->getCachedProperty( $pageID, $propertyName );
				if ( $propertyValue === false ) {
					$queryIDs[] = $pageID;
					break;
				} else {
					if ( $gotArray ) {
						$values[$pageID][$propertyName] = $propertyValue;
					} else {
						$values[$pageID] = $propertyValue;
					}
				}
			}
		}

		if ( $queryIDs ) {
			$dbr = wfGetDB( DB_SLAVE );
			$result = $dbr->select(
				'page_props',
				[
					'pp_page',
					'pp_propname',
					'pp_value'
				],
				[
					'pp_page' => $queryIDs,
					'pp_propname' => $propertyNames
				],
				__METHOD__
			);

			foreach ( $result as $row ) {
				$pageID = $row->pp_page;
				$propertyName = $row->pp_propname;
				$propertyValue = $row->pp_value;
				$this->cacheProperty( $pageID, $propertyName, $propertyValue );
				if ( $gotArray ) {
					$values[$pageID][$propertyName] = $propertyValue;
				} else {
					$values[$pageID] = $propertyValue;
				}
			}
		}

		return $values;
	}

	/**
	 * Get all page property values.
	 * Given one or more Titles, returns an associative array mapping page
	 * ID to an associative array mapping property names to property
	 * values. Pages in the provided set of Titles that do not have any
	 * properties will not appear in the returned array. If a single Title
	 * is provided, it does not need to be passed in an array, but an array
	 * will always be returned. An empty array will be returned if no
	 * matching properties were found.
	 *
	 * @param Title[]|Title $titles
	 * @return array associative array mapping page ID to property value array
	 */
	public function getAllProperties( $titles ) {
		$values = [];
		$goodIDs = $this->getGoodIDs( $titles );
		$queryIDs = [];
		foreach ( $goodIDs as $pageID ) {
			$pageProperties = $this->getCachedProperties( $pageID );
			if ( $pageProperties === false ) {
				$queryIDs[] = $pageID;
			} else {
				$values[$pageID] = $pageProperties;
			}
		}

		if ( $queryIDs != [] ) {
			$dbr = wfGetDB( DB_SLAVE );
			$result = $dbr->select(
				'page_props',
				[
					'pp_page',
					'pp_propname',
					'pp_value'
				],
				[
					'pp_page' => $queryIDs,
				],
				__METHOD__
			);

			$currentPageID = 0;
			$pageProperties = [];
			foreach ( $result as $row ) {
				$pageID = $row->pp_page;
				if ( $currentPageID != $pageID ) {
					if ( $pageProperties != [] ) {
						$this->cacheProperties( $currentPageID, $pageProperties );
						$values[$currentPageID] = $pageProperties;
					}
					$currentPageID = $pageID;
					$pageProperties = [];
				}
				$pageProperties[$row->pp_propname] = $row->pp_value;
			}
			if ( $pageProperties != [] ) {
				$this->cacheProperties( $pageID, $pageProperties );
				$values[$pageID] = $pageProperties;
			}
		}

		return $values;
	}

	/**
	 * @param Title[]|Title $titles
	 * @return array array of good page IDs
	 */
	private function getGoodIDs( $titles ) {
		$result = [];
		if ( is_array( $titles ) ) {
			foreach ( $titles as $title ) {
				$pageID = $title->getArticleID();
				if ( $pageID > 0 ) {
					$result[] = $pageID;
				}
			}
		} else {
			$pageID = $titles->getArticleID();
			if ( $pageID > 0 ) {
				$result[] = $pageID;
			}
		}
		return $result;
	}

	/**
	 * Get a property from the cache.
	 *
	 * @param int $pageID page ID of page being queried
	 * @param string $propertyName name of property being queried
	 * @return string|bool property value array or false if not found
	 */
	private function getCachedProperty( $pageID, $propertyName ) {
		if ( $this->cache->has( $pageID, $propertyName, self::CACHE_TTL ) ) {
			return $this->cache->get( $pageID, $propertyName );
		}
		if ( $this->cache->has( 0, $pageID, self::CACHE_TTL ) ) {
			$pageProperties = $this->cache->get( 0, $pageID );
			if ( isset( $pageProperties[$propertyName] ) ) {
				return $pageProperties[$propertyName];
			}
		}
		return false;
	}

	/**
	 * Get properties from the cache.
	 *
	 * @param int $pageID page ID of page being queried
	 * @return string|bool property value array or false if not found
	 */
	private function getCachedProperties( $pageID ) {
		if ( $this->cache->has( 0, $pageID, self::CACHE_TTL ) ) {
			return $this->cache->get( 0, $pageID );
		}
		return false;
	}

	/**
	 * Save a property to the cache.
	 *
	 * @param int $pageID page ID of page being cached
	 * @param string $propertyName name of property being cached
	 * @param mixed $propertyValue value of property
	 */
	private function cacheProperty( $pageID, $propertyName, $propertyValue ) {
		$this->cache->set( $pageID, $propertyName, $propertyValue );
	}

	/**
	 * Save properties to the cache.
	 *
	 * @param int $pageID page ID of page being cached
	 * @param string[] $pageProperties associative array of page properties to be cached
	 */
	private function cacheProperties( $pageID, $pageProperties ) {
		$this->cache->clear( $pageID );
		$this->cache->set( 0, $pageID, $pageProperties );
	}
}
