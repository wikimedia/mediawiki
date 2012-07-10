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
	private $mMemc;
	const try116cache = false; /* Only useful $wgParserCacheExpireTime after updating to 1.17 */

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
	 * May be a memcached client or a BagOStuff derivative.
	 *
	 * @param $memCached Object
	 */
	protected function __construct( $memCached ) {
		if ( !$memCached ) {
			throw new MWException( "Tried to create a ParserCache with an invalid memcached" );
		}
		$this->mMemc = $memCached;
	}

	/**
	 * @param $article Article
	 * @param $hash string
	 * @return mixed|string
	 */
	protected function getParserOutputKey( $article, $hash ) {
		global $wgRequest;

		// idhash seem to mean 'page id' + 'rendering hash' (r3710)
		$pageid = $article->getID();
		$renderkey = (int)($wgRequest->getVal('action') == 'render');

		$key = wfMemcKey( 'pcache', 'idhash', "{$pageid}-{$renderkey}!{$hash}" );
		return $key;
	}

	/**
	 * @param $article Article
	 * @return mixed|string
	 */
	protected function getOptionsKey( $article ) {
		$pageid = $article->getID();
		return wfMemcKey( 'pcache', 'idoptions', "{$pageid}" );
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
	 * @param $article Article
	 * @param $popts ParserOptions
	 * @return string
	 */
	function getETag( $article, $popts ) {
		return 'W/"' . $this->getParserOutputKey( $article,
			$popts->optionsHash( ParserOptions::legacyOptions(), $article->getTitle() ) ) .
				"--" . $article->getTouched() . '"';
	}

	/**
	 * Retrieve the ParserOutput from ParserCache, even if it's outdated.
	 * @param $article Article
	 * @param $popts ParserOptions
	 * @return ParserOutput|bool False on failure
	 */
	public function getDirty( $article, $popts ) {
		$value = $this->get( $article, $popts, true );
		return is_object( $value ) ? $value : false;
	}

	/**
	 * Used to provide a unique id for the PoolCounter.
	 * It would be preferable to have this code in get()
	 * instead of having Article looking in our internals.
	 *
	 * @todo Document parameter $useOutdated
	 *
	 * @param $article     Article
	 * @param $popts       ParserOptions
	 * @param $useOutdated Boolean (default true)
	 * @return bool|mixed|string
	 */
	public function getKey( $article, $popts, $useOutdated = true ) {
		global $wgCacheEpoch;

		if( $popts instanceof User ) {
			wfWarn( "Use of outdated prototype ParserCache::getKey( &\$article, &\$user )\n" );
			$popts = ParserOptions::newFromUser( $popts );
		}

		// Determine the options which affect this article
		$optionsKey = $this->mMemc->get( $this->getOptionsKey( $article ) );
		if ( $optionsKey != false ) {
			if ( !$useOutdated && $optionsKey->expired( $article->getTouched() ) ) {
				wfIncrStats( "pcache_miss_expired" );
				$cacheTime = $optionsKey->getCacheTime();
				wfDebug( "Parser options key expired, touched " . $article->getTouched() . ", epoch $wgCacheEpoch, cached $cacheTime\n" );
				return false;
			}

			$usedOptions = $optionsKey->mUsedOptions;
			wfDebug( "Parser cache options found.\n" );
		} else {
			if ( !$useOutdated && !self::try116cache ) {
				return false;
			}
			$usedOptions = ParserOptions::legacyOptions();
		}

		return $this->getParserOutputKey( $article, $popts->optionsHash( $usedOptions, $article->getTitle() ) );
	}

	/**
	 * Retrieve the ParserOutput from ParserCache.
	 * false if not found or outdated.
	 *
	 * @param $article     Article
	 * @param $popts       ParserOptions
	 * @param $useOutdated Boolean (default false)
	 *
	 * @return ParserOutput|bool False on failure
	 */
	public function get( $article, $popts, $useOutdated = false ) {
		global $wgCacheEpoch;
		wfProfileIn( __METHOD__ );

		$canCache = $article->checkTouched();
		if ( !$canCache ) {
			// It's a redirect now
			wfProfileOut( __METHOD__ );
			return false;
		}

		$touched = $article->getTouched();

		$parserOutputKey = $this->getKey( $article, $popts, $useOutdated );
		if ( $parserOutputKey === false ) {
			wfIncrStats( 'pcache_miss_absent' );
			wfProfileOut( __METHOD__ );
			return false;
		}

		$value = $this->mMemc->get( $parserOutputKey );
		if ( self::try116cache && !$value && strpos( $value, '*' ) !== -1 ) {
			wfDebug( "New format parser cache miss.\n" );
			$parserOutputKey = $this->getParserOutputKey( $article,
				$popts->optionsHash( ParserOptions::legacyOptions(), $article->getTitle() ) );
			$value = $this->mMemc->get( $parserOutputKey );
		}
		if ( !$value ) {
			wfDebug( "ParserOutput cache miss.\n" );
			wfIncrStats( "pcache_miss_absent" );
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfDebug( "ParserOutput cache found.\n" );

		// The edit section preference may not be the appropiate one in 
		// the ParserOutput, as we are not storing it in the parsercache 
		// key. Force it here. See bug 31445.
		$value->setEditSectionTokens( $popts->getEditSection() );

		if ( !$useOutdated && $value->expired( $touched ) ) {
			wfIncrStats( "pcache_miss_expired" );
			$cacheTime = $value->getCacheTime();
			wfDebug( "ParserOutput key expired, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
			$value = false;
		} else {
			wfIncrStats( "pcache_hit" );
		}

		wfProfileOut( __METHOD__ );
		return $value;
	}

	/**
	 * @param $parserOutput ParserOutput
	 * @param $article Article
	 * @param $popts ParserOptions
	 */
	public function save( $parserOutput, $article, $popts ) {
		$expire = $parserOutput->getCacheExpiry();

		if( $expire > 0 ) {
			$now = wfTimestampNow();

			$optionsKey = new CacheTime;
			$optionsKey->mUsedOptions = $parserOutput->getUsedOptions();
			$optionsKey->updateCacheExpiry( $expire );

			$optionsKey->setCacheTime( $now );
			$parserOutput->setCacheTime( $now );

			$optionsKey->setContainsOldMagic( $parserOutput->containsOldMagic() );

			$parserOutputKey = $this->getParserOutputKey( $article,
				$popts->optionsHash( $optionsKey->mUsedOptions, $article->getTitle() ) );

			// Save the timestamp so that we don't have to load the revision row on view
			$parserOutput->setTimestamp( $article->getTimestamp() );

			$parserOutput->mText .= "\n<!-- Saved in parser cache with key $parserOutputKey and timestamp $now -->\n";
			wfDebug( "Saved in parser cache with key $parserOutputKey and timestamp $now\n" );

			// Save the parser output
			$this->mMemc->set( $parserOutputKey, $parserOutput, $expire );

			// ...and its pointer
			$this->mMemc->set( $this->getOptionsKey( $article ), $optionsKey, $expire );
		} else {
			wfDebug( "Parser output was marked as uncacheable and has not been saved.\n" );
		}
	}
}
