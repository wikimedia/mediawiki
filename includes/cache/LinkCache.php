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

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageStoreRecord;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 *
 * @ingroup Cache
 */
class LinkCache implements LoggerAwareInterface {
	/** @var MapCacheLRU */
	private $goodLinks;
	/** @var MapCacheLRU */
	private $badLinks;
	/** @var WANObjectCache */
	private $wanCache;

	/** @var bool */
	private $mForUpdate = false;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var ILoadBalancer|null */
	private $loadBalancer;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * How many Titles to store. There are two caches, so the amount actually
	 * stored in memory can be up to twice this.
	 */
	private const MAX_SIZE = 10000;

	/**
	 * @param TitleFormatter $titleFormatter
	 * @param WANObjectCache $cache
	 * @param NamespaceInfo|null $nsInfo Null for backward compatibility, but deprecated
	 * @param ILoadBalancer|null $loadBalancer Use null when no database is set up, for example on installation
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		WANObjectCache $cache,
		NamespaceInfo $nsInfo = null,
		ILoadBalancer $loadBalancer = null
	) {
		if ( !$nsInfo ) {
			wfDeprecated( __METHOD__ . ' with no NamespaceInfo argument', '1.34' );
			$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		}
		$this->goodLinks = new MapCacheLRU( self::MAX_SIZE );
		$this->badLinks = new MapCacheLRU( self::MAX_SIZE );
		$this->wanCache = $cache;
		$this->titleFormatter = $titleFormatter;
		$this->nsInfo = $nsInfo;
		$this->loadBalancer = $loadBalancer;
		$this->logger = new NullLogger();
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * General accessor to get/set whether the primary DB should be used
	 *
	 * This used to also set the FOR UPDATE option (locking the rows read
	 * in order to avoid link table inconsistency), which was later removed
	 * for performance on wikis with a high edit rate.
	 *
	 * @param bool|null $update
	 * @return bool
	 * @deprecated Since 1.34. Use PageStore::getPageForLink with IDBAccessObject::READ_LATEST.
	 */
	public function forUpdate( $update = null ) {
		wfDeprecated( __METHOD__, '1.34' ); // hard deprecated since 1.37
		return wfSetVar( $this->mForUpdate, $update );
	}

	/**
	 * @param LinkTarget|PageReference|array|string $page
	 * @param bool $passThrough Return $page if $page is a string
	 *
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
	 * Returns the ID of the given page, if information about this page has been cached.
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

		[ $row ] = $this->goodLinks->get( $key );

		return $row ? (int)$row->page_id : 0;
	}

	/**
	 * Get a field of a page from the cache.
	 *
	 * If this link is not a cached good title, it will return NULL.
	 * @param LinkTarget|PageReference|array $page The page to get cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param string $field ( 'id', 'length', 'redirect', 'revision', 'model', 'lang', 'restrictions' )
	 * @return string|int|null The field value, or null if the page was not cached or does not exist
	 *         or is not a proper page (e.g. a special page or interwiki link).
	 */
	public function getGoodLinkFieldObj( $page, string $field ) {
		$key = $this->getCacheKey( $page );
		if ( $key === null ) {
			return null;
		}

		if ( $this->isBadLink( $key ) ) {
			return null;
		}

		[ $row ] = $this->goodLinks->get( $key );

		if ( !$row ) {
			return null;
		}

		switch ( $field ) {
			case 'id':
				return intval( $row->page_id );
			case 'length':
				return intval( $row->page_len );
			case 'redirect':
				return intval( $row->page_is_redirect );
			case 'revision':
				return intval( $row->page_latest );
			case 'model':
				return !empty( $row->page_content_model )
					? strval( $row->page_content_model )
					: null;
			case 'lang':
				return !empty( $row->page_lang )
					? strval( $row->page_lang )
					: null;
			case 'restrictions':
				return !empty( $row->page_restrictions )
					? strval( $row->page_restrictions )
					: null;
			default:
				throw new InvalidArgumentException( "Unknown field: $field" );
		}
	}

	/**
	 * Returns true if the fact that this page does not exist had been added to the cache.
	 *
	 * @param LinkTarget|PageReference|array|string $page The page to get cached info for,
	 *        as an object, an array containing the page_namespace and page_title fields,
	 *        or a prefixed DB key. In MediaWiki 1.36 and earlier, only a string was accepted.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 * @return bool True if the page is known to not exist.
	 */
	public function isBadLink( $page ) {
		$key = $this->getCacheKey( $page, true );
		if ( $key === null ) {
			return false;
		}

		return $this->badLinks->has( $key );
	}

	/**
	 * Add information about an existing page to the cache.
	 *
	 * @deprecated since 1.37, use addGoodLinkObjFromRow() instead. PHPUnit tests
	 *             must use LinkCacheTestTrait::addGoodLinkObject().
	 *
	 * @param int $id Page's ID
	 * @param LinkTarget|PageReference $page The page to set cached info for.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param int $len Text's length
	 * @param int|null $redir Whether the page is a redirect
	 * @param int $revision Latest revision's ID
	 * @param string|null $model Latest revision's content model ID
	 * @param string|null $lang Language code of the page, if not the content language
	 */
	public function addGoodLinkObj( $id, $page, $len = -1, $redir = null,
		$revision = 0, $model = null, $lang = null
	) {
		wfDeprecated( __METHOD__, '1.38' );
		$this->addGoodLinkObjFromRow( $page, (object)[
			'page_id' => (int)$id,
			'page_namespace' => $page->getNamespace(),
			'page_title' => $page->getDBkey(),
			'page_len' => (int)$len,
			'page_is_redirect' => (int)$redir,
			'page_latest' => (int)$revision,
			'page_content_model' => $model ? (string)$model : null,
			'page_lang' => $lang ? (string)$lang : null,
			'page_restrictions' => null,
			'page_is_new' => 0,
			'page_touched' => '',
		] );
	}

	/**
	 * Same as above with better interface.
	 *
	 * @param LinkTarget|PageReference|array $page The page to set cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param stdClass $row Object which has all fields returned by getSelectFields().
	 * @param int $queryFlags The query flags used to retrieve the row, IDBAccessObject::READ_XXX
	 *
	 * @since 1.19
	 *
	 */
	public function addGoodLinkObjFromRow(
		$page,
		stdClass $row,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	) {
		foreach ( self::getSelectFields() as $field ) {
			if ( !property_exists( $row, $field ) ) {
				throw new InvalidArgumentException( "Missing field: $field" );
			}
		}

		$key = $this->getCacheKey( $page );
		if ( $key === null ) {
			return;
		}

		$this->goodLinks->set( $key, [ $row, $queryFlags ] );
		$this->badLinks->clear( $key );
	}

	/**
	 * @param LinkTarget|PageReference|array $page The page to set cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 */
	public function addBadLinkObj( $page ) {
		$key = $this->getCacheKey( $page );
		if ( $key !== null && !$this->isBadLink( $key ) ) {
			$this->badLinks->set( $key, 1 );
			$this->goodLinks->clear( $key );
		}
	}

	/**
	 * @param LinkTarget|PageReference|array|string $page The page to clear cached info for,
	 *        as an object, an array containing the page_namespace and page_title fields,
	 *        or a prefixed DB key. In MediaWiki 1.36 and earlier, only a string was accepted.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 */
	public function clearBadLink( $page ) {
		$key = $this->getCacheKey( $page, true );

		if ( $key !== null ) {
			$this->badLinks->clear( $key );
		}
	}

	/**
	 * @param LinkTarget|PageReference|array $page The page to clear cached info for.
	 *        Can be given as an object or an associative array containing the
	 *        page_namespace and page_title fields.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 */
	public function clearLink( $page ) {
		$key = $this->getCacheKey( $page );

		if ( $key !== null ) {
			$this->badLinks->clear( $key );
			$this->goodLinks->clear( $key );
		}
	}

	/**
	 * Fields that LinkCache needs to select
	 *
	 * @since 1.28
	 * @return array
	 */
	public static function getSelectFields() {
		$pageLanguageUseDB = MediaWikiServices::getInstance()->getMainConfig()->get( 'PageLanguageUseDB' );

		$fields = array_merge(
			PageStoreRecord::REQUIRED_FIELDS,
			[
				'page_len',
				'page_restrictions',
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
	 * @param TitleValue|null $link
	 * @param callable|null $fetchCallback
	 * @param int $queryFlags
	 * @return array [ $shouldAddGoodLink, $row ], $shouldAddGoodLink is a bool indicating
	 * whether addGoodLinkObjFromRow should be called, and $row is the row the caller was looking
	 * for (or false, when it was not found).
	 */
	private function getGoodLinkRowInternal(
		?TitleValue $link,
		callable $fetchCallback = null,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$key = $link ? $this->getCacheKey( $link ) : null;
		if ( $key === null ) {
			return [ false, false ];
		}

		$ns = $link->getNamespace();
		$dbkey = $link->getDBkey();
		$callerShouldAddGoodLink = false;

		if ( $this->mForUpdate ) {
			$queryFlags |= IDBAccessObject::READ_LATEST;
		}
		$forUpdate = $queryFlags & IDBAccessObject::READ_LATEST;

		if ( !$forUpdate && $this->isBadLink( $key ) ) {
			return [ $callerShouldAddGoodLink, false ];
		}

		[ $row, $rowFlags ] = $this->goodLinks->get( $key );
		if ( $row && $rowFlags >= $queryFlags ) {
			return [ $callerShouldAddGoodLink, $row ];
		}

		if ( !$fetchCallback ) {
			return [ $callerShouldAddGoodLink, false ];
		}

		$callerShouldAddGoodLink = true;
		if ( $this->usePersistentCache( $ns ) && !$forUpdate ) {
			// Some pages are often transcluded heavily, so use persistent caching
			$wanCacheKey = $this->wanCache->makeKey( 'page', $ns, sha1( $dbkey ) );

			$row = $this->wanCache->getWithSetCallback(
				$wanCacheKey,
				WANObjectCache::TTL_DAY,
				function ( $curValue, &$ttl, array &$setOpts ) use ( $fetchCallback, $ns, $dbkey ) {
					$dbr = $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );
					$setOpts += Database::getCacheSetOptions( $dbr );

					$row = $fetchCallback( $dbr, $ns, $dbkey, [] );
					$mtime = $row ? (int)wfTimestamp( TS_UNIX, $row->page_touched ) : false;
					$ttl = $this->wanCache->adaptiveTTL( $mtime, $ttl );

					return $row;
				}
			);
		} else {
			// No persistent caching needed, but we can still use the callback.
			[ $mode, $options ] = DBAccessObjectUtils::getDBOptions( $queryFlags );
			$dbr = $this->loadBalancer->getConnectionRef( $mode );
			$row = $fetchCallback( $dbr, $ns, $dbkey, $options );
		}

		return [ $callerShouldAddGoodLink, $row ];
	}

	/**
	 * Returns the row for the page if the page exists (subject to race conditions).
	 * The row will be returned from local cache or WAN cache if possible, or it
	 * will be looked up using the callback provided.
	 *
	 * @param int $ns
	 * @param string $dbkey
	 * @param callable|null $fetchCallback A callback that will retrieve the link row with the
	 *        signature ( IDatabase $db, int $ns, string $dbkey, array $queryOptions ): ?stdObj.
	 * @param int $queryFlags IDBAccessObject::READ_XXX
	 *
	 * @return stdClass|null
	 * @internal for use by PageStore. Other code should use a PageLookup instead.
	 */
	public function getGoodLinkRow(
		int $ns,
		string $dbkey,
		callable $fetchCallback = null,
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
					[ $shouldAddGoodLink, $row ] = $this->getGoodLinkRowInternal(
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
	 * @param WANObjectCache $cache
	 * @param LinkTarget|Pagereference $page
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @return string[]
	 * @since 1.28
	 */
	public function getMutableCacheKeys( WANObjectCache $cache, $page ) {
		$key = $this->getCacheKey( $page );
		// if no key can be derived, the page isn't cacheable
		if ( $key === null ) {
			return [];
		}

		if ( $this->usePersistentCache( $page ) ) {
			return [ $cache->makeKey( 'page', $page->getNamespace(), sha1( $page->getDBkey() ) ) ];
		}

		return [];
	}

	/**
	 * @param LinkTarget|PageReference|int $pageOrNamespace
	 *
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
	 * @param IDatabase $db
	 * @param int $ns
	 * @param string $dbkey
	 * @param array $options Query options, see IDatabase::select() for details.
	 *
	 * @return stdClass|false
	 */
	private function fetchPageRow( IDatabase $db, int $ns, string $dbkey, $options = [] ) {
		$fields = self::getSelectFields();
		if ( $this->usePersistentCache( $ns ) ) {
			$fields[] = 'page_touched';
		}

		return $db->selectRow(
			'page',
			$fields,
			[ 'page_namespace' => $ns, 'page_title' => $dbkey ],
			__METHOD__,
			$options
		);
	}

	/**
	 * Purge the persistent link cache for a title
	 *
	 * @param LinkTarget|PageReference $page
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @since 1.28
	 */
	public function invalidateTitle( $page ) {
		if ( $this->usePersistentCache( $page ) ) {
			$cache = $this->wanCache;
			$cache->delete(
				$cache->makeKey( 'page', $page->getNamespace(), sha1( $page->getDBkey() ) )
			);
		}

		$this->clearLink( $page );
	}

	/**
	 * Clears cache
	 */
	public function clear() {
		$this->goodLinks->clear();
		$this->badLinks->clear();
	}

}
