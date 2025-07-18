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

namespace MediaWiki\Page;

use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Gives access to properties of a page.
 *
 * @since 1.27
 * @ingroup Page
 */
class PageProps {
	/* TTL in seconds */
	private const CACHE_TTL = 10;
	/* max cached pages */
	private const CACHE_SIZE = 100;

	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;
	private MapCacheLRU $cache;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider
	) {
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
		$this->cache = new MapCacheLRU( self::CACHE_SIZE );
	}

	/**
	 * Ensure that cache has at least this size
	 * @param int $size
	 */
	public function ensureCacheSize( $size ) {
		if ( $this->cache->getMaxSize() < $size ) {
			$this->cache->setMaxSize( $size );
		}
	}

	/**
	 * Fetch one or more properties for one or more Titles.
	 *
	 * Returns an associative array mapping page ID to property value.
	 *
	 * If a single Title is provided without an array, the output will still
	 * be returned as an array by page ID.
	 *
	 * Pages in the provided set of Titles that do not have a value for
	 * any of the properties will not appear in the returned array.
	 *
	 * If a single property name is requested, it does not need to be passed
	 * in as an array. In that case, the return array will map directly from
	 * page ID to property value. Otherwise, a multi-dimensional array is
	 * returned keyed by page ID, then property name, to property value.
	 *
	 * An empty array will be returned if no matching properties were found.
	 *
	 * @param iterable<PageIdentity>|PageIdentity $titles
	 * @param string[]|string $propertyNames
	 * @return array<int,string|array<string,string>> Keyed by page ID and property name
	 *  to property value
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
				} elseif ( $gotArray ) {
					$values[$pageID][$propertyName] = $propertyValue;
				} else {
					$values[$pageID] = $propertyValue;
				}
			}
		}

		if ( $queryIDs ) {
			$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
			$queryBuilder->select( [ 'pp_page', 'pp_propname', 'pp_value' ] )
				->from( 'page_props' )
				->where( [ 'pp_page' => $queryIDs, 'pp_propname' => $propertyNames ] )
				->caller( __METHOD__ );
			$result = $queryBuilder->fetchResultSet();

			foreach ( $result as $row ) {
				$pageID = $row->pp_page;
				$propertyName = $row->pp_propname;
				$propertyValue = $row->pp_value;
				$this->cache->setField( $pageID, $propertyName, $propertyValue );
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
	 * Get all page properties of one or more page titles.
	 *
	 * Given one or more Titles, returns an array keyed by page ID to another
	 * array from property names to property values.
	 *
	 * If a single Title is provided without an array, the output will still
	 * be returned as an array by page ID.
	 *
	 * Pages in the provided set of Titles that do have no page properties,
	 * will not get a page ID key in the returned array.
	 *
	 * An empty array will be returned if none of the titles have any page properties.
	 *
	 * @param iterable<PageIdentity>|PageIdentity $titles
	 * @return array<int,array<string,string>> Keyed by page ID and property name to property value
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
			$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
			$queryBuilder->select( [ 'pp_page', 'pp_propname', 'pp_value' ] )
				->from( 'page_props' )
				->where( [ 'pp_page' => $queryIDs ] )
				->caller( __METHOD__ );
			$result = $queryBuilder->fetchResultSet();

			$currentPageID = 0;
			$pageProperties = [];
			foreach ( $result as $row ) {
				$pageID = $row->pp_page;
				if ( $currentPageID != $pageID ) {
					if ( $pageProperties ) {
						// @phan-suppress-next-line PhanTypeMismatchArgument False positive
						$this->cacheProperties( $currentPageID, $pageProperties );
						$values[$currentPageID] = $pageProperties;
					}
					$currentPageID = $pageID;
					$pageProperties = [];
				}
				$pageProperties[$row->pp_propname] = $row->pp_value;
			}
			if ( $pageProperties != [] ) {
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable pageID set when used
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable pageID set when used
				$this->cacheProperties( $pageID, $pageProperties );
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable pageID set when used
				// @phan-suppress-next-line PhanTypeMismatchDimAssignment pageID set when used
				$values[$pageID] = $pageProperties;
			}
		}

		return $values;
	}

	/**
	 * @param iterable<PageIdentity>|PageIdentity $titles
	 * @return int[] List of good page IDs
	 */
	private function getGoodIDs( $titles ) {
		$result = [];
		if ( is_iterable( $titles ) ) {
			if ( $titles instanceof TitleArrayFromResult ||
				( is_array( $titles ) && reset( $titles ) instanceof Title
			) ) {
				// If the first element is a Title, assume all elements are Titles,
				// and pre-fetch their IDs using a batch query. For PageIdentityValues
				// or PageStoreRecords, this is not necessary, since they already
				// know their ID.
				$this->linkBatchFactory->newLinkBatch( $titles )->execute();
			}

			foreach ( $titles as $title ) {
				// Until we only allow ProperPageIdentity, Title objects
				// can deceive us with an unexpected Special page
				if ( $title->canExist() ) {
					$pageID = $title->getId();
					if ( $pageID > 0 ) {
						$result[] = $pageID;
					}
				}
			}
		} else {
			// Until we only allow ProperPageIdentity, Title objects
			// can deceive us with an unexpected Special page
			if ( $titles->canExist() ) {
				$pageID = $titles->getId();
				if ( $pageID > 0 ) {
					$result[] = $pageID;
				}
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
		if ( $this->cache->hasField( $pageID, $propertyName, self::CACHE_TTL ) ) {
			return $this->cache->getField( $pageID, $propertyName );
		}
		if ( $this->cache->hasField( 0, $pageID, self::CACHE_TTL ) ) {
			$pageProperties = $this->cache->getField( 0, $pageID );
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
		if ( $this->cache->hasField( 0, $pageID, self::CACHE_TTL ) ) {
			return $this->cache->getField( 0, $pageID );
		}
		return false;
	}

	/**
	 * Save properties to the cache.
	 *
	 * @param int $pageID page ID of page being cached
	 * @param string[] $pageProperties associative array of page properties to be cached
	 */
	private function cacheProperties( $pageID, $pageProperties ) {
		$this->cache->clear( $pageID );
		$this->cache->setField( 0, $pageID, $pageProperties );
	}
}
