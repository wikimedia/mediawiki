<?php
/**
 * Cache for outputs of the PHP parser
 *
 * @file
 */

/**
 * @ingroup Cache Parser
 * @todo document
 */
class ParserCache {
	private $mMemc;

	/**
	 * Get an instance of this object
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
	function __construct( $memCached ) {
		if ( !$memCached ) {
			global $parserMemc;
			$parserMemc = $memCached = wfGetParserCacheStorage();
		}
		$this->mMemc = $memCached;
	}
	
	protected function getParserOutputKey( $article, $hash ) {
		global $wgRequest;
		
		// idhash seem to mean 'page id' + 'rendering hash' (r3710)
		$pageid = $article->getID();
		$renderkey = (int)($wgRequest->getVal('action') == 'render');
		
		$key = wfMemcKey( 'pcache', 'idhash', "{$pageid}-{$renderkey}!{$hash}" );
		return $key;
	}

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
	 */
	function getETag( $article, $popts ) {
		return 'W/"' . $this->getParserOutputKey( $article, 
			$popts->optionsHash( ParserOptions::legacyOptions() ) ) .
				"--" . $article->mTouched . '"';
	}

	/**
	 * Retrieve the ParserOutput from ParserCache, even if it's outdated.
	 */
	public function getDirty( $article, $popts ) {
		$value = $this->mMemc->get( $article, $popts, true );
		return is_object( $value ) ? $value : false;
	}

	/**
	 * Used to provide a unique id for the PoolCounter.
	 * It would be preferable to have this code in get() 
	 * instead of having Article looking in our internals.
	 * 
	 * Precondition: $article->checkTouched() has been called.
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
			if ( !$useOutdated && $optionsKey->expired( $article->mTouched ) ) {
				wfIncrStats( "pcache_miss_expired" );
				$cacheTime = $optionsKey->getCacheTime();
				wfDebug( "Parser options key expired, touched {$article->mTouched}, epoch $wgCacheEpoch, cached $cacheTime\n" );
				return false;
			}
			
			$usedOptions = $optionsKey->mUsedOptions;
			wfDebug( "Parser cache options found.\n" );
		} else {
			# TODO: Fail here $wgParserCacheExpireTime after deployment unless $useOutdated
			
			$usedOptions = ParserOptions::legacyOptions();
		}

		return $this->getParserOutputKey( $article, $popts->optionsHash( $usedOptions ) );
	}

	/**
	 * Retrieve the ParserOutput from ParserCache.
	 * false if not found or outdated.
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

		// Having called checkTouched() ensures this will be loaded
		$touched = $article->mTouched;
		
		$parserOutputKey = $this->getKey( $article, $popts, $useOutdated );
		if ( $parserOutputKey === false ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$value = $this->mMemc->get( $parserOutputKey );
		if ( !$value ) {
			wfDebug( "Parser cache miss.\n" );
			wfIncrStats( "pcache_miss_absent" );
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfDebug( "Found.\n" );
		
		if ( !$useOutdated && $value->expired( $touched ) ) {
			wfIncrStats( "pcache_miss_expired" );
			$cacheTime = $value->getCacheTime();
			wfDebug( "ParserOutput key expired, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
			$value = false;
		} else {
			if ( isset( $value->mTimestamp ) ) {
				$article->mTimestamp = $value->mTimestamp;
			}
			wfIncrStats( "pcache_hit" );
		}

		wfProfileOut( __METHOD__ );
		return $value;
	}


	public function save( $parserOutput, $article, $popts ) {
		$expire = $parserOutput->getCacheExpiry();

		if( $expire > 0 ) {			
			$now = wfTimestampNow();

			$optionsKey = new CacheTime;			
			$optionsKey->mUsedOptions = $popts->usedOptions();
			$optionsKey->updateCacheExpiry( $expire );
			
			$optionsKey->setCacheTime( $now );
			$parserOutput->setCacheTime( $now );

			$optionsKey->setContainsOldMagic( $parserOutput->containsOldMagic() );

			$parserOutputKey = $this->getParserOutputKey( $article, $popts->optionsHash( $optionsKey->mUsedOptions ) );

			// Save the timestamp so that we don't have to load the revision row on view
			$parserOutput->mTimestamp = $article->getTimestamp();

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
