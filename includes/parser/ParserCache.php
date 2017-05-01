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

/**
 * @ingroup Cache Parser
 * @todo document
 */
class ParserCache {
	/** @var BagOStuff */
	private $mMemc;
	/**
	 * Get an instance of this object
	 *
	 * @return ParserCache
	 */
	public static function singleton() {
		static $instance;
		if ( !isset( $instance ) ) {
			global $parserMemc;
			$instance = new ParserCache( $parserMemc );
		}
		return $instance;
	}

	/**
	 * Setup a cache pathway with a given back-end storage mechanism.
	 *
	 * This class use an invalidation strategy that is compatible with
	 * MultiWriteBagOStuff in async replication mode.
	 *
	 * @param BagOStuff $memCached
	 * @throws MWException
	 */
	protected function __construct( BagOStuff $memCached ) {
		$this->mMemc = $memCached;
	}

	/**
	 * @param WikiPage $article
	 * @param string $hash
	 * @return mixed|string
	 */
	protected function getParserOutputKey( $article, $hash ) {
		global $wgRequest;

		// idhash seem to mean 'page id' + 'rendering hash' (r3710)
		$pageid = $article->getId();
		$renderkey = (int)( $wgRequest->getVal( 'action' ) == 'render' );

		$key = wfMemcKey( 'pcache', 'idhash', "{$pageid}-{$renderkey}!{$hash}" );
		return $key;
	}

	/**
	 * @param WikiPage $page
	 * @return mixed|string
	 */
	protected function getOptionsKey( $page ) {
		return wfMemcKey( 'pcache', 'idoptions', $page->getId() );
	}

	/**
	 * @param WikiPage $page
	 * @since 1.28
	 */
	public function deleteOptionsKey( $page ) {
		$this->mMemc->delete( $this->getOptionsKey( $page ) );
	}

	/**
	 * Provides an E-Tag suitable for the whole page. Note that $article
	 * is just the main wikitext. The E-Tag has to be unique to the whole
	 * page, even if the article itself is the same, so it uses the
	 * complete set of user options. We don't want to use the preference
	 * of a different user on a message just because it wasn't used in
	 * $article. For example give a Chinese interface to a user with
	 * English preferences. That's why we take into account *all* user
	 * options. (r70809 CR)
	 *
	 * @param WikiPage $article
	 * @param ParserOptions $popts
	 * @return string
	 */
	public function getETag( $article, $popts ) {
		return 'W/"' . $this->getParserOutputKey( $article,
			$popts->optionsHash( ParserOptions::legacyOptions(), $article->getTitle() ) ) .
				"--" . $article->getTouched() . '"';
	}

	/**
	 * Retrieve the ParserOutput from ParserCache, even if it's outdated.
	 * @param WikiPage $article
	 * @param ParserOptions $popts
	 * @return ParserOutput|bool False on failure
	 */
	public function getDirty( $article, $popts ) {
		$value = $this->get( $article, $popts, true );
		return is_object( $value ) ? $value : false;
	}

	/**
	 * Generates a key for caching the given article considering
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
	 * @todo Document parameter $useOutdated
	 *
	 * @param WikiPage $article
	 * @param ParserOptions $popts
	 * @param bool $useOutdated (default true)
	 * @return bool|mixed|string
	 */
	public function getKey( $article, $popts, $useOutdated = true ) {
		global $wgCacheEpoch;

		if ( $popts instanceof User ) {
			wfWarn( "Use of outdated prototype ParserCache::getKey( &\$article, &\$user )\n" );
			$popts = ParserOptions::newFromUser( $popts );
		}

		// Determine the options which affect this article
		$casToken = null;
		$optionsKey = $this->mMemc->get(
			$this->getOptionsKey( $article ), $casToken, BagOStuff::READ_VERIFIED );
		if ( $optionsKey instanceof CacheTime ) {
			if ( !$useOutdated && $optionsKey->expired( $article->getTouched() ) ) {
				wfIncrStats( "pcache.miss.expired" );
				$cacheTime = $optionsKey->getCacheTime();
				wfDebugLog( "ParserCache",
					"Parser options key expired, touched " . $article->getTouched()
					. ", epoch $wgCacheEpoch, cached $cacheTime\n" );
				return false;
			} elseif ( !$useOutdated && $optionsKey->isDifferentRevision( $article->getLatest() ) ) {
				wfIncrStats( "pcache.miss.revid" );
				$revId = $article->getLatest();
				$cachedRevId = $optionsKey->getCacheRevisionId();
				wfDebugLog( "ParserCache",
					"ParserOutput key is for an old revision, latest $revId, cached $cachedRevId\n"
				);
				return false;
			}

			// $optionsKey->mUsedOptions is set by save() by calling ParserOutput::getUsedOptions()
			$usedOptions = $optionsKey->mUsedOptions;
			wfDebug( "Parser cache options found.\n" );
		} else {
			if ( !$useOutdated ) {
				return false;
			}
			$usedOptions = ParserOptions::legacyOptions();
		}

		return $this->getParserOutputKey(
			$article,
			$popts->optionsHash( $usedOptions, $article->getTitle() )
		);
	}

	/**
	 * Retrieve the ParserOutput from ParserCache.
	 * false if not found or outdated.
	 *
	 * @param WikiPage|Article $article
	 * @param ParserOptions $popts
	 * @param bool $useOutdated (default false)
	 *
	 * @return ParserOutput|bool False on failure
	 */
	public function get( $article, $popts, $useOutdated = false ) {
		global $wgCacheEpoch;

		$canCache = $article->checkTouched();
		if ( !$canCache ) {
			// It's a redirect now
			return false;
		}

		$touched = $article->getTouched();

		$parserOutputKey = $this->getKey( $article, $popts, $useOutdated );
		if ( $parserOutputKey === false ) {
			wfIncrStats( 'pcache.miss.absent' );
			return false;
		}

		$casToken = null;
		/** @var ParserOutput $value */
		$value = $this->mMemc->get( $parserOutputKey, $casToken, BagOStuff::READ_VERIFIED );
		if ( !$value ) {
			wfDebug( "ParserOutput cache miss.\n" );
			wfIncrStats( "pcache.miss.absent" );
			return false;
		}

		wfDebug( "ParserOutput cache found.\n" );

		// The edit section preference may not be the appropiate one in
		// the ParserOutput, as we are not storing it in the parsercache
		// key. Force it here. See T33445.
		$value->setEditSectionTokens( $popts->getEditSection() );

		$wikiPage = method_exists( $article, 'getPage' )
			? $article->getPage()
			: $article;

		if ( !$useOutdated && $value->expired( $touched ) ) {
			wfIncrStats( "pcache.miss.expired" );
			$cacheTime = $value->getCacheTime();
			wfDebugLog( "ParserCache",
				"ParserOutput key expired, touched $touched, "
				. "epoch $wgCacheEpoch, cached $cacheTime\n" );
			$value = false;
		} elseif ( !$useOutdated && $value->isDifferentRevision( $article->getLatest() ) ) {
			wfIncrStats( "pcache.miss.revid" );
			$revId = $article->getLatest();
			$cachedRevId = $value->getCacheRevisionId();
			wfDebugLog( "ParserCache",
				"ParserOutput key is for an old revision, latest $revId, cached $cachedRevId\n"
			);
			$value = false;
		} elseif (
			Hooks::run( 'RejectParserCacheValue', [ $value, $wikiPage, $popts ] ) === false
		) {
			wfIncrStats( 'pcache.miss.rejected' );
			wfDebugLog( "ParserCache",
				"ParserOutput key valid, but rejected by RejectParserCacheValue hook handler.\n"
			);
			$value = false;
		} else {
			wfIncrStats( "pcache.hit" );
		}

		return $value;
	}

	/**
	 * @param ParserOutput $parserOutput
	 * @param WikiPage $page
	 * @param ParserOptions $popts
	 * @param string $cacheTime Time when the cache was generated
	 * @param int $revId Revision ID that was parsed
	 */
	public function save( $parserOutput, $page, $popts, $cacheTime = null, $revId = null ) {
		$expire = $parserOutput->getCacheExpiry();
		if ( $expire > 0 && !$this->mMemc instanceof EmptyBagOStuff ) {
			$cacheTime = $cacheTime ?: wfTimestampNow();
			if ( !$revId ) {
				$revision = $page->getRevision();
				$revId = $revision ? $revision->getId() : null;
			}

			$optionsKey = new CacheTime;
			$optionsKey->mUsedOptions = $parserOutput->getUsedOptions();
			$optionsKey->updateCacheExpiry( $expire );

			$optionsKey->setCacheTime( $cacheTime );
			$parserOutput->setCacheTime( $cacheTime );
			$optionsKey->setCacheRevisionId( $revId );
			$parserOutput->setCacheRevisionId( $revId );

			$parserOutputKey = $this->getParserOutputKey( $page,
				$popts->optionsHash( $optionsKey->mUsedOptions, $page->getTitle() ) );

			// Save the timestamp so that we don't have to load the revision row on view
			$parserOutput->setTimestamp( $page->getTimestamp() );

			$msg = "Saved in parser cache with key $parserOutputKey" .
				" and timestamp $cacheTime" .
				" and revision id $revId" .
				"\n";

			$parserOutput->mText .= "\n<!-- $msg -->\n";
			wfDebug( $msg );

			// Save the parser output
			$this->mMemc->set( $parserOutputKey, $parserOutput, $expire );

			// ...and its pointer
			$this->mMemc->set( $this->getOptionsKey( $page ), $optionsKey, $expire );

			Hooks::run(
				'ParserCacheSaveComplete',
				[ $this, $parserOutput, $page->getTitle(), $popts, $revId ]
			);
		} elseif ( $expire <= 0 ) {
			wfDebug( "Parser output was marked as uncacheable and has not been saved.\n" );
		}
	}
}
