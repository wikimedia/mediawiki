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
	 * Get an instance of this class.
	 *
	 * @return LinkCache
	 * @deprecated since 1.28, hard deprecated since 1.37
	 * Use MediaWikiServices::getLinkCache instead
	 */
	public static function singleton() {
		wfDeprecated( __METHOD__, '1.28' );
		return MediaWikiServices::getInstance()->getLinkCache();
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
	 * @deprecated Since 1.34
	 */
	public function forUpdate( $update = null ) {
		return wfSetVar( $this->mForUpdate, $update );
	}

	/**
	 * @param LinkTarget|PageReference|string $page
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
	 * @param LinkTarget|PageReference|string $page The page to get the ID for,
	 *        as an object or a prefixed DB key.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 * @return int Page ID, or zero if the page was not cached or does not exist or is not a
	 *         proper page (e.g. a special page or an interwiki link).
	 */
	public function getGoodLinkID( $page ) {
		$key = $this->getCacheKey( $page, true );

		if ( $key === null ) {
			return 0;
		}

		$info = $this->goodLinks->get( $key );

		if ( !$info ) {
			return 0;
		}
		return $info['id'];
	}

	/**
	 * Get a field of a page from the cache.
	 *
	 * If this link is not a cached good title, it will return NULL.
	 * @param LinkTarget|PageReference $page The page to get cached info for.
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

		$info = $this->goodLinks->get( $key );
		if ( !$info ) {
			return null;
		}
		return $info[$field];
	}

	/**
	 * Returns true if the fact that this page does not exist had been added to the cache.
	 *
	 * @param LinkTarget|PageReference|string $page The page to get cached info for,
	 *        as an object or a prefixed DB key.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 * @return bool True if the page is known to not exist.
	 */
	public function isBadLink( $page ) {
		$key = $this->getCacheKey( $page, true );

		return $key !== null && $this->badLinks->has( $key );
	}

	/**
	 * Add information about an existing page to the cache.
	 *
	 * @see addGoodLinkObjFromRow()
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
		$key = $this->getCacheKey( $page );

		if ( $key === null ) {
			return;
		}

		$this->goodLinks->set( $key, [
			'id' => (int)$id,
			'length' => (int)$len,
			'redirect' => (int)$redir,
			'revision' => (int)$revision,
			'model' => $model ? (string)$model : null,
			'lang' => $lang ? (string)$lang : null,
			'restrictions' => null
		] );
		$this->badLinks->clear( $key );
	}

	/**
	 * Same as above with better interface.
	 * @since 1.19
	 *
	 * @param LinkTarget|PageReference $page The page to set cached info for.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @param stdClass $row Object which has all fields returned by getSelectFields().
	 *
	 */
	public function addGoodLinkObjFromRow( $page, stdClass $row ) {
		$key = $this->getCacheKey( $page );

		if ( $key === null ) {
			return;
		}

		$this->goodLinks->set( $key, [
			'id' => intval( $row->page_id ),
			'length' => intval( $row->page_len ),
			'redirect' => intval( $row->page_is_redirect ),
			'revision' => intval( $row->page_latest ),
			'model' => !empty( $row->page_content_model )
				? strval( $row->page_content_model )
				: null,
			'lang' => !empty( $row->page_lang )
				? strval( $row->page_lang )
				: null,
			'restrictions' => !empty( $row->page_restrictions )
				? strval( $row->page_restrictions )
				: null
		] );
		$this->badLinks->clear( $key );
	}

	/**
	 * @param LinkTarget|PageReference $page The page to set cached info for.
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
	 * @param LinkTarget|PageReference|string $page The page to clear cached info for,
	 *        as an object or a prefixed DB key.
	 *        In MediaWiki 1.36 and earlier, only a string was accepted.
	 */
	public function clearBadLink( $page ) {
		$key = $this->getCacheKey( $page, true );

		if ( $key !== null ) {
			$this->badLinks->clear( $key );
		}
	}

	/**
	 * @param LinkTarget|PageReference $page The page to clear cached info for.
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
		global $wgPageLanguageUseDB;

		$fields = [
			'page_id',
			'page_len',
			'page_is_redirect',
			'page_latest',
			'page_restrictions',
			'page_content_model',
		];

		if ( $wgPageLanguageUseDB ) {
			$fields[] = 'page_lang';
		}

		return $fields;
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent.
	 * This causes the link to be looked up in the database if it is not yet cached.
	 *
	 * @param LinkTarget|PageReference $page The page to load.
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 *
	 * @return int Page ID or zero
	 */
	public function addLinkObj( $page ) {
		if ( $page instanceof LinkTarget ) {
			$nt = $page;
		} else {
			$nt = TitleValue::castPageToLinkTarget( $page );
		}

		$key = $this->getCacheKey( $nt );
		if ( $key === null ) {
			return 0;
		}

		if ( !$this->mForUpdate ) {
			$id = $this->getGoodLinkID( $key );
			if ( $id != 0 ) {
				return $id;
			}

			if ( $this->isBadLink( $key ) ) {
				return 0;
			}
		}

		// Only query database, when load balancer is provided by service wiring
		// This maybe not happen when running as part of the installer
		if ( $this->loadBalancer === null ) {
			return 0;
		}

		// Cache template/file pages as they are less often viewed but heavily used
		if ( $this->mForUpdate ) {
			$row = $this->fetchPageRow( $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_PRIMARY ), $nt );
		} elseif ( $this->isCacheable( $nt ) ) {
			// These pages are often transcluded heavily, so cache them
			$cache = $this->wanCache;
			$row = $cache->getWithSetCallback(
				$cache->makeKey( 'page', $nt->getNamespace(), sha1( $nt->getDBkey() ) ),
				$cache::TTL_DAY,
				function ( $curValue, &$ttl, array &$setOpts ) use ( $cache, $nt ) {
					$dbr = $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );
					$setOpts += Database::getCacheSetOptions( $dbr );

					$row = $this->fetchPageRow( $dbr, $nt );
					$mtime = $row ? wfTimestamp( TS_UNIX, $row->page_touched ) : false;
					$ttl = $cache->adaptiveTTL( $mtime, $ttl );

					return $row;
				}
			);
		} else {
			$row = $this->fetchPageRow( $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA ), $nt );
		}

		if ( $row ) {
			$this->addGoodLinkObjFromRow( $nt, $row );
			$id = intval( $row->page_id );
		} else {
			$this->addBadLinkObj( $nt );
			$id = 0;
		}

		return $id;
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

		if ( $this->isCacheable( $page ) ) {
			return [ $cache->makeKey( 'page', $page->getNamespace(), sha1( $page->getDBkey() ) ) ];
		}

		return [];
	}

	/**
	 * @param LinkTarget|PageReference $page
	 *
	 * @return bool
	 */
	private function isCacheable( $page ) {
		$ns = $page->getNamespace();
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
	 * @param LinkTarget|PageReference $page
	 *
	 * @return stdClass|false
	 */
	private function fetchPageRow( IDatabase $db, $page ) {
		$fields = self::getSelectFields();
		if ( $this->isCacheable( $page ) ) {
			$fields[] = 'page_touched';
		}

		return $db->selectRow(
			'page',
			$fields,
			[ 'page_namespace' => $page->getNamespace(), 'page_title' => $page->getDBkey() ],
			__METHOD__
		);
	}

	/**
	 * Purge the link cache for a title
	 *
	 * @param LinkTarget|PageReference $page
	 *        In MediaWiki 1.36 and earlier, only LinkTarget was accepted.
	 * @since 1.28
	 */
	public function invalidateTitle( $page ) {
		if ( $this->isCacheable( $page ) ) {
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
