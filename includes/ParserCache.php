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
		global $wgMemc;
		$fname = "ParserCache::get";
		wfProfileIn( $fname );

		$hash = $user->getPageRenderingHash();
		$pageid = intval( $article->getID() );
		$key = $this->getKey( $article, $user );
		$value = $wgMemc->get( $key );
		if ( $value ) {
			# Delete if article has changed since the cache was made
			if ( $value->getTouched() != $article->getTouched() ) {
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
		$parserOutput->setTouched( $article->getTouched() );
		if( $parserOutput->containsOldMagic() ){
			$expire = 3600; # 1 hour
		} else {
			$expire = 7*86400; # 7 days
		}

		$wgMemc->set( $key, $parserOutput, $expire );
	}
	
}


?>
