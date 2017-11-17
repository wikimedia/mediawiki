<?php
/**
 * Value object representing a content page associated with a page revision.
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
 */

namespace MediaWiki\Storage;

use Content;
use LogicException;
use OutOfBoundsException;
use Title;
use Wikimedia\Assert\Assert;

/**
 * Value object representing a content page associated with a page revision.
 * PageRecord provides direct access to a Content object.
 * That access may be implemented through a callback.
 *
 * FIXME: update documentation!
 *
 * @since 1.31
 */
class PageRecord {


	/**
	 * @var Title
	 */
	public $mTitle = null;

	/**@{{
	 * @protected
	 */
	public $mDataLoaded = false;         // !< Boolean
	public $mIsRedirect = false;         // !< Boolean
	public $mLatest = false;             // !< Integer (false means "not loaded")
	/**@}}*/

	/** @var PreparedEdit Map of cache fields (text, parser output, ect) for a proposed/new edit */
	public $mPreparedEdit = false;

	/**
	 * @var int
	 */
	protected $mId = null;

	/**
	 * @var int One of the READ_* constants
	 */
	protected $mDataLoadedFrom = self::READ_NONE;

	/**
	 * @var Title
	 */
	protected $mRedirectTarget = null;

	/**
	 * @var Revision
	 */
	protected $mLastRevision = null;

	/**
	 * @var string Timestamp of the current revision or empty string if not loaded
	 */
	protected $mTimestamp = '';

	/**
	 * @var string
	 */
	protected $mTouched = '19700101000000';

	/**
	 * @var string
	 */
	protected $mLinksUpdated = '19700101000000';

	/**
	 * Constructor and clear the article
	 * @param Title $title Reference to a Title object.
	 */
	public function __construct( Title $title ) {
		$this->mTitle = $title;
	}

	/**
	 * Makes sure that the mTitle object is cloned
	 * to the newly cloned WikiPage.
	 */
	public function __clone() {
		$this->mTitle = clone $this->mTitle;
	}

	/**
	 * Get the title object of the article
	 * @return Title Title object of this page
	 */
	public function getTitle() {
		return $this->mTitle;
	}
	/**
	 * @return int Page ID
	 */
	public function getId() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mId;
	}

	/**
	 * @return bool Whether or not the page exists in the database
	 */
	public function exists() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mId > 0;
	}

	/**
	 * Check if this page is something we're going to be showing
	 * some sort of sensible content for. If we return false, page
	 * views (plain action=view) will return an HTTP 404 response,
	 * so spiders and robots can know they're following a bad link.
	 *
	 * @return bool
	 */
	public function hasViewableContent() {
		return $this->mTitle->isKnown();
	}

	/**
	 * Tests if the article content represents a redirect
	 *
	 * @return bool
	 */
	public function isRedirect() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return (bool)$this->mIsRedirect;
	}

	/**
	 * Returns the page's content model id (see the CONTENT_MODEL_XXX constants).
	 *
	 * Will use the revisions actual content model if the page exists,
	 * and the page's default if the page doesn't exist yet.
	 *
	 * @return string
	 *
	 * @since 1.21
	 */
	public function getContentModel() {
		if ( $this->exists() ) {
			$cache = ObjectCache::getMainWANInstance();

			return $cache->getWithSetCallback(
				$cache->makeKey( 'page-content-model', $this->getLatest() ),
				$cache::TTL_MONTH,
				function () {
					$rev = $this->getRevision();
					if ( $rev ) {
						// Look at the revision's actual content model
						return $rev->getContentModel();
					} else {
						$title = $this->mTitle->getPrefixedDBkey();
						wfWarn( "Page $title exists but has no (visible) revisions!" );
						return $this->mTitle->getContentModel();
					}
				}
			);
		}

		// use the default model for this page
		return $this->mTitle->getContentModel();
	}

	/**
	 * Loads page_touched and returns a value indicating if it should be used
	 * @return bool True if this page exists and is not a redirect
	 */
	public function checkTouched() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return ( $this->mId && !$this->mIsRedirect );
	}

	/**
	 * Get the page_touched field
	 * @return string Containing GMT timestamp
	 */
	public function getTouched() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mTouched;
	}

	/**
	 * Get the page_links_updated field
	 * @return string|null Containing GMT timestamp
	 */
	public function getLinksTimestamp() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mLinksUpdated;
	}

	/**
	 * Get the page_latest field
	 * @return int The rev_id of current revision
	 */
	public function getLatest() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return (int)$this->mLatest;
	}

	/**
	 * Get the latest revision
	 * @return Revision|null
	 */
	public function getRevision() {
		// TODO: callback use RevisionStore::getRevisionByTitle
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision;
		}
		return null;
	}

	/**
	 * @return string MW timestamp of last article revision
	 */
	public function getTimestamp() {
		// Check if the field has been filled by WikiPage::setTimestamp()
		if ( !$this->mTimestamp ) {
			$this->loadLastEdit();
		}

		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * Returns a list of categories this page is a member of.
	 * Results will include hidden categories
	 *
	 * @return TitleArray
	 */
	public function getCategories() {
		$id = $this->getId();
		if ( $id == 0 ) {
			return TitleArray::newFromResult( new FakeResultWrapper( [] ) );
		}

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( 'categorylinks',
			[ 'cl_to AS page_title, ' . NS_CATEGORY . ' AS page_namespace' ],
			// Have to do that since Database::fieldNamesWithAlias treats numeric indexes
			// as not being aliases, and NS_CATEGORY is numeric
			[ 'cl_from' => $id ],
			__METHOD__ );

		return TitleArray::newFromResult( $res );
	}

	/**
	 * Returns a list of hidden categories this page is a member of.
	 * Uses the page_props and categorylinks tables.
	 *
	 * @return array Array of Title objects
	 */
	public function getHiddenCategories() {
		$result = [];
		$id = $this->getId();

		if ( $id == 0 ) {
			return [];
		}

		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select( [ 'categorylinks', 'page_props', 'page' ],
			[ 'cl_to' ],
			[ 'cl_from' => $id, 'pp_page=page_id', 'pp_propname' => 'hiddencat',
			  'page_namespace' => NS_CATEGORY, 'page_title=cl_to' ],
			__METHOD__ );

		if ( $res !== false ) {
			foreach ( $res as $row ) {
				$result[] = Title::makeTitle( NS_CATEGORY, $row->cl_to );
			}
		}

		return $result;
	}

}
