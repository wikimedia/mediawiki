<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Cache;

use MediaWiki\Deferred\CdnCacheUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\HtmlFileCacheUpdate;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\TitleFactory;
use Traversable;

/**
 * Class to invalidate the CDN and HTMLFileCache entries associated with URLs/titles
 *
 * @ingroup Cache
 * @since 1.35
 */
class HTMLCacheUpdater {
	/** @var int Seconds between initial and rebound purges; 0 if disabled */
	private $reboundDelay;
	/** @var bool Whether filesystem-based HTML output caching is enabled */
	private $useFileCache;
	/** @var int Max seconds for CDN to served cached objects without revalidation */
	private $cdnMaxAge;

	/** @var HookRunner */
	private $hookRunner;

	/** @var int Issue purge immediately and do not schedule a rebound purge */
	public const PURGE_NAIVE = 0;
	/**
	 * @var int Defer purge via PRESEND deferred updates. The pending DeferrableUpdate instances
	 *  will combined/de-duplicated into a single DeferrableUpdate instance; this lowers overhead
	 *  and improves HTTP PURGE request pipelining.
	 */
	public const PURGE_PRESEND = 1;
	/**
	 * @var int Upon purge, schedule a delayed CDN purge if rebound purges are enabled
	 *  ($wgCdnReboundPurgeDelay). Rebound purges are schedule via the job queue.
	 */
	public const PURGE_REBOUND = 2;

	/**
	 * @var int Defer purge until no LBFactory transaction round is pending and then schedule
	 *  a delayed rebound purge if rebound purges are enabled ($wgCdnReboundPurgeDelay). This is
	 *  useful for CDN purges triggered by data changes in the current or last transaction round.
	 *  Even if the origin server uses lagged replicas, the use of rebound purges corrects the
	 *  cache in cases where lag is less than the rebound delay. If the lag is anywhere near the
	 *  rebound delay, then auxiliary mechanisms should lower the cache TTL ($wgCdnMaxageLagged).
	 */
	public const PURGE_INTENT_TXROUND_REFLECTED = self::PURGE_PRESEND | self::PURGE_REBOUND;

	/**
	 * Reduce set of URLs to be purged to only those that may be affected by
	 * change propagation from LinksUpdate (e.g. after a used template was edited).
	 *
	 * Specifically, this means URLs only affected by direct revision edits,
	 * will not be purged.
	 * @var int
	 */
	public const PURGE_URLS_LINKSUPDATE_ONLY = 4;

	/**
	 * Do not bother purging cache items if the default max cache TTL implies that no objects
	 * can still be in cache from before the given timestamp.
	 *
	 * @var string
	 */
	public const UNLESS_CACHE_MTIME_AFTER = 'unless-timestamp-exceeds';

	/** @var TitleFactory */
	private $titleFactory;

	/**
	 * @param HookContainer $hookContainer
	 * @param TitleFactory $titleFactory
	 * @param int $reboundDelay $wgCdnReboundPurgeDelay
	 * @param bool $useFileCache $wgUseFileCache
	 * @param int $cdnMaxAge $wgCdnMaxAge
	 *
	 * @internal For use with MediaWikiServices->getHtmlCacheUpdater()
	 */
	public function __construct(
		HookContainer $hookContainer,
		TitleFactory $titleFactory,
		$reboundDelay,
		$useFileCache,
		$cdnMaxAge
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->titleFactory = $titleFactory;
		$this->reboundDelay = $reboundDelay;
		$this->useFileCache = $useFileCache;
		$this->cdnMaxAge = $cdnMaxAge;
	}

	/**
	 * @param int $flags Bit field
	 * @param int $flag Constant to check for
	 * @return bool If $flags contains $flag
	 */
	private function fieldHasFlag( $flags, $flag ) {
		return ( ( $flags & $flag ) === $flag );
	}

	/**
	 * Purge the CDN for a URL or list of URLs
	 *
	 * @param string[]|string $urls URL or list of URLs
	 * @param int $flags Bit field of class PURGE_* constants
	 *  [Default: HTMLCacheUpdater::PURGE_PRESEND]
	 * @param mixed[] $unless Optional map of (HTMLCacheUpdater::UNLESS_* constant => value)
	 */
	public function purgeUrls( $urls, $flags = self::PURGE_PRESEND, array $unless = [] ) {
		$minFreshCacheMtime = $unless[self::UNLESS_CACHE_MTIME_AFTER] ?? null;
		if ( $minFreshCacheMtime && time() > ( $minFreshCacheMtime + $this->cdnMaxAge ) ) {
			return;
		}

		$urls = is_string( $urls ) ? [ $urls ] : $urls;

		$reboundDelay = $this->fieldHasFlag( $flags, self::PURGE_REBOUND )
			? $this->reboundDelay
			: 0; // no second purge

		$update = new CdnCacheUpdate( $urls, [ 'reboundDelay' => $reboundDelay ] );
		if ( $this->fieldHasFlag( $flags, self::PURGE_PRESEND ) ) {
			DeferredUpdates::addUpdate( $update, DeferredUpdates::PRESEND );
		} else {
			$update->doUpdate();
		}
	}

	/**
	 * Purge the CDN/HTMLFileCache for a title or the titles yielded by an iterator
	 *
	 * All cacheable canonical URLs associated with the titles will be purged from CDN.
	 * All cacheable actions associated with the titles will be purged from HTMLFileCache.
	 *
	 * @param Traversable|PageReference[]|PageReference $pages PageReference or iterator yielding
	 *        PageReference instances
	 * @param int $flags Bit field of class PURGE_* constants
	 *  [Default: HTMLCacheUpdater::PURGE_PRESEND]
	 * @param mixed[] $unless Optional map of (HTMLCacheUpdater::UNLESS_* constant => value)
	 */
	public function purgeTitleUrls( $pages, $flags = self::PURGE_PRESEND, array $unless = [] ) {
		$pages = is_iterable( $pages ) ? $pages : [ $pages ];
		$pageIdentities = [];

		foreach ( $pages as $page ) {
			// TODO: We really only need to cast to PageIdentity. We could use a LinkBatch for that.
			$title = $this->titleFactory->newFromPageReference( $page );

			if ( $title->canExist() ) {
				$pageIdentities[] = $title;
			}
		}

		if ( !$pageIdentities ) {
			return;
		}

		if ( $this->useFileCache ) {
			$update = HtmlFileCacheUpdate::newFromPages( $pageIdentities );
			if ( $this->fieldHasFlag( $flags, self::PURGE_PRESEND ) ) {
				DeferredUpdates::addUpdate( $update, DeferredUpdates::PRESEND );
			} else {
				$update->doUpdate();
			}
		}

		$minFreshCacheMtime = $unless[self::UNLESS_CACHE_MTIME_AFTER] ?? null;
		if ( !$minFreshCacheMtime || time() <= ( $minFreshCacheMtime + $this->cdnMaxAge ) ) {
			$urls = [];
			foreach ( $pageIdentities as $pi ) {
				/** @var PageIdentity $pi */
				$urls = array_merge( $urls, $this->getUrls( $pi, $flags ) );
			}
			$this->purgeUrls( $urls, $flags );
		}
	}

	/**
	 * Get a list of URLs to purge from the CDN cache when this page changes.
	 *
	 * @param PageReference $page
	 * @param int $flags Bit field of `PURGE_URLS_*` class constants (optional).
	 * @return string[] URLs
	 */
	public function getUrls( PageReference $page, int $flags = 0 ): array {
		$title = $this->titleFactory->newFromPageReference( $page );

		if ( !$title->canExist() ) {
			return [];
		}

		// These urls are affected both by direct revisions as well,
		// as re-rendering of the same content during a LinksUpdate.
		$urls = [
			$title->getInternalURL()
		];
		// Language variant page views are currently not cached
		// and thus not purged (T250511).

		// These urls are only affected by direct revisions, and do not require
		// purging when a LinksUpdate merely rerenders the same content.
		// This exists to avoid large amounts of redundant PURGE traffic (T250261).
		if ( !$this->fieldHasFlag( $flags, self::PURGE_URLS_LINKSUPDATE_ONLY ) ) {
			$urls[] = $title->getInternalURL( 'action=history' );

			// Canonical action=raw URLs for user and site config pages (T58874, T261371).
			if ( $title->isUserJsConfigPage() || $title->isSiteJsConfigPage() ) {
				$urls[] = $title->getInternalURL( 'action=raw&ctype=text/javascript' );
			} elseif ( $title->isUserJsonConfigPage() || $title->isSiteJsonConfigPage() ) {
				$urls[] = $title->getInternalURL( 'action=raw&ctype=application/json' );
			} elseif ( $title->isUserCssConfigPage() || $title->isSiteCssConfigPage() ) {
				$urls[] = $title->getInternalURL( 'action=raw&ctype=text/css' );
			}
		}

		// Extensions may add novel ways to access this content
		$append = [];
		$mode = $flags & self::PURGE_URLS_LINKSUPDATE_ONLY;
		$this->hookRunner->onHtmlCacheUpdaterAppendUrls( $title, $mode, $append );
		$urls = array_merge( $urls, $append );

		// Extensions may add novel ways to access the site overall
		$append = [];
		$this->hookRunner->onHtmlCacheUpdaterVaryUrls( $urls, $append );
		$urls = array_merge( $urls, $append );

		// Legacy. TODO: Deprecate this
		$this->hookRunner->onTitleSquidURLs( $title, $urls );

		return $urls;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLCacheUpdater::class, 'HtmlCacheUpdater' );
