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
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 *
 * @ingroup Cache
 */
class LinkCache {
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

	/**
	 * How many Titles to store. There are two caches, so the amount actually
	 * stored in memory can be up to twice this.
	 */
	private const MAX_SIZE = 10000;

	public function __construct(
		TitleFormatter $titleFormatter,
		WANObjectCache $cache,
		NamespaceInfo $nsInfo = null
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
	}

	/**
	 * Get an instance of this class.
	 *
	 * @return LinkCache
	 * @deprecated since 1.28, use MediaWikiServices instead
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getLinkCache();
	}

	/**
	 * General accessor to get/set whether the master DB should be used
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
	 * @param string $title Prefixed DB key
	 * @return int Page ID or zero
	 */
	public function getGoodLinkID( $title ) {
		$info = $this->goodLinks->get( $title );
		if ( !$info ) {
			return 0;
		}
		return $info['id'];
	}

	/**
	 * Get a field of a title object from cache.
	 * If this link is not a cached good title, it will return NULL.
	 * @param LinkTarget $target
	 * @param string $field ('length','redirect','revision','model')
	 * @return string|int|null
	 */
	public function getGoodLinkFieldObj( LinkTarget $target, $field ) {
		$dbkey = $this->titleFormatter->getPrefixedDBkey( $target );
		$info = $this->goodLinks->get( $dbkey );
		if ( !$info ) {
			return null;
		}
		return $info[$field];
	}

	/**
	 * @param string $title Prefixed DB key
	 * @return bool
	 */
	public function isBadLink( $title ) {
		// Use get() to ensure it records as used for LRU.
		return $this->badLinks->has( $title );
	}

	/**
	 * Add a link for the title to the link cache
	 *
	 * @param int $id Page's ID
	 * @param LinkTarget $target
	 * @param int $len Text's length
	 * @param int|null $redir Whether the page is a redirect
	 * @param int $revision Latest revision's ID
	 * @param string|null $model Latest revision's content model ID
	 * @param string|null $lang Language code of the page, if not the content language
	 */
	public function addGoodLinkObj( $id, LinkTarget $target, $len = -1, $redir = null,
		$revision = 0, $model = null, $lang = null
	) {
		$dbkey = $this->titleFormatter->getPrefixedDBkey( $target );
		$this->goodLinks->set( $dbkey, [
			'id' => (int)$id,
			'length' => (int)$len,
			'redirect' => (int)$redir,
			'revision' => (int)$revision,
			'model' => $model ? (string)$model : null,
			'lang' => $lang ? (string)$lang : null,
			'restrictions' => null
		] );
	}

	/**
	 * Same as above with better interface.
	 * @since 1.19
	 * @param LinkTarget $target
	 * @param stdClass $row Object which has the fields page_id, page_is_redirect,
	 *  page_latest and page_content_model
	 */
	public function addGoodLinkObjFromRow( LinkTarget $target, $row ) {
		$dbkey = $this->titleFormatter->getPrefixedDBkey( $target );
		$this->goodLinks->set( $dbkey, [
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
	}

	/**
	 * @param LinkTarget $target
	 */
	public function addBadLinkObj( LinkTarget $target ) {
		$dbkey = $this->titleFormatter->getPrefixedDBkey( $target );
		if ( !$this->isBadLink( $dbkey ) ) {
			$this->badLinks->set( $dbkey, 1 );
		}
	}

	/**
	 * @param string $title Prefixed DB key
	 */
	public function clearBadLink( $title ) {
		$this->badLinks->clear( $title );
	}

	/**
	 * @param LinkTarget $target
	 */
	public function clearLink( LinkTarget $target ) {
		$dbkey = $this->titleFormatter->getPrefixedDBkey( $target );
		$this->badLinks->clear( $dbkey );
		$this->goodLinks->clear( $dbkey );
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
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param LinkTarget $nt LinkTarget object to add
	 * @return int Page ID or zero
	 */
	public function addLinkObj( LinkTarget $nt ) {
		$key = $this->titleFormatter->getPrefixedDBkey( $nt );
		if ( $this->isBadLink( $key ) || $nt->isExternal() || $nt->getNamespace() < 0 ) {
			return 0;
		}
		$id = $this->getGoodLinkID( $key );
		if ( $id != 0 ) {
			return $id;
		}

		if ( $key === '' ) {
			return 0;
		}

		// Cache template/file pages as they are less often viewed but heavily used
		if ( $this->mForUpdate ) {
			$row = $this->fetchPageRow( wfGetDB( DB_MASTER ), $nt );
		} elseif ( $this->isCacheable( $nt ) ) {
			// These pages are often transcluded heavily, so cache them
			$cache = $this->wanCache;
			$row = $cache->getWithSetCallback(
				$cache->makeKey( 'page', $nt->getNamespace(), sha1( $nt->getDBkey() ) ),
				$cache::TTL_DAY,
				function ( $curValue, &$ttl, array &$setOpts ) use ( $cache, $nt ) {
					$dbr = wfGetDB( DB_REPLICA );
					$setOpts += Database::getCacheSetOptions( $dbr );

					$row = $this->fetchPageRow( $dbr, $nt );
					$mtime = $row ? wfTimestamp( TS_UNIX, $row->page_touched ) : false;
					$ttl = $cache->adaptiveTTL( $mtime, $ttl );

					return $row;
				}
			);
		} else {
			$row = $this->fetchPageRow( wfGetDB( DB_REPLICA ), $nt );
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
	 * @param LinkTarget $t
	 * @return string[]
	 * @since 1.28
	 */
	public function getMutableCacheKeys( WANObjectCache $cache, LinkTarget $t ) {
		if ( $this->isCacheable( $t ) ) {
			return [ $cache->makeKey( 'page', $t->getNamespace(), sha1( $t->getDBkey() ) ) ];
		}

		return [];
	}

	private function isCacheable( LinkTarget $title ) {
		$ns = $title->getNamespace();
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

	private function fetchPageRow( IDatabase $db, LinkTarget $nt ) {
		$fields = self::getSelectFields();
		if ( $this->isCacheable( $nt ) ) {
			$fields[] = 'page_touched';
		}

		return $db->selectRow(
			'page',
			$fields,
			[ 'page_namespace' => $nt->getNamespace(), 'page_title' => $nt->getDBkey() ],
			__METHOD__
		);
	}

	/**
	 * Purge the link cache for a title
	 *
	 * @param LinkTarget $title
	 * @since 1.28
	 */
	public function invalidateTitle( LinkTarget $title ) {
		if ( $this->isCacheable( $title ) ) {
			$cache = $this->wanCache;
			$cache->delete(
				$cache->makeKey( 'page', $title->getNamespace(), sha1( $title->getDBkey() ) )
			);
		}
	}

	/**
	 * Clears cache
	 */
	public function clear() {
		$this->goodLinks->clear();
		$this->badLinks->clear();
	}
}
