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
	private $mClassVer = 5;

	private $mGoodLinks = array();
	private $mGoodLinkFields = array();
	private $mBadLinks = array();
	private $mForUpdate = false;
	private $wiki;

	/**
	 * Mapping of wikiid to LinkCache instance
	 *
	 * @var LinkCache[]
	 */
	protected static $instances = array();

	/**
	 * Get an instance of this class.
	 *
	 * @param string|bool $wiki wikiid or false for the current wiki
	 * @return LinkCache
	 */
	static function &singleton( $wiki = false ) {
		if ( $wiki === false ) {
			$wiki = wfWikiID();
		}
		if ( isset( self::$instances[$wiki]) ) {
			return self::$instances[$wiki];
		}
		self::$instances[$wiki] = new LinkCache( $wiki );

		return self::$instances[$wiki];
	}

	public function __construct( $wiki = false ) {
		if ( $wiki === false ) {
			$wiki = wfWikiID();
		}
		$this->wiki = $wiki;
	}

	/**
	 * Destroy the singleton instance, a new one will be created next time
	 * singleton() is called.
	 * @param string|bool $wiki wikiid to destroy the instance for
	 * @since 1.22
	 */
	static function destroySingleton( $wiki = false ) {
		if ( $wiki === false ) {
			$wiki = wfWikiID();
		}
		self::$instances[$wiki] = null;
	}

	/**
	 * Set the singleton instance to a given object.
	 * Since we do not have an interface for LinkCache, you have to be sure the
	 * given object implements all the LinkCache public methods.
	 * @param LinkCache $instance
	 * @param string|bool $wiki wikiid to set the instance for
	 * @since 1.22
	 */
	static function setSingleton( LinkCache $instance, $wiki = false ) {
		if ( $wiki === false ) {
			$wiki = wfWikiID();
		}
		self::$instances[$wiki] = $instance;
	}

	/**
	 * General accessor to get/set whether SELECT FOR UPDATE should be used
	 *
	 * @param bool $update
	 * @return bool
	 */
	public function forUpdate( $update = null ) {
		return wfSetVar( $this->mForUpdate, $update );
	}

	/**
	 * @param TitleValue $title
	 * @return int
	 */
	public function getGoodLinkID( $title ) {
		if ( is_string( $title ) ) {
			// b/c, convert to TitleValue
			$title = Title::newFromDBkey( $title );
			if ( $title ) {
				$title = $title->getTitleValue();
			} else {
				return 0;
			}
		}
		$key = (string)$title;
		if ( array_key_exists( $key, $this->mGoodLinks ) ) {
			return $this->mGoodLinks[$key];
		} else {
			return 0;
		}
	}

	/**
	 * Get a field of a title object from cache.
	 * If this link is not good, it will return NULL.
	 * @param TitleValue $title
	 * @param string $field ('length','redirect','revision','model')
	 * @return string|null
	 */
	public function getGoodLinkFieldObj( $title, $field ) {
		if ( $title instanceof Title ) {
			$title = $title->getTitleValue();
		}
		$key = (string)$title;
		if ( array_key_exists( $key, $this->mGoodLinkFields ) ) {
			return $this->mGoodLinkFields[$key][$field];
		} else {
			return null;
		}
	}

	/**
	 * @param TitleValue $titleValue
	 * @return bool
	 */
	public function isBadLink( $titleValue ) {
		if ( is_string( $titleValue ) ) {
			// Old calling style
			$title = Title::newFromDBkey( $titleValue );
			if ( $title ) {
				$titleValue = $title->getTitleValue();
			} else {
				return false;
			}
		}
		return array_key_exists( (string)$titleValue, $this->mBadLinks );
	}

	/**
	 * Add a link for the title to the link cache
	 *
	 * @param int $id Page's ID
	 * @param Title $title
	 * @param int $len Text's length
	 * @param int $redir Whether the page is a redirect
	 * @param int $revision Latest revision's ID
	 * @param string|null $model Latest revision's content model ID
	 * @deprecated since 1.25 use addGoodLinkObjFromRow instead
	 */
	public function addGoodLinkObj( $id, $title, $len = -1, $redir = null,
		$revision = 0, $model = null
	) {
		$this->addGoodLinkObjFromRow(
			$title->getTitleValue(),
			(object)array(
				'page_id' => (int)$id,
				'page_len' => (int)$len,
				'page_is_redirect' => (int)$redir,
				'page_latest' => (int)$revision,
				'page_content_model' => $model ? (string)$model : null,
			)
		);
	}

	/**
	 * Same as above with better interface.
	 * @since 1.19
	 * @param TitleValue $titleValue
	 * @param stdClass $row Object which has the fields page_id, page_is_redirect,
	 *  page_latest and page_content_model
	 */
	public function addGoodLinkObjFromRow( $titleValue, $row ) {
		if ( $titleValue instanceof Title ) { // b/c
			$titleValue = $titleValue->getTitleValue();
		}
		$this->mGoodLinks[(string)$titleValue] = intval( $row->page_id );
		$this->mGoodLinkFields[(string)$titleValue] = array(
			'length' => intval( $row->page_len ),
			'redirect' => intval( $row->page_is_redirect ),
			'revision' => intval( $row->page_latest ),
			'model' => !empty( $row->page_content_model ) ? strval( $row->page_content_model ) : null,
		);
	}

	/**
	 * @param TitleValue $titleValue
	 */
	public function addBadLinkObj( $titleValue ) {
		if ( $titleValue instanceof Title ) { // b/c
			$titleValue = $titleValue->getTitleValue();
		}
		$key = (string)$titleValue;
		if ( !$this->isBadLink( $titleValue ) ) {
			$this->mBadLinks[$key] = 1;
		}
	}

	/**
	 * @param string $text
	 * @deprecated since 1.25, use clearBadLinkObj instead
	 */
	public function clearBadLink( $text ) {
		$title = Title::newFromDBkey( $text );
		if ( $title ) {
			$this->clearBadLinkObj( $title->getTitleValue() );
		}
	}

	/**
	 * @param TitleValue $titleValue
	 * @since 1.25
	 */
	public function clearBadLinkObj( TitleValue $titleValue ) {
		unset( $this->mBadLinks[(string)$titleValue] );
	}

	/**
	 * @param TitleValue $titleValue
	 */
	public function clearLink( $titleValue ) {
		if ( $titleValue instanceof Title ) {
			$titleValue = $titleValue->getTitleValue();
		}
		$key = (string)$titleValue;
		unset( $this->mBadLinks[$key] );
		unset( $this->mGoodLinks[$key] );
		unset( $this->mGoodLinkFields[$key] );
	}

	/**
	 * @deprecated since 1.25 use the individual methods instead
	 * @return array
	 */
	public function getGoodLinks() {
		return $this->mGoodLinks;
	}

	/**
	 * @deprecated since 1.25, use isBadLink individually instead
	 * @return array
	 */
	public function getBadLinks() {
		return array_keys( $this->mBadLinks );
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param TitleValue $titleValue title to add
	 * @return int
	 */
	public function addLink( $titleValue ) {
		if ( is_string( $titleValue ) ) {
			$nt = Title::newFromDBkey( $titleValue );
			if ( $nt ) {
				$titleValue = $nt->getTitleValue();
			} else {
				return 0;
			}
		}

		return $this->addLinkObj( $titleValue );
	}

	/**
	 * Add a title to the link cache, return the page_id or zero if non-existent
	 *
	 * @param TitleValue $titleValue Object to add
	 * @return int
	 */
	public function addLinkObj( $titleValue ) {
		global $wgContentHandlerUseDB;
		if ( $titleValue instanceof Title ) {
			if ( $titleValue->isExternal() ) {
				return 0;
			}
			$titleValue = $titleValue->getTitleValue();
		}
		if ( $titleValue === null ) {
			// Title::getTitleValue() can return null
			return 0;
		}
		wfProfileIn( __METHOD__ );

		if ( $this->isBadLink( $titleValue ) ) {
			wfProfileOut( __METHOD__ );

			return 0;
		}
		$id = $this->getGoodLinkID( $titleValue );
		if ( $id != 0 ) {
			wfProfileOut( __METHOD__ );

			return $id;
		}

		if ( $titleValue->getDBkey() === '' ) {
			wfProfileOut( __METHOD__ );

			return 0;
		}

		# Some fields heavily used for linking...
		$type = $this->mForUpdate ? DB_MASTER : DB_SLAVE;
		$db = wfGetLB( $this->wiki )->getConnectionRef( $type, array(), $this->wiki );

		$f = array( 'page_id', 'page_len', 'page_is_redirect', 'page_latest' );
		if ( $wgContentHandlerUseDB ) {
			$f[] = 'page_content_model';
		}

		$s = $db->selectRow( 'page', $f,
			array( 'page_namespace' => $titleValue->getNamespace(), 'page_title' => $titleValue->getDBkey() ),
			__METHOD__ );
		# Set fields...
		if ( $s !== false ) {
			$this->addGoodLinkObjFromRow( $titleValue, $s );
			$id = intval( $s->page_id );
		} else {
			$this->addBadLinkObj( $titleValue );
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
