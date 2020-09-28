<?php
/**
 * Cache for outputs of the PHP parser
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
 * @ingroup Cache Parser
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;

/**
 * @ingroup Cache Parser
 * @todo document
 */
class ParserCache {
	/**
	 * Constants for self::getKey()
	 * @since 1.30
	 */

	/** Use only current data */
	private const USE_CURRENT_ONLY = 0;

	/** Use expired data if current data is unavailable */
	private const USE_EXPIRED = 1;

	/** Use expired data or data from different revisions if current data is unavailable */
	private const USE_OUTDATED = 2;

	/**
	 * Use expired data and data from different revisions, and if all else
	 * fails vary on all variable options
	 */
	private const USE_ANYTHING = 3;

	/** @var BagOStuff */
	private $cache;

	/**
	 * Anything cached prior to this is invalidated
	 *
	 * @var string
	 */
	private $cacheEpoch;

	/** @var HookRunner */
	private $hookRunner;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/**
	 * Get an instance of this object
	 *
	 * @deprecated since 1.30, use MediaWikiServices instead
	 * @return ParserCache
	 */
	public static function singleton() {
		wfDeprecated( __METHOD__, '1.30' );
		return MediaWikiServices::getInstance()->getParserCache();
	}

	/**
	 * Setup a cache pathway with a given back-end storage mechanism.
	 *
	 * This class use an invalidation strategy that is compatible with
	 * MultiWriteBagOStuff in async replication mode.
	 *
	 * @param BagOStuff $cache
	 * @param string $cacheEpoch Anything before this timestamp is invalidated
	 * @param HookContainer|null $hookContainer
	 * @param IBufferingStatsdDataFactory|null $stats
	 * @throws MWException
	 */
	public function __construct(
		BagOStuff $cache,
		$cacheEpoch = '20030516000000',
		HookContainer $hookContainer = null,
		IBufferingStatsdDataFactory $stats = null
	) {
		$this->cache = $cache;
		$this->cacheEpoch = $cacheEpoch;
		$this->hookRunner = new HookRunner(
			$hookContainer ?: MediaWikiServices::getInstance()->getHookContainer()
		);
		$this->stats = $stats ?: MediaWikiServices::getInstance()->getStatsdDataFactory();
	}

	/**
	 * @param WikiPage $wikiPage
	 * @param string $hash
	 * @return mixed|string
	 */
	protected function getParserOutputKey( WikiPage $wikiPage, $hash ) {
		global $wgRequest;

		// idhash seem to mean 'page id' + 'rendering hash' (r3710)
		$pageid = $wikiPage->getId();
		$renderkey = (int)( $wgRequest->getVal( 'action' ) == 'render' );

		$key = $this->cache->makeKey( 'pcache', 'idhash', "{$pageid}-{$renderkey}!{$hash}" );
		return $key;
	}

	/**
	 * @param WikiPage $wikiPage
	 * @return mixed|string
	 */
	protected function getOptionsKey( WikiPage $wikiPage ) {
		return $this->cache->makeKey( 'pcache', 'idoptions', $wikiPage->getId() );
	}

	/**
	 * @param WikiPage $wikiPage
	 * @since 1.28
	 */
	public function deleteOptionsKey( WikiPage $wikiPage ) {
		$this->cache->delete( $this->getOptionsKey( $wikiPage ) );
	}

	/**
	 * Provides an E-Tag suitable for the whole page. Note that $wikiPage
	 * is just the main wikitext. The E-Tag has to be unique to the whole
	 * page, even if the article itself is the same, so it uses the
	 * complete set of user options. We don't want to use the preference
	 * of a different user on a message just because it wasn't used in
	 * $wikiPage. For example give a Chinese interface to a user with
	 * English preferences. That's why we take into account *all* user
	 * options. (r70809 CR)
	 *
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @return string
	 */
	public function getETag( WikiPage $wikiPage, $popts ) {
		return 'W/"'
			. $this->getParserOutputKey(
				$wikiPage,
				$popts->optionsHash(
					ParserOptions::allCacheVaryingOptions(),
					$wikiPage->getTitle()
				)
			)
			. "--" . $wikiPage->getTouched() . '"';
	}

	/**
	 * Retrieve the ParserOutput from ParserCache, even if it's outdated.
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @return ParserOutput|bool False on failure
	 */
	public function getDirty( WikiPage $wikiPage, $popts ) {
		$value = $this->get( $wikiPage, $popts, true );
		return is_object( $value ) ? $value : false;
	}

	/**
	 * @param WikiPage $wikiPage
	 * @param string $metricSuffix
	 */
	private function incrementStats( WikiPage $wikiPage, $metricSuffix ) {
		$contentModel = str_replace( '.', '_', $wikiPage->getContentModel() );
		$metricSuffix = str_replace( '.', '_', $metricSuffix );
		$this->stats->increment( 'pcache.' . $contentModel . '.' . $metricSuffix );
	}

	/**
	 * Generates a key for caching the given page considering
	 * the given parser options.
	 *
	 * @note Which parser options influence the cache key
	 * is controlled via ParserOutput::recordOption() or
	 * ParserOptions::addExtraKey().
	 *
	 * @note Used by Article to provide a unique id for the PoolCounter.
	 * It would be preferable to have this code in get()
	 * instead of having Article looking in our internals.
	 *
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @param int|bool $useOutdated One of the USE constants. For backwards
	 *  compatibility, boolean false is treated as USE_CURRENT_ONLY and
	 *  boolean true is treated as USE_ANYTHING.
	 * @return bool|mixed|string
	 * @since 1.30 Changed $useOutdated to an int and added the non-boolean values
	 */
	public function getKey( WikiPage $wikiPage, $popts, $useOutdated = self::USE_ANYTHING ) {
		if ( is_bool( $useOutdated ) ) {
			$useOutdated = $useOutdated ? self::USE_ANYTHING : self::USE_CURRENT_ONLY;
		}

		if ( $popts instanceof User ) {
			wfWarn( "Use of outdated prototype ParserCache::getKey( &\$wikiPage, &\$user )\n" );
			$popts = ParserOptions::newFromUser( $popts );
		}

		// Determine the options which affect this article
		$optionsKey = $this->cache->get(
			$this->getOptionsKey( $wikiPage ), BagOStuff::READ_VERIFIED );
		if ( $optionsKey instanceof CacheTime ) {
			if (
				$useOutdated < self::USE_EXPIRED
				&& $optionsKey->expired( $wikiPage->getTouched() )
			) {
				$this->incrementStats( $wikiPage, "miss.expired" );
				$cacheTime = $optionsKey->getCacheTime();
				wfDebugLog( "ParserCache",
					"Parser options key expired, touched {$wikiPage->getTouched()}"
					. ", epoch {$this->cacheEpoch}, cached $cacheTime" );
				return false;
			} elseif ( $useOutdated < self::USE_OUTDATED &&
				$optionsKey->isDifferentRevision( $wikiPage->getLatest() )
			) {
				$this->incrementStats( $wikiPage, "miss.revid" );
				$revId = $wikiPage->getLatest();
				$cachedRevId = $optionsKey->getCacheRevisionId();
				wfDebugLog( "ParserCache",
					"ParserOutput key is for an old revision, latest $revId, cached $cachedRevId"
				);
				return false;
			}

			// $optionsKey->mUsedOptions is set by save() by calling ParserOutput::getUsedOptions()
			$usedOptions = $optionsKey->mUsedOptions;
			wfDebug( "Parser cache options found." );
		} else {
			if ( $useOutdated < self::USE_ANYTHING ) {
				return false;
			}
			$usedOptions = ParserOptions::allCacheVaryingOptions();
		}

		return $this->getParserOutputKey(
			$wikiPage,
			$popts->optionsHash( $usedOptions, $wikiPage->getTitle() )
		);
	}

	/**
	 * Retrieve the ParserOutput from ParserCache.
	 * false if not found or outdated.
	 *
	 * @param WikiPage|Article|Page $wikiPage Article is hard deprecated since 1.35
	 * @param ParserOptions $popts
	 * @param bool $useOutdated (default false)
	 *
	 * @return ParserOutput|bool False on failure
	 */
	public function get( Page $wikiPage, $popts, $useOutdated = false ) {
		if ( $wikiPage instanceof Article ) {
			wfDeprecated(
				__METHOD__ . ' with Article parameter',
				'1.35'
			);
			$wikiPage = $wikiPage->getPage();
		}

		$canCache = $wikiPage->checkTouched();
		if ( !$canCache ) {
			// It's a redirect now
			return false;
		}

		$touched = $wikiPage->getTouched();

		$parserOutputKey = $this->getKey( $wikiPage, $popts,
			$useOutdated ? self::USE_OUTDATED : self::USE_CURRENT_ONLY
		);
		if ( $parserOutputKey === false ) {
			$this->incrementStats( $wikiPage, 'miss.absent' );
			return false;
		}

		$casToken = null;
		/** @var ParserOutput $value */
		$value = $this->cache->get( $parserOutputKey, BagOStuff::READ_VERIFIED );
		if ( !$value ) {
			wfDebug( "ParserOutput cache miss." );
			$this->incrementStats( $wikiPage, "miss.absent" );
			return false;
		}

		wfDebug( "ParserOutput cache found." );

		if ( !$useOutdated && $value->expired( $touched ) ) {
			$this->incrementStats( $wikiPage, "miss.expired" );
			$cacheTime = $value->getCacheTime();
			wfDebugLog( "ParserCache",
				"ParserOutput key expired, touched $touched, "
				. "epoch {$this->cacheEpoch}, cached $cacheTime" );
			$value = false;
		} elseif (
			!$useOutdated
			&& $value->isDifferentRevision( $wikiPage->getLatest() )
		) {
			$this->incrementStats( $wikiPage, "miss.revid" );
			$revId = $wikiPage->getLatest();
			$cachedRevId = $value->getCacheRevisionId();
			wfDebugLog( "ParserCache",
				"ParserOutput key is for an old revision, latest $revId, cached $cachedRevId"
			);
			$value = false;
		} elseif (
			$this->hookRunner->onRejectParserCacheValue( $value, $wikiPage, $popts ) === false
		) {
			$this->incrementStats( $wikiPage, 'miss.rejected' );
			wfDebugLog( "ParserCache",
				"ParserOutput key valid, but rejected by RejectParserCacheValue hook handler."
			);
			$value = false;
		} else {
			$this->incrementStats( $wikiPage, "hit" );
		}

		return $value;
	}

	/**
	 * @param ParserOutput $parserOutput
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @param string|null $cacheTime TS_MW timestamp when the cache was generated
	 * @param int|null $revId Revision ID that was parsed
	 */
	public function save(
		ParserOutput $parserOutput,
		WikiPage $wikiPage,
		$popts,
		$cacheTime = null,
		$revId = null
	) {
		if ( !$parserOutput->hasText() ) {
			throw new InvalidArgumentException( 'Attempt to cache a ParserOutput with no text set!' );
		}

		$expire = $parserOutput->getCacheExpiry();
		if ( $expire > 0 && !$this->cache instanceof EmptyBagOStuff ) {
			$cacheTime = $cacheTime ?: wfTimestampNow();
			if ( !$revId ) {
				$revision = $wikiPage->getRevisionRecord();
				$revId = $revision ? $revision->getId() : null;
			}

			$optionsKey = new CacheTime;
			$optionsKey->mUsedOptions = $parserOutput->getUsedOptions();
			$optionsKey->updateCacheExpiry( $expire );

			$optionsKey->setCacheTime( $cacheTime );
			$parserOutput->setCacheTime( $cacheTime );
			$optionsKey->setCacheRevisionId( $revId );
			$parserOutput->setCacheRevisionId( $revId );

			$parserOutputKey = $this->getParserOutputKey( $wikiPage,
				$popts->optionsHash( $optionsKey->mUsedOptions, $wikiPage->getTitle() ) );

			// Save the timestamp so that we don't have to load the revision row on view
			$parserOutput->setTimestamp( $wikiPage->getTimestamp() );

			$msg = "Saved in parser cache with key $parserOutputKey" .
				" and timestamp $cacheTime" .
				" and revision id $revId";

			$parserOutput->mText .= "\n<!-- $msg\n -->\n";
			wfDebug( $msg );

			// Save the parser output
			$this->cache->set(
				$parserOutputKey,
				$parserOutput,
				$expire,
				BagOStuff::WRITE_ALLOW_SEGMENTS
			);

			// ...and its pointer
			$this->cache->set( $this->getOptionsKey( $wikiPage ), $optionsKey, $expire );

			$this->hookRunner->onParserCacheSaveComplete(
				$this, $parserOutput, $wikiPage->getTitle(), $popts, $revId );
		} elseif ( $expire <= 0 ) {
			wfDebug( "Parser output was marked as uncacheable and has not been saved." );
		}
	}

	/**
	 * Get the backend BagOStuff instance that
	 * powers the parser cache
	 *
	 * @since 1.30
	 * @return BagOStuff
	 */
	public function getCacheStorage() {
		return $this->cache;
	}
}
