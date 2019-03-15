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

/**
 * Class to invalidate the CDN and HTMLFileCache entries associated with URLs/titles
 *
 * @ingroup Cache
 * @since 1.35
 */
class HtmlCacheUpdater {
	/** @var int Seconds between initial and rebound purges; 0 if disabled */
	private $reboundDelay;
	/** @var int Whether filesystem-based HTML output caching is enabled */
	private $useFileCache;

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
	 * @param int $reboundDelay $wgCdnReboundPurgeDelay
	 * @param bool $useFileCache $wgUseFileCache
	 * @internal For use with MediaWikiServices->getHtmlCacheUpdater()
	 */
	public function __construct( $reboundDelay, $useFileCache ) {
		$this->reboundDelay = $reboundDelay;
		$this->useFileCache = $useFileCache;
	}

	/**
	 * Purge the CDN for a URL or list of URLs
	 *
	 * @param string[]|string $urls URL or list of URLs
	 * @param int $flags Bit field of class PURGE_* constants
	 *  [Default: HtmlCacheUpdater::PURGE_PRESEND]
	 */
	public function purgeUrls( $urls, $flags = self::PURGE_PRESEND ) {
		$urls = is_string( $urls ) ? [ $urls ] : $urls;

		$reboundDelay = ( ( $flags & self::PURGE_REBOUND ) == self::PURGE_REBOUND )
			? $this->reboundDelay
			: 0; // no second purge

		$update = new CdnCacheUpdate( $urls, [ 'reboundDelay' => $reboundDelay ] );
		if ( ( $flags & self::PURGE_PRESEND ) == self::PURGE_PRESEND ) {
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
	 * @param Traversable|Title[]|Title $titles Title or iterator yielding Title instances
	 * @param int $flags Bit field of class PURGE_* constants
	 *  [Default: HtmlCacheUpdater::PURGE_PRESEND]
	 */
	public function purgeTitleUrls( $titles, $flags = self::PURGE_PRESEND ) {
		$titles = $titles instanceof Title ? [ $titles ] : $titles;

		if ( $this->useFileCache ) {
			$update = HtmlFileCacheUpdate::newFromTitles( $titles );
			if ( ( $flags & self::PURGE_PRESEND ) == self::PURGE_PRESEND ) {
				DeferredUpdates::addUpdate( $update, DeferredUpdates::PRESEND );
			} else {
				$update->doUpdate();
			}
		}

		$urls = [];
		foreach ( $titles as $title ) {
			/** @var Title $title */
			$urls = array_merge( $urls, $title->getCdnUrls() );
		}
		$this->purgeUrls( $urls, $flags );
	}
}
