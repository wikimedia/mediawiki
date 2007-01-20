<?php
/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 * @addtogroup Cache
 */

/**
 * @addtogroup Cache
 */
class LinkCache {
	// Increment $mClassVer whenever old serialized versions of this class
	// becomes incompatible with the new version.
	/* private */ var $mClassVer = 3;

	/* private */ var $mPageLinks;
	/* private */ var $mGoodLinks, $mBadLinks;
	/* private */ var $mForUpdate;

	/**
	 * Get an instance of this class
	 */
	static function &singleton() {
		static $instance;
		if ( !isset( $instance ) ) {
			$instance = new LinkCache;
		}
		return $instance;
	}

	function __construct() {
		$this->mForUpdate = false;
		$this->mPageLinks = array();
		$this->mGoodLinks = array();
		$this->mBadLinks = array();
	}

	/* private */ function getKey( $title ) {
		return wfMemcKey( 'lc', 'title', $title );
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
		$dbkey = $title->getPrefixedDbKey();
		$this->mGoodLinks[$dbkey] = $id;
		$this->mPageLinks[$dbkey] = $title;
	}

	function addBadLinkObj( $title ) {
		$dbkey = $title->getPrefixedDbKey();
		if ( ! $this->isBadLink( $dbkey ) ) {
			$this->mBadLinks[$dbkey] = 1;
			$this->mPageLinks[$dbkey] = $title;
		}
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

	function getPageLinks() { return $this->mPageLinks; }
	function getGoodLinks() { return $this->mGoodLinks; }
	function getBadLinks() { return array_keys( $this->mBadLinks ); }

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 * @param $title String: title to add
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
	 * @param $nt Title to add.
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
	 * Clears cache
	 */
	function clear() {
		$this->mPageLinks = array();
		$this->mGoodLinks = array();
		$this->mBadLinks = array();
	}
}
?>
