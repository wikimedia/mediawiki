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
	private $useDatabase = true;

	/**
	 * @var LinkCache
	 */
	protected static $instance;

	/**
	 * Get an instance of this class.
	 *
	 * @return LinkCache
	 */
	static function &singleton() {
		if ( self::$instance ) {
			return self::$instance;
		}
		self::$instance = new LinkCache;
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance, a new one will be created next time
	 * singleton() is called.
	 * @since 1.22
	 */
	static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Set the singleton instance to a given object.
	 * Since we do not have an interface for LinkCache, you have to be sure the
	 * given object implements all the LinkCache public methods.
	 * @param LinkCache $instance
	 * @since 1.22
	 */
	static function setSingleton( LinkCache $instance ) {
		self::$instance = $instance;
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
	 * @param string $field ('length','redirect','revision','model')
	 * @return mixed
	 */
	public function getGoodLinkFieldObj( $title, $field ) {
		$dbkey = $title->getPrefixedDBkey();
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
	 * @param $model Integer: latest revision's content model ID
	 */
	public function addGoodLinkObj( $id, $title, $len = -1, $redir = null, $revision = false, $model = false ) {
		$dbkey = $title->getPrefixedDBkey();
		$this->mGoodLinks[$dbkey] = intval( $id );
		$this->mGoodLinkFields[$dbkey] = array(
			'length' => intval( $len ),
			'redirect' => intval( $redir ),
			'revision' => intval( $revision ),
			'model' => intval( $model ) );
	}

	/**
	 * Same as above with better interface.
	 * @since 1.19
	 * @param $title Title
	 * @param $row object which has the fields page_id, page_is_redirect,
	 *  page_latest and page_content_model
	 */
	public function addGoodLinkObjFromRow( $title, $row ) {
		$dbkey = $title->getPrefixedDBkey();
		$this->mGoodLinks[$dbkey] = intval( $row->page_id );
		$this->mGoodLinkFields[$dbkey] = array(
			'length' => intval( $row->page_len ),
			'redirect' => intval( $row->page_is_redirect ),
			'revision' => intval( $row->page_latest ),
			'model' => !empty( $row->page_content_model ) ? strval( $row->page_content_model ) : null,
		);
	}

	/**
	 * @param $title Title
	 */
	public function addBadLinkObj( $title ) {
		$dbkey = $title->getPrefixedDBkey();
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
		$dbkey = $title->getPrefixedDBkey();
		unset( $this->mBadLinks[$dbkey] );
		unset( $this->mGoodLinks[$dbkey] );
		unset( $this->mGoodLinkFields[$dbkey] );
	}

	public function getGoodLinks() {
		return $this->mGoodLinks;
	}

	public function getBadLinks() {
		return array_keys( $this->mBadLinks );
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param string $title title to add
	 * @return Integer
	 */
	public function addLink( $title ) {
		$nt = Title::newFromDBkey( $title );
		if ( $nt ) {
			return $this->addLinkObj( $nt );
		} else {
			return 0;
		}
	}

	/**
	 * Enable or disable database use.
	 * @since 1.22
	 * @param $value Boolean
	 * @return Boolean
	 */
	public function useDatabase( $value = null ) {
		if ( $value !== null ) {
			$this->useDatabase = (bool)$value;
		}
		return $this->useDatabase;
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param $nt Title object to add
	 * @return Integer
	 */
	public function addLinkObj( $nt ) {
		global $wgAntiLockFlags, $wgContentHandlerUseDB;

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

		if( !$this->useDatabase ) {
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

		$f = array( 'page_id', 'page_len', 'page_is_redirect', 'page_latest' );
		if ( $wgContentHandlerUseDB ) {
			$f[] = 'page_content_model';
		}

		$s = $db->selectRow( 'page', $f,
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
