<?php
/**
 * HTML/file cache invalidation of cacheable variant/action URLs for a page
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

/**
 * Class to invalidate the HTML/file cache of cacheable variant/action URLs for a page
 *
 * @ingroup Cache
 * @since 1.34
 */
class HtmlCacheUpdater {
	/** @var int Purge after the main transaction round and respect $wgCdnReboundPurgeDelay */
	const ISOLATION_AND_LAG_AWARE = 1;
	/** @var int Purge immediately and only once (ignore $wgCdnReboundPurgeDelay) */
	const IMMEDIATE_WITHOUT_REBOUND = 2;

	/**
	 * Purge CDN/HTMLFileCache for a URL, Title, or iteratable of URL or Title entries
	 *
	 * String entries will be treated as URLs to be purged from the CDN layer.
	 * For Title entries, all cacheable canonical URLs associated with the page
	 * will be purged from the CDN and HTMLFileCache.
	 *
	 * The cache purges are queued as PRESEND deferred updates so that they run after the
	 * main database transaction round of LBFactory. This reduces the chance of race conditions
	 * where a stale value is re-populated before commit. Depending on $wgCdnReboundPurgeDelay,
	 * a secondary set of purges might be issued several seconds later through the use of a
	 * delayed job. This is used to mitigate the effects of DB replication lag as well as
	 * multiple layers of CDN proxies. All deferred CDN purges are combined and de-duplicated
	 * into a single DeferrableUpdate instance. This improves HTTP PURGE request pipelining.
	 *
	 * Use the IMMEDIATE_WITHOUT_REBOUND class constant to instantly issue the purges instead
	 * and skip the use of any secondary purges regardless of $wgCdnReboundPurgeDelay.
	 *
	 * @param Traversable|Title[]|Title|string[]|string $entries
	 * @param int $mode ISOLATION_AND_LAG_AWARE or IMMEDIATE_WITHOUT_REBOUND class constant
	 */
	public function purge( $entries, $mode = self::ISOLATION_AND_LAG_AWARE ) {
		$urls = [];
		$titles = [];
		if ( is_string( $entries ) ) {
			$urls = [ $entries ];
		} elseif ( $entries instanceof Title ) {
			$titles = [ $entries ];
		} elseif ( $entries instanceof TitleArray ) {
			$titles = $entries; // save memory
		} else {
			foreach ( $entries as $entry ) {
				if ( is_string( $entry ) ) {
					$urls[] = $entry;
				} else {
					$titles[] = $entry;
				}
			}
		}

		if ( $mode === self::IMMEDIATE_WITHOUT_REBOUND ) {
			HTMLFileCache::clearFileCache( $titles );
			foreach ( $titles as $title ) {
				/** @var Title $title */
				$urls = array_merge( $urls, $title->getCdnUrls() );
			}
			CdnCacheUpdate::purge( $urls ); // purge once (no "rebound" purges)
		} else {
			DeferredUpdates::addUpdate(
				HtmlFileCacheUpdate::newFromTitles( $titles ),
				DeferredUpdates::PRESEND
			);
			DeferredUpdates::addUpdate(
				CdnCacheUpdate::newFromTitles( $titles, $urls ),
				DeferredUpdates::PRESEND
			);
		}
	}
}
