<?php
/**
 * Page existence cache.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Cache
 */

/**
 * Cache for article titles (prefixed DB keys) and ids linked from one source
 *
 * @ingroup Cache
 */
class LinkCache {
	// Increment $mClassVer whenever old serialized versions of this class
	// becomes incompatible with the new version.
	private $mClassVer = 4;

	private $mGoodLinks = array();
	private $mGoodLinkFields = array();
	private $mBadLinks = array();
	private $mForUpdate = false;

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
	 * Same as above with better interface.
	 * @since 1.19
	 * @param $title Title
	 * @param $row object which has the fields page_id, page_is_redirect,
	 *  page_latest
	 */
	public function addGoodLinkObjFromRow( $title, $row ) {
		$dbkey = $title->getPrefixedDbKey();
		$this->mGoodLinks[$dbkey] = intval( $row->page_id );
		$this->mGoodLinkFields[$dbkey] = array(
			'length' => intval( $row->page_len ),
			'redirect' => intval( $row->page_is_redirect ),
			'revision' => intval( $row->page_latest ),
		);
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
		unset( $this->mBadLinks[$dbkey] );
		unset( $this->mGoodLinks[$dbkey] );
		unset( $this->mGoodLinkFields[$dbkey] );
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
			$this->addGoodLinkObjFromRow( $nt, $s );
			$id = intval( $s->page_id );
		} else {
			$this->addBadLinkObj( $nt );
			$id = 0;
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
