<?php
/**
 * Base representation for a MediaWiki page.
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

use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\RevisionSlotsUpdate;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Class representing a MediaWiki article and history.
 *
 * Some fields are public only for backwards-compatibility. Use accessors.
 * In the past, this class was part of Article.php and everything was public.
 */
class WikiPage implements Page, IDBAccessObject {
	// Constants for $mDataLoadedFrom and related

	/**
	 * @var Title
	 */
	public $mTitle = null;

	/**
	 * @var bool
	 * @protected
	 */
	public $mDataLoaded = false;

	/**
	 * @var bool
	 * @protected
	 */
	public $mIsRedirect = false;

	/**
	 * @var int|false False means "not loaded"
	 * @protected
	 */
	public $mLatest = false;

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
	 * @var DerivedPageDataUpdater|null
	 */
	private $derivedDataUpdater = null;

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
	 * Create a WikiPage object of the appropriate class for the given title.
	 *
	 * @param Title $title
	 *
	 * @throws MWException
	 * @return WikiPage|WikiCategoryPage|WikiFilePage
	 */
	public static function factory( Title $title ) {
		$ns = $title->getNamespace();

		if ( $ns == NS_MEDIA ) {
			throw new MWException( "NS_MEDIA is a virtual namespace; use NS_FILE." );
		} elseif ( $ns < 0 ) {
			throw new MWException( "Invalid or virtual namespace $ns given." );
		}

		$page = null;
		if ( !Hooks::run( 'WikiPageFactory', [ $title, &$page ] ) ) {
			return $page;
		}

		switch ( $ns ) {
			case NS_FILE:
				$page = new WikiFilePage( $title );
				break;
			case NS_CATEGORY:
				$page = new WikiCategoryPage( $title );
				break;
			default:
				$page = new WikiPage( $title );
		}

		return $page;
	}

	/**
	 * Constructor from a page id
	 *
	 * @param int $id Article ID to load
	 * @param string|int $from One of the following values:
	 *        - "fromdb" or WikiPage::READ_NORMAL to select from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST to select from the master database
	 *
	 * @return WikiPage|null
	 */
	public static function newFromID( $id, $from = 'fromdb' ) {
		// page ids are never 0 or negative, see T63166
		if ( $id < 1 ) {
			return null;
		}

		$from = self::convertSelectType( $from );
		$db = wfGetDB( $from === self::READ_LATEST ? DB_MASTER : DB_REPLICA );
		$pageQuery = self::getQueryInfo();
		$row = $db->selectRow(
			$pageQuery['tables'], $pageQuery['fields'], [ 'page_id' => $id ], __METHOD__,
			[], $pageQuery['joins']
		);
		if ( !$row ) {
			return null;
		}
		return self::newFromRow( $row, $from );
	}

	/**
	 * Constructor from a database row
	 *
	 * @since 1.20
	 * @param object $row Database row containing at least fields returned by selectFields().
	 * @param string|int $from Source of $data:
	 *        - "fromdb" or WikiPage::READ_NORMAL: from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST: from the master DB
	 *        - "forupdate" or WikiPage::READ_LOCKING: from the master DB using SELECT FOR UPDATE
	 * @return WikiPage
	 */
	public static function newFromRow( $row, $from = 'fromdb' ) {
		$page = self::factory( Title::newFromRow( $row ) );
		$page->loadFromRow( $row, $from );
		return $page;
	}

	/**
	 * Convert 'fromdb', 'fromdbmaster' and 'forupdate' to READ_* constants.
	 *
	 * @param object|string|int $type
	 * @return mixed
	 */
	protected static function convertSelectType( $type ) {
		switch ( $type ) {
			case 'fromdb':
				return self::READ_NORMAL;
			case 'fromdbmaster':
				return self::READ_LATEST;
			case 'forupdate':
				return self::READ_LOCKING;
			default:
				// It may already be an integer or whatever else
				return $type;
		}
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		return MediaWikiServices::getInstance()->getRevisionStore();
	}

	/**
	 * @return RevisionRenderer
	 */
	private function getRevisionRenderer() {
		return MediaWikiServices::getInstance()->getRevisionRenderer();
	}

	/**
	 * @return SlotRoleRegistry
	 */
	private function getSlotRoleRegistry() {
		return MediaWikiServices::getInstance()->getSlotRoleRegistry();
	}

	/**
	 * @return ParserCache
	 */
	private function getParserCache() {
		return MediaWikiServices::getInstance()->getParserCache();
	}

	/**
	 * @return LoadBalancer
	 */
	private function getDBLoadBalancer() {
		return MediaWikiServices::getInstance()->getDBLoadBalancer();
	}

	/**
	 * @todo Move this UI stuff somewhere else
	 *
	 * @see ContentHandler::getActionOverrides
	 * @return array
	 */
	public function getActionOverrides() {
		return $this->getContentHandler()->getActionOverrides();
	}

	/**
	 * Returns the ContentHandler instance to be used to deal with the content of this WikiPage.
	 *
	 * Shorthand for ContentHandler::getForModelID( $this->getContentModel() );
	 *
	 * @return ContentHandler
	 *
	 * @since 1.21
	 */
	public function getContentHandler() {
		return ContentHandler::getForModelID( $this->getContentModel() );
	}

	/**
	 * Get the title object of the article
	 * @return Title Title object of this page
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Clear the object
	 * @return void
	 */
	public function clear() {
		$this->mDataLoaded = false;
		$this->mDataLoadedFrom = self::READ_NONE;

		$this->clearCacheFields();
	}

	/**
	 * Clear the object cache fields
	 * @return void
	 */
	protected function clearCacheFields() {
		$this->mId = null;
		$this->mRedirectTarget = null; // Title object if set
		$this->mLastRevision = null; // Latest revision
		$this->mTouched = '19700101000000';
		$this->mLinksUpdated = '19700101000000';
		$this->mTimestamp = '';
		$this->mIsRedirect = false;
		$this->mLatest = false;
		// T59026: do not clear $this->derivedDataUpdater since getDerivedDataUpdater() already
		// checks the requested rev ID and content against the cached one. For most
		// content types, the output should not change during the lifetime of this cache.
		// Clearing it can cause extra parses on edit for no reason.
	}

	/**
	 * Clear the mPreparedEdit cache field, as may be needed by mutable content types
	 * @return void
	 * @since 1.23
	 */
	public function clearPreparedEdit() {
		$this->mPreparedEdit = false;
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new page.
	 *
	 * @deprecated since 1.31, use self::getQueryInfo() instead.
	 * @return array
	 */
	public static function selectFields() {
		global $wgContentHandlerUseDB, $wgPageLanguageUseDB;

		wfDeprecated( __METHOD__, '1.31' );

		$fields = [
			'page_id',
			'page_namespace',
			'page_title',
			'page_restrictions',
			'page_is_redirect',
			'page_is_new',
			'page_random',
			'page_touched',
			'page_links_updated',
			'page_latest',
			'page_len',
		];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'page_content_model';
		}

		if ( $wgPageLanguageUseDB ) {
			$fields[] = 'page_lang';
		}

		return $fields;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new page object.
	 * @since 1.31
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getQueryInfo() {
		global $wgContentHandlerUseDB, $wgPageLanguageUseDB;

		$ret = [
			'tables' => [ 'page' ],
			'fields' => [
				'page_id',
				'page_namespace',
				'page_title',
				'page_restrictions',
				'page_is_redirect',
				'page_is_new',
				'page_random',
				'page_touched',
				'page_links_updated',
				'page_latest',
				'page_len',
			],
			'joins' => [],
		];

		if ( $wgContentHandlerUseDB ) {
			$ret['fields'][] = 'page_content_model';
		}

		if ( $wgPageLanguageUseDB ) {
			$ret['fields'][] = 'page_lang';
		}

		return $ret;
	}

	/**
	 * Fetch a page record with the given conditions
	 * @param IDatabase $dbr
	 * @param array $conditions
	 * @param array $options
	 * @return object|bool Database result resource, or false on failure
	 */
	protected function pageData( $dbr, $conditions, $options = [] ) {
		$pageQuery = self::getQueryInfo();

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		Hooks::run( 'ArticlePageDataBefore', [
			&$wikiPage, &$pageQuery['fields'], &$pageQuery['tables'], &$pageQuery['joins']
		] );

		$row = $dbr->selectRow(
			$pageQuery['tables'],
			$pageQuery['fields'],
			$conditions,
			__METHOD__,
			$options,
			$pageQuery['joins']
		);

		Hooks::run( 'ArticlePageDataAfter', [ &$wikiPage, &$row ] );

		return $row;
	}

	/**
	 * Fetch a page record matching the Title object's namespace and title
	 * using a sanitized title string
	 *
	 * @param IDatabase $dbr
	 * @param Title $title
	 * @param array $options
	 * @return object|bool Database result resource, or false on failure
	 */
	public function pageDataFromTitle( $dbr, $title, $options = [] ) {
		return $this->pageData( $dbr, [
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getDBkey() ], $options );
	}

	/**
	 * Fetch a page record matching the requested ID
	 *
	 * @param IDatabase $dbr
	 * @param int $id
	 * @param array $options
	 * @return object|bool Database result resource, or false on failure
	 */
	public function pageDataFromId( $dbr, $id, $options = [] ) {
		return $this->pageData( $dbr, [ 'page_id' => $id ], $options );
	}

	/**
	 * Load the object from a given source by title
	 *
	 * @param object|string|int $from One of the following:
	 *   - A DB query result object.
	 *   - "fromdb" or WikiPage::READ_NORMAL to get from a replica DB.
	 *   - "fromdbmaster" or WikiPage::READ_LATEST to get from the master DB.
	 *   - "forupdate"  or WikiPage::READ_LOCKING to get from the master DB
	 *     using SELECT FOR UPDATE.
	 *
	 * @return void
	 */
	public function loadPageData( $from = 'fromdb' ) {
		$from = self::convertSelectType( $from );
		if ( is_int( $from ) && $from <= $this->mDataLoadedFrom ) {
			// We already have the data from the correct location, no need to load it twice.
			return;
		}

		if ( is_int( $from ) ) {
			list( $index, $opts ) = DBAccessObjectUtils::getDBOptions( $from );
			$loadBalancer = $this->getDBLoadBalancer();
			$db = $loadBalancer->getConnection( $index );
			$data = $this->pageDataFromTitle( $db, $this->mTitle, $opts );

			if ( !$data
				&& $index == DB_REPLICA
				&& $loadBalancer->getServerCount() > 1
				&& $loadBalancer->hasOrMadeRecentMasterChanges()
			) {
				$from = self::READ_LATEST;
				list( $index, $opts ) = DBAccessObjectUtils::getDBOptions( $from );
				$db = $loadBalancer->getConnection( $index );
				$data = $this->pageDataFromTitle( $db, $this->mTitle, $opts );
			}
		} else {
			// No idea from where the caller got this data, assume replica DB.
			$data = $from;
			$from = self::READ_NORMAL;
		}

		$this->loadFromRow( $data, $from );
	}

	/**
	 * Checks whether the page data was loaded using the given database access mode (or better).
	 *
	 * @since 1.32
	 *
	 * @param string|int $from One of the following:
	 *   - "fromdb" or WikiPage::READ_NORMAL to get from a replica DB.
	 *   - "fromdbmaster" or WikiPage::READ_LATEST to get from the master DB.
	 *   - "forupdate"  or WikiPage::READ_LOCKING to get from the master DB
	 *     using SELECT FOR UPDATE.
	 *
	 * @return bool
	 */
	public function wasLoadedFrom( $from ) {
		$from = self::convertSelectType( $from );

		if ( !is_int( $from ) ) {
			// No idea from where the caller got this data, assume replica DB.
			$from = self::READ_NORMAL;
		}

		if ( is_int( $from ) && $from <= $this->mDataLoadedFrom ) {
			return true;
		}

		return false;
	}

	/**
	 * Load the object from a database row
	 *
	 * @since 1.20
	 * @param object|bool $data DB row containing fields returned by selectFields() or false
	 * @param string|int $from One of the following:
	 *        - "fromdb" or WikiPage::READ_NORMAL if the data comes from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST if the data comes from the master DB
	 *        - "forupdate"  or WikiPage::READ_LOCKING if the data comes from
	 *          the master DB using SELECT FOR UPDATE
	 */
	public function loadFromRow( $data, $from ) {
		$lc = MediaWikiServices::getInstance()->getLinkCache();
		$lc->clearLink( $this->mTitle );

		if ( $data ) {
			$lc->addGoodLinkObjFromRow( $this->mTitle, $data );

			$this->mTitle->loadFromRow( $data );

			// Old-fashioned restrictions
			$this->mTitle->loadRestrictions( $data->page_restrictions );

			$this->mId = intval( $data->page_id );
			$this->mTouched = wfTimestamp( TS_MW, $data->page_touched );
			$this->mLinksUpdated = wfTimestampOrNull( TS_MW, $data->page_links_updated );
			$this->mIsRedirect = intval( $data->page_is_redirect );
			$this->mLatest = intval( $data->page_latest );
			// T39225: $latest may no longer match the cached latest Revision object.
			// Double-check the ID of any cached latest Revision object for consistency.
			if ( $this->mLastRevision && $this->mLastRevision->getId() != $this->mLatest ) {
				$this->mLastRevision = null;
				$this->mTimestamp = '';
			}
		} else {
			$lc->addBadLinkObj( $this->mTitle );

			$this->mTitle->loadFromRow( false );

			$this->clearCacheFields();

			$this->mId = 0;
		}

		$this->mDataLoaded = true;
		$this->mDataLoadedFrom = self::convertSelectType( $from );
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
			$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();

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
	 * Get the Revision object of the oldest revision
	 * @return Revision|null
	 */
	public function getOldestRevision() {
		// Try using the replica DB first, then try the master
		$rev = $this->mTitle->getFirstRevision();
		if ( !$rev ) {
			$rev = $this->mTitle->getFirstRevision( Title::GAID_FOR_UPDATE );
		}
		return $rev;
	}

	/**
	 * Loads everything except the text
	 * This isn't necessary for all uses, so it's only done if needed.
	 */
	protected function loadLastEdit() {
		if ( $this->mLastRevision !== null ) {
			return; // already loaded
		}

		$latest = $this->getLatest();
		if ( !$latest ) {
			return; // page doesn't exist or is missing page_latest info
		}

		if ( $this->mDataLoadedFrom == self::READ_LOCKING ) {
			// T39225: if session S1 loads the page row FOR UPDATE, the result always
			// includes the latest changes committed. This is true even within REPEATABLE-READ
			// transactions, where S1 normally only sees changes committed before the first S1
			// SELECT. Thus we need S1 to also gets the revision row FOR UPDATE; otherwise, it
			// may not find it since a page row UPDATE and revision row INSERT by S2 may have
			// happened after the first S1 SELECT.
			// https://dev.mysql.com/doc/refman/5.0/en/set-transaction.html#isolevel_repeatable-read
			$flags = Revision::READ_LOCKING;
			$revision = Revision::newFromPageId( $this->getId(), $latest, $flags );
		} elseif ( $this->mDataLoadedFrom == self::READ_LATEST ) {
			// Bug T93976: if page_latest was loaded from the master, fetch the
			// revision from there as well, as it may not exist yet on a replica DB.
			// Also, this keeps the queries in the same REPEATABLE-READ snapshot.
			$flags = Revision::READ_LATEST;
			$revision = Revision::newFromPageId( $this->getId(), $latest, $flags );
		} else {
			$dbr = wfGetDB( DB_REPLICA );
			$revision = Revision::newKnownCurrent( $dbr, $this->getTitle(), $latest );
		}

		if ( $revision ) { // sanity
			$this->setLastEdit( $revision );
		}
	}

	/**
	 * Set the latest revision
	 * @param Revision $revision
	 */
	protected function setLastEdit( Revision $revision ) {
		$this->mLastRevision = $revision;
		$this->mTimestamp = $revision->getTimestamp();
	}

	/**
	 * Get the latest revision
	 * @return Revision|null
	 */
	public function getRevision() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision;
		}
		return null;
	}

	/**
	 * Get the latest revision
	 * @return RevisionRecord|null
	 */
	public function getRevisionRecord() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getRevisionRecord();
		}
		return null;
	}

	/**
	 * Get the content of the current revision. No side-effects...
	 *
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return Content|null The content of the current revision
	 *
	 * @since 1.21
	 */
	public function getContent( $audience = Revision::FOR_PUBLIC, User $user = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getContent( $audience, $user );
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
	 * Set the page timestamp (use only to avoid DB queries)
	 * @param string $ts MW timestamp of last article revision
	 * @return void
	 */
	public function setTimestamp( $ts ) {
		$this->mTimestamp = wfTimestamp( TS_MW, $ts );
	}

	/**
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return int User ID for the user that made the last article revision
	 */
	public function getUser( $audience = Revision::FOR_PUBLIC, User $user = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getUser( $audience, $user );
		} else {
			return -1;
		}
	}

	/**
	 * Get the User object of the user who created the page
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return User|null
	 */
	public function getCreator( $audience = Revision::FOR_PUBLIC, User $user = null ) {
		$revision = $this->getOldestRevision();
		if ( $revision ) {
			$userName = $revision->getUserText( $audience, $user );
			return User::newFromName( $userName, false );
		} else {
			return null;
		}
	}

	/**
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string Username of the user that made the last article revision
	 */
	public function getUserText( $audience = Revision::FOR_PUBLIC, User $user = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getUserText( $audience, $user );
		} else {
			return '';
		}
	}

	/**
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User|null $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return string|null Comment stored for the last article revision, or null if the specified
	 *  audience does not have access to the comment.
	 */
	public function getComment( $audience = Revision::FOR_PUBLIC, User $user = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getComment( $audience, $user );
		} else {
			return '';
		}
	}

	/**
	 * Returns true if last revision was marked as "minor edit"
	 *
	 * @return bool Minor edit indicator for the last article revision.
	 */
	public function getMinorEdit() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->isMinor();
		} else {
			return false;
		}
	}

	/**
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @param PreparedEdit|bool $editInfo (false): object returned by prepareTextForEdit(),
	 *   if false, the current database state will be used
	 * @return bool
	 */
	public function isCountable( $editInfo = false ) {
		global $wgArticleCountMethod;

		// NOTE: Keep in sync with DerivedPageDataUpdater::isCountable.

		if ( !$this->mTitle->isContentPage() ) {
			return false;
		}

		if ( $editInfo ) {
			// NOTE: only the main slot can make a page a redirect
			$content = $editInfo->pstContent;
		} else {
			$content = $this->getContent();
		}

		if ( !$content || $content->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $wgArticleCountMethod === 'link' ) {
			// nasty special case to avoid re-parsing to detect links

			if ( $editInfo ) {
				// ParserOutput::getLinks() is a 2D array of page links, so
				// to be really correct we would need to recurse in the array
				// but the main array should only have items in it if there are
				// links.
				$hasLinks = (bool)count( $editInfo->output->getLinks() );
			} else {
				// NOTE: keep in sync with RevisionRenderer::getLinkCount
				// NOTE: keep in sync with DerivedPageDataUpdater::isCountable
				$hasLinks = (bool)wfGetDB( DB_REPLICA )->selectField( 'pagelinks', 1,
					[ 'pl_from' => $this->getId() ], __METHOD__ );
			}
		}

		// TODO: MCR: determine $hasLinks for each slot, and use that info
		// with that slot's Content's isCountable method. That requires per-
		// slot ParserOutput in the ParserCache, or per-slot info in the
		// pagelinks table.
		return $content->isCountable( $hasLinks );
	}

	/**
	 * If this page is a redirect, get its target
	 *
	 * The target will be fetched from the redirect table if possible.
	 * If this page doesn't have an entry there, call insertRedirect()
	 * @return Title|null Title object, or null if this page is not a redirect
	 */
	public function getRedirectTarget() {
		if ( !$this->mTitle->isRedirect() ) {
			return null;
		}

		if ( $this->mRedirectTarget !== null ) {
			return $this->mRedirectTarget;
		}

		// Query the redirect table
		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow( 'redirect',
			[ 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ],
			[ 'rd_from' => $this->getId() ],
			__METHOD__
		);

		// rd_fragment and rd_interwiki were added later, populate them if empty
		if ( $row && !is_null( $row->rd_fragment ) && !is_null( $row->rd_interwiki ) ) {
			// (T203942) We can't redirect to Media namespace because it's virtual.
			// We don't want to modify Title objects farther down the
			// line. So, let's fix this here by changing to File namespace.
			if ( $row->rd_namespace == NS_MEDIA ) {
				$namespace = NS_FILE;
			} else {
				$namespace = $row->rd_namespace;
			}
			$this->mRedirectTarget = Title::makeTitle(
				$namespace, $row->rd_title,
				$row->rd_fragment, $row->rd_interwiki
			);
			return $this->mRedirectTarget;
		}

		// This page doesn't have an entry in the redirect table
		$this->mRedirectTarget = $this->insertRedirect();
		return $this->mRedirectTarget;
	}

	/**
	 * Insert an entry for this page into the redirect table if the content is a redirect
	 *
	 * The database update will be deferred via DeferredUpdates
	 *
	 * Don't call this function directly unless you know what you're doing.
	 * @return Title|null Title object or null if not a redirect
	 */
	public function insertRedirect() {
		$content = $this->getContent();
		$retval = $content ? $content->getUltimateRedirectTarget() : null;
		if ( !$retval ) {
			return null;
		}

		// Update the DB post-send if the page has not cached since now
		$latest = $this->getLatest();
		DeferredUpdates::addCallableUpdate(
			function () use ( $retval, $latest ) {
				$this->insertRedirectEntry( $retval, $latest );
			},
			DeferredUpdates::POSTSEND,
			wfGetDB( DB_MASTER )
		);

		return $retval;
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate it redirects to $rt
	 * @param Title $rt Redirect target
	 * @param int|null $oldLatest Prior page_latest for check and set
	 */
	public function insertRedirectEntry( Title $rt, $oldLatest = null ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		if ( !$oldLatest || $oldLatest == $this->lockAndGetLatest() ) {
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			$truncatedFragment = $contLang->truncateForDatabase( $rt->getFragment(), 255 );
			$dbw->upsert(
				'redirect',
				[
					'rd_from' => $this->getId(),
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $truncatedFragment,
					'rd_interwiki' => $rt->getInterwiki(),
				],
				[ 'rd_from' ],
				[
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $truncatedFragment,
					'rd_interwiki' => $rt->getInterwiki(),
				],
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );
	}

	/**
	 * Get the Title object or URL this page redirects to
	 *
	 * @return bool|Title|string False, Title of in-wiki target, or string with URL
	 */
	public function followRedirect() {
		return $this->getRedirectURL( $this->getRedirectTarget() );
	}

	/**
	 * Get the Title object or URL to use for a redirect. We use Title
	 * objects for same-wiki, non-special redirects and URLs for everything
	 * else.
	 * @param Title $rt Redirect target
	 * @return bool|Title|string False, Title object of local target, or string with URL
	 */
	public function getRedirectURL( $rt ) {
		if ( !$rt ) {
			return false;
		}

		if ( $rt->isExternal() ) {
			if ( $rt->isLocal() ) {
				// Offsite wikis need an HTTP redirect.
				// This can be hard to reverse and may produce loops,
				// so they may be disabled in the site configuration.
				$source = $this->mTitle->getFullURL( 'redirect=no' );
				return $rt->getFullURL( [ 'rdfrom' => $source ] );
			} else {
				// External pages without "local" bit set are not valid
				// redirect targets
				return false;
			}
		}

		if ( $rt->isSpecialPage() ) {
			// Gotta handle redirects to special pages differently:
			// Fill the HTTP response "Location" header and ignore the rest of the page we're on.
			// Some pages are not valid targets.
			if ( $rt->isValidRedirectTarget() ) {
				return $rt->getFullURL();
			} else {
				return false;
			}
		}

		return $rt;
	}

	/**
	 * Get a list of users who have edited this article, not including the user who made
	 * the most recent revision, which you can get from $article->getUser() if you want it
	 * @return UserArrayFromResult
	 */
	public function getContributors() {
		// @todo: This is expensive; cache this info somewhere.

		$dbr = wfGetDB( DB_REPLICA );

		$actorMigration = ActorMigration::newMigration();
		$actorQuery = $actorMigration->getJoin( 'rev_user' );

		$tables = array_merge( [ 'revision' ], $actorQuery['tables'], [ 'user' ] );

		$fields = [
			'user_id' => $actorQuery['fields']['rev_user'],
			'user_name' => $actorQuery['fields']['rev_user_text'],
			'actor_id' => $actorQuery['fields']['rev_actor'],
			'user_real_name' => 'MIN(user_real_name)',
			'timestamp' => 'MAX(rev_timestamp)',
		];

		$conds = [ 'rev_page' => $this->getId() ];

		// The user who made the top revision gets credited as "this page was last edited by
		// John, based on contributions by Tom, Dick and Harry", so don't include them twice.
		$user = $this->getUser()
			? User::newFromId( $this->getUser() )
			: User::newFromName( $this->getUserText(), false );
		$conds[] = 'NOT(' . $actorMigration->getWhere( $dbr, 'rev_user', $user )['conds'] . ')';

		// Username hidden?
		$conds[] = "{$dbr->bitAnd( 'rev_deleted', Revision::DELETED_USER )} = 0";

		$jconds = [
			'user' => [ 'LEFT JOIN', $actorQuery['fields']['rev_user'] . ' = user_id' ],
		] + $actorQuery['joins'];

		$options = [
			'GROUP BY' => [ $fields['user_id'], $fields['user_name'] ],
			'ORDER BY' => 'timestamp DESC',
		];

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $jconds );
		return new UserArrayFromResult( $res );
	}

	/**
	 * Should the parser cache be used?
	 *
	 * @param ParserOptions $parserOptions ParserOptions to check
	 * @param int $oldId
	 * @return bool
	 */
	public function shouldCheckParserCache( ParserOptions $parserOptions, $oldId ) {
		return $parserOptions->getStubThreshold() == 0
			&& $this->exists()
			&& ( $oldId === null || $oldId === 0 || $oldId === $this->getLatest() )
			&& $this->getContentHandler()->isParserCacheSupported();
	}

	/**
	 * Get a ParserOutput for the given ParserOptions and revision ID.
	 *
	 * The parser cache will be used if possible. Cache misses that result
	 * in parser runs are debounced with PoolCounter.
	 *
	 * XXX merge this with updateParserCache()?
	 *
	 * @since 1.19
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse operation
	 * @param null|int $oldid Revision ID to get the text from, passing null or 0 will
	 *   get the current revision (default value)
	 * @param bool $forceParse Force reindexing, regardless of cache settings
	 * @return bool|ParserOutput ParserOutput or false if the revision was not found
	 */
	public function getParserOutput(
		ParserOptions $parserOptions, $oldid = null, $forceParse = false
	) {
		$useParserCache =
			( !$forceParse ) && $this->shouldCheckParserCache( $parserOptions, $oldid );

		if ( $useParserCache && !$parserOptions->isSafeToCache() ) {
			throw new InvalidArgumentException(
				'The supplied ParserOptions are not safe to cache. Fix the options or set $forceParse = true.'
			);
		}

		wfDebug( __METHOD__ .
			': using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $parserOptions->getStubThreshold() ) {
			wfIncrStats( 'pcache.miss.stub' );
		}

		if ( $useParserCache ) {
			$parserOutput = $this->getParserCache()
				->get( $this, $parserOptions );
			if ( $parserOutput !== false ) {
				return $parserOutput;
			}
		}

		if ( $oldid === null || $oldid === 0 ) {
			$oldid = $this->getLatest();
		}

		$pool = new PoolWorkArticleView( $this, $parserOptions, $oldid, $useParserCache );
		$pool->execute();

		return $pool->getParserOutput();
	}

	/**
	 * Do standard deferred updates after page view (existing or missing page)
	 * @param User $user The relevant user
	 * @param int $oldid Revision id being viewed; if not given or 0, latest revision is assumed
	 */
	public function doViewUpdates( User $user, $oldid = 0 ) {
		if ( wfReadOnly() ) {
			return;
		}

		// Update newtalk / watchlist notification status;
		// Avoid outage if the master is not reachable by using a deferred updated
		DeferredUpdates::addCallableUpdate(
			function () use ( $user, $oldid ) {
				Hooks::run( 'PageViewUpdates', [ $this, $user ] );

				$user->clearNotification( $this->mTitle, $oldid );
			},
			DeferredUpdates::PRESEND
		);
	}

	/**
	 * Perform the actions of a page purging
	 * @return bool
	 * @note In 1.28 (and only 1.28), this took a $flags parameter that
	 *  controlled how much purging was done.
	 */
	public function doPurge() {
		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		if ( !Hooks::run( 'ArticlePurge', [ &$wikiPage ] ) ) {
			return false;
		}

		$this->mTitle->invalidateCache();

		// Clear file cache
		HTMLFileCache::clearFileCache( $this->getTitle() );
		// Send purge after above page_touched update was committed
		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( $this->mTitle->getCdnUrls() ),
			DeferredUpdates::PRESEND
		);

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			$messageCache = MessageCache::singleton();
			$messageCache->updateMessageOverride( $this->mTitle, $this->getContent() );
		}

		return true;
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateRevisionOn( ... );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * @todo Factor out into a PageStore service, to be used by PageUpdater.
	 *
	 * @param IDatabase $dbw
	 * @param int|null $pageId Custom page ID that will be used for the insert statement
	 *
	 * @return bool|int The newly created page_id key; false if the row was not
	 *   inserted, e.g. because the title already existed or because the specified
	 *   page ID is already in use.
	 */
	public function insertOn( $dbw, $pageId = null ) {
		$pageIdForInsert = $pageId ? [ 'page_id' => $pageId ] : [];
		$dbw->insert(
			'page',
			[
				'page_namespace'    => $this->mTitle->getNamespace(),
				'page_title'        => $this->mTitle->getDBkey(),
				'page_restrictions' => '',
				'page_is_redirect'  => 0, // Will set this shortly...
				'page_is_new'       => 1,
				'page_random'       => wfRandom(),
				'page_touched'      => $dbw->timestamp(),
				'page_latest'       => 0, // Fill this in shortly...
				'page_len'          => 0, // Fill this in shortly...
			] + $pageIdForInsert,
			__METHOD__,
			'IGNORE'
		);

		if ( $dbw->affectedRows() > 0 ) {
			$newid = $pageId ? (int)$pageId : $dbw->insertId();
			$this->mId = $newid;
			$this->mTitle->resetArticleID( $newid );

			return $newid;
		} else {
			return false; // nothing changed
		}
	}

	/**
	 * Update the page record to point to a newly saved revision.
	 *
	 * @todo Factor out into a PageStore service, or move into PageUpdater.
	 *
	 * @param IDatabase $dbw
	 * @param Revision $revision For ID number, and text used to set
	 *   length and redirect status fields
	 * @param int|null $lastRevision If given, will not overwrite the page field
	 *   when different from the currently set value.
	 *   Giving 0 indicates the new page flag should be set on.
	 * @param bool|null $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return bool Success; false if the page row was missing or page_latest changed
	 */
	public function updateRevisionOn( $dbw, $revision, $lastRevision = null,
		$lastRevIsRedirect = null
	) {
		global $wgContentHandlerUseDB;

		// TODO: move into PageUpdater or PageStore
		// NOTE: when doing that, make sure cached fields get reset in doEditContent,
		// and in the compat stub!

		// Assertion to try to catch T92046
		if ( (int)$revision->getId() === 0 ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': Revision has ID ' . var_export( $revision->getId(), 1 )
			);
		}

		$content = $revision->getContent();
		$len = $content ? $content->getSize() : 0;
		$rt = $content ? $content->getUltimateRedirectTarget() : null;

		$conditions = [ 'page_id' => $this->getId() ];

		if ( !is_null( $lastRevision ) ) {
			// An extra check against threads stepping on each other
			$conditions['page_latest'] = $lastRevision;
		}

		$revId = $revision->getId();
		Assert::parameter( $revId > 0, '$revision->getId()', 'must be > 0' );

		$row = [ /* SET */
			'page_latest'      => $revId,
			'page_touched'     => $dbw->timestamp( $revision->getTimestamp() ),
			'page_is_new'      => ( $lastRevision === 0 ) ? 1 : 0,
			'page_is_redirect' => $rt !== null ? 1 : 0,
			'page_len'         => $len,
		];

		if ( $wgContentHandlerUseDB ) {
			$row['page_content_model'] = $revision->getContentModel();
		}

		$dbw->update( 'page',
			$row,
			$conditions,
			__METHOD__ );

		$result = $dbw->affectedRows() > 0;
		if ( $result ) {
			$this->updateRedirectOn( $dbw, $rt, $lastRevIsRedirect );
			$this->setLastEdit( $revision );
			$this->mLatest = $revision->getId();
			$this->mIsRedirect = (bool)$rt;
			// Update the LinkCache.
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addGoodLinkObj(
				$this->getId(),
				$this->mTitle,
				$len,
				$this->mIsRedirect,
				$this->mLatest,
				$revision->getContentModel()
			);
		}

		return $result;
	}

	/**
	 * Add row to the redirect table if this is a redirect, remove otherwise.
	 *
	 * @param IDatabase $dbw
	 * @param Title|null $redirectTitle Title object pointing to the redirect target,
	 *   or NULL if this is not a redirect
	 * @param null|bool $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return bool True on success, false on failure
	 * @private
	 */
	public function updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = !is_null( $redirectTitle );

		if ( !$isRedirect && $lastRevIsRedirect === false ) {
			return true;
		}

		if ( $isRedirect ) {
			$this->insertRedirectEntry( $redirectTitle );
		} else {
			// This is not a redirect, remove row from redirect table
			$where = [ 'rd_from' => $this->getId() ];
			$dbw->delete( 'redirect', $where, __METHOD__ );
		}

		if ( $this->getTitle()->getNamespace() == NS_FILE ) {
			RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $this->getTitle() );
		}

		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * If the given revision is newer than the currently set page_latest,
	 * update the page record. Otherwise, do nothing.
	 *
	 * @deprecated since 1.24, use updateRevisionOn instead
	 *
	 * @param IDatabase $dbw
	 * @param Revision $revision
	 * @return bool
	 */
	public function updateIfNewerOn( $dbw, $revision ) {
		$row = $dbw->selectRow(
			[ 'revision', 'page' ],
			[ 'rev_id', 'rev_timestamp', 'page_is_redirect' ],
			[
				'page_id' => $this->getId(),
				'page_latest=rev_id' ],
			__METHOD__ );

		if ( $row ) {
			if ( wfTimestamp( TS_MW, $row->rev_timestamp ) >= $revision->getTimestamp() ) {
				return false;
			}
			$prev = $row->rev_id;
			$lastRevIsRedirect = (bool)$row->page_is_redirect;
		} else {
			// No or missing previous revision; mark the page as new
			$prev = 0;
			$lastRevIsRedirect = null;
		}

		$ret = $this->updateRevisionOn( $dbw, $revision, $prev, $lastRevIsRedirect );

		return $ret;
	}

	/**
	 * Helper method for checking whether two revisions have differences that go
	 * beyond the main slot.
	 *
	 * MCR migration note: this method should go away!
	 *
	 * @deprecated Use only as a stop-gap before refactoring to support MCR.
	 *
	 * @param Revision $a
	 * @param Revision $b
	 * @return bool
	 */
	public static function hasDifferencesOutsideMainSlot( Revision $a, Revision $b ) {
		$aSlots = $a->getRevisionRecord()->getSlots();
		$bSlots = $b->getRevisionRecord()->getSlots();
		$changedRoles = $aSlots->getRolesWithDifferentContent( $bSlots );

		return ( $changedRoles !== [ SlotRecord::MAIN ] && $changedRoles !== [] );
	}

	/**
	 * Get the content that needs to be saved in order to undo all revisions
	 * between $undo and $undoafter. Revisions must belong to the same page,
	 * must exist and must not be deleted
	 *
	 * @param Revision $undo
	 * @param Revision $undoafter Must be an earlier revision than $undo
	 * @return Content|bool Content on success, false on failure
	 * @since 1.21
	 * Before we had the Content object, this was done in getUndoText
	 */
	public function getUndoContent( Revision $undo, Revision $undoafter ) {
		// TODO: MCR: replace this with a method that returns a RevisionSlotsUpdate

		if ( self::hasDifferencesOutsideMainSlot( $undo, $undoafter ) ) {
			// Cannot yet undo edits that involve anything other the main slot.
			return false;
		}

		$handler = $undo->getContentHandler();
		return $handler->getUndoContent( $this->getRevision(), $undo, $undoafter );
	}

	/**
	 * Returns true if this page's content model supports sections.
	 *
	 * @return bool
	 *
	 * @todo The skin should check this and not offer section functionality if
	 *   sections are not supported.
	 * @todo The EditPage should check this and not offer section functionality
	 *   if sections are not supported.
	 */
	public function supportsSections() {
		return $this->getContentHandler()->supportsSections();
	}

	/**
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param string $edittime Revision timestamp or null to use the current revision.
	 *
	 * @throws MWException
	 * @return Content|null New complete article content, or null if error.
	 *
	 * @since 1.21
	 * @deprecated since 1.24, use replaceSectionAtRev instead
	 */
	public function replaceSectionContent(
		$sectionId, Content $sectionContent, $sectionTitle = '', $edittime = null
	) {
		$baseRevId = null;
		if ( $edittime && $sectionId !== 'new' ) {
			$lb = $this->getDBLoadBalancer();
			$dbr = $lb->getConnection( DB_REPLICA );
			$rev = Revision::loadFromTimestamp( $dbr, $this->mTitle, $edittime );
			// Try the master if this thread may have just added it.
			// This could be abstracted into a Revision method, but we don't want
			// to encourage loading of revisions by timestamp.
			if ( !$rev
				&& $lb->getServerCount() > 1
				&& $lb->hasOrMadeRecentMasterChanges()
			) {
				$dbw = $lb->getConnection( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );
			}
			if ( $rev ) {
				$baseRevId = $rev->getId();
			}
		}

		return $this->replaceSectionAtRev( $sectionId, $sectionContent, $sectionTitle, $baseRevId );
	}

	/**
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param int|null $baseRevId
	 *
	 * @throws MWException
	 * @return Content|null New complete article content, or null if error.
	 *
	 * @since 1.24
	 */
	public function replaceSectionAtRev( $sectionId, Content $sectionContent,
		$sectionTitle = '', $baseRevId = null
	) {
		if ( strval( $sectionId ) === '' ) {
			// Whole-page edit; let the whole text through
			$newContent = $sectionContent;
		} else {
			if ( !$this->supportsSections() ) {
				throw new MWException( "sections not supported for content model " .
					$this->getContentHandler()->getModelID() );
			}

			// T32711: always use current version when adding a new section
			if ( is_null( $baseRevId ) || $sectionId === 'new' ) {
				$oldContent = $this->getContent();
			} else {
				$rev = Revision::newFromId( $baseRevId );
				if ( !$rev ) {
					wfDebug( __METHOD__ . " asked for bogus section (page: " .
						$this->getId() . "; section: $sectionId)\n" );
					return null;
				}

				$oldContent = $rev->getContent();
			}

			if ( !$oldContent ) {
				wfDebug( __METHOD__ . ": no page text\n" );
				return null;
			}

			$newContent = $oldContent->replaceSection( $sectionId, $sectionContent, $sectionTitle );
		}

		return $newContent;
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 *
	 * @deprecated since 1.32, use exists() instead, or simply omit the EDIT_UPDATE
	 * and EDIT_NEW flags. To protect against race conditions, use PageUpdater::grabParentRevision.
	 *
	 * @param int $flags
	 * @return int Updated $flags
	 */
	public function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->exists() ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		return $flags;
	}

	/**
	 * @return DerivedPageDataUpdater
	 */
	private function newDerivedDataUpdater() {
		global $wgRCWatchCategoryMembership, $wgArticleCountMethod;

		$derivedDataUpdater = new DerivedPageDataUpdater(
			$this, // NOTE: eventually, PageUpdater should not know about WikiPage
			$this->getRevisionStore(),
			$this->getRevisionRenderer(),
			$this->getSlotRoleRegistry(),
			$this->getParserCache(),
			JobQueueGroup::singleton(),
			MessageCache::singleton(),
			MediaWikiServices::getInstance()->getContentLanguage(),
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
		);

		$derivedDataUpdater->setRcWatchCategoryMembership( $wgRCWatchCategoryMembership );
		$derivedDataUpdater->setArticleCountMethod( $wgArticleCountMethod );

		return $derivedDataUpdater;
	}

	/**
	 * Returns a DerivedPageDataUpdater for use with the given target revision or new content.
	 * This method attempts to re-use the same DerivedPageDataUpdater instance for subsequent calls.
	 * The parameters passed to this method are used to ensure that the DerivedPageDataUpdater
	 * returned matches that caller's expectations, allowing an existing instance to be re-used
	 * if the given parameters match that instance's internal state according to
	 * DerivedPageDataUpdater::isReusableFor(), and creating a new instance of the parameters do not
	 * match the existign one.
	 *
	 * If neither $forRevision nor $forUpdate is given, a new DerivedPageDataUpdater is always
	 * created, replacing any DerivedPageDataUpdater currently cached.
	 *
	 * MCR migration note: this replaces WikiPage::prepareContentForEdit.
	 *
	 * @since 1.32
	 *
	 * @param User|null $forUser The user that will be used for, or was used for, PST.
	 * @param RevisionRecord|null $forRevision The revision created by the edit for which
	 *        to perform updates, if the edit was already saved.
	 * @param RevisionSlotsUpdate|null $forUpdate The new content to be saved by the edit (pre PST),
	 *        if the edit was not yet saved.
	 * @param bool $forEdit Only re-use if the cached DerivedPageDataUpdater has the current
	 *       revision as the edit's parent revision. This ensures that the same
	 *       DerivedPageDataUpdater cannot be re-used for two consecutive edits.
	 *
	 * @return DerivedPageDataUpdater
	 */
	private function getDerivedDataUpdater(
		User $forUser = null,
		RevisionRecord $forRevision = null,
		RevisionSlotsUpdate $forUpdate = null,
		$forEdit = false
	) {
		if ( !$forRevision && !$forUpdate ) {
			// NOTE: can't re-use an existing derivedDataUpdater if we don't know what the caller is
			// going to use it with.
			$this->derivedDataUpdater = null;
		}

		if ( $this->derivedDataUpdater && !$this->derivedDataUpdater->isContentPrepared() ) {
			// NOTE: can't re-use an existing derivedDataUpdater if other code that has a reference
			// to it did not yet initialize it, because we don't know what data it will be
			// initialized with.
			$this->derivedDataUpdater = null;
		}

		// XXX: It would be nice to have an LRU cache instead of trying to re-use a single instance.
		// However, there is no good way to construct a cache key. We'd need to check against all
		// cached instances.

		if ( $this->derivedDataUpdater
			&& !$this->derivedDataUpdater->isReusableFor(
				$forUser,
				$forRevision,
				$forUpdate,
				$forEdit ? $this->getLatest() : null
			)
		) {
			$this->derivedDataUpdater = null;
		}

		if ( !$this->derivedDataUpdater ) {
			$this->derivedDataUpdater = $this->newDerivedDataUpdater();
		}

		return $this->derivedDataUpdater;
	}

	/**
	 * Returns a PageUpdater for creating new revisions on this page (or creating the page).
	 *
	 * The PageUpdater can also be used to detect the need for edit conflict resolution,
	 * and to protected such conflict resolution from concurrent edits using a check-and-set
	 * mechanism.
	 *
	 * @since 1.32
	 *
	 * @param User $user
	 * @param RevisionSlotsUpdate|null $forUpdate If given, allows any cached ParserOutput
	 *        that may already have been returned via getDerivedDataUpdater to be re-used.
	 *
	 * @return PageUpdater
	 */
	public function newPageUpdater( User $user, RevisionSlotsUpdate $forUpdate = null ) {
		global $wgAjaxEditStash, $wgUseAutomaticEditSummaries, $wgPageCreationLog;

		$pageUpdater = new PageUpdater(
			$user,
			$this, // NOTE: eventually, PageUpdater should not know about WikiPage
			$this->getDerivedDataUpdater( $user, null, $forUpdate, true ),
			$this->getDBLoadBalancer(),
			$this->getRevisionStore(),
			$this->getSlotRoleRegistry()
		);

		$pageUpdater->setUsePageCreationLog( $wgPageCreationLog );
		$pageUpdater->setAjaxEditStash( $wgAjaxEditStash );
		$pageUpdater->setUseAutomaticEditSummaries( $wgUseAutomaticEditSummaries );

		return $pageUpdater;
	}

	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * @deprecated since 1.32, use PageUpdater::saveRevision instead. Note that the new method
	 * expects callers to take care of checking EDIT_MINOR against the minoredit right, and to
	 * apply the autopatrol right as appropriate.
	 *
	 * @param Content $content New content
	 * @param string|CommentStoreComment $summary Edit summary
	 * @param int $flags Bitfield:
	 *      EDIT_NEW
	 *          Article is known or assumed to be non-existent, create a new one
	 *      EDIT_UPDATE
	 *          Article is known or assumed to be pre-existing, update it
	 *      EDIT_MINOR
	 *          Mark this edit minor, if the user is allowed to do so
	 *      EDIT_SUPPRESS_RC
	 *          Do not log the change in recentchanges
	 *      EDIT_FORCE_BOT
	 *          Mark the edit a "bot" edit regardless of user rights
	 *      EDIT_AUTOSUMMARY
	 *          Fill in blank summaries with generated text where possible
	 *      EDIT_INTERNAL
	 *          Signal that the page retrieve/save cycle happened entirely in this request.
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the
	 * article will be detected. If EDIT_UPDATE is specified and the article
	 * doesn't exist, the function will return an edit-gone-missing error. If
	 * EDIT_NEW is specified and the article does exist, an edit-already-exists
	 * error will be returned. These two conditions are also possible with
	 * auto-detection due to MediaWiki's performance-optimised locking strategy.
	 *
	 * @param bool|int $originalRevId: The ID of an original revision that the edit
	 * restores or repeats. The new revision is expected to have the exact same content as
	 * the given original revision. This is used with rollbacks and with dummy "null" revisions
	 * which are created to record things like page moves.
	 * @param User|null $user The user doing the edit
	 * @param string|null $serialFormat IGNORED.
	 * @param array|null $tags Change tags to apply to this edit
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 * @param Int $undidRevId Id of revision that was undone or 0
	 *
	 * @throws MWException
	 * @return Status Possible errors:
	 *     edit-hook-aborted: The ArticleSave hook aborted the edit but didn't
	 *       set the fatal flag of $status.
	 *     edit-gone-missing: In update mode, but the article didn't exist.
	 *     edit-conflict: In update mode, the article changed unexpectedly.
	 *     edit-no-change: Warning that the text was the same as before.
	 *     edit-already-exists: In creation mode, but the article already exists.
	 *
	 *  Extensions may define additional errors.
	 *
	 *  $return->value will contain an associative array with members as follows:
	 *     new: Boolean indicating if the function attempted to create a new article.
	 *     revision: The revision object for the inserted revision, or null.
	 *
	 * @since 1.21
	 * @throws MWException
	 */
	public function doEditContent(
		Content $content, $summary, $flags = 0, $originalRevId = false,
		User $user = null, $serialFormat = null, $tags = [], $undidRevId = 0
	) {
		global $wgUser, $wgUseNPPatrol, $wgUseRCPatrol;

		if ( !( $summary instanceof CommentStoreComment ) ) {
			$summary = CommentStoreComment::newUnsavedComment( trim( $summary ) );
		}

		if ( !$user ) {
			$user = $wgUser;
		}

		// TODO: this check is here for backwards-compatibility with 1.31 behavior.
		// Checking the minoredit right should be done in the same place the 'bot' right is
		// checked for the EDIT_FORCE_BOT flag, which is currently in EditPage::attemptSave.
		if ( ( $flags & EDIT_MINOR ) && !$user->isAllowed( 'minoredit' ) ) {
			$flags = ( $flags & ~EDIT_MINOR );
		}

		$slotsUpdate = new RevisionSlotsUpdate();
		$slotsUpdate->modifyContent( SlotRecord::MAIN, $content );

		// NOTE: while doEditContent() executes, callbacks to getDerivedDataUpdater and
		// prepareContentForEdit will generally use the DerivedPageDataUpdater that is also
		// used by this PageUpdater. However, there is no guarantee for this.
		$updater = $this->newPageUpdater( $user, $slotsUpdate );
		$updater->setContent( SlotRecord::MAIN, $content );
		$updater->setOriginalRevisionId( $originalRevId );
		$updater->setUndidRevisionId( $undidRevId );

		$needsPatrol = $wgUseRCPatrol || ( $wgUseNPPatrol && !$this->exists() );

		// TODO: this logic should not be in the storage layer, it's here for compatibility
		// with 1.31 behavior. Applying the 'autopatrol' right should be done in the same
		// place the 'bot' right is handled, which is currently in EditPage::attemptSave.
		if ( $needsPatrol && $this->getTitle()->userCan( 'autopatrol', $user ) ) {
			$updater->setRcPatrolStatus( RecentChange::PRC_AUTOPATROLLED );
		}

		$updater->addTags( $tags );

		$revRec = $updater->saveRevision(
			$summary,
			$flags
		);

		// $revRec will be null if the edit failed, or if no new revision was created because
		// the content did not change.
		if ( $revRec ) {
			// update cached fields
			// TODO: this is currently redundant to what is done in updateRevisionOn.
			// But updateRevisionOn() should move into PageStore, and then this will be needed.
			$this->setLastEdit( new Revision( $revRec ) ); // TODO: use RevisionRecord
			$this->mLatest = $revRec->getId();
		}

		return $updater->getStatus();
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 *
	 * @see ParserOptions::newCanonical
	 *
	 * @param IContextSource|User|string $context One of the following:
	 *        - IContextSource: Use the User and the Language of the provided
	 *          context
	 *        - User: Use the provided User object and $wgLang for the language,
	 *          so use an IContextSource object if possible.
	 *        - 'canonical': Canonical options (anonymous user with default
	 *          preferences and content language).
	 * @return ParserOptions
	 */
	public function makeParserOptions( $context ) {
		$options = ParserOptions::newCanonical( $context );

		if ( $this->getTitle()->isConversionTable() ) {
			// @todo ConversionTable should become a separate content model, so
			// we don't need special cases like this one.
			$options->disableContentConversion();
		}

		return $options;
	}

	/**
	 * Prepare content which is about to be saved.
	 *
	 * Prior to 1.30, this returned a stdClass.
	 *
	 * @deprecated since 1.32, use getDerivedDataUpdater instead.
	 *
	 * @param Content $content
	 * @param Revision|RevisionRecord|int|null $revision Revision object.
	 *        For backwards compatibility, a revision ID is also accepted,
	 *        but this is deprecated.
	 *        Used with vary-revision or vary-revision-id.
	 * @param User|null $user
	 * @param string|null $serialFormat IGNORED
	 * @param bool $useCache Check shared prepared edit cache
	 *
	 * @return PreparedEdit
	 *
	 * @since 1.21
	 */
	public function prepareContentForEdit(
		Content $content,
		$revision = null,
		User $user = null,
		$serialFormat = null,
		$useCache = true
	) {
		global $wgUser;

		if ( !$user ) {
			$user = $wgUser;
		}

		if ( !is_object( $revision ) ) {
			$revid = $revision;
			// This code path is deprecated, and nothing is known to
			// use it, so performance here shouldn't be a worry.
			if ( $revid !== null ) {
				wfDeprecated( __METHOD__ . ' with $revision = revision ID', '1.25' );
				$store = $this->getRevisionStore();
				$revision = $store->getRevisionById( $revid, Revision::READ_LATEST );
			} else {
				$revision = null;
			}
		} elseif ( $revision instanceof Revision ) {
			$revision = $revision->getRevisionRecord();
		}

		$slots = RevisionSlotsUpdate::newFromContent( [ SlotRecord::MAIN => $content ] );
		$updater = $this->getDerivedDataUpdater( $user, $revision, $slots );

		if ( !$updater->isUpdatePrepared() ) {
			$updater->prepareContent( $user, $slots, $useCache );

			if ( $revision ) {
				$updater->prepareUpdate(
					$revision,
					[
						'causeAction' => 'prepare-edit',
						'causeAgent' => $user->getName(),
					]
				);
			}
		}

		return $updater->getPreparedEdit();
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @deprecated since 1.32, use PageUpdater::doUpdates instead.
	 *
	 * @param Revision $revision
	 * @param User $user User object that did the revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
	 * - oldcountable: bool, null, or string 'no-change' (default null):
	 *   - bool: whether the page was counted as an article before that
	 *     revision, only used in changed is true and created is false
	 *   - null: if created is false, don't update the article count; if created
	 *     is true, do update the article count
	 *   - 'no-change': don't update the article count, ever
	 *  - causeAction: an arbitrary string identifying the reason for the update.
	 *    See DataUpdate::getCauseAction(). (default 'edit-page')
	 *  - causeAgent: name of the user who caused the update. See DataUpdate::getCauseAgent().
	 *    (string, defaults to the passed user)
	 */
	public function doEditUpdates( Revision $revision, User $user, array $options = [] ) {
		$options += [
			'causeAction' => 'edit-page',
			'causeAgent' => $user->getName(),
		];

		$revision = $revision->getRevisionRecord();

		$updater = $this->getDerivedDataUpdater( $user, $revision );

		$updater->prepareUpdate( $revision, $options );

		$updater->doUpdates();
	}

	/**
	 * Update the parser cache.
	 *
	 * @note This is a temporary workaround until there is a proper data updater class.
	 *   It will become deprecated soon.
	 *
	 * @param array $options
	 *   - causeAction: an arbitrary string identifying the reason for the update.
	 *     See DataUpdate::getCauseAction(). (default 'edit-page')
	 *   - causeAgent: name of the user who caused the update (string, defaults to the
	 *     user who created the revision)
	 * @since 1.32
	 */
	public function updateParserCache( array $options = [] ) {
		$revision = $this->getRevisionRecord();
		if ( !$revision || !$revision->getId() ) {
			LoggerFactory::getInstance( 'wikipage' )->info(
				__METHOD__ . 'called with ' . ( $revision ? 'unsaved' : 'no' ) . ' revision'
			);
			return;
		}
		$user = User::newFromIdentity( $revision->getUser( RevisionRecord::RAW ) );

		$updater = $this->getDerivedDataUpdater( $user, $revision );
		$updater->prepareUpdate( $revision, $options );
		$updater->doParserCacheUpdate();
	}

	/**
	 * Do secondary data updates (such as updating link tables).
	 * Secondary data updates are only a small part of the updates needed after saving
	 * a new revision; normally PageUpdater::doUpdates should be used instead (which includes
	 * secondary data updates). This method is provided for partial purges.
	 *
	 * @note This is a temporary workaround until there is a proper data updater class.
	 *   It will become deprecated soon.
	 *
	 * @param array $options
	 *   - recursive (bool, default true): whether to do a recursive update (update pages that
	 *     depend on this page, e.g. transclude it). This will set the $recursive parameter of
	 *     Content::getSecondaryDataUpdates. Typically this should be true unless the update
	 *     was something that did not really change the page, such as a null edit.
	 *   - triggeringUser: The user triggering the update (UserIdentity, defaults to the
	 *     user who created the revision)
	 *   - causeAction: an arbitrary string identifying the reason for the update.
	 *     See DataUpdate::getCauseAction(). (default 'unknown')
	 *   - causeAgent: name of the user who caused the update (string, default 'unknown')
	 *   - defer: one of the DeferredUpdates constants, or false to run immediately (default: false).
	 *     Note that even when this is set to false, some updates might still get deferred (as
	 *     some update might directly add child updates to DeferredUpdates).
	 *   - transactionTicket: a transaction ticket from LBFactory::getEmptyTransactionTicket(),
	 *     only when defer is false (default: null)
	 * @since 1.32
	 */
	public function doSecondaryDataUpdates( array $options = [] ) {
		$options['recursive'] = $options['recursive'] ?? true;
		$revision = $this->getRevisionRecord();
		if ( !$revision || !$revision->getId() ) {
			LoggerFactory::getInstance( 'wikipage' )->info(
				__METHOD__ . 'called with ' . ( $revision ? 'unsaved' : 'no' ) . ' revision'
			);
			return;
		}
		$user = User::newFromIdentity( $revision->getUser( RevisionRecord::RAW ) );

		$updater = $this->getDerivedDataUpdater( $user, $revision );
		$updater->prepareUpdate( $revision, $options );
		$updater->doSecondaryDataUpdates( $options );
	}

	/**
	 * Update the article's restriction field, and leave a log entry.
	 * This works for protection both existing and non-existing pages.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param int &$cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param User $user The user updating the restrictions
	 * @param string|string[]|null $tags Change tags to add to the pages and protection log entries
	 *   ($user should be able to add the specified tags before this is called)
	 * @return Status Status object; if action is taken, $status->value is the log_id of the
	 *   protection log entry.
	 */
	public function doUpdateRestrictions( array $limit, array $expiry,
		&$cascade, $reason, User $user, $tags = null
	) {
		global $wgCascadingRestrictionLevels;

		if ( wfReadOnly() ) {
			return Status::newFatal( wfMessage( 'readonlytext', wfReadOnlyReason() ) );
		}

		$this->loadPageData( 'fromdbmaster' );
		$this->mTitle->loadRestrictions( null, Title::READ_LATEST );
		$restrictionTypes = $this->mTitle->getRestrictionTypes();
		$id = $this->getId();

		if ( !$cascade ) {
			$cascade = false;
		}

		// Take this opportunity to purge out expired restrictions
		Title::purgeExpiredRestrictions();

		// @todo: Same limitations as described in ProtectionForm.php (line 37);
		// we expect a single selection, but the schema allows otherwise.
		$isProtected = false;
		$protect = false;
		$changed = false;

		$dbw = wfGetDB( DB_MASTER );

		foreach ( $restrictionTypes as $action ) {
			if ( !isset( $expiry[$action] ) || $expiry[$action] === $dbw->getInfinity() ) {
				$expiry[$action] = 'infinity';
			}
			if ( !isset( $limit[$action] ) ) {
				$limit[$action] = '';
			} elseif ( $limit[$action] != '' ) {
				$protect = true;
			}

			// Get current restrictions on $action
			$current = implode( '', $this->mTitle->getRestrictions( $action ) );
			if ( $current != '' ) {
				$isProtected = true;
			}

			if ( $limit[$action] != $current ) {
				$changed = true;
			} elseif ( $limit[$action] != '' ) {
				// Only check expiry change if the action is actually being
				// protected, since expiry does nothing on an not-protected
				// action.
				if ( $this->mTitle->getRestrictionExpiry( $action ) != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		if ( !$changed && $protect && $this->mTitle->areRestrictionsCascading() != $cascade ) {
			$changed = true;
		}

		// If nothing has changed, do nothing
		if ( !$changed ) {
			return Status::newGood();
		}

		if ( !$protect ) { // No protection at all means unprotection
			$revCommentMsg = 'unprotectedarticle-comment';
			$logAction = 'unprotect';
		} elseif ( $isProtected ) {
			$revCommentMsg = 'modifiedarticleprotection-comment';
			$logAction = 'modify';
		} else {
			$revCommentMsg = 'protectedarticle-comment';
			$logAction = 'protect';
		}

		$logRelationsValues = [];
		$logRelationsField = null;
		$logParamsDetails = [];

		// Null revision (used for change tag insertion)
		$nullRevision = null;

		if ( $id ) { // Protection of existing page
			// Avoid PHP 7.1 warning of passing $this by reference
			$wikiPage = $this;

			if ( !Hooks::run( 'ArticleProtect', [ &$wikiPage, &$user, $limit, $reason ] ) ) {
				return Status::newGood();
			}

			// Only certain restrictions can cascade...
			$editrestriction = isset( $limit['edit'] )
				? [ $limit['edit'] ]
				: $this->mTitle->getRestrictions( 'edit' );
			foreach ( array_keys( $editrestriction, 'sysop' ) as $key ) {
				$editrestriction[$key] = 'editprotected'; // backwards compatibility
			}
			foreach ( array_keys( $editrestriction, 'autoconfirmed' ) as $key ) {
				$editrestriction[$key] = 'editsemiprotected'; // backwards compatibility
			}

			$cascadingRestrictionLevels = $wgCascadingRestrictionLevels;
			foreach ( array_keys( $cascadingRestrictionLevels, 'sysop' ) as $key ) {
				$cascadingRestrictionLevels[$key] = 'editprotected'; // backwards compatibility
			}
			foreach ( array_keys( $cascadingRestrictionLevels, 'autoconfirmed' ) as $key ) {
				$cascadingRestrictionLevels[$key] = 'editsemiprotected'; // backwards compatibility
			}

			// The schema allows multiple restrictions
			if ( !array_intersect( $editrestriction, $cascadingRestrictionLevels ) ) {
				$cascade = false;
			}

			// insert null revision to identify the page protection change as edit summary
			$latest = $this->getLatest();
			$nullRevision = $this->insertProtectNullRevision(
				$revCommentMsg,
				$limit,
				$expiry,
				$cascade,
				$reason,
				$user
			);

			if ( $nullRevision === null ) {
				return Status::newFatal( 'no-null-revision', $this->mTitle->getPrefixedText() );
			}

			$logRelationsField = 'pr_id';

			// Update restrictions table
			foreach ( $limit as $action => $restrictions ) {
				$dbw->delete(
					'page_restrictions',
					[
						'pr_page' => $id,
						'pr_type' => $action
					],
					__METHOD__
				);
				if ( $restrictions != '' ) {
					$cascadeValue = ( $cascade && $action == 'edit' ) ? 1 : 0;
					$dbw->insert(
						'page_restrictions',
						[
							'pr_page' => $id,
							'pr_type' => $action,
							'pr_level' => $restrictions,
							'pr_cascade' => $cascadeValue,
							'pr_expiry' => $dbw->encodeExpiry( $expiry[$action] )
						],
						__METHOD__
					);
					$logRelationsValues[] = $dbw->insertId();
					$logParamsDetails[] = [
						'type' => $action,
						'level' => $restrictions,
						'expiry' => $expiry[$action],
						'cascade' => (bool)$cascadeValue,
					];
				}
			}

			// Clear out legacy restriction fields
			$dbw->update(
				'page',
				[ 'page_restrictions' => '' ],
				[ 'page_id' => $id ],
				__METHOD__
			);

			// Avoid PHP 7.1 warning of passing $this by reference
			$wikiPage = $this;

			Hooks::run( 'NewRevisionFromEditComplete',
				[ $this, $nullRevision, $latest, $user ] );
			Hooks::run( 'ArticleProtectComplete', [ &$wikiPage, &$user, $limit, $reason ] );
		} else { // Protection of non-existing page (also known as "title protection")
			// Cascade protection is meaningless in this case
			$cascade = false;

			if ( $limit['create'] != '' ) {
				$commentFields = CommentStore::getStore()->insert( $dbw, 'pt_reason', $reason );
				$dbw->replace( 'protected_titles',
					[ [ 'pt_namespace', 'pt_title' ] ],
					[
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey(),
						'pt_create_perm' => $limit['create'],
						'pt_timestamp' => $dbw->timestamp(),
						'pt_expiry' => $dbw->encodeExpiry( $expiry['create'] ),
						'pt_user' => $user->getId(),
					] + $commentFields, __METHOD__
				);
				$logParamsDetails[] = [
					'type' => 'create',
					'level' => $limit['create'],
					'expiry' => $expiry['create'],
				];
			} else {
				$dbw->delete( 'protected_titles',
					[
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey()
					], __METHOD__
				);
			}
		}

		$this->mTitle->flushRestrictions();
		InfoAction::invalidateCache( $this->mTitle );

		if ( $logAction == 'unprotect' ) {
			$params = [];
		} else {
			$protectDescriptionLog = $this->protectDescriptionLog( $limit, $expiry );
			$params = [
				'4::description' => $protectDescriptionLog, // parameter for IRC
				'5:bool:cascade' => $cascade,
				'details' => $logParamsDetails, // parameter for localize and api
			];
		}

		// Update the protection log
		$logEntry = new ManualLogEntry( 'protect', $logAction );
		$logEntry->setTarget( $this->mTitle );
		$logEntry->setComment( $reason );
		$logEntry->setPerformer( $user );
		$logEntry->setParameters( $params );
		if ( !is_null( $nullRevision ) ) {
			$logEntry->setAssociatedRevId( $nullRevision->getId() );
		}
		$logEntry->setTags( $tags );
		if ( $logRelationsField !== null && count( $logRelationsValues ) ) {
			$logEntry->setRelations( [ $logRelationsField => $logRelationsValues ] );
		}
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		return Status::newGood( $logId );
	}

	/**
	 * Insert a new null revision for this page.
	 *
	 * @param string $revCommentMsg Comment message key for the revision
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param int $cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param User|null $user
	 * @return Revision|null Null on error
	 */
	public function insertProtectNullRevision( $revCommentMsg, array $limit,
		array $expiry, $cascade, $reason, $user = null
	) {
		$dbw = wfGetDB( DB_MASTER );

		// Prepare a null revision to be added to the history
		$editComment = wfMessage(
			$revCommentMsg,
			$this->mTitle->getPrefixedText(),
			$user ? $user->getName() : ''
		)->inContentLanguage()->text();
		if ( $reason ) {
			$editComment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
		}
		$protectDescription = $this->protectDescription( $limit, $expiry );
		if ( $protectDescription ) {
			$editComment .= wfMessage( 'word-separator' )->inContentLanguage()->text();
			$editComment .= wfMessage( 'parentheses' )->params( $protectDescription )
				->inContentLanguage()->text();
		}
		if ( $cascade ) {
			$editComment .= wfMessage( 'word-separator' )->inContentLanguage()->text();
			$editComment .= wfMessage( 'brackets' )->params(
				wfMessage( 'protect-summary-cascade' )->inContentLanguage()->text()
			)->inContentLanguage()->text();
		}

		$nullRev = Revision::newNullRevision( $dbw, $this->getId(), $editComment, true, $user );
		if ( $nullRev ) {
			$nullRev->insertOn( $dbw );

			// Update page record and touch page
			$oldLatest = $nullRev->getParentId();
			$this->updateRevisionOn( $dbw, $nullRev, $oldLatest );
		}

		return $nullRev;
	}

	/**
	 * @param string $expiry 14-char timestamp or "infinity", or false if the input was invalid
	 * @return string
	 */
	protected function formatExpiry( $expiry ) {
		if ( $expiry != 'infinity' ) {
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			return wfMessage(
				'protect-expiring',
				$contLang->timeanddate( $expiry, false, false ),
				$contLang->date( $expiry, false, false ),
				$contLang->time( $expiry, false, false )
			)->inContentLanguage()->text();
		} else {
			return wfMessage( 'protect-expiry-indefinite' )
				->inContentLanguage()->text();
		}
	}

	/**
	 * Builds the description to serve as comment for the edit.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @return string
	 */
	public function protectDescription( array $limit, array $expiry ) {
		$protectDescription = '';

		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			# $action is one of $wgRestrictionTypes = [ 'create', 'edit', 'move', 'upload' ].
			# All possible message keys are listed here for easier grepping:
			# * restriction-create
			# * restriction-edit
			# * restriction-move
			# * restriction-upload
			$actionText = wfMessage( 'restriction-' . $action )->inContentLanguage()->text();
			# $restrictions is one of $wgRestrictionLevels = [ '', 'autoconfirmed', 'sysop' ],
			# with '' filtered out. All possible message keys are listed below:
			# * protect-level-autoconfirmed
			# * protect-level-sysop
			$restrictionsText = wfMessage( 'protect-level-' . $restrictions )
				->inContentLanguage()->text();

			$expiryText = $this->formatExpiry( $expiry[$action] );

			if ( $protectDescription !== '' ) {
				$protectDescription .= wfMessage( 'word-separator' )->inContentLanguage()->text();
			}
			$protectDescription .= wfMessage( 'protect-summary-desc' )
				->params( $actionText, $restrictionsText, $expiryText )
				->inContentLanguage()->text();
		}

		return $protectDescription;
	}

	/**
	 * Builds the description to serve as comment for the log entry.
	 *
	 * Some bots may parse IRC lines, which are generated from log entries which contain plain
	 * protect description text. Keep them in old format to avoid breaking compatibility.
	 * TODO: Fix protection log to store structured description and format it on-the-fly.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @return string
	 */
	public function protectDescriptionLog( array $limit, array $expiry ) {
		$protectDescriptionLog = '';

		$dirMark = MediaWikiServices::getInstance()->getContentLanguage()->getDirMark();
		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			$expiryText = $this->formatExpiry( $expiry[$action] );
			$protectDescriptionLog .=
				$dirMark .
				"[$action=$restrictions] ($expiryText)";
		}

		return trim( $protectDescriptionLog );
	}

	/**
	 * Take an array of page restrictions and flatten it to a string
	 * suitable for insertion into the page_restrictions field.
	 *
	 * @param string[] $limit
	 *
	 * @throws MWException
	 * @return string
	 */
	protected static function flattenRestrictions( $limit ) {
		if ( !is_array( $limit ) ) {
			throw new MWException( __METHOD__ . ' given non-array restriction set' );
		}

		$bits = [];
		ksort( $limit );

		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			$bits[] = "$action=$restrictions";
		}

		return implode( ':', $bits );
	}

	/**
	 * Determines if deletion of this page would be batched (executed over time by the job queue)
	 * or not (completed in the same request as the delete call).
	 *
	 * It is unlikely but possible that an edit from another request could push the page over the
	 * batching threshold after this function is called, but before the caller acts upon the
	 * return value.  Callers must decide for themselves how to deal with this.  $safetyMargin
	 * is provided as an unreliable but situationally useful help for some common cases.
	 *
	 * @param int $safetyMargin Added to the revision count when checking for batching
	 * @return bool True if deletion would be batched, false otherwise
	 */
	public function isBatchedDelete( $safetyMargin = 0 ) {
		global $wgDeleteRevisionsBatchSize;

		$dbr = wfGetDB( DB_REPLICA );
		$revCount = $this->getRevisionStore()->countRevisionsByPageId( $dbr, $this->getId() );
		$revCount += $safetyMargin;

		return $revCount >= $wgDeleteRevisionsBatchSize;
	}

	/**
	 * Same as doDeleteArticleReal(), but returns a simple boolean. This is kept around for
	 * backwards compatibility, if you care about error reporting you should use
	 * doDeleteArticleReal() instead.
	 *
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param string $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *        the suppression log instead of the deletion log
	 * @param int|null $u1 Unused
	 * @param bool|null $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User|null $user The deleting user
	 * @param bool $immediate false allows deleting over time via the job queue
	 * @return bool True if successful
	 * @throws FatalError
	 * @throws MWException
	 */
	public function doDeleteArticle(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $user = null,
		$immediate = false
	) {
		$status = $this->doDeleteArticleReal( $reason, $suppress, $u1, $u2, $error, $user,
			[], 'delete', $immediate );

		// Returns true if the page was actually deleted, or is scheduled for deletion
		return $status->isOK();
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @since 1.19
	 *
	 * @param string $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *   the suppression log instead of the deletion log
	 * @param int|null $u1 Unused
	 * @param bool|null $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User|null $deleter The deleting user
	 * @param array $tags Tags to apply to the deletion action
	 * @param string $logsubtype
	 * @param bool $immediate false allows deleting over time via the job queue
	 * @return Status Status object; if successful, $status->value is the log_id of the
	 *   deletion log entry. If the page couldn't be deleted because it wasn't
	 *   found, $status is a non-fatal 'cannotdelete' error
	 * @throws FatalError
	 * @throws MWException
	 */
	public function doDeleteArticleReal(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $deleter = null,
		$tags = [], $logsubtype = 'delete', $immediate = false
	) {
		global $wgUser;

		wfDebug( __METHOD__ . "\n" );

		$status = Status::newGood();

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		if ( !$deleter ) {
			$deleter = $wgUser;
		}
		if ( !Hooks::run( 'ArticleDelete',
			[ &$wikiPage, &$deleter, &$reason, &$error, &$status, $suppress ]
		) ) {
			if ( $status->isOK() ) {
				// Hook aborted but didn't set a fatal status
				$status->fatal( 'delete-hook-aborted' );
			}
			return $status;
		}

		return $this->doDeleteArticleBatched( $reason, $suppress, $deleter, $tags,
			$logsubtype, $immediate );
	}

	/**
	 * Back-end article deletion
	 *
	 * Only invokes batching via the job queue if necessary per $wgDeleteRevisionsBatchSize.
	 * Deletions can often be completed inline without involving the job queue.
	 *
	 * Potentially called many times per deletion operation for pages with many revisions.
	 */
	public function doDeleteArticleBatched(
		$reason, $suppress, User $deleter, $tags,
		$logsubtype, $immediate = false, $webRequestId = null
	) {
		wfDebug( __METHOD__ . "\n" );

		$status = Status::newGood();

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		$this->loadPageData( self::READ_LATEST );
		$id = $this->getId();
		// T98706: lock the page from various other updates but avoid using
		// WikiPage::READ_LOCKING as that will carry over the FOR UPDATE to
		// the revisions queries (which also JOIN on user). Only lock the page
		// row and CAS check on page_latest to see if the trx snapshot matches.
		$lockedLatest = $this->lockAndGetLatest();
		if ( $id == 0 || $this->getLatest() != $lockedLatest ) {
			$dbw->endAtomic( __METHOD__ );
			// Page not there or trx snapshot is stale
			$status->error( 'cannotdelete',
				wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) );
			return $status;
		}

		// At this point we are now committed to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// we need to remember the old content so we can use it to generate all deletion updates.
		$revision = $this->getRevision();
		try {
			$content = $this->getContent( Revision::RAW );
		} catch ( Exception $ex ) {
			wfLogWarning( __METHOD__ . ': failed to load content during deletion! '
				. $ex->getMessage() );

			$content = null;
		}

		// Archive revisions.  In immediate mode, archive all revisions.  Otherwise, archive
		// one batch of revisions and defer archival of any others to the job queue.
		$explictTrxLogged = false;
		while ( true ) {
			$done = $this->archiveRevisions( $dbw, $id, $suppress );
			if ( $done || !$immediate ) {
				break;
			}
			$dbw->endAtomic( __METHOD__ );
			if ( $dbw->explicitTrxActive() ) {
				// Explict transactions may never happen here in practice.  Log to be sure.
				if ( !$explictTrxLogged ) {
					$explictTrxLogged = true;
					LoggerFactory::getInstance( 'wfDebug' )->debug(
						'explicit transaction active in ' . __METHOD__ . ' while deleting {title}', [
						'title' => $this->getTitle()->getText(),
					] );
				}
				continue;
			}
			if ( $dbw->trxLevel() ) {
				$dbw->commit();
			}
			$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
			$lbFactory->waitForReplication();
			$dbw->startAtomic( __METHOD__ );
		}

		// If done archiving, also delete the article.
		if ( !$done ) {
			$dbw->endAtomic( __METHOD__ );

			$jobParams = [
				'wikiPageId' => $id,
				'requestId' => $webRequestId ?? WebRequest::getRequestId(),
				'reason' => $reason,
				'suppress' => $suppress,
				'userId' => $deleter->getId(),
				'tags' => json_encode( $tags ),
				'logsubtype' => $logsubtype,
			];

			$job = new DeletePageJob( $this->getTitle(), $jobParams );
			JobQueueGroup::singleton()->push( $job );

			$status->warning( 'delete-scheduled',
				wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) );
		} else {
			// Get archivedRevisionCount by db query, because there's no better alternative.
			// Jobs cannot pass a count of archived revisions to the next job, because additional
			// deletion operations can be started while the first is running.  Jobs from each
			// gracefully interleave, but would not know about each other's count.  Deduplication
			// in the job queue to avoid simultaneous deletion operations would add overhead.
			// Number of archived revisions cannot be known beforehand, because edits can be made
			// while deletion operations are being processed, changing the number of archivals.
			$archivedRevisionCount = (int)$dbw->selectField(
				'archive', 'COUNT(*)',
				[
					'ar_namespace' => $this->getTitle()->getNamespace(),
					'ar_title' => $this->getTitle()->getDBkey(),
					'ar_page_id' => $id
				], __METHOD__
			);

			// Clone the title and wikiPage, so we have the information we need when
			// we log and run the ArticleDeleteComplete hook.
			$logTitle = clone $this->mTitle;
			$wikiPageBeforeDelete = clone $this;

			// Now that it's safely backed up, delete it
			$dbw->delete( 'page', [ 'page_id' => $id ], __METHOD__ );

			// Log the deletion, if the page was suppressed, put it in the suppression log instead
			$logtype = $suppress ? 'suppress' : 'delete';

			$logEntry = new ManualLogEntry( $logtype, $logsubtype );
			$logEntry->setPerformer( $deleter );
			$logEntry->setTarget( $logTitle );
			$logEntry->setComment( $reason );
			$logEntry->setTags( $tags );
			$logid = $logEntry->insert();

			$dbw->onTransactionPreCommitOrIdle(
				function () use ( $logEntry, $logid ) {
					// T58776: avoid deadlocks (especially from FileDeleteForm)
					$logEntry->publish( $logid );
				},
				__METHOD__
			);

			$dbw->endAtomic( __METHOD__ );

			$this->doDeleteUpdates( $id, $content, $revision, $deleter );

			Hooks::run( 'ArticleDeleteComplete', [
				&$wikiPageBeforeDelete,
				&$deleter,
				$reason,
				$id,
				$content,
				$logEntry,
				$archivedRevisionCount
			] );
			$status->value = $logid;

			// Show log excerpt on 404 pages rather than just a link
			$cache = MediaWikiServices::getInstance()->getMainObjectStash();
			$key = $cache->makeKey( 'page-recent-delete', md5( $logTitle->getPrefixedText() ) );
			$cache->set( $key, 1, $cache::TTL_DAY );
		}

		return $status;
	}

	/**
	 * Archives revisions as part of page deletion.
	 *
	 * @param IDatabase $dbw
	 * @param int $id
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *   the suppression log instead of the deletion log
	 * @return bool
	 */
	protected function archiveRevisions( $dbw, $id, $suppress ) {
		global $wgContentHandlerUseDB, $wgMultiContentRevisionSchemaMigrationStage,
			$wgActorTableSchemaMigrationStage, $wgDeleteRevisionsBatchSize;

		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $this->getTitle()->getNamespace();
		$dbKey = $this->getTitle()->getDBkey();

		$commentStore = CommentStore::getStore();
		$actorMigration = ActorMigration::newMigration();

		$revQuery = Revision::getQueryInfo();
		$bitfield = false;

		// Bitfields to further suppress the content
		if ( $suppress ) {
			$bitfield = Revision::SUPPRESSED_ALL;
			$revQuery['fields'] = array_diff( $revQuery['fields'], [ 'rev_deleted' ] );
		}

		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.

		// Lock rows in `revision` and its temp tables, but not any others.
		// Note array_intersect() preserves keys from the first arg, and we're
		// assuming $revQuery has `revision` primary and isn't using subtables
		// for anything we care about.
		$dbw->lockForUpdate(
			array_intersect(
				$revQuery['tables'],
				[ 'revision', 'revision_comment_temp', 'revision_actor_temp' ]
			),
			[ 'rev_page' => $id ],
			__METHOD__,
			[],
			$revQuery['joins']
		);

		// If SCHEMA_COMPAT_WRITE_OLD is set, also select all extra fields we still write,
		// so we can copy it to the archive table.
		// We know the fields exist, otherwise SCHEMA_COMPAT_WRITE_OLD could not function.
		if ( $wgMultiContentRevisionSchemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) {
			$revQuery['fields'][] = 'rev_text_id';

			if ( $wgContentHandlerUseDB ) {
				$revQuery['fields'][] = 'rev_content_model';
				$revQuery['fields'][] = 'rev_content_format';
			}
		}

		// Get as many of the page revisions as we are allowed to.  The +1 lets us recognize the
		// unusual case where there were exactly $wgDeleteRevisionBatchSize revisions remaining.
		$res = $dbw->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_page' => $id ],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC', 'LIMIT' => $wgDeleteRevisionsBatchSize + 1 ],
			$revQuery['joins']
		);

		// Build their equivalent archive rows
		$rowsInsert = [];
		$revids = [];

		/** @var int[] Revision IDs of edits that were made by IPs */
		$ipRevIds = [];

		$done = true;
		foreach ( $res as $row ) {
			if ( count( $revids ) >= $wgDeleteRevisionsBatchSize ) {
				$done = false;
				break;
			}

			$comment = $commentStore->getComment( 'rev_comment', $row );
			$user = User::newFromAnyId( $row->rev_user, $row->rev_user_text, $row->rev_actor );
			$rowInsert = [
					'ar_namespace'  => $namespace,
					'ar_title'      => $dbKey,
					'ar_timestamp'  => $row->rev_timestamp,
					'ar_minor_edit' => $row->rev_minor_edit,
					'ar_rev_id'     => $row->rev_id,
					'ar_parent_id'  => $row->rev_parent_id,
					/**
					 * ar_text_id should probably not be written to when the multi content schema has
					 * been migrated to (wgMultiContentRevisionSchemaMigrationStage) however there is no
					 * default for the field in WMF production currently so we must keep writing
					 * writing until a default of 0 is set.
					 * Task: https://phabricator.wikimedia.org/T190148
					 * Copying the value from the revision table should not lead to any issues for now.
					 */
					'ar_len'        => $row->rev_len,
					'ar_page_id'    => $id,
					'ar_deleted'    => $suppress ? $bitfield : $row->rev_deleted,
					'ar_sha1'       => $row->rev_sha1,
				] + $commentStore->insert( $dbw, 'ar_comment', $comment )
				+ $actorMigration->getInsertValues( $dbw, 'ar_user', $user );

			if ( $wgMultiContentRevisionSchemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) {
				$rowInsert['ar_text_id'] = $row->rev_text_id;

				if ( $wgContentHandlerUseDB ) {
					$rowInsert['ar_content_model'] = $row->rev_content_model;
					$rowInsert['ar_content_format'] = $row->rev_content_format;
				}
			}

			$rowsInsert[] = $rowInsert;
			$revids[] = $row->rev_id;

			// Keep track of IP edits, so that the corresponding rows can
			// be deleted in the ip_changes table.
			if ( (int)$row->rev_user === 0 && IP::isValid( $row->rev_user_text ) ) {
				$ipRevIds[] = $row->rev_id;
			}
		}

		// This conditional is just a sanity check
		if ( count( $revids ) > 0 ) {
			// Copy them into the archive table
			$dbw->insert( 'archive', $rowsInsert, __METHOD__ );

			$dbw->delete( 'revision', [ 'rev_id' => $revids ], __METHOD__ );
			$dbw->delete( 'revision_comment_temp', [ 'revcomment_rev' => $revids ], __METHOD__ );
			if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
				$dbw->delete( 'revision_actor_temp', [ 'revactor_rev' => $revids ], __METHOD__ );
			}

			// Also delete records from ip_changes as applicable.
			if ( count( $ipRevIds ) > 0 ) {
				$dbw->delete( 'ip_changes', [ 'ipc_rev_id' => $ipRevIds ], __METHOD__ );
			}
		}

		return $done;
	}

	/**
	 * Lock the page row for this title+id and return page_latest (or 0)
	 *
	 * @return int Returns 0 if no row was found with this title+id
	 * @since 1.27
	 */
	public function lockAndGetLatest() {
		return (int)wfGetDB( DB_MASTER )->selectField(
			'page',
			'page_latest',
			[
				'page_id' => $this->getId(),
				// Typically page_id is enough, but some code might try to do
				// updates assuming the title is the same, so verify that
				'page_namespace' => $this->getTitle()->getNamespace(),
				'page_title' => $this->getTitle()->getDBkey()
			],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);
	}

	/**
	 * Do some database updates after deletion
	 *
	 * @param int $id The page_id value of the page being deleted
	 * @param Content|null $content Page content to be used when determining
	 *   the required updates. This may be needed because $this->getContent()
	 *   may already return null when the page proper was deleted.
	 * @param Revision|null $revision The current page revision at the time of
	 *   deletion, used when determining the required updates. This may be needed because
	 *   $this->getRevision() may already return null when the page proper was deleted.
	 * @param User|null $user The user that caused the deletion
	 */
	public function doDeleteUpdates(
		$id, Content $content = null, Revision $revision = null, User $user = null
	) {
		if ( $id !== $this->getId() ) {
			throw new InvalidArgumentException( 'Mismatching page ID' );
		}

		try {
			$countable = $this->isCountable();
		} catch ( Exception $ex ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => 1, 'articles' => -$countable, 'pages' => -1 ]
		) );

		// Delete pagelinks, update secondary indexes, etc
		$updates = $this->getDeletionUpdates(
			$revision ? $revision->getRevisionRecord() : $content
		);
		foreach ( $updates as $update ) {
			DeferredUpdates::addUpdate( $update );
		}

		$causeAgent = $user ? $user->getName() : 'unknown';
		// Reparse any pages transcluding this page
		LinksUpdate::queueRecursiveJobsForTable(
			$this->mTitle, 'templatelinks', 'delete-page', $causeAgent );
		// Reparse any pages including this image
		if ( $this->mTitle->getNamespace() == NS_FILE ) {
			LinksUpdate::queueRecursiveJobsForTable(
				$this->mTitle, 'imagelinks', 'delete-page', $causeAgent );
		}

		// Clear caches
		self::onArticleDelete( $this->mTitle );
		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->mTitle,
			$revision,
			null,
			WikiMap::getCurrentWikiDbDomain()->getId()
		);

		// Reset this object and the Title object
		$this->loadFromRow( false, self::READ_LATEST );

		// Search engine
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $this->mTitle ) );
	}

	/**
	 * Roll back the most recent consecutive set of edits to a page
	 * from the same user; fails if there are no eligible edits to
	 * roll back to, e.g. user is the sole contributor. This function
	 * performs permissions checks on $user, then calls commitRollback()
	 * to do the dirty work
	 *
	 * @todo Separate the business/permission stuff out from backend code
	 * @todo Remove $token parameter. Already verified by RollbackAction and ApiRollback.
	 *
	 * @param string $fromP Name of the user whose edits to rollback.
	 * @param string $summary Custom summary. Set to default summary if empty.
	 * @param string $token Rollback token.
	 * @param bool $bot If true, mark all reverted edits as bot.
	 *
	 * @param array &$resultDetails Array contains result-specific array of additional values
	 *    'alreadyrolled' : 'current' (rev)
	 *    success        : 'summary' (str), 'current' (rev), 'target' (rev)
	 *
	 * @param User $user The user performing the rollback
	 * @param array|null $tags Change tags to apply to the rollback
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 *
	 * @return array Array of errors, each error formatted as
	 *   array(messagekey, param1, param2, ...).
	 * On success, the array is empty.  This array can also be passed to
	 * OutputPage::showPermissionsErrorPage().
	 */
	public function doRollback(
		$fromP, $summary, $token, $bot, &$resultDetails, User $user, $tags = null
	) {
		$resultDetails = null;

		// Check permissions
		$editErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $user );
		$rollbackErrors = $this->mTitle->getUserPermissionsErrors( 'rollback', $user );
		$errors = array_merge( $editErrors, wfArrayDiff2( $rollbackErrors, $editErrors ) );

		if ( !$user->matchEditToken( $token, 'rollback' ) ) {
			$errors[] = [ 'sessionfailure' ];
		}

		if ( $user->pingLimiter( 'rollback' ) || $user->pingLimiter() ) {
			$errors[] = [ 'actionthrottledtext' ];
		}

		// If there were errors, bail out now
		if ( !empty( $errors ) ) {
			return $errors;
		}

		return $this->commitRollback( $fromP, $summary, $bot, $resultDetails, $user, $tags );
	}

	/**
	 * Backend implementation of doRollback(), please refer there for parameter
	 * and return value documentation
	 *
	 * NOTE: This function does NOT check ANY permissions, it just commits the
	 * rollback to the DB. Therefore, you should only call this function direct-
	 * ly if you want to use custom permissions checks. If you don't, use
	 * doRollback() instead.
	 * @param string $fromP Name of the user whose edits to rollback.
	 * @param string $summary Custom summary. Set to default summary if empty.
	 * @param bool $bot If true, mark all reverted edits as bot.
	 *
	 * @param array &$resultDetails Contains result-specific array of additional values
	 * @param User $guser The user performing the rollback
	 * @param array|null $tags Change tags to apply to the rollback
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 *
	 * @return array An array of error messages, as returned by Status::getErrorsArray()
	 */
	public function commitRollback( $fromP, $summary, $bot,
		&$resultDetails, User $guser, $tags = null
	) {
		global $wgUseRCPatrol;

		$dbw = wfGetDB( DB_MASTER );

		if ( wfReadOnly() ) {
			return [ [ 'readonlytext' ] ];
		}

		// Begin revision creation cycle by creating a PageUpdater.
		// If the page is changed concurrently after grabParentRevision(), the rollback will fail.
		$updater = $this->newPageUpdater( $guser );
		$current = $updater->grabParentRevision();

		if ( is_null( $current ) ) {
			// Something wrong... no page?
			return [ [ 'notanarticle' ] ];
		}

		$currentEditorForPublic = $current->getUser( RevisionRecord::FOR_PUBLIC );
		$legacyCurrent = new Revision( $current );
		$from = str_replace( '_', ' ', $fromP );

		// User name given should match up with the top revision.
		// If the revision's user is not visible, then $from should be empty.
		if ( $from !== ( $currentEditorForPublic ? $currentEditorForPublic->getName() : '' ) ) {
			$resultDetails = [ 'current' => $legacyCurrent ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $currentEditorForPublic ? $currentEditorForPublic->getName() : '' )
			] ];
		}

		// Get the last edit not by this person...
		// Note: these may not be public values
		$actorWhere = ActorMigration::newMigration()->getWhere(
			$dbw,
			'rev_user',
			$current->getUser( RevisionRecord::RAW )
		);

		$s = $dbw->selectRow(
			[ 'revision' ] + $actorWhere['tables'],
			[ 'rev_id', 'rev_timestamp', 'rev_deleted' ],
			[
				'rev_page' => $current->getPageId(),
				'NOT(' . $actorWhere['conds'] . ')',
			],
			__METHOD__,
			[
				'USE INDEX' => [ 'revision' => 'page_timestamp' ],
				'ORDER BY' => 'rev_timestamp DESC'
			],
			$actorWhere['joins']
		);
		if ( $s === false ) {
			// No one else ever edited this page
			return [ [ 'cantrollback' ] ];
		} elseif ( $s->rev_deleted & RevisionRecord::DELETED_TEXT
			|| $s->rev_deleted & RevisionRecord::DELETED_USER
		) {
			// Only admins can see this text
			return [ [ 'notvisiblerev' ] ];
		}

		// Generate the edit summary if necessary
		$target = $this->getRevisionStore()->getRevisionById(
			$s->rev_id,
			RevisionStore::READ_LATEST
		);
		if ( empty( $summary ) ) {
			if ( !$currentEditorForPublic ) { // no public user name
				$summary = wfMessage( 'revertpage-nouser' );
			} else {
				$summary = wfMessage( 'revertpage' );
			}
		}
		$legacyTarget = new Revision( $target );
		$targetEditorForPublic = $target->getUser( RevisionRecord::FOR_PUBLIC );

		// Allow the custom summary to use the same args as the default message
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$args = [
			$targetEditorForPublic ? $targetEditorForPublic->getName() : null,
			$currentEditorForPublic ? $currentEditorForPublic->getName() : null,
			$s->rev_id,
			$contLang->timeanddate( wfTimestamp( TS_MW, $s->rev_timestamp ) ),
			$current->getId(),
			$contLang->timeanddate( $current->getTimestamp() )
		];
		if ( $summary instanceof Message ) {
			$summary = $summary->params( $args )->inContentLanguage()->text();
		} else {
			$summary = wfMsgReplaceArgs( $summary, $args );
		}

		// Trim spaces on user supplied text
		$summary = trim( $summary );

		// Save
		$flags = EDIT_UPDATE | EDIT_INTERNAL;

		if ( $guser->isAllowed( 'minoredit' ) ) {
			$flags |= EDIT_MINOR;
		}

		if ( $bot && ( $guser->isAllowedAny( 'markbotedits', 'bot' ) ) ) {
			$flags |= EDIT_FORCE_BOT;
		}

		// TODO: MCR: also log model changes in other slots, in case that becomes possible!
		$currentContent = $current->getContent( SlotRecord::MAIN );
		$targetContent = $target->getContent( SlotRecord::MAIN );
		$changingContentModel = $targetContent->getModel() !== $currentContent->getModel();

		if ( in_array( 'mw-rollback', ChangeTags::getSoftwareTags() ) ) {
			$tags[] = 'mw-rollback';
		}

		// Build rollback revision:
		// Restore old content
		// TODO: MCR: test this once we can store multiple slots
		foreach ( $target->getSlots()->getSlots() as $slot ) {
			$updater->inheritSlot( $slot );
		}

		// Remove extra slots
		// TODO: MCR: test this once we can store multiple slots
		foreach ( $current->getSlotRoles() as $role ) {
			if ( !$target->hasSlot( $role ) ) {
				$updater->removeSlot( $role );
			}
		}

		$updater->setOriginalRevisionId( $target->getId() );
		// Do not call setUndidRevisionId(), that causes an extra "mw-undo" tag to be added (T190374)
		$updater->addTags( $tags );

		// TODO: this logic should not be in the storage layer, it's here for compatibility
		// with 1.31 behavior. Applying the 'autopatrol' right should be done in the same
		// place the 'bot' right is handled, which is currently in EditPage::attemptSave.
		if ( $wgUseRCPatrol && $this->getTitle()->userCan( 'autopatrol', $guser ) ) {
			$updater->setRcPatrolStatus( RecentChange::PRC_AUTOPATROLLED );
		}

		// Actually store the rollback
		$rev = $updater->saveRevision(
			CommentStoreComment::newUnsavedComment( $summary ),
			$flags
		);

		// Set patrolling and bot flag on the edits, which gets rollbacked.
		// This is done even on edit failure to have patrolling in that case (T64157).
		$set = [];
		if ( $bot && $guser->isAllowed( 'markbotedits' ) ) {
			// Mark all reverted edits as bot
			$set['rc_bot'] = 1;
		}

		if ( $wgUseRCPatrol ) {
			// Mark all reverted edits as patrolled
			$set['rc_patrolled'] = RecentChange::PRC_AUTOPATROLLED;
		}

		if ( count( $set ) ) {
			$actorWhere = ActorMigration::newMigration()->getWhere(
				$dbw,
				'rc_user',
				$current->getUser( RevisionRecord::RAW ),
				false
			);
			$dbw->update( 'recentchanges', $set,
				[ /* WHERE */
					'rc_cur_id' => $current->getPageId(),
					'rc_timestamp > ' . $dbw->addQuotes( $s->rev_timestamp ),
					$actorWhere['conds'], // No tables/joins are needed for rc_user
				],
				__METHOD__
			);
		}

		if ( !$updater->wasSuccessful() ) {
			return $updater->getStatus()->getErrorsArray();
		}

		// Report if the edit was not created because it did not change the content.
		if ( $updater->isUnchanged() ) {
			$resultDetails = [ 'current' => $legacyCurrent ];
			return [ [ 'alreadyrolled',
					htmlspecialchars( $this->mTitle->getPrefixedText() ),
					htmlspecialchars( $fromP ),
					htmlspecialchars( $targetEditorForPublic ? $targetEditorForPublic->getName() : '' )
			] ];
		}

		if ( $changingContentModel ) {
			// If the content model changed during the rollback,
			// make sure it gets logged to Special:Log/contentmodel
			$log = new ManualLogEntry( 'contentmodel', 'change' );
			$log->setPerformer( $guser );
			$log->setTarget( $this->mTitle );
			$log->setComment( $summary );
			$log->setParameters( [
				'4::oldmodel' => $currentContent->getModel(),
				'5::newmodel' => $targetContent->getModel(),
			] );

			$logId = $log->insert( $dbw );
			$log->publish( $logId );
		}

		$revId = $rev->getId();

		Hooks::run( 'ArticleRollbackComplete', [ $this, $guser, $legacyTarget, $legacyCurrent ] );

		$resultDetails = [
			'summary' => $summary,
			'current' => $legacyCurrent,
			'target' => $legacyTarget,
			'newid' => $revId,
			'tags' => $tags
		];

		// TODO: make this return a Status object and wrap $resultDetails in that.
		return [];
	}

	/**
	 * The onArticle*() functions are supposed to be a kind of hooks
	 * which should be called whenever any of the specified actions
	 * are done.
	 *
	 * This is a good place to put code to clear caches, for instance.
	 *
	 * This is called on page move and undelete, as well as edit
	 *
	 * @param Title $title
	 */
	public static function onArticleCreate( Title $title ) {
		// TODO: move this into a PageEventEmitter service

		// Update existence markers on article/talk tabs...
		$other = $title->getOtherPage();

		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();
		$title->deleteTitleProtection();

		MediaWikiServices::getInstance()->getLinkCache()->invalidateTitle( $title );

		// Invalidate caches of articles which include this page
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'templatelinks', 'page-create' )
		);

		if ( $title->getNamespace() == NS_CATEGORY ) {
			// Load the Category object, which will schedule a job to create
			// the category table row if necessary. Checking a replica DB is ok
			// here, in the worst case it'll run an unnecessary recount job on
			// a category that probably doesn't have many members.
			Category::newFromTitle( $title )->getID();
		}
	}

	/**
	 * Clears caches when article is deleted
	 *
	 * @param Title $title
	 */
	public static function onArticleDelete( Title $title ) {
		// TODO: move this into a PageEventEmitter service

		// Update existence markers on article/talk tabs...
		// Clear Backlink cache first so that purge jobs use more up-to-date backlink information
		BacklinkCache::get( $title )->clear();
		$other = $title->getOtherPage();

		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();

		MediaWikiServices::getInstance()->getLinkCache()->invalidateTitle( $title );

		// File cache
		HTMLFileCache::clearFileCache( $title );
		InfoAction::invalidateCache( $title );

		// Messages
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->updateMessageOverride( $title, null );
		}

		// Images
		if ( $title->getNamespace() == NS_FILE ) {
			DeferredUpdates::addUpdate(
				new HTMLCacheUpdate( $title, 'imagelinks', 'page-delete' )
			);
		}

		// User talk pages
		if ( $title->getNamespace() == NS_USER_TALK ) {
			$user = User::newFromName( $title->getText(), false );
			if ( $user ) {
				$user->setNewtalk( false );
			}
		}

		// Image redirects
		RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $title );

		// Purge cross-wiki cache entities referencing this page
		self::purgeInterwikiCheckKey( $title );
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param Title $title
	 * @param Revision|null $revision Revision that was just saved, may be null
	 * @param string[]|null $slotsChanged The role names of the slots that were changed.
	 *        If not given, all slots are assumed to have changed.
	 */
	public static function onArticleEdit(
		Title $title,
		Revision $revision = null,
		$slotsChanged = null
	) {
		// TODO: move this into a PageEventEmitter service

		if ( $slotsChanged === null || in_array( SlotRecord::MAIN,  $slotsChanged ) ) {
			// Invalidate caches of articles which include this page.
			// Only for the main slot, because only the main slot is transcluded.
			// TODO: MCR: not true for TemplateStyles! [SlotHandler]
			DeferredUpdates::addUpdate(
				new HTMLCacheUpdate( $title, 'templatelinks', 'page-edit' )
			);
		}

		// Invalidate the caches of all pages which redirect here
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'redirect', 'page-edit' )
		);

		MediaWikiServices::getInstance()->getLinkCache()->invalidateTitle( $title );

		// Purge CDN for this page only
		$title->purgeSquid();
		// Clear file cache for this page only
		HTMLFileCache::clearFileCache( $title );

		// Purge ?action=info cache
		$revid = $revision ? $revision->getId() : null;
		DeferredUpdates::addCallableUpdate( function () use ( $title, $revid ) {
			InfoAction::invalidateCache( $title, $revid );
		} );

		// Purge cross-wiki cache entities referencing this page
		self::purgeInterwikiCheckKey( $title );
	}

	/**#@-*/

	/**
	 * Purge the check key for cross-wiki cache entries referencing this page
	 *
	 * @param Title $title
	 */
	private static function purgeInterwikiCheckKey( Title $title ) {
		global $wgEnableScaryTranscluding;

		if ( !$wgEnableScaryTranscluding ) {
			return; // @todo: perhaps this wiki is only used as a *source* for content?
		}

		DeferredUpdates::addCallableUpdate( function () use ( $title ) {
			$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
			$cache->resetCheckKey(
				// Do not include the namespace since there can be multiple aliases to it
				// due to different namespace text definitions on different wikis. This only
				// means that some cache invalidations happen that are not strictly needed.
				$cache->makeGlobalKey(
					'interwiki-page',
					WikiMap::getCurrentWikiDbDomain()->getId(),
					$title->getDBkey()
				)
			);
		} );
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

	/**
	 * Auto-generates a deletion reason
	 *
	 * @param bool &$hasHistory Whether the page has a history
	 * @return string|bool String containing deletion reason or empty string, or boolean false
	 *    if no revision occurred
	 */
	public function getAutoDeleteReason( &$hasHistory ) {
		return $this->getContentHandler()->getAutoDeleteReason( $this->getTitle(), $hasHistory );
	}

	/**
	 * Update all the appropriate counts in the category table, given that
	 * we've added the categories $added and deleted the categories $deleted.
	 *
	 * This should only be called from deferred updates or jobs to avoid contention.
	 *
	 * @param array $added The names of categories that were added
	 * @param array $deleted The names of categories that were deleted
	 * @param int $id Page ID (this should be the original deleted page ID)
	 */
	public function updateCategoryCounts( array $added, array $deleted, $id = 0 ) {
		$id = $id ?: $this->getId();
		$type = MWNamespace::getCategoryLinkType( $this->getTitle()->getNamespace() );

		$addFields = [ 'cat_pages = cat_pages + 1' ];
		$removeFields = [ 'cat_pages = cat_pages - 1' ];
		if ( $type !== 'page' ) {
			$addFields[] = "cat_{$type}s = cat_{$type}s + 1";
			$removeFields[] = "cat_{$type}s = cat_{$type}s - 1";
		}

		$dbw = wfGetDB( DB_MASTER );

		if ( count( $added ) ) {
			$existingAdded = $dbw->selectFieldValues(
				'category',
				'cat_title',
				[ 'cat_title' => $added ],
				__METHOD__
			);

			// For category rows that already exist, do a plain
			// UPDATE instead of INSERT...ON DUPLICATE KEY UPDATE
			// to avoid creating gaps in the cat_id sequence.
			if ( count( $existingAdded ) ) {
				$dbw->update(
					'category',
					$addFields,
					[ 'cat_title' => $existingAdded ],
					__METHOD__
				);
			}

			$missingAdded = array_diff( $added, $existingAdded );
			if ( count( $missingAdded ) ) {
				$insertRows = [];
				foreach ( $missingAdded as $cat ) {
					$insertRows[] = [
						'cat_title'   => $cat,
						'cat_pages'   => 1,
						'cat_subcats' => ( $type === 'subcat' ) ? 1 : 0,
						'cat_files'   => ( $type === 'file' ) ? 1 : 0,
					];
				}
				$dbw->upsert(
					'category',
					$insertRows,
					[ 'cat_title' ],
					$addFields,
					__METHOD__
				);
			}
		}

		if ( count( $deleted ) ) {
			$dbw->update(
				'category',
				$removeFields,
				[ 'cat_title' => $deleted ],
				__METHOD__
			);
		}

		foreach ( $added as $catName ) {
			$cat = Category::newFromName( $catName );
			Hooks::run( 'CategoryAfterPageAdded', [ $cat, $this ] );
		}

		foreach ( $deleted as $catName ) {
			$cat = Category::newFromName( $catName );
			Hooks::run( 'CategoryAfterPageRemoved', [ $cat, $this, $id ] );
			// Refresh counts on categories that should be empty now (after commit, T166757)
			DeferredUpdates::addCallableUpdate( function () use ( $cat ) {
				$cat->refreshCountsIfEmpty();
			} );
		}
	}

	/**
	 * Opportunistically enqueue link update jobs given fresh parser output if useful
	 *
	 * @param ParserOutput $parserOutput Current version page output
	 * @since 1.25
	 */
	public function triggerOpportunisticLinksUpdate( ParserOutput $parserOutput ) {
		if ( wfReadOnly() ) {
			return;
		}

		if ( !Hooks::run( 'OpportunisticLinksUpdate',
			[ $this, $this->mTitle, $parserOutput ]
		) ) {
			return;
		}

		$config = RequestContext::getMain()->getConfig();

		$params = [
			'isOpportunistic' => true,
			'rootJobTimestamp' => $parserOutput->getCacheTime()
		];

		if ( $this->mTitle->areRestrictionsCascading() ) {
			// If the page is cascade protecting, the links should really be up-to-date
			JobQueueGroup::singleton()->lazyPush(
				RefreshLinksJob::newPrioritized( $this->mTitle, $params )
			);
		} elseif ( !$config->get( 'MiserMode' ) && $parserOutput->hasDynamicContent() ) {
			// Assume the output contains "dynamic" time/random based magic words.
			// Only update pages that expired due to dynamic content and NOT due to edits
			// to referenced templates/files. When the cache expires due to dynamic content,
			// page_touched is unchanged. We want to avoid triggering redundant jobs due to
			// views of pages that were just purged via HTMLCacheUpdateJob. In that case, the
			// template/file edit already triggered recursive RefreshLinksJob jobs.
			if ( $this->getLinksTimestamp() > $this->getTouched() ) {
				// If a page is uncacheable, do not keep spamming a job for it.
				// Although it would be de-duplicated, it would still waste I/O.
				$cache = ObjectCache::getLocalClusterInstance();
				$key = $cache->makeKey( 'dynamic-linksupdate', 'last', $this->getId() );
				$ttl = max( $parserOutput->getCacheExpiry(), 3600 );
				if ( $cache->add( $key, time(), $ttl ) ) {
					JobQueueGroup::singleton()->lazyPush(
						RefreshLinksJob::newDynamic( $this->mTitle, $params )
					);
				}
			}
		}
	}

	/**
	 * Returns a list of updates to be performed when this page is deleted. The
	 * updates should remove any information about this page from secondary data
	 * stores such as links tables.
	 *
	 * @param RevisionRecord|Content|null $rev The revision being deleted. Also accepts a Content
	 *       object for backwards compatibility.
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( $rev = null ) {
		if ( !$rev ) {
			wfDeprecated( __METHOD__ . ' without a RevisionRecord', '1.32' );

			try {
				$rev = $this->getRevisionRecord();
			} catch ( Exception $ex ) {
				// If we can't load the content, something is wrong. Perhaps that's why
				// the user is trying to delete the page, so let's not fail in that case.
				// Note that doDeleteArticleReal() will already have logged an issue with
				// loading the content.
				wfDebug( __METHOD__ . ' failed to load current revision of page ' . $this->getId() );
			}
		}

		if ( !$rev ) {
			$slotContent = [];
		} elseif ( $rev instanceof Content ) {
			wfDeprecated( __METHOD__ . ' with a Content object instead of a RevisionRecord', '1.32' );

			$slotContent = [ SlotRecord::MAIN => $rev ];
		} else {
			$slotContent = array_map( function ( SlotRecord $slot ) {
				return $slot->getContent( Revision::RAW );
			}, $rev->getSlots()->getSlots() );
		}

		$allUpdates = [ new LinksDeletionUpdate( $this ) ];

		// NOTE: once Content::getDeletionUpdates() is removed, we only need to content
		// model here, not the content object!
		// TODO: consolidate with similar logic in DerivedPageDataUpdater::getSecondaryDataUpdates()
		/** @var Content $content */
		foreach ( $slotContent as $role => $content ) {
			$handler = $content->getContentHandler();

			$updates = $handler->getDeletionUpdates(
				$this->getTitle(),
				$role
			);
			$allUpdates = array_merge( $allUpdates, $updates );

			// TODO: remove B/C hack in 1.32!
			$legacyUpdates = $content->getDeletionUpdates( $this );

			// HACK: filter out redundant and incomplete LinksDeletionUpdate
			$legacyUpdates = array_filter( $legacyUpdates, function ( $update ) {
				return !( $update instanceof LinksDeletionUpdate );
			} );

			$allUpdates = array_merge( $allUpdates, $legacyUpdates );
		}

		Hooks::run( 'PageDeletionDataUpdates', [ $this->getTitle(), $rev, &$allUpdates ] );

		// TODO: hard deprecate old hook in 1.33
		Hooks::run( 'WikiPageDeletionUpdates', [ $this, $content, &$allUpdates ] );
		return $allUpdates;
	}

	/**
	 * Whether this content displayed on this page
	 * comes from the local database
	 *
	 * @since 1.28
	 * @return bool
	 */
	public function isLocal() {
		return true;
	}

	/**
	 * The display name for the site this content
	 * come from. If a subclass overrides isLocal(),
	 * this could return something other than the
	 * current site name
	 *
	 * @since 1.28
	 * @return string
	 */
	public function getWikiDisplayName() {
		global $wgSitename;
		return $wgSitename;
	}

	/**
	 * Get the source URL for the content on this page,
	 * typically the canonical URL, but may be a remote
	 * link if the content comes from another site
	 *
	 * @since 1.28
	 * @return string
	 */
	public function getSourceURL() {
		return $this->getTitle()->getCanonicalURL();
	}

	/**
	 * @param WANObjectCache $cache
	 * @return string[]
	 * @since 1.28
	 */
	public function getMutableCacheKeys( WANObjectCache $cache ) {
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();

		return $linkCache->getMutableCacheKeys( $cache, $this->getTitle() );
	}

}
