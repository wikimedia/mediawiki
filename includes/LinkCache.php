<?php
# Cache for article titles (prefixed DB keys) and ids linked from one source

# These are used in incrementalSetup()
define ('LINKCACHE_GOOD', 0);
define ('LINKCACHE_BAD', 1);
define ('LINKCACHE_IMAGE', 2);

class LinkCache {	
	// Increment $mClassVer whenever old serialized versions of this class
	// becomes incompatible with the new version.
	/* private */ var $mClassVer = 2;

	/* private */ var $mGoodLinks, $mBadLinks, $mActive;
	/* private */ var $mImageLinks, $mCategoryLinks;
	/* private */ var $mPreFilled, $mOldGoodLinks, $mOldBadLinks;
	
	/* private */ function getKey( $title ) {
		global $wgDBname;
		return "$wgDBname:lc:title:$title";
	}
	
	function LinkCache()
	{
		$this->mActive = true;
		$this->mPreFilled = false;
		$this->mGoodLinks = array();
		$this->mBadLinks = array();
		$this->mImageLinks = array();
		$this->mCategoryLinks = array();
		$this->mOldGoodLinks = array();
		$this->mOldBadLinks = array();
	}

	function getGoodLinkID( $title )
	{
		if ( array_key_exists( $title, $this->mGoodLinks ) ) {
			return $this->mGoodLinks[$title];
		} else {
			return 0;
		}
	}

	function isBadLink( $title )
	{
		return array_key_exists( $title, $this->mBadLinks ); 
	}

	function addGoodLink( $id, $title )
	{
		if ( $this->mActive ) {
			$this->mGoodLinks[$title] = $id;
		}
	}

	function addBadLink( $title )
	{
		if ( $this->mActive && ( ! $this->isBadLink( $title ) ) ) {
			$this->mBadLinks[$title] = 1;
		}
	}

	function addImageLink( $title )
	{
		if ( $this->mActive ) { $this->mImageLinks[$title] = 1; }
	}

	function addImageLinkObj( $nt )
	{
		if ( $this->mActive ) { $this->mImageLinks[$nt->getDBkey()] = 1; }
	}
	
	function addCategoryLink( $title, $sortkey ) {
		if ( $this->mActive ) { $this->mCategoryLinks[$title] = $sortkey; }
	}
	
	function addCategoryLinkObj( &$nt, $sortkey ) {
		$this->addCategoryLink( $nt->getDBkey(), $sortkey );
	}

	function clearBadLink( $title )
	{
		unset( $this->mBadLinks[$title] );
		$this->clearLink( $title );
	}
	
	function clearLink( $title ) 
	{
		global $wgMemc, $wgLinkCacheMemcached;
		if( $wgLinkCacheMemcached )
			$wgMemc->delete( $this->getKey( $title ) );
	}

	function suspend() { $this->mActive = false; }
	function resume() { $this->mActive = true; }
	function getGoodLinks() { return $this->mGoodLinks; }
	function getBadLinks() { return array_keys( $this->mBadLinks ); }
	function getImageLinks() { return $this->mImageLinks; }
	function getCategoryLinks() { return $this->mCategoryLinks; }

	function addLink( $title )
	{
		$nt = Title::newFromDBkey( $title );
		if( $nt ) {
			return $this->addLinkObj( $nt );
		} else {
			return 0;
		}
	}
	
	function addLinkObj( &$nt )
	{
		global $wgMemc, $wgLinkCacheMemcached;
		$title = $nt->getPrefixedDBkey();
		if ( $this->isBadLink( $title ) ) { return 0; }		
		$id = $this->getGoodLinkID( $title );
		if ( 0 != $id ) { return $id; }

		$fname = "LinkCache::addLinkObj";
		wfProfileIn( $fname );

		$ns = $nt->getNamespace();
		$t = $nt->getDBkey();

		if ( "" == $title ) { 
			wfProfileOut( $fname );
			return 0; 
		}
		
		$id = NULL;
		if( $wgLinkCacheMemcached )
			$id = $wgMemc->get( $key = $this->getKey( $title ) );
		if( ! is_integer( $id ) ) {
			$sql = "SELECT cur_id FROM cur WHERE cur_namespace=" .
			  "{$ns} AND cur_title='" . wfStrencode( $t ) . "'";
			$res = wfQuery( $sql, DB_READ, "LinkCache::addLink" );

			if ( 0 == wfNumRows( $res ) ) {
				$id = 0;
			} else {
				$s = wfFetchObject( $res );
				$id = $s->cur_id;
			}
			if( $wgLinkCacheMemcached )
				$wgMemc->add( $key, $id, 3600*24 );
		}
		
		if ( 0 == $id ) { $this->addBadLink( $title ); }
		else { $this->addGoodLink( $id, $title ); }
		wfProfileOut( $fname );
		return $id;
	}

	function preFill( &$fromtitle )
	{
		global $wgEnablePersistentLC;

		$fname = "LinkCache::preFill";
		wfProfileIn( $fname );
		# Note -- $fromtitle is a Title *object*

		$this->suspend();
		$id = $fromtitle->getArticleID();
		$this->resume();
		
		if( $id == 0 ) {
			wfDebug( "$fname - got id 0 for title '" . $fromtitle->getPrefixedDBkey() . "'\n" );
			wfProfileOut( $fname );
			return;
		}
		
		if ( $wgEnablePersistentLC ) {
			if( $this->fillFromLinkscc( $id ) ){
				wfProfileOut( $fname );
				return;
			}
		}

		$sql = "SELECT cur_id,cur_namespace,cur_title
			FROM cur,links
			WHERE cur_id=l_to AND l_from=$id";
		$res = wfQuery( $sql, DB_READ, $fname );
		while( $s = wfFetchObject( $res ) ) {
			$this->addGoodLink( $s->cur_id,
				Title::makeName( $s->cur_namespace, $s->cur_title )
				);
		}
		
		$sql = "SELECT bl_to
			FROM brokenlinks
			WHERE bl_from='{$id}'";
		$res = wfQuery( $sql, DB_READ, "LinkCache::preFill" );
		while( $s = wfFetchObject( $res ) ) {
			$this->addBadLink( $s->bl_to );
		}
		
		$this->mOldBadLinks = $this->mBadLinks;
		$this->mOldGoodLinks = $this->mGoodLinks;
		$this->mPreFilled = true;

		if ( $wgEnablePersistentLC ) {
			$this->saveToLinkscc( $id );
		}
		wfProfileOut( $fname );
	}

	function getGoodAdditions() 
	{
		return array_diff( $this->mGoodLinks, $this->mOldGoodLinks );
	}

	function getBadAdditions() 
	{
		#wfDebug( "mOldBadLinks: " . implode( ', ', array_keys( $this->mOldBadLinks ) ) . "\n" );
		#wfDebug( "mBadLinks: " . implode( ', ', array_keys( $this->mBadLinks ) ) . "\n" );
		return array_values( array_diff( array_keys( $this->mBadLinks ), array_keys( $this->mOldBadLinks ) ) );
	}

	function getImageAdditions()
	{
		return array_diff_assoc( $this->mImageLinks, $this->mOldImageLinks );
	}

	function getGoodDeletions() 
	{
		return array_diff( $this->mOldGoodLinks, $this->mGoodLinks );
	}

	function getBadDeletions()
	{
		return array_values( array_diff( array_keys( $this->mOldBadLinks ), array_keys( $this->mBadLinks ) ));
	}

	function getImageDeletions()
	{
		return array_diff_assoc( $this->mOldImageLinks, $this->mImageLinks );
	}

	#     Parameters: $which is one of the LINKCACHE_xxx constants, $del and $add are 
	# the incremental update arrays which will be filled. Returns whether or not it's
	# worth doing the incremental version. For example, if [[List of mathematical topics]]
	# was blanked, it would take a long, long time to do incrementally.
	function incrementalSetup( $which, &$del, &$add )
	{
		if ( ! $this->mPreFilled ) {
			return false;
		}

		switch ( $which ) {
			case LINKCACHE_GOOD:
				$old =& $this->mOldGoodLinks;
				$cur =& $this->mGoodLinks;
				$del = $this->getGoodDeletions();
				$add = $this->getGoodAdditions();
				break;
			case LINKCACHE_BAD:
				$old =& $this->mOldBadLinks;
				$cur =& $this->mBadLinks;
				$del = $this->getBadDeletions();
				$add = $this->getBadAdditions();
				break;
			default: # LINKCACHE_IMAGE
				return false;		
		}
		
		return true;
	}

	# Clears cache but leaves old preFill copies alone
	function clear() 
	{
		$this->mGoodLinks = array();
		$this->mBadLinks = array();
		$this->mImageLinks = array();
	}

	/* private */ function fillFromLinkscc( $id ){ 
		$id = IntVal( $id );
		$res = wfQuery("SELECT lcc_cacheobj FROM linkscc WHERE lcc_pageid = $id", 
			DB_READ);
		$row = wfFetchObject( $res );
		if( $row == FALSE)
			return false;	

		$cacheobj = false;
		if( function_exists( "gzuncompress" ) )
			$cacheobj = @gzuncompress( $row->lcc_cacheobj );

		if($cacheobj == FALSE){
			$cacheobj = $row->lcc_cacheobj;
		}
		$cc = @unserialize( $cacheobj );
		if( isset( $cc->mClassVer ) and ($cc->mClassVer == $this->mClassVer ) ){
			$this->mOldGoodLinks = $this->mGoodLinks = $cc->mGoodLinks;
			$this->mOldBadLinks = $this->mBadLinks = $cc->mBadLinks;
			$this->mPreFilled = true;
			return TRUE;
		} else {
			return FALSE;
		}

	}

	/* private */ function saveToLinkscc( $pid ){
		global $wgCompressedPersistentLC, $wgIsMySQL;
		if( $wgCompressedPersistentLC and function_exists( "gzcompress" ) ) {
			$ser = wfStrencode( gzcompress( serialize( $this ), 3 ));
		} else {
			$ser = wfStrencode( serialize( $this ) );
		}
		if ($wgIsMySQL) {
			wfQuery("REPLACE INTO linkscc(lcc_pageid,lcc_cacheobj) " .
				"VALUES({$pid}, '{$ser}')", DB_WRITE);
		} else {
			wfQuery("DELETE FROM linkscc WHERE lcc_pageid={$pid}",DB_WRITE);
			wfQuery("INSERT INTO linkscc(lcc_pageid,lcc_cacheobj) " .
				"VALUES({$pid}, '{$ser}')", DB_WRITE);
		}
	}

	# $pid is a page id
	/* static */ function linksccClearLinksTo( $pid ){
		global $wgEnablePersistentLC, $wgIsMySQL;
		if ( $wgEnablePersistentLC ) {
			$pid = intval( $pid );
			if ($wgIsMySQL) {
				wfQuery("DELETE linkscc FROM linkscc,links ".
					"WHERE lcc_pageid=links.l_from AND l_to={$pid}", DB_WRITE);
			} else {
				wfQuery("DELETE FROM linkscc WHERE lcc_pageid IN ".
					"(SELECT l_from FROM links WHERE l_to={$pid})", DB_WRITE);
			}
			wfQuery("DELETE FROM linkscc WHERE lcc_pageid='{$pid}'", DB_WRITE);
		}
	}

	# $title is a prefixed db title, for example like Title->getPrefixedDBkey() returns.
	/* static */ function linksccClearBrokenLinksTo( $title ){
		global $wgEnablePersistentLC,$wgIsMySQL;
		if ( $wgEnablePersistentLC ) {
			$title = wfStrencode( $title );
			if ($wgIsMySQL) {
				wfQuery("DELETE linkscc FROM linkscc,brokenlinks ".
					"WHERE lcc_pageid=bl_from AND bl_to='{$title}'", DB_WRITE);
			} else {
				wfQuery("DELETE FROM linkscc WHERE lcc_pageid IN ".
					"(SELECT bl_from FROM brokenlinks ".
					"WHERE bl_to='{$title}')",DB_WRITE);
			}
		}
	}

	# $pid is a page id
	/* static */ function linksccClearPage( $pid ){
		global $wgEnablePersistentLC;
		if ( $wgEnablePersistentLC ) {
			$pid = intval( $pid );
			wfQuery("DELETE FROM linkscc WHERE lcc_pageid='{$pid}'", DB_WRITE);
		}
	}
}
?>
