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
		$value = $wgMemc->get( $key );
		if ( $value ) {
			# Delete if article has changed since the cache was made
			$touched = $article->getTouched();
			if ( $value->getTouched() != $touched || $touched > $wgCacheEpoch ) {
				$wgMemc->delete( $key );
				$value = false;
			}
		} else {
			$value = false;
		}

		wfProfileOut( $fname );
		return $value;
	}
	
	function save( $parserOutput, &$article, &$user ){
		global $wgMemc;
		$key = $this->getKey( $article, $user );
		$touched = $article->getTouched();
		$parserOutput->setTouched( $touched );
		$parserOutput->mText .= "\n<-- Saved in parser cache with key $key and timestamp $touched -->\n";

		if( $parserOutput->containsOldMagic() ){
			$expire = 3600; # 1 hour
		} else {
			$expire = 7*86400; # 7 days
		}

		$wgMemc->set( $key, $parserOutput, $expire );
	}
	
}


?>
