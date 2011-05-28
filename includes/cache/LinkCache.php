<?php
/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 *
 * @ingroup Cache
 */
class LinkCache {
	// Increment $mClassVer whenever old serialized versions of this class
	// becomes incompatible with the new version.
	private $mClassVer = 4;

	private $mGoodLinks, $mBadLinks;
	private $mForUpdate;

	/**
	 * Get an instance of this class
	 *
	 * @return LinkCache
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
		$this->mGoodLinks = array();
		$this->mGoodLinkFields = array();
		$this->mBadLinks = array();
	}

	/**
	 * General accessor to get/set whether SELECT FOR UPDATE should be used
	 *
	 * @return bool
	 */
	public function forUpdate( $update = null ) {
		return wfSetVar( $this->mForUpdate, $update );
	}

	/**
	 * @param $title
	 * @return array|int
	 */
	public function getGoodLinkID( $title ) {
		if ( array_key_exists( $title, $this->mGoodLinks ) ) {
			return $this->mGoodLinks[$title];
		} else {
			return 0;
		}
	}

	/**
	 * Get a field of a title object from cache.
	 * If this link is not good, it will return NULL.
	 * @param $title Title
	 * @param $field String: ('length','redirect','revision')
	 * @return mixed
	 */
	public function getGoodLinkFieldObj( $title, $field ) {
		$dbkey = $title->getPrefixedDbKey();
		if ( array_key_exists( $dbkey, $this->mGoodLinkFields ) ) {
			return $this->mGoodLinkFields[$dbkey][$field];
		} else {
			return null;
		}
	}

	/**
	 * @param $title
	 * @return bool
	 */
	public function isBadLink( $title ) {
		return array_key_exists( $title, $this->mBadLinks );
	}

	/**
	 * Add a link for the title to the link cache
	 *
	 * @param $id Integer: page's ID
	 * @param $title Title object
	 * @param $len Integer: text's length
	 * @param $redir Integer: whether the page is a redirect
	 * @param $revision Integer: latest revision's ID
	 */
	public function addGoodLinkObj( $id, $title, $len = -1, $redir = null, $revision = false ) {
		$dbkey = $title->getPrefixedDbKey();
		$this->mGoodLinks[$dbkey] = intval( $id );
		$this->mGoodLinkFields[$dbkey] = array(
			'length' => intval( $len ),
			'redirect' => intval( $redir ),
			'revision' => intval( $revision ) );
	}

	/**
	 * @param $title Title
	 */
	public function addBadLinkObj( $title ) {
		$dbkey = $title->getPrefixedDbKey();
		if ( !$this->isBadLink( $dbkey ) ) {
			$this->mBadLinks[$dbkey] = 1;
		}
	}

	public function clearBadLink( $title ) {
		unset( $this->mBadLinks[$title] );
	}

	/**
	 * @param $title Title
	 */
	public function clearLink( $title ) {
		$dbkey = $title->getPrefixedDbKey();
		if( isset($this->mBadLinks[$dbkey]) ) {
			unset($this->mBadLinks[$dbkey]);
		}
		if( isset($this->mGoodLinks[$dbkey]) ) {
			unset($this->mGoodLinks[$dbkey]);
		}
		if( isset($this->mGoodLinkFields[$dbkey]) ) {
			unset($this->mGoodLinkFields[$dbkey]);
		}
	}

	public function getGoodLinks() { return $this->mGoodLinks; }
	public function getBadLinks() { return array_keys( $this->mBadLinks ); }

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param $title String: title to add
	 * @return Integer
	 */
	public function addLink( $title ) {
		$nt = Title::newFromDBkey( $title );
		if( $nt ) {
			return $this->addLinkObj( $nt );
		} else {
			return 0;
		}
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param $nt Title object to add
	 * @return Integer
	 */
	public function addLinkObj( $nt ) {
		global $wgAntiLockFlags;
		wfProfileIn( __METHOD__ );

		$key = $nt->getPrefixedDBkey();
		if ( $this->isBadLink( $key ) || $nt->isExternal() ) {
			wfProfileOut( __METHOD__ );
			return 0;
		}
		$id = $this->getGoodLinkID( $key );
		if ( $id != 0 ) {
			wfProfileOut( __METHOD__ );
			return $id;
		}

		if ( $key === '' ) {
			wfProfileOut( __METHOD__ );
			return 0;
		}

		# Some fields heavily used for linking...
		if ( $this->mForUpdate ) {
			$db = wfGetDB( DB_MASTER );
			if ( !( $wgAntiLockFlags & ALF_NO_LINK_LOCK ) ) {
				$options = array( 'FOR UPDATE' );
			} else {
				$options = array();
			}
		} else {
			$db = wfGetDB( DB_SLAVE );
			$options = array();
		}

		$s = $db->selectRow( 'page',
			array( 'page_id', 'page_len', 'page_is_redirect', 'page_latest' ),
			array( 'page_namespace' => $nt->getNamespace(), 'page_title' => $nt->getDBkey() ),
			__METHOD__, $options );
		# Set fields...
		if ( $s !== false ) {
			$id = intval( $s->page_id );
			$len = intval( $s->page_len );
			$redirect = intval( $s->page_is_redirect );
			$revision = intval( $s->page_latest );
		} else {
			$id = 0;
			$len = -1;
			$redirect = 0;
			$revision = 0;
		}

		if ( $id == 0 ) {
			$this->addBadLinkObj( $nt );
		} else {
			$this->addGoodLinkObj( $id, $nt, $len, $redirect, $revision );
		}
		wfProfileOut( __METHOD__ );
		return $id;
	}

	/**
	 * Clears cache
	 */
	public function clear() {
		$this->mGoodLinks = array();
		$this->mGoodLinkFields = array();
		$this->mBadLinks = array();
	}
}
