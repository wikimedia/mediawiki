<?php

class ParserCache
{
	function getKey( &$article, &$user ) {
		global $wgDBname;
		$hash = $user->getPageRenderingHash();
		$pageid = intval( $article->getID() );
		$key = "$wgDBname:pcache:idhash:$pageid-$hash";
		return $key;
	}
	
	function get( &$article, &$user ) {
		global $wgMemc, $wgCacheEpoch;
		$fname = "ParserCache::get";
		wfProfileIn( $fname );

		$hash = $user->getPageRenderingHash();
		$pageid = intval( $article->getID() );
		$key = $this->getKey( $article, $user );

		wfDebug( "Trying parser cache $key\n" );
		$value = $wgMemc->get( $key );
		if ( $value ) {
			wfDebug( "Found.\n" );
			# Delete if article has changed since the cache was made
			$canCache = $article->checkTouched();
			$cacheTime = $value->getCacheTime();
			$touched = $article->mTouched;
			if ( !$canCache || $value->getCacheTime() <= $touched || $cacheTime < $wgCacheEpoch ) {
				if ( !$canCache ) {
					$this->incrStats( "pcache_miss_invalid" );
					wfDebug( "Invalid cached redirect, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
				} else {
					$this->incrStats( "pcache_miss_expired" );
					wfDebug( "Key expired, touched $touched, epoch $wgCacheEpoch, cached $cacheTime\n" );
				}
				$wgMemc->delete( $key );
				$value = false;

			} else {
				$this->incrStats( "pcache_hit" );
			}
		} else {
			$this->incrStats( "pcache_miss_absent" );
			$value = false;
		}

		wfProfileOut( $fname );
		return $value;
	}
	
	function save( $parserOutput, &$article, &$user ){
		global $wgMemc;

		$key = $this->getKey( $article, $user );
		$now = wfTimestampNow();
		$parserOutput->setCacheTime( $now );
		$parserOutput->mText .= "\n<!-- Saved in parser cache with key $key and timestamp $now -->\n";

		if( $parserOutput->containsOldMagic() ){
			$expire = 3600; # 1 hour
		} else {
			$expire = 86400; # 1 day
		}

		$wgMemc->set( $key, $parserOutput, $expire );
	}

	function incrStats( $key ) {
		global $wgDBname, $wgMemc;
		$key = "$wgDBname:stats:$key";
		if ( is_null( $wgMemc->incr( $key ) ) ) {
			$wgMemc->set( $key, 1 );
		}
	}
}


?>
