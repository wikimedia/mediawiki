<?
# Cache for article titles and ids linked from one source

# These are used in incrementalSetup()
define ('LINKCACHE_GOOD', 0);
define ('LINKCACHE_BAD', 1);
define ('LINKCACHE_IMAGE', 2);

class LinkCache {

	/* private */ var $mGoodLinks, $mBadLinks, $mActive;
	/* private */ var $mImageLinks; 
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
		return in_array( $title, $this->mBadLinks );
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
			array_push( $this->mBadLinks, $title );
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

	function clearBadLink( $title )
	{
		$index = array_search( $title, $this->mBadLinks );
		if ( isset( $index ) ) {
			unset( $this->mBadLinks[$index] );
		}
		$this->clearLink( $title );
	}
	
	function clearLink( $title ) {
		global $wgMemc;
		$wgMemc->delete( $this->getKey( $title ) );
	}

	function suspend() { $this->mActive = false; }
	function resume() { $this->mActive = true; }
	function getGoodLinks() { return $this->mGoodLinks; }
	function getBadLinks() { return $this->mBadLinks; }
	function getImageLinks() { return $this->mImageLinks; }

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
		$title = $nt->getPrefixedDBkey();
		if ( $this->isBadLink( $title ) ) { return 0; }
		$id = $this->getGoodLinkID( $title );
		if ( 0 != $id ) { return $id; }

		global $wgMemc;
		$fname = "LinkCache::addLinkObj";
		wfProfileIn( $fname );

		$ns = $nt->getNamespace();
		$t = $nt->getDBkey();

		if ( "" == $title ) { 
			wfProfileOut( $fname );
			return 0; 
		}

		$id = $wgMemc->get( $key = $this->getKey( $title ) );
		if( $id === FALSE ) {
			$sql = "SELECT cur_id FROM cur WHERE cur_namespace=" .
			  "{$ns} AND cur_title='" . wfStrencode( $t ) . "'";
			$res = wfQuery( $sql, DB_READ, "LinkCache::addLink" );

			if ( 0 == wfNumRows( $res ) ) {
				$id = 0;
			} else {
				$s = wfFetchObject( $res );
				$id = $s->cur_id;
			}
			$wgMemc->add( $key, $id, time()+3600 );
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
		$dbkeyfrom = wfStrencode( $fromtitle->getPrefixedDBKey() );

		if ( $wgEnablePersistentLC ) {
			$res = wfQuery("SELECT lcc_cacheobj FROM linkscc WHERE lcc_title = '{$dbkeyfrom}'", 
				DB_READ);
			$row = wfFetchObject( $res );
			if( $row != FALSE){
				$cacheobj = gzuncompress( $row->lcc_cacheobj );
				$cc = unserialize( $cacheobj );
				$this->mGoodLinks = $cc->mGoodLinks;
				$this->mBadLinks = $cc->mBadLinks;
				$this->mPreFilled = true;
				wfProfileOut( $fname );
				return;
			} 
		}


		$sql = "SELECT cur_id,cur_namespace,cur_title
			FROM cur,links
			WHERE cur_id=l_to AND l_from='{$dbkeyfrom}'";
		$res = wfQuery( $sql, DB_READ, $fname );
		while( $s = wfFetchObject( $res ) ) {
			$this->addGoodLink( $s->cur_id,
				Title::makeName( $s->cur_namespace, $s->cur_title )
				);
		}
		
		$this->suspend();
		$id = $fromtitle->getArticleID();
		$this->resume();
		
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
			// put fetched link data into cache
			$serCachegz = wfStrencode( gzcompress( serialize( $this ), 3) );
			wfQuery("REPLACE INTO linkscc VALUES({$id}, '{$dbkeyfrom}', '{$serCachegz}')", 
				DB_WRITE);
		}

		wfProfileOut( $fname );
	}

	function getGoodAdditions() 
	{
		return array_diff( $this->mGoodLinks, $this->mOldGoodLinks );
	}

	function getBadAdditions() 
	{
		return array_values( array_diff( $this->mBadLinks, $this->mOldBadLinks ) );
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
		return array_values( array_diff( $this->mOldBadLinks, $this->mBadLinks ) );
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
	
}
?>
