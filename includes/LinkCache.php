<?php
/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 * @package MediaWiki
 * @subpackage Cache
 */

/**
 *
 */
# These are used in incrementalSetup()
define ('LINKCACHE_GOOD', 0);
define ('LINKCACHE_BAD', 1);
define ('LINKCACHE_IMAGE', 2);
define ('LINKCACHE_PAGE', 3);

/**
 * @package MediaWiki
 * @subpackage Cache
 */
class LinkCache {
	// Increment $mClassVer whenever old serialized versions of this class
	// becomes incompatible with the new version.
	/* private */ var $mClassVer = 3;

	/* private */ var $mPageLinks;
	/* private */ var $mGoodLinks, $mBadLinks, $mActive;
	/* private */ var $mImageLinks, $mCategoryLinks;
	/* private */ var $mPreFilled, $mOldGoodLinks, $mOldBadLinks;
	/* private */ var $mForUpdate;

	/* private */ function getKey( $title ) {
		global $wgDBname;
		return $wgDBname.':lc:title:'.$title;
	}

	function LinkCache() {
		$this->mActive = true;
		$this->mPreFilled = false;
		$this->mForUpdate = false;
		$this->mPageLinks = array();
		$this->mGoodLinks = array();
		$this->mBadLinks = array();
		$this->mImageLinks = array();
		$this->mCategoryLinks = array();
		$this->mOldGoodLinks = array();
		$this->mOldBadLinks = array();
		$this->mOldPageLinks = array();
	}

	/**
	 * General accessor to get/set whether SELECT FOR UPDATE should be used
	 */
	function forUpdate( $update = NULL ) {
		return wfSetVar( $this->mForUpdate, $update );
	}

	function getGoodLinkID( $title ) {
		if ( array_key_exists( $title, $this->mGoodLinks ) ) {
			return $this->mGoodLinks[$title];
		} else {
			return 0;
		}
	}

	function isBadLink( $title ) {
		return array_key_exists( $title, $this->mBadLinks );
	}

	function addGoodLinkObj( $id, $title ) {
		if ( $this->mActive ) {
			$dbkey = $title->getPrefixedDbKey();
			$this->mGoodLinks[$dbkey] = $id;
			$this->mPageLinks[$dbkey] = $title;
		}
	}

	function addBadLinkObj( $title ) {
		$dbkey = $title->getPrefixedDbKey();
		if ( $this->mActive && ( ! $this->isBadLink( $dbkey ) ) ) {
			$this->mBadLinks[$dbkey] = 1;
			$this->mPageLinks[$dbkey] = $title;
		}
	}

	function addImageLink( $title ) {
		if ( $this->mActive ) { $this->mImageLinks[$title] = 1; }
	}

	function addImageLinkObj( $nt ) {
		if ( $this->mActive ) { $this->mImageLinks[$nt->getDBkey()] = 1; }
	}

	function addCategoryLink( $title, $sortkey ) {
		if ( $this->mActive ) { $this->mCategoryLinks[$title] = $sortkey; }
	}

	function addCategoryLinkObj( &$nt, $sortkey ) {
		$this->addCategoryLink( $nt->getDBkey(), $sortkey );
	}

	function clearBadLink( $title ) {
		unset( $this->mBadLinks[$title] );
		$this->clearLink( $title );
	}

	function clearLink( $title ) {
		global $wgMemc, $wgLinkCacheMemcached;
		if( $wgLinkCacheMemcached )
			$wgMemc->delete( $this->getKey( $title ) );
	}

	/** @deprecated */
	function suspend() { $this->mActive = false; }
	/** @deprecated */
	function resume() { $this->mActive = true; }

	function getPageLinks() { return $this->mPageLinks; }
	function getGoodLinks() { return $this->mGoodLinks; }
	function getBadLinks() { return array_keys( $this->mBadLinks ); }
	function getImageLinks() { return $this->mImageLinks; }
	function getCategoryLinks() { return $this->mCategoryLinks; }

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 * @param string $title Title to add
	 * @return integer
	 */
	function addLink( $title ) {
		$nt = Title::newFromDBkey( $title );
		if( $nt ) {
			return $this->addLinkObj( $nt );
		} else {
			return 0;
		}
	}
	
	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 * @param Title $nt Title to add
	 * @return integer
	 */
	function addLinkObj( &$nt ) {
		global $wgMemc, $wgLinkCacheMemcached, $wgAntiLockFlags;
		$title = $nt->getPrefixedDBkey();
		if ( $this->isBadLink( $title ) ) { return 0; }
		$id = $this->getGoodLinkID( $title );
		if ( 0 != $id ) { return $id; }

		$fname = 'LinkCache::addLinkObj';
		global $wgProfiling, $wgProfiler;
		if ( $wgProfiling && isset( $wgProfiler ) ) {
			$fname .= ' (' . $wgProfiler->getCurrentSection() . ')';
		}

		wfProfileIn( $fname );

		$ns = $nt->getNamespace();
		$t = $nt->getDBkey();

		if ( '' == $title ) {
			wfProfileOut( $fname );
			return 0;
		}

		$id = NULL;
		if( $wgLinkCacheMemcached )
			$id = $wgMemc->get( $key = $this->getKey( $title ) );
		if( ! is_integer( $id ) ) {
			if ( $this->mForUpdate ) {
				$db =& wfGetDB( DB_MASTER );
				if ( !( $wgAntiLockFlags & ALF_NO_LINK_LOCK ) ) {
					$options = array( 'FOR UPDATE' );
				} else {
					$options = array();
				}
			} else {
				$db =& wfGetDB( DB_SLAVE );
				$options = array();
			}

			$id = $db->selectField( 'page', 'page_id',
					array( 'page_namespace' => $ns, 'page_title' => $t ),
					$fname, $options );
			if ( !$id ) {
				$id = 0;
			}
			if( $wgLinkCacheMemcached )
				$wgMemc->add( $key, $id, 3600*24 );
		}

		if( 0 == $id ) {
			$this->addBadLinkObj( $nt );
		} else {
			$this->addGoodLinkObj( $id, $nt );
		}
		wfProfileOut( $fname );
		return $id;
	}

	/**
	 * Bulk-check the pagelinks and page arrays for existence info.
	 * @param Title $fromtitle
	 * @deprecated
	 */
	function preFill( &$fromtitle ) {
		global $wgAntiLockFlags;
		$fname = 'LinkCache::preFill';
		wfProfileIn( $fname );

		$this->suspend();
		$id = $fromtitle->getArticleID();
		$this->resume();

		if( $id == 0 ) {
			wfDebug( "$fname - got id 0 for title '" . $fromtitle->getPrefixedDBkey() . "'\n" );
			wfProfileOut( $fname );
			return;
		}

		if ( $this->mForUpdate ) {
			$db =& wfGetDB( DB_MASTER );
			if ( !( $wgAntiLockFlags & ALF_NO_LINK_LOCK ) ) {
				$options = 'FOR UPDATE';
			} else {
				$options = '';
			}
		} else {
			$db =& wfGetDB( DB_SLAVE );
			$options = '';
		}

		$page = $db->tableName( 'page' );
		$pagelinks = $db->tableName( 'pagelinks' );

		$sql = "SELECT page_id,pl_namespace,pl_title
			FROM $pagelinks
			LEFT JOIN $page
			ON pl_namespace=page_namespace AND pl_title=page_title
			WHERE pl_from=$id $options";
		$res = $db->query( $sql, $fname );
		while( $s = $db->fetchObject( $res ) ) {
			$title = Title::makeTitle( $s->pl_namespace, $s->pl_title );
			if( $s->page_id ) {
				$this->addGoodLinkObj( $s->page_id, $title );
			} else {
				$this->addBadLinkObj( $title );
			}
		}
		$this->mPreFilled = true;

		wfProfileOut( $fname );
	}

	/**
	 * Clears cache
	 */
	function clear() {
		$this->mPageLinks = array();
		$this->mGoodLinks = array();
		$this->mBadLinks = array();
		$this->mImageLinks = array();
		$this->mCategoryLinks = array();
		$this->mOldGoodLinks = array();
		$this->mOldBadLinks = array();
		$this->mOldPageLinks = array();
	}

	/**
	 * Swaps old and current link registers
	 * @deprecated
	 */
	function swapRegisters() {
		swap( $this->mGoodLinks, $this->mOldGoodLinks );
		swap( $this->mBadLinks, $this->mOldBadLinks );
		swap( $this->mImageLinks, $this->mOldImageLinks );
		swap( $this->mPageLinks, $this->mOldPageLinks );
	}
}
?>
