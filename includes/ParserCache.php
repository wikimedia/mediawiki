<?php
/**
 *
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */
class ParserCache {
	/**
	 * Setup a cache pathway with a given back-end storage mechanism.
	 * May be a memcached client or a BagOStuff derivative.
	 *
	 * @param object $memCached
	 */
	function ParserCache( &$memCached ) {
		$this->mMemc =& $memCached;
	}
	
	function getKey( &$article, &$user ) {
		global $wgDBname;
		$hash = $user->getPageRenderingHash();
		$pageid = intval( $article->getID() );
		$key = "$wgDBname:pcache:idhash:$pageid-$hash";
		return $key;
	}
	
	function get( &$article, &$user ) {
		global $wgCacheEpoch;
		$fname = 'ParserCache::get';
		wfProfileIn( $fname );

		$hash = $user->getPageRenderingHash();
		$pageid = intval( $article->getID() );
		$key = $this->getKey( $article, $user );
		wfDebug( "Trying parser cache $key\n" );
		$value = $this->mMemc->get( $key );
		if ( is_object( $value ) ) {
			wfDebug( "Found.\n" );
			# Delete if article has changed since the cache was made
			$canCache = $article->checkTouched();
			$cacheTime = $value->getCacheTime();
			$touched = $article->mTouched;
			if ( !$canCache || $value->getCacheTime() <= $touched || $cacheTime < $wgCacheEpoch ) {
				if ( !$canCache ) {
					wfDebug( "Invalid cached redirect, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
				} else {
					wfDebug( "Key expired, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
				}
				$this->mMemc->delete( $key );
				$value = false;
			}
		} else {
			wfDebug( "Parser cache miss.\n" );
			$value = false;
		}

		wfProfileOut( $fname );
		return $value;
	}
	
	function save( $parserOutput, &$article, &$user ){
		$key = $this->getKey( $article, $user );
		$now = wfTimestampNow();
		$parserOutput->setCacheTime( $now );
		$parserOutput->mText .= "\n<!-- Saved in parser cache with key $key and timestamp $now -->\n";
		wfDebug( "Saved in parser cache with key $key and timestamp $now\n" );

		if( $parserOutput->containsOldMagic() ){
			$expire = 3600; # 1 hour
		} else {
			$expire = 86400; # 1 day
		}
		$this->mMemc->set( $key, $parserOutput, $expire );
	}
}


?>
