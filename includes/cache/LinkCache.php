<?php
/**
 * Page existence cache.
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
 * @ingroup Cache
 */

namespace MediaWiki\Cache;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use stdClass;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Parsoid\Core\LinkTarget;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 *
 * @ingroup Cache
 */
class LinkCache implements LoggerAwareInterface {
	/** @var MapCacheLRU */
	private $entries;
	/** @var WANObjectCache */
	private $wanCache;
	/** @var TitleFormatter */
	private $titleFormatter;
	/** @var NamespaceInfo */
	private $nsInfo;
	/** @var ILoadBalancer|null */
	private $loadBalancer;
	/** @var LoggerInterface */
	private $logger;

	/** How many Titles to store */
	private const MAX_SIZE = 10000;

	/** Key to page row object or null */
	private const ROW = 0;
	/** Key to query READ_* flags */
	private const FLAGS = 1;

	/**
	 * @param TitleFormatter $titleFormatter
	 * @param WANObjectCache $cache
	 * @param NamespaceInfo $nsInfo
	 * @param ILoadBalancer|null $loadBalancer Use null when no database is set up, for example on installation
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		WANObjectCache $cache,
		NamespaceInfo $nsInfo,
		?ILoadBalancer $loadBalancer = null
	) {
		$this->entries = new MapCacheLRU( self::MAX_SIZE );
		$this->wanCache = $cache;
		$this->titleFormatter = $titleFormatter;
		$this->nsInfo = $nsInfo;
		$this->loadBalancer = $loadBalancer;
		$this->logger = new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param LinkTarget|PageReference|array|string $page
	 * @param bool $passThrough Return $page if $page is a string
	 * @return ?string the cache key
	 */
	private function getCacheKey( $page, $passThrough = false ) {
		if ( is_string( $page ) ) {
			if ( $passThrough ) {
				return $page;
			} else {
				throw new InvalidArgumentException( 'They key may not be given as a string here' );
			}
		}

		if ( is_array( $page ) ) {
			$namespace = $page['page_namespace'];
			$dbkey = $page['page_title'];
			return strtr( $this->titleFormatter->formatTitle( $namespace, $dbkey ), ' ', '_' );
		}

		if ( $page instanceof PageReference && $page->getWikiId() !== PageReference::LOCAL ) {
			// No cross-wiki support yet. Perhaps LinkCache can become wiki-aware in the future.
			$this->logger->info(
				'cross-wiki page reference',
				[
					'page-wiki' => $page->getWikiId(),
					'page-reference' => $this->titleFormatter->getFullText( $page )
				]
			);
			return null;
		}

		if ( $page instanceof PageIdentity && !$page->canExist() ) {
			// Non-proper page, perhaps a special page or interwiki link or relative section link.
			$this->logger->warning(
				'non-proper page reference: {page-reference}',
				[ 'page-reference' => $this->titleFormatter->getFullText( $page ) ]
			);
			return null;
		}

		if ( $page instanceof LinkTarget
			&& ( $page->isExternal() || $page->getText() === '' || $page->getNamespace() < 0 )
		) {
			// Interwiki link or relative section link. These do not have a page ID, so they
			// can neither be "good" nor "bad" in the sense of this class.
			$this->logger->warning(
				'link to non-proper page: {page-link}',
				[ 'page-link' => $this->titleFormatter->getFullText( $page ) ]
			);
			return null;
		}

		return $this->titleFormatter->getPrefixedDBkey( $page );
	}

	/**
	 * Get the ID of a page known to the process cache
	 *
	 * @param LinkTarget|PageReference|array|string $page The page to get the ID for,
	 *        as an object, an array containing the page_namespace and page_title fields,
	 *        or a prefixed DB key. In MediaWiki 1.36 and earlier, only a string was accepted.
	 * @return int Page ID, or zero if the page was not cached or does not exist or is not a
	 *         proper page (e.g. a special page or an interwiki link).
	 */
	public function getGoodLinkID( $page ) {
		$key = $this->getCacheKey( $page, true );
		if ( $key === null ) {
			return 0;
		}

		$entry = $this->entries->get( $key );
		if ( !$entry ) {
			return 0;
		}

		$row = $entry[self::ROW];

		return $row ? (int)$row->page_id : 0;
	}

	/**
	 * Get the field of a page known to the process cache
	 *
	 * If this link is not a cached good title, it will return NULL.
	 * @param LinkTarget|PageReference|array $page The page to get cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param string $field ( 'id', 'length', 'redirect', 'revision', 'model', 'lang' )
	 * @return string|int|null The field value, or null if the page was not cached or does not exist
	 *         or is not a proper page (e.g. a special page or interwiki link).
	 */
	public function getGoodLinkFieldObj( $page, string $field ) {
		$key = $this->getCacheKey( $page );
		if ( $key === null ) {
			return null;
		}

		$entry = $this->entries->get( $key );
		if ( !$entry ) {
			return null;
		}

		$row = $entry[self::ROW];
		if ( !$row ) {
			return null;
		}

		switch ( $field ) {
			case 'id':
				return (int)$row->page_id;
			case 'length':
				return (int)$row->page_len;
			case 'redirect':
				return (int)$row->page_is_redirect;
			case 'revision':
				return (int)$row->page_latest;
			case 'model':
				return !empty( $row->page_content_model )
					? (string)$row->page_content_model
					: null;
			case 'lang':
				return !empty( $row->page_lang )
					? (string)$row->page_lang
					: null;
			default:
				throw new InvalidArgumentException( "Unknown field: $field" );
		}
	}

	/**
	 * Check if a page is known to be missing based on the process cache
	 *
	 * @param LinkTarget|PageReference|array|string $page The page to get cached info for,
	 *        as an object, an array containing the page_namespace and page_title fields,
	 *        or a prefixed DB key. In MediaWiki 1.36 and earlier, only a string was accepted.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 * @return bool Whether the page is known to be missing based on the process cache
	 */
	public function isBadLink( $page ) {
		$key = $this->getCacheKey( $page, true );
		if ( $key === null ) {
			return false;
		}

		$entry = $this->entries->get( $key );

		return ( $entry && !$entry[self::ROW] );
	}

	/**
	 * Add information about an existing page to the process cache
	 *
	 * Callers must set the READ_LATEST flag if the row came from a DB_PRIMARY source.
	 * However, the use of such data is highly discouraged; most callers rely on seeing
	 * consistent DB_REPLICA data (e.g. REPEATABLE-READ point-in-time snapshots) and the
	 * accidental use of DB_PRIMARY data via LinkCache is prone to causing anomalies.
	 *
	 * @param LinkTarget|PageReference|array $page The page to set cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param stdClass $row Object which has all fields returned by getSelectFields().
	 * @param int $queryFlags The query flags used to retrieve the row, IDBAccessObject::READ_*
	 * @since 1.19
	 */
	public function addGoodLinkObjFromRow(
		$page,
		stdClass $row,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	) {
		$key = $this->getCacheKey( $page );
		if ( $key === null ) {
			return;
		}

		foreach ( self::getSelectFields() as $field ) {
			if ( !property_exists( $row, $field ) ) {
				throw new InvalidArgumentException( "Missing field: $field" );
			}
		}

		$this->entries->set( $key, [ self::ROW => $row, self::FLAGS => $queryFlags ] );
	}

	/**
	 * Add information about a missing page to the process cache
	 *
	 * Callers must set the READ_LATEST flag if the row came from a DB_PRIMARY source.
	 * However, the use of such data is highly discouraged; most callers rely on seeing
	 * consistent DB_REPLICA data (e.g. REPEATABLE-READ point-in-time snapshots) and the
	 * accidental use of DB_PRIMARY data via LinkCache is prone to causing anomalies.
	 *
	 * @param LinkTarget|PageReference|array $page The page to set cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param int $queryFlags The query flags used to retrieve the row, IDBAccessObject::READ_*
	 */
	public function addBadLinkObj( $page, int $queryFlags = IDBAccessObject::READ_NORMAL ) {
		$key = $this->getCacheKey( $page );
		if ( $key === null ) {
			return;
		}

		$this->entries->set( $key, [ self::ROW => null, self::FLAGS => $queryFlags ] );
	}

	/**
	 * Clear information about a page being missing from the process cache
	 *
	 * @param LinkTarget|PageReference|array|string $page The page to clear cached info for,
	 *        as an object, an array containing the page_namespace and page_title fields,
	 *        or a prefixed DB key. In MediaWiki 1.36 and earlier, only a string was accepted.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 */
	public function clearBadLink( $page ) {
		$key = $this->getCacheKey( $page, true );
		if ( $key === null ) {
			return;
		}

		$entry = $this->entries->get( $key );
		if ( $entry && !$entry[self::ROW] ) {
			$this->entries->clear( $key );
		}
	}

	/**
	 * Clear information about a page from the process cache
	 *
	 * @param LinkTarget|PageReference|array $page The page to clear cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 */
	public function clearLink( $page ) {
		$key = $this->getCacheKey( $page );
		if ( $key !== null ) {
			$this->entries->clear( $key );
		}
	}

	/**
	 * Fields that LinkCache needs to select
	 *
	 * @since 1.28
	 * @return array
	 */
	public static function getSelectFields() {
		$pageLanguageUseDB = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::PageLanguageUseDB );

		$fields = array_merge(
			PageStoreRecord::REQUIRED_FIELDS,
			[
				'page_len',
				'page_content_model',
			]
		);

		if ( $pageLanguageUseDB ) {
			$fields[] = 'page_lang';
		}

		return $fields;
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent.
	 * This causes the link to be looked up in the database if it is not yet cached.
	 *
	 * @deprecated since 1.37, use PageStore::getPageForLink() instead.
	 *
	 * @param LinkTarget|PageReference|array $page The page to load.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param int $queryFlags IDBAccessObject::READ_XXX
	 *
	 * @return int Page ID or zero
	 */
	public function addLinkObj( $page, int $queryFlags = IDBAccessObject::READ_NORMAL ) {
		$row = $this->getGoodLinkRow(
			$page->getNamespace(),
			$page->getDBkey(),
			[ $this, 'fetchPageRow' ],
			$queryFlags
		);

		return $row ? (int)$row->page_id : 0;
	}

	/**
	 * @param TitleValue $link
	 * @param callable|null $fetchCallback
	 * @param int $queryFlags
	 * @return array [ $shouldAddGoodLink, $row ], $shouldAddGoodLink is a bool indicating
	 * whether addGoodLinkObjFromRow should be called, and $row is the row the caller was looking
	 * for (or null, when it was not found).
	 */
	private function getGoodLinkRowInternal(
		TitleValue $link,
		?callable $fetchCallback = null,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$callerShouldAddGoodLink = false;

		$key = $this->getCacheKey( $link );
		if ( $key === null ) {
			return [ $callerShouldAddGoodLink, null ];
		}

		$ns = $link->getNamespace();
		$dbkey = $link->getDBkey();

		$entry = $this->entries->get( $key );
		if ( $entry && $entry[self::FLAGS] >= $queryFlags ) {
			return [ $callerShouldAddGoodLink, $entry[self::ROW] ?: null ];
		}

		if ( !$fetchCallback ) {
			return [ $callerShouldAddGoodLink, null ];
		}

		$callerShouldAddGoodLink = true;

		$wanCacheKey = $this->getPersistentCacheKey( $link );
		if ( $wanCacheKey !== null && !( $queryFlags & IDBAccessObject::READ_LATEST ) ) {
			// Some pages are often transcluded heavily, so use persistent caching
			$row = $this->wanCache->getWithSetCallback(
				$wanCacheKey,
				WANObjectCache::TTL_DAY,
				function ( $curValue, &$ttl, array &$setOpts ) use ( $fetchCallback, $ns, $dbkey ) {
					$dbr = $this->loadBalancer->getConnection( ILoadBalancer::DB_REPLICA );
					$setOpts += Database::getCacheSetOptions( $dbr );

					$row = $fetchCallback( $dbr, $ns, $dbkey, [] );
					$mtime = $row ? (int)wfTimestamp( TS_UNIX, $row->page_touched ) : false;
					$ttl = $this->wanCache->adaptiveTTL( $mtime, $ttl );

					return $row;
				}
			);
		} else {
			// No persistent caching needed, but we can still use the callback.
			if ( ( $queryFlags & IDBAccessObject::READ_LATEST ) == IDBAccessObject::READ_LATEST ) {
				$dbr = $this->loadBalancer->getConnection( DB_PRIMARY );
			} else {
				$dbr = $this->loadBalancer->getConnection( DB_REPLICA );
			}
			$options = [];
			if ( ( $queryFlags & IDBAccessObject::READ_EXCLUSIVE ) == IDBAccessObject::READ_EXCLUSIVE ) {
				$options[] = 'FOR UPDATE';
			} elseif ( ( $queryFlags & IDBAccessObject::READ_LOCKING ) == IDBAccessObject::READ_LOCKING ) {
				$options[] = 'LOCK IN SHARE MODE';
			}
			$row = $fetchCallback( $dbr, $ns, $dbkey, $options );
		}

		return [ $callerShouldAddGoodLink, $row ?: null ];
	}

	/**
	 * Returns the row for the page if the page exists (subject to race conditions).
	 * The row will be returned from local cache or WAN cache if possible, or it
	 * will be looked up using the callback provided.
	 *
	 * @param int $ns
	 * @param string $dbkey
	 * @param callable|null $fetchCallback A callback that will retrieve the link row with the
	 *        signature ( IReadableDatabase $db, int $ns, string $dbkey, array $queryOptions ): ?stdObj.
	 * @param int $queryFlags IDBAccessObject::READ_XXX
	 *
	 * @return stdClass|null
	 * @internal for use by PageStore. Other code should use a PageLookup instead.
	 */
	public function getGoodLinkRow(
		int $ns,
		string $dbkey,
		?callable $fetchCallback = null,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): ?stdClass {
		$link = TitleValue::tryNew( $ns, $dbkey );
		if ( $link === null ) {
			return null;
		}

		[ $shouldAddGoodLink, $row ] = $this->getGoodLinkRowInternal(
			$link,
			$fetchCallback,
			$queryFlags
		);

		if ( $row ) {
			if ( $shouldAddGoodLink ) {
				try {
					$this->addGoodLinkObjFromRow( $link, $row, $queryFlags );
				} catch ( InvalidArgumentException $e ) {
					// a field is missing from $row; maybe we used a cache?; invalidate it and try again
					$this->invalidateTitle( $link );
					[ , $row ] = $this->getGoodLinkRowInternal(
						$link,
						$fetchCallback,
						$queryFlags
					);
					$this->addGoodLinkObjFromRow( $link, $row, $queryFlags );
				}
			}
		} else {
			$this->addBadLinkObj( $link );
		}

		return $row ?: null;
	}

	/**
	 * @param LinkTarget|PageReference|TitleValue $page
	 * @return string|null
	 */
	private function getPersistentCacheKey( $page ) {
		// if no key can be derived, the page isn't cacheable
		if ( $this->getCacheKey( $page ) === null || !$this->usePersistentCache( $page ) ) {
			return null;
		}

		return $this->wanCache->makeKey(
			'page',
			$page->getNamespace(),
			sha1( $page->getDBkey() )
		);
	}

	/**
	 * @param LinkTarget|PageReference|int $pageOrNamespace
	 * @return bool
	 */
	private function usePersistentCache( $pageOrNamespace ) {
		$ns = is_int( $pageOrNamespace ) ? $pageOrNamespace : $pageOrNamespace->getNamespace();
		if ( in_array( $ns, [ NS_TEMPLATE, NS_FILE, NS_CATEGORY, NS_MEDIAWIKI ] ) ) {
			return true;
		}
		// Focus on transcluded pages more than the main content
		if ( $this->nsInfo->isContent( $ns ) ) {
			return false;
		}
		// Non-talk extension namespaces (e.g. NS_MODULE)
		return ( $ns >= 100 && $this->nsInfo->isSubject( $ns ) );
	}

	/**
	 * @param IReadableDatabase $db
	 * @param int $ns
	 * @param string $dbkey
	 * @param array $options Query options, see IDatabase::select() for details.
	 * @return stdClass|false
	 */
	private function fetchPageRow( IReadableDatabase $db, int $ns, string $dbkey, $options = [] ) {
		$queryBuilder = $db->newSelectQueryBuilder()
			->select( self::getSelectFields() )
			->from( 'page' )
			->where( [ 'page_namespace' => $ns, 'page_title' => $dbkey ] )
			->options( $options );

		return $queryBuilder->caller( __METHOD__ )->fetchRow();
	}

	/**
	 * Purge the persistent link cache for a title
	 *
	 * @param LinkTarget|PageReference $page
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @since 1.28
	 */
	public function invalidateTitle( $page ) {
		$wanCacheKey = $this->getPersistentCacheKey( $page );
		if ( $wanCacheKey !== null ) {
			$this->wanCache->delete( $wanCacheKey );
		}

		$this->clearLink( $page );
	}

	/**
	 * Clears cache
	 */
	public function clear() {
		$this->entries->clear();
	}
}

/** @deprecated class alias since 1.42 */
class_alias( LinkCache::class, 'LinkCache' );
