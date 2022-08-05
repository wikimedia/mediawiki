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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Gives access to properties of a page.
 *
 * @since 1.27
 */
class PageProps {

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** Cache parameters */
	private const CACHE_TTL = 10; // integer; TTL in seconds
	private const CACHE_SIZE = 100; // integer; max cached pages

	/** @var MapCacheLRU */
	private $cache;

	/**
	 * @deprecated since 1.38, hard deprecated since 1.39
	 * Use MediaWikiServices::getPageProps() instead
	 *
	 * @return PageProps
	 */
	public static function getInstance() {
		wfDeprecated( __METHOD__, '1.38' );
		return MediaWikiServices::getInstance()->getPageProps();
	}

	/**
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		ILoadBalancer $loadBalancer
	) {
		$this->linkBatchFactory = $linkBatchFactory;
		$this->loadBalancer = $loadBalancer;
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
	 * Given one or more Titles and one or more names of properties,
	 * returns an associative array mapping page ID to property value.
	 * Pages in the provided set of Titles that do not have a value for
	 * the given properties will not appear in the returned array. If a
	 * single Title is provided, it does not need to be passed in an array,
	 * but an array will always be returned. If a single property name is
	 * provided, it does not need to be passed in an array. In that case,
	 * an associative array mapping page ID to property value will be
	 * returned; otherwise, an associative array mapping page ID to
	 * an associative array mapping property name to property value will be
	 * returned. An empty array will be returned if no matching properties
	 * were found.
	 *
	 * @param iterable<PageIdentity>|PageIdentity $titles
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
				} elseif ( $gotArray ) {
					$values[$pageID][$propertyName] = $propertyValue;
				} else {
					$values[$pageID] = $propertyValue;
				}
			}
		}

		if ( $queryIDs ) {
			$queryBuilder = $this->loadBalancer->getConnectionRef( DB_REPLICA )->newSelectQueryBuilder();
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
	 * Get all page property values.
	 * Given one or more Titles, returns an associative array mapping page
	 * ID to an associative array mapping property names to property
	 * values. Pages in the provided set of Titles that do not have any
	 * properties will not appear in the returned array. If a single Title
	 * is provided, it does not need to be passed in an array, but an array
	 * will always be returned. An empty array will be returned if no
	 * matching properties were found.
	 *
	 * @param iterable<PageIdentity>|PageIdentity $titles
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
			$queryBuilder = $this->loadBalancer->getConnectionRef( DB_REPLICA )->newSelectQueryBuilder();
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
			if ( $titles instanceof TitleArray ||
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
