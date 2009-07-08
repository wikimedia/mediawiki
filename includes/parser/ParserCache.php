<?php
/**
 * @ingroup Cache Parser
 * @todo document
 */
class ParserCache {
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
	 * @param object $memCached
	 */
	function __construct( $memCached ) {
		$this->mMemc = $memCached;
	}

	function getKey( $article, $popts ) {
		global $wgRequest;

		if( $popts instanceof User )	// It used to be getKey( &$article, &$user )
			$popts = ParserOptions::newFromUser( $popts );

		$user = $popts->mUser;
		$printable = ( $popts->getIsPrintable() ) ? '!printable=1' : '';
		$hash = $user->getPageRenderingHash();
		if( !$article->mTitle->quickUserCan( 'edit' ) ) {
			// section edit links are suppressed even if the user has them on
			$edit = '!edit=0';
		} else {
			$edit = '';
		}
		$pageid = $article->getID();
		$renderkey = (int)($wgRequest->getVal('action') == 'render');
		$key = wfMemcKey( 'pcache', 'idhash', "{$pageid}-{$renderkey}!{$hash}{$edit}{$printable}" );
		return $key;
	}

	function getETag( $article, $popts ) {
		return 'W/"' . $this->getKey($article, $popts) . "--" . $article->mTouched. '"';
	}

	function getDirty( $article, $popts ) {
		$key = $this->getKey( $article, $popts );
		wfDebug( "Trying parser cache $key\n" );
		$value = $this->mMemc->get( $key );
		return is_object( $value ) ? $value : false;
	}

	function get( $article, $popts ) {
		global $wgCacheEpoch;
		wfProfileIn( __METHOD__ );

		$value = $this->getDirty( $article, $popts );
		if ( !$value ) {
			wfDebug( "Parser cache miss.\n" );
			wfIncrStats( "pcache_miss_absent" );
			wfProfileOut( __METHOD__ );
			return false;
		}

		wfDebug( "Found.\n" );
		# Invalid if article has changed since the cache was made
		$canCache = $article->checkTouched();
		$cacheTime = $value->getCacheTime();
		$touched = $article->mTouched;
		if ( !$canCache || $value->expired( $touched ) ) {
			if ( !$canCache ) {
				wfIncrStats( "pcache_miss_invalid" );
				wfDebug( "Invalid cached redirect, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
			} else {
				wfIncrStats( "pcache_miss_expired" );
				wfDebug( "Key expired, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
			}
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

	function save( $parserOutput, $article, $popts ){
		global $wgParserCacheExpireTime;
		$key = $this->getKey( $article, $popts );

		if( $parserOutput->getCacheTime() != -1 ) {

			$now = wfTimestampNow();
			$parserOutput->setCacheTime( $now );

			// Save the timestamp so that we don't have to load the revision row on view
			$parserOutput->mTimestamp = $article->getTimestamp();

			$parserOutput->mText .= "\n<!-- Saved in parser cache with key $key and timestamp $now -->\n";
			wfDebug( "Saved in parser cache with key $key and timestamp $now\n" );

			if( $parserOutput->containsOldMagic() ){
				$expire = 3600; # 1 hour
			} else {
				$expire = $wgParserCacheExpireTime;
			}
			$this->mMemc->set( $key, $parserOutput, $expire );

		} else {
			wfDebug( "Parser output was marked as uncacheable and has not been saved.\n" );
		}
	}

}
