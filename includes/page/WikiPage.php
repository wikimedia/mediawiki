<?php
/**
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

use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\DeletePage;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Storage\PreparedUpdate;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\NonSerializable\NonSerializableTrait;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\RequestTimeout\TimeoutException;

/**
 * Base representation for an editable wiki page.
 *
 * Some fields are public only for backwards-compatibility. Use accessor methods.
 * In the past, this class was part of Article.php and everything was public.
 */
class WikiPage implements Page, IDBAccessObject, PageRecord {
	use NonSerializableTrait;
	use ProtectedHookAccessorTrait;
	use WikiAwareEntityTrait;

	// Constants for $mDataLoadedFrom and related

	/**
	 * @var Title
	 * @note for access by subclasses only
	 */
	protected $mTitle;

	/**
	 * @var bool
	 * @note for access by subclasses only
	 */
	protected $mDataLoaded = false;

	/**
	 * A cache of the page_is_redirect field, loaded with page data
	 * @var bool
	 */
	private $mPageIsRedirectField = false;

	/**
	 * Boolean if the redirect status is definitively known.
	 * If this is true, getRedirectTarget() must return non-null.
	 *
	 * @var bool|null
	 */
	private $mHasRedirectTarget = null;

	/**
	 * The cache of the redirect target
	 *
	 * @var Title|null
	 */
	protected $mRedirectTarget = null;

	/**
	 * @var bool
	 */
	private $mIsNew = false;

	/**
	 * @var bool
	 */
	private $mIsRedirect = false;

	/**
	 * @var int|false False means "not loaded"
	 * @note for access by subclasses only
	 */
	protected $mLatest = false;

	/**
	 * @var PreparedEdit|false Map of cache fields (text, parser output, ect) for a proposed/new edit
	 * @note for access by subclasses only
	 */
	protected $mPreparedEdit = false;

	/**
	 * @var int|null
	 */
	protected $mId = null;

	/**
	 * @var int One of the READ_* constants
	 */
	protected $mDataLoadedFrom = self::READ_NONE;

	/**
	 * @var RevisionRecord|null
	 */
	private $mLastRevision = null;

	/**
	 * @var string Timestamp of the current revision or empty string if not loaded
	 */
	protected $mTimestamp = '';

	/**
	 * @var string
	 */
	protected $mTouched = '19700101000000';

	/**
	 * @var string|null
	 */
	protected $mLanguage = null;

	/**
	 * @var string
	 */
	protected $mLinksUpdated = '19700101000000';

	/**
	 * @var DerivedPageDataUpdater|null
	 */
	private $derivedDataUpdater = null;

	/**
	 * @param PageIdentity $pageIdentity
	 */
	public function __construct( PageIdentity $pageIdentity ) {
		$pageIdentity->assertWiki( PageIdentity::LOCAL );

		// TODO: remove the need for casting to Title.
		$title = Title::castFromPageIdentity( $pageIdentity );
		if ( !$title->canExist() ) {
			throw new InvalidArgumentException( "WikiPage constructed on a Title that cannot exist as a page: $title" );
		}

		// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty castFrom does not return null here
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
	 * Create a WikiPage object of the appropriate class for the given PageIdentity.
	 * The PageIdentity must represent a proper page that can exist on the wiki,
	 * that is, not a special page or media link or section link or interwiki link.
	 *
	 * @param PageIdentity $pageIdentity
	 *
	 * @throws MWException
	 * @return WikiPage|WikiCategoryPage|WikiFilePage
	 * @deprecated since 1.36, use WikiPageFactory::newFromTitle instead
	 */
	public static function factory( PageIdentity $pageIdentity ) {
		return MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $pageIdentity );
	}

	/**
	 * Constructor from a page id
	 *
	 * @param int $id Article ID to load
	 * @param string|int $from One of the following values:
	 *        - "fromdb" or WikiPage::READ_NORMAL to select from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST to select from the primary database
	 *
	 * @return WikiPage|null
	 * @deprecated since 1.36, use WikiPageFactory::newFromID instead
	 */
	public static function newFromID( $id, $from = 'fromdb' ) {
		return MediaWikiServices::getInstance()->getWikiPageFactory()->newFromID( $id, $from );
	}

	/**
	 * Constructor from a database row
	 *
	 * @since 1.20
	 * @param stdClass $row Database row containing at least fields returned by getQueryInfo().
	 * @param string|int $from Source of $data:
	 *        - "fromdb" or WikiPage::READ_NORMAL: from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST: from the primary DB
	 *        - "forupdate" or WikiPage::READ_LOCKING: from the primary DB using SELECT FOR UPDATE
	 * @return WikiPage
	 * @deprecated since 1.36, use WikiPageFactory::newFromRow instead
	 */
	public static function newFromRow( $row, $from = 'fromdb' ) {
		return MediaWikiServices::getInstance()->getWikiPageFactory()->newFromRow( $row, $from );
	}

	/**
	 * Convert 'fromdb', 'fromdbmaster' and 'forupdate' to READ_* constants.
	 *
	 * @param stdClass|string|int $type
	 * @return mixed
	 */
	public static function convertSelectType( $type ) {
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
	 * @return PageUpdaterFactory
	 */
	private function getPageUpdaterFactory(): PageUpdaterFactory {
		return MediaWikiServices::getInstance()->getPageUpdaterFactory();
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		return MediaWikiServices::getInstance()->getRevisionStore();
	}

	/**
	 * @return ILoadBalancer
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
		$factory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		return $factory->getContentHandler( $this->getContentModel() );
	}

	/**
	 * Get the title object of the article
	 * @return Title Title object of this page
	 */
	public function getTitle(): Title {
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
		$this->mHasRedirectTarget = null;
		$this->mPageIsRedirectField = false;
		$this->mLastRevision = null; // Latest revision
		$this->mTouched = '19700101000000';
		$this->mLanguage = null;
		$this->mLinksUpdated = '19700101000000';
		$this->mTimestamp = '';
		$this->mIsNew = false;
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
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new page object.
	 * @since 1.31
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo() {
		$pageLanguageUseDB = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::PageLanguageUseDB );

		$ret = [
			'tables' => [ 'page' ],
			'fields' => [
				'page_id',
				'page_namespace',
				'page_title',
				'page_is_redirect',
				'page_is_new',
				'page_random',
				'page_touched',
				'page_links_updated',
				'page_latest',
				'page_len',
				'page_content_model',
			],
			'joins' => [],
		];

		if ( $pageLanguageUseDB ) {
			$ret['fields'][] = 'page_lang';
		}

		return $ret;
	}

	/**
	 * Fetch a page record with the given conditions
	 * @param IDatabase $dbr
	 * @param array $conditions
	 * @param array $options
	 * @return stdClass|false Database result resource, or false on failure
	 */
	protected function pageData( $dbr, $conditions, $options = [] ) {
		$pageQuery = self::getQueryInfo();

		$this->getHookRunner()->onArticlePageDataBefore(
			$this, $pageQuery['fields'], $pageQuery['tables'], $pageQuery['joins'] );

		$row = $dbr->selectRow(
			$pageQuery['tables'],
			$pageQuery['fields'],
			$conditions,
			__METHOD__,
			$options,
			$pageQuery['joins']
		);

		$this->getHookRunner()->onArticlePageDataAfter( $this, $row );

		return $row;
	}

	/**
	 * Fetch a page record matching the Title object's namespace and title
	 * using a sanitized title string
	 *
	 * @param IDatabase $dbr
	 * @param Title $title
	 * @param array $options
	 * @return stdClass|false Database result resource, or false on failure
	 */
	public function pageDataFromTitle( $dbr, $title, $options = [] ) {
		if ( !$title->canExist() ) {
			return false;
		}

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
	 * @return stdClass|false Database result resource, or false on failure
	 */
	public function pageDataFromId( $dbr, $id, $options = [] ) {
		return $this->pageData( $dbr, [ 'page_id' => $id ], $options );
	}

	/**
	 * Load the object from a given source by title
	 *
	 * @param stdClass|string|int $from One of the following:
	 *   - A DB query result object.
	 *   - "fromdb" or WikiPage::READ_NORMAL to get from a replica DB.
	 *   - "fromdbmaster" or WikiPage::READ_LATEST to get from the primary DB.
	 *   - "forupdate"  or WikiPage::READ_LOCKING to get from the primary DB
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
			$db = $loadBalancer->getConnectionRef( $index );
			$data = $this->pageDataFromTitle( $db, $this->mTitle, $opts );

			if ( !$data
				&& $index == DB_REPLICA
				&& $loadBalancer->getServerCount() > 1
				&& $loadBalancer->hasOrMadeRecentPrimaryChanges()
			) {
				$from = self::READ_LATEST;
				list( $index, $opts ) = DBAccessObjectUtils::getDBOptions( $from );
				$db = $loadBalancer->getConnectionRef( $index );
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
	 *   - "fromdbmaster" or WikiPage::READ_LATEST to get from the primary DB.
	 *   - "forupdate"  or WikiPage::READ_LOCKING to get from the primary DB
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

		if ( $from <= $this->mDataLoadedFrom ) {
			return true;
		}

		return false;
	}

	/**
	 * Load the object from a database row
	 *
	 * @since 1.20
	 * @param stdClass|bool $data DB row containing fields returned by getQueryInfo() or false
	 * @param string|int $from One of the following:
	 *        - "fromdb" or WikiPage::READ_NORMAL if the data comes from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST if the data comes from the primary DB
	 *        - "forupdate"  or WikiPage::READ_LOCKING if the data comes from
	 *          the primary DB using SELECT FOR UPDATE
	 */
	public function loadFromRow( $data, $from ) {
		$lc = MediaWikiServices::getInstance()->getLinkCache();
		$lc->clearLink( $this->mTitle );

		if ( $data ) {
			$lc->addGoodLinkObjFromRow( $this->mTitle, $data );

			$this->mTitle->loadFromRow( $data );
			$this->mId = intval( $data->page_id );
			$this->mTouched = MWTimestamp::convert( TS_MW, $data->page_touched );
			$this->mLanguage = $data->page_lang ?? null;
			$this->mLinksUpdated = $data->page_links_updated === null
				? null
				: MWTimestamp::convert( TS_MW, $data->page_links_updated );
			$this->mPageIsRedirectField = (bool)$data->page_is_redirect;
			$this->mIsNew = (bool)( $data->page_is_new ?? 0 );
			$this->mIsRedirect = (bool)( $data->page_is_redirect ?? 0 );
			$this->mLatest = intval( $data->page_latest );
			// T39225: $latest may no longer match the cached latest RevisionRecord object.
			// Double-check the ID of any cached latest RevisionRecord object for consistency.
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
	 * @param string|false $wikiId
	 *
	 * @return int Page ID
	 */
	public function getId( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );

		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mId;
	}

	/**
	 * @return bool Whether or not the page exists in the database
	 */
	public function exists(): bool {
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
	 * Is the page a redirect, according to secondary tracking tables?
	 * If this is true, getRedirectTarget() will return a Title.
	 *
	 * @return bool
	 */
	public function isRedirect() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return $this->mIsRedirect;
	}

	/**
	 * Get the value of the page_is_redirect field in the DB. This is probably
	 * not what you want. Use WikiPage::isRedirect() to test if the page is a
	 * redirect. Use Title::isRedirect() for a fast check for the purposes of
	 * linking to a page.
	 *
	 * @since 1.36
	 * @return bool
	 */
	public function getPageIsRedirectField() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mPageIsRedirectField;
	}

	/**
	 * Tests if the page is new (only has one revision).
	 * May produce false negatives for some old pages.
	 *
	 * @since 1.36
	 *
	 * @return bool
	 */
	public function isNew() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return $this->mIsNew;
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
					$rev = $this->getRevisionRecord();
					if ( $rev ) {
						// Look at the revision's actual content model
						$slot = $rev->getSlot(
							SlotRecord::MAIN,
							RevisionRecord::RAW
						);
						return $slot->getModel();
					} else {
						LoggerFactory::getInstance( 'wikipage' )->warning(
							'Page exists but has no (visible) revisions!',
							[
								'page-title' => $this->mTitle->getPrefixedDBkey(),
								'page-id' => $this->getId(),
							]
						);
						return $this->mTitle->getContentModel();
					}
				},
				[ 'pcTTL' => $cache::TTL_PROC_LONG ]
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
		return ( $this->exists() && !$this->isRedirect() );
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
	 * @return ?string language code for the page
	 */
	public function getLanguage() {
		if ( !$this->mDataLoaded ) {
			$this->loadLastEdit();
		}

		return $this->mLanguage;
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
	 * @param bool $wikiId
	 * @return int The rev_id of current revision
	 */
	public function getLatest( $wikiId = self::LOCAL ) {
		$this->assertWiki( $wikiId );

		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return (int)$this->mLatest;
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
			// https://dev.mysql.com/doc/refman/5.7/en/set-transaction.html#isolevel_repeatable-read
			$revision = $this->getRevisionStore()
				->getRevisionByPageId( $this->getId(), $latest, RevisionStore::READ_LOCKING );
		} elseif ( $this->mDataLoadedFrom == self::READ_LATEST ) {
			// Bug T93976: if page_latest was loaded from the primary DB, fetch the
			// revision from there as well, as it may not exist yet on a replica DB.
			// Also, this keeps the queries in the same REPEATABLE-READ snapshot.
			$revision = $this->getRevisionStore()
				->getRevisionByPageId( $this->getId(), $latest, RevisionStore::READ_LATEST );
		} else {
			$revision = $this->getRevisionStore()->getKnownCurrentRevision( $this->getTitle(), $latest );
		}

		if ( $revision ) {
			$this->setLastEdit( $revision );
		}
	}

	/**
	 * Set the latest revision
	 * @param RevisionRecord $revRecord
	 */
	private function setLastEdit( RevisionRecord $revRecord ) {
		$this->mLastRevision = $revRecord;
		$this->mLatest = $revRecord->getId();
		$this->mTimestamp = $revRecord->getTimestamp();
		$this->mTouched = max( $this->mTouched, $revRecord->getTimestamp() );
	}

	/**
	 * Get the latest revision
	 * @since 1.32
	 * @return RevisionRecord|null
	 */
	public function getRevisionRecord() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision;
		}
		return null;
	}

	/**
	 * Get the content of the current revision. No side-effects...
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @return Content|null The content of the current revision
	 *
	 * @since 1.21
	 */
	public function getContent( $audience = RevisionRecord::FOR_PUBLIC, Authority $performer = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getContent( SlotRecord::MAIN, $audience, $performer );
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

		return MWTimestamp::convert( TS_MW, $this->mTimestamp );
	}

	/**
	 * Set the page timestamp (use only to avoid DB queries)
	 * @param string $ts MW timestamp of last article revision
	 * @return void
	 */
	public function setTimestamp( $ts ) {
		$this->mTimestamp = MWTimestamp::convert( TS_MW, $ts );
	}

	/**
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (since 1.36, if using FOR_THIS_USER and not specifying
	 *   a user no fallback is provided and the RevisionRecord method will throw an error)
	 * @return int User ID for the user that made the last article revision
	 */
	public function getUser( $audience = RevisionRecord::FOR_PUBLIC, Authority $performer = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			$revUser = $this->mLastRevision->getUser( $audience, $performer );
			return $revUser ? $revUser->getId() : 0;
		} else {
			return -1;
		}
	}

	/**
	 * Get the User object of the user who created the page
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (since 1.36, if using FOR_THIS_USER and not specifying
	 *   a user no fallback is provided and the RevisionRecord method will throw an error)
	 * @return UserIdentity|null
	 */
	public function getCreator( $audience = RevisionRecord::FOR_PUBLIC, Authority $performer = null ) {
		$revRecord = $this->getRevisionStore()->getFirstRevision( $this->getTitle() );
		if ( $revRecord ) {
			return $revRecord->getUser( $audience, $performer );
		} else {
			return null;
		}
	}

	/**
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (since 1.36, if using FOR_THIS_USER and not specifying
	 *   a user no fallback is provided and the RevisionRecord method will throw an error)
	 * @return string Username of the user that made the last article revision
	 */
	public function getUserText( $audience = RevisionRecord::FOR_PUBLIC, Authority $performer = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			$revUser = $this->mLastRevision->getUser( $audience, $performer );
			return $revUser ? $revUser->getName() : '';
		} else {
			return '';
		}
	}

	/**
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to the given user
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param Authority|null $performer object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter (since 1.36, if using FOR_THIS_USER and not specifying
	 *   a user no fallback is provided and the RevisionRecord method will throw an error)
	 * @return string|null Comment stored for the last article revision, or null if the specified
	 *  audience does not have access to the comment.
	 */
	public function getComment( $audience = RevisionRecord::FOR_PUBLIC, Authority $performer = null ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			$revComment = $this->mLastRevision->getComment( $audience, $performer );
			return $revComment ? $revComment->text : '';
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
	 * @param PreparedEdit|PreparedUpdate|bool $editInfo (false):
	 *   An object returned by prepareTextForEdit() or getCurrentUpdate() respectively;
	 *   If false is given, the current database state will be used.
	 *
	 * @return bool
	 */
	public function isCountable( $editInfo = false ) {
		$articleCountMethod = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ArticleCountMethod );

		// NOTE: Keep in sync with DerivedPageDataUpdater::isCountable.

		if ( !$this->mTitle->isContentPage() ) {
			return false;
		}

		if ( $editInfo instanceof PreparedEdit ) {
			// NOTE: only the main slot can make a page a redirect
			$content = $editInfo->pstContent;
		} elseif ( $editInfo instanceof PreparedUpdate ) {
			// NOTE: only the main slot can make a page a redirect
			$content = $editInfo->getRawContent( SlotRecord::MAIN );
		} else {
			$content = $this->getContent();
		}

		if ( !$content || $content->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $articleCountMethod === 'link' ) {
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
				$hasLinks = (bool)wfGetDB( DB_REPLICA )->selectField( 'pagelinks', '1',
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
	 *
	 * @deprecated since 1.38 Use RedirectLookup::getRedirectTarget() instead.
	 *
	 * @return Title|null Title object, or null if this page is not a redirect
	 */
	public function getRedirectTarget() {
		if ( $this->mRedirectTarget !== null ) {
			return $this->mRedirectTarget;
		}

		if ( $this->mHasRedirectTarget === false || !$this->getPageIsRedirectField() ) {
			return null;
		}

		// Query the redirect table
		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow( 'redirect',
			[ 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ],
			[ 'rd_from' => $this->getId() ],
			__METHOD__
		);

		// rd_fragment and rd_interwiki were added later, populate them if empty
		if ( $row && $row->rd_fragment !== null && $row->rd_interwiki !== null ) {
			// (T203942) We can't redirect to Media namespace because it's virtual.
			// We don't want to modify Title objects farther down the
			// line. So, let's fix this here by changing to File namespace.
			if ( $row->rd_namespace == NS_MEDIA ) {
				$namespace = NS_FILE;
			} else {
				$namespace = $row->rd_namespace;
			}
			// T261347: be defensive when fetching data from the redirect table.
			// Use Title::makeTitleSafe(), and if that returns null, ignore the
			// row. In an ideal world, the DB would be cleaned up after a
			// namespace change, but nobody could be bothered to do that.
			$this->mRedirectTarget = Title::makeTitleSafe(
				$namespace, $row->rd_title,
				$row->rd_fragment, $row->rd_interwiki
			);
			$this->mHasRedirectTarget = $this->mRedirectTarget !== null;
			return $this->mRedirectTarget;
		}

		// This page doesn't have an entry in the redirect table
		$this->mRedirectTarget = $this->insertRedirect();
		$this->mHasRedirectTarget = $this->mRedirectTarget !== null;
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
		$retval = $content ? $content->getRedirectTarget() : null;
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
			wfGetDB( DB_PRIMARY )
		);

		return $retval;
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate it redirects to $rt
	 * @param LinkTarget $rt Redirect target
	 * @param int|null $oldLatest Prior page_latest for check and set
	 * @return bool Success
	 */
	public function insertRedirectEntry( LinkTarget $rt, $oldLatest = null ) {
		$rt = Title::castFromLinkTarget( $rt );
		if ( !$rt->isValidRedirectTarget() ) {
			// Don't put a bad redirect into the database (T278367)
			return false;
		}

		$dbw = wfGetDB( DB_PRIMARY );
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
				'rd_from',
				[
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $truncatedFragment,
					'rd_interwiki' => $rt->getInterwiki(),
				],
				__METHOD__
			);
			$success = true;
		} else {
			$success = false;
		}

		$dbw->endAtomic( __METHOD__ );

		return $success;
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
		} elseif ( !$rt->isValidRedirectTarget() ) {
			// We somehow got a bad redirect target into the database (T278367)
			return false;
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

		$revactor_actor = $actorQuery['fields']['rev_actor'];
		$fields = [
			'user_id' => $actorQuery['fields']['rev_user'],
			'user_name' => $actorQuery['fields']['rev_user_text'],
			'actor_id' => "MIN($revactor_actor)",
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
		$conds[] = "{$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER )} = 0";

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
		// NOTE: Keep in sync with ParserOutputAccess::shouldUseCache().
		// TODO: Once ParserOutputAccess is stable, deprecated this method.
		return $this->exists()
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
	 * @param ParserOptions|null $parserOptions ParserOptions to use for the parse operation
	 * @param null|int $oldid Revision ID to get the text from, passing null or 0 will
	 *   get the current revision (default value)
	 * @param bool $noCache Do not read from or write to caches.
	 * @return bool|ParserOutput ParserOutput or false if the revision was not found or is not public
	 */
	public function getParserOutput(
		?ParserOptions $parserOptions = null, $oldid = null, $noCache = false
	) {
		if ( $oldid ) {
			$revision = $this->getRevisionStore()->getRevisionByTitle( $this->getTitle(), $oldid );

			if ( !$revision ) {
				return false;
			}
		} else {
			$revision = $this->getRevisionRecord();
		}

		if ( !$parserOptions ) {
			$parserOptions = ParserOptions::newFromAnon();
		}

		$options = $noCache ? ParserOutputAccess::OPT_NO_CACHE : 0;

		$status = MediaWikiServices::getInstance()->getParserOutputAccess()->getParserOutput(
			$this, $parserOptions, $revision, $options
		);
		return $status->isOK() ? $status->getValue() : false; // convert null to false
	}

	/**
	 * Do standard deferred updates after page view (existing or missing page)
	 * @param Authority $performer The viewing user
	 * @param int $oldid Revision id being viewed; if not given or 0, latest revision is assumed
	 * @param RevisionRecord|null $oldRev The RevisionRecord associated with $oldid.
	 */
	public function doViewUpdates(
		Authority $performer,
		$oldid = 0,
		RevisionRecord $oldRev = null
	) {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			return;
		}

		DeferredUpdates::addCallableUpdate(
			function () use ( $performer ) {
				// In practice, these hook handlers simply debounce into a post-send
				// to do their work since none of the use cases for this hook require
				// a blocking pre-send callback.
				//
				// TODO: Move this hook to post-send.
				//
				// For now, it is unofficially possible for an extension to use
				// onPageViewUpdates to try to insert JavaScript via global $wgOut.
				// This isn't supported (the hook doesn't pass OutputPage), and
				// can't be since OutputPage may be disabled or replaced on some
				// pages that we do support page view updates for. We also run
				// this hook after HTMLFileCache, which also naturally can't
				// support modifying OutputPage. Handlers that modify the page
				// may use onBeforePageDisplay instead, which runs behind
				// HTMLFileCache and won't run on non-OutputPage responses.
				$legacyUser = MediaWikiServices::getInstance()
					->getUserFactory()
					->newFromAuthority( $performer );
				$this->getHookRunner()->onPageViewUpdates( $this, $legacyUser );
			},
			DeferredUpdates::PRESEND
		);

		// Update newtalk and watchlist notification status
		MediaWikiServices::getInstance()
			->getWatchlistManager()
			->clearTitleUserNotifications( $performer, $this, $oldid, $oldRev );
	}

	/**
	 * Perform the actions of a page purging
	 * @return bool
	 * @note In 1.28 (and only 1.28), this took a $flags parameter that
	 *  controlled how much purging was done.
	 */
	public function doPurge() {
		if ( !$this->getHookRunner()->onArticlePurge( $this ) ) {
			return false;
		}

		$this->mTitle->invalidateCache();

		// Clear file cache and send purge after above page_touched update was committed
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( $this->mTitle, $hcu::PURGE_PRESEND );

		if ( $this->mTitle->getNamespace() === NS_MEDIAWIKI ) {
			MediaWikiServices::getInstance()->getMessageCache()
				->updateMessageOverride( $this->mTitle, $this->getContent() );
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
				'page_is_redirect'  => 0, // Will set this shortly...
				'page_is_new'       => 1,
				'page_random'       => wfRandom(),
				'page_touched'      => $dbw->timestamp(),
				'page_latest'       => 0, // Fill this in shortly...
				'page_len'          => 0, // Fill this in shortly...
			] + $pageIdForInsert,
			__METHOD__,
			[ 'IGNORE' ]
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
	 * @param RevisionRecord $revision For ID number, and text used to set
	 *   length and redirect status fields.
	 * @param int|null $lastRevision If given, will not overwrite the page field
	 *   when different from the currently set value.
	 *   Giving 0 indicates the new page flag should be set on.
	 * @param bool|null $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return bool Success; false if the page row was missing or page_latest changed
	 */
	public function updateRevisionOn(
		$dbw,
		RevisionRecord $revision,
		$lastRevision = null,
		$lastRevIsRedirect = null
	) {
		// TODO: move into PageUpdater or PageStore
		// NOTE: when doing that, make sure cached fields get reset in doUserEditContent,
		// and in the compat stub!

		// Assertion to try to catch T92046
		if ( (int)$revision->getId() === 0 ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': revision has ID ' . var_export( $revision->getId(), true )
			);
		}

		$content = $revision->getContent( SlotRecord::MAIN );
		$len = $content ? $content->getSize() : 0;
		$rt = $content ? $content->getRedirectTarget() : null;
		$isNew = $lastRevision === 0;
		$isRedirect = $rt !== null;

		$conditions = [ 'page_id' => $this->getId() ];

		if ( $lastRevision !== null ) {
			// An extra check against threads stepping on each other
			$conditions['page_latest'] = $lastRevision;
		}

		$revId = $revision->getId();
		Assert::parameter( $revId > 0, '$revision->getId()', 'must be > 0' );

		$model = $revision->getSlot( SlotRecord::MAIN, RevisionRecord::RAW )->getModel();

		$row = [ /* SET */
			'page_latest'        => $revId,
			'page_touched'       => $dbw->timestamp( $revision->getTimestamp() ),
			'page_is_new'        => $isNew ? 1 : 0,
			'page_is_redirect'   => $isRedirect ? 1 : 0,
			'page_len'           => $len,
			'page_content_model' => $model,
		];

		$dbw->update( 'page',
			$row,
			$conditions,
			__METHOD__ );

		$result = $dbw->affectedRows() > 0;
		if ( $result ) {
			$insertedRow = $this->pageData( $dbw, [ 'page_id' => $this->getId() ] );

			if ( !$insertedRow ) {
				throw new MWException( 'Failed to load freshly inserted row' );
			}

			$this->mTitle->loadFromRow( $insertedRow );
			$this->updateRedirectOn( $dbw, $rt, $lastRevIsRedirect );
			$this->setLastEdit( $revision );
			$this->mRedirectTarget = null;
			$this->mHasRedirectTarget = null;
			$this->mPageIsRedirectField = (bool)$rt;
			$this->mIsNew = $isNew;
			$this->mIsRedirect = $isRedirect;

			// Update the LinkCache.
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			$linkCache->addGoodLinkObjFromRow(
				$this->mTitle,
				$insertedRow
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
	 * @internal
	 */
	public function updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = $redirectTitle !== null;

		if ( !$isRedirect && $lastRevIsRedirect === false ) {
			return true;
		}

		if ( $isRedirect ) {
			$success = $this->insertRedirectEntry( $redirectTitle );
		} else {
			// This is not a redirect, remove row from redirect table
			$where = [ 'rd_from' => $this->getId() ];
			$dbw->delete( 'redirect', $where, __METHOD__ );
			$success = true;
		}

		if ( $this->getTitle()->getNamespace() === NS_FILE ) {
			MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
				->invalidateImageRedirect( $this->getTitle() );
		}

		return $success;
	}

	/**
	 * Helper method for checking whether two revisions have differences that go
	 * beyond the main slot.
	 *
	 * MCR migration note: this method should go away!
	 *
	 * @deprecated Use only as a stop-gap before refactoring to support MCR.
	 *
	 * @param RevisionRecord $a
	 * @param RevisionRecord $b
	 * @return bool
	 */
	public static function hasDifferencesOutsideMainSlot( RevisionRecord $a, RevisionRecord $b ) {
		$aSlots = $a->getSlots();
		$bSlots = $b->getSlots();
		$changedRoles = $aSlots->getRolesWithDifferentContent( $bSlots );

		return ( $changedRoles !== [ SlotRecord::MAIN ] && $changedRoles !== [] );
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
			$rev = $this->getRevisionStore()->getRevisionByTimestamp( $this->mTitle, $edittime );
			// Try the primary database if this thread may have just added it.
			// The logic to fallback to the primary database if the replica is missing
			// the revision could be generalized into RevisionStore, but we don't want
			// to encourage loading of revisions by timestamp.
			if ( !$rev
				&& $lb->getServerCount() > 1
				&& $lb->hasOrMadeRecentPrimaryChanges()
			) {
				$rev = $this->getRevisionStore()->getRevisionByTimestamp(
					$this->mTitle, $edittime, RevisionStore::READ_LATEST );
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
			if ( $baseRevId === null || $sectionId === 'new' ) {
				$oldContent = $this->getContent();
			} else {
				$revRecord = $this->getRevisionStore()->getRevisionById( $baseRevId );
				if ( !$revRecord ) {
					wfDebug( __METHOD__ . " asked for bogus section (page: " .
						$this->getId() . "; section: $sectionId)" );
					return null;
				}

				$oldContent = $revRecord->getContent( SlotRecord::MAIN );
			}

			if ( !$oldContent ) {
				wfDebug( __METHOD__ . ": no page text" );
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
	 * Returns a DerivedPageDataUpdater for use with the given target revision or new content.
	 * This method attempts to re-use the same DerivedPageDataUpdater instance for subsequent calls.
	 * The parameters passed to this method are used to ensure that the DerivedPageDataUpdater
	 * returned matches that caller's expectations, allowing an existing instance to be re-used
	 * if the given parameters match that instance's internal state according to
	 * DerivedPageDataUpdater::isReusableFor(), and creating a new instance of the parameters do not
	 * match the existing one.
	 *
	 * If neither $forRevision nor $forUpdate is given, a new DerivedPageDataUpdater is always
	 * created, replacing any DerivedPageDataUpdater currently cached.
	 *
	 * MCR migration note: this replaces WikiPage::prepareContentForEdit.
	 *
	 * @since 1.32
	 *
	 * @param UserIdentity|null $forUser The user that will be used for, or was used for, PST.
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
		UserIdentity $forUser = null,
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
			$this->derivedDataUpdater =
				$this->getPageUpdaterFactory()->newDerivedPageDataUpdater( $this );
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
	 * @note Once extensions no longer rely on WikiPage to get access to the state of an ongoing
	 * edit via prepareContentForEdit() and WikiPage::getCurrentUpdate(),
	 * this method should be deprecated and callers should be migrated to using
	 * PageUpdaterFactory::newPageUpdater() instead.
	 *
	 * @param Authority|UserIdentity $performer
	 * @param RevisionSlotsUpdate|null $forUpdate If given, allows any cached ParserOutput
	 *        that may already have been returned via getDerivedDataUpdater to be re-used.
	 *
	 * @return PageUpdater
	 */
	public function newPageUpdater( $performer, RevisionSlotsUpdate $forUpdate = null ) {
		if ( $performer instanceof Authority ) {
			// TODO: Deprecate this. But better get rid of this method entirely.
			$performer = $performer->getUser();
		}

		$pageUpdater = $this->getPageUpdaterFactory()->newPageUpdaterForDerivedPageDataUpdater(
			$this,
			$performer,
			$this->getDerivedDataUpdater( $performer, null, $forUpdate, true )
		);

		return $pageUpdater;
	}

	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * @deprecated since 1.36, use PageUpdater::saveRevision instead. Note that the new method
	 * expects callers to take care of checking EDIT_MINOR against the minoredit right, and to
	 * apply the autopatrol right as appropriate.
	 *
	 * @param Content $content New content
	 * @param Authority $performer doing the edit
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
	 * @param array|null $tags Change tags to apply to this edit
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 * @param int $undidRevId Id of revision that was undone or 0
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
	 *     revision-record: The revision record object for the inserted revision, or null.
	 *
	 * @since 1.36
	 */
	public function doUserEditContent(
		Content $content,
		Authority $performer,
		$summary,
		$flags = 0,
		$originalRevId = false,
		$tags = [],
		$undidRevId = 0
	) {
		$useNPPatrol = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::UseNPPatrol );
		$useRCPatrol = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::UseRCPatrol );
		if ( !( $summary instanceof CommentStoreComment ) ) {
			$summary = CommentStoreComment::newUnsavedComment( trim( $summary ) );
		}

		// TODO: this check is here for backwards-compatibility with 1.31 behavior.
		// Checking the minoredit right should be done in the same place the 'bot' right is
		// checked for the EDIT_FORCE_BOT flag, which is currently in EditPage::attemptSave.
		if ( ( $flags & EDIT_MINOR ) && !$performer->isAllowed( 'minoredit' ) ) {
			$flags &= ~EDIT_MINOR;
		}

		$slotsUpdate = new RevisionSlotsUpdate();
		$slotsUpdate->modifyContent( SlotRecord::MAIN, $content );

		// NOTE: while doUserEditContent() executes, callbacks to getDerivedDataUpdater and
		// prepareContentForEdit will generally use the DerivedPageDataUpdater that is also
		// used by this PageUpdater. However, there is no guarantee for this.
		$updater = $this->newPageUpdater( $performer, $slotsUpdate )
				->setContent( SlotRecord::MAIN, $content )
			->setOriginalRevisionId( $originalRevId );
		if ( $undidRevId ) {
			$updater->markAsRevert(
				EditResult::REVERT_UNDO,
				$undidRevId,
				$originalRevId ?: null
			);
		}

		$needsPatrol = $useRCPatrol || ( $useNPPatrol && !$this->exists() );

		// TODO: this logic should not be in the storage layer, it's here for compatibility
		// with 1.31 behavior. Applying the 'autopatrol' right should be done in the same
		// place the 'bot' right is handled, which is currently in EditPage::attemptSave.

		if ( $needsPatrol && $performer->authorizeWrite( 'autopatrol', $this->getTitle() ) ) {
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
			$this->setLastEdit( $revRec );
		}

		return $updater->getStatus();
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 *
	 * @see ParserOptions::newCanonical
	 *
	 * @param IContextSource|UserIdentity|string $context One of the following:
	 *        - IContextSource: Use the User and the Language of the provided
	 *          context
	 *        - UserIdentity: Use the provided UserIdentity object and $wgLang
	 *          for the language, so use an IContextSource object if possible.
	 *        - 'canonical': Canonical options (anonymous user with default
	 *          preferences and content language).
	 * @return ParserOptions
	 */
	public function makeParserOptions( $context ) {
		return self::makeParserOptionsFromTitleAndModel(
			$this->getTitle(), $this->getContentModel(), $context
		);
	}

	/**
	 * Create canonical parser options for a given title and content model.
	 * @internal
	 * @param PageReference $pageRef
	 * @param string $contentModel
	 * @param IContextSource|UserIdentity|string $context See ::makeParserOptions
	 * @return ParserOptions
	 */
	public static function makeParserOptionsFromTitleAndModel(
		PageReference $pageRef, string $contentModel, $context
	) {
		$options = ParserOptions::newCanonical( $context );

		$title = Title::castFromPageReference( $pageRef );
		if ( $title->isConversionTable() ) {
			// @todo ConversionTable should become a separate content model, so
			// we don't need special cases like this one, but see T313455.
			$options->disableContentConversion();
		}
		if ( $contentModel !== CONTENT_MODEL_WIKITEXT ) {
			$textModelsToParse = MediaWikiServices::getInstance()->getMainConfig()->get(
				MainConfigNames::TextModelsToParse );
			if ( in_array( $contentModel, $textModelsToParse, true ) ) {
				// @todo Content model should have a means to tweak options, so
				// we don't need special cases like this one. (T313455)
				// ( See TextContentHandler::fillParserOutput() )
				$options->setSuppressTOC(); # T307691
			}
		}

		return $options;
	}

	/**
	 * Prepare content which is about to be saved.
	 *
	 * Prior to 1.30, this returned a stdClass.
	 *
	 * @deprecated since 1.32, use newPageUpdater() or getCurrentUpdate() instead.
	 * @note Calling without a UserIdentity was separately deprecated from 1.37 to 1.39, since
	 * 1.39 the UserIdentity has been required.
	 *
	 * @param Content $content
	 * @param RevisionRecord|null $revision
	 *        Used with vary-revision or vary-revision-id.
	 * @param UserIdentity $user
	 * @param string|null $serialFormat IGNORED
	 * @param bool $useStash Use prepared edit stash
	 *
	 * @return PreparedEdit
	 *
	 * @since 1.21
	 */
	public function prepareContentForEdit(
		Content $content,
		?RevisionRecord $revision,
		UserIdentity $user,
		$serialFormat = null,
		$useStash = true
	) {
		$slots = RevisionSlotsUpdate::newFromContent( [ SlotRecord::MAIN => $content ] );
		$updater = $this->getDerivedDataUpdater( $user, $revision, $slots );

		if ( !$updater->isUpdatePrepared() ) {
			$updater->prepareContent( $user, $slots, $useStash );

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
	 * @deprecated since 1.32 (soft), use DerivedPageDataUpdater::doUpdates instead.
	 *
	 * @param RevisionRecord $revisionRecord (Switched from the old Revision class to
	 *    RevisionRecord since 1.35)
	 * @param UserIdentity $user User object that did the revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: RevisionRecord object for the pre-update revision (default null)
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
	public function doEditUpdates(
		RevisionRecord $revisionRecord,
		UserIdentity $user,
		array $options = []
	) {
		$options += [
			'causeAction' => 'edit-page',
			'causeAgent' => $user->getName(),
		];

		$updater = $this->getDerivedDataUpdater( $user, $revisionRecord );

		$updater->prepareUpdate( $revisionRecord, $options );

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
				__METHOD__ . ' called with ' . ( $revision ? 'unsaved' : 'no' ) . ' revision'
			);
			return;
		}
		$userIdentity = $revision->getUser( RevisionRecord::RAW );

		$updater = $this->getDerivedDataUpdater( $userIdentity, $revision );
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
	 *   - known-revision-output: a combined canonical ParserOutput for the revision, perhaps
	 *     from some cache. The caller is responsible for ensuring that the ParserOutput indeed
	 *     matched the $rev and $options. This mechanism is intended as a temporary stop-gap,
	 *     for the time until caches have been changed to store RenderedRevision states instead
	 *     of ParserOutput objects. (default: null) (since 1.33)
	 * @since 1.32
	 */
	public function doSecondaryDataUpdates( array $options = [] ) {
		$options['recursive'] = $options['recursive'] ?? true;
		$revision = $this->getRevisionRecord();
		if ( !$revision || !$revision->getId() ) {
			LoggerFactory::getInstance( 'wikipage' )->info(
				__METHOD__ . ' called with ' . ( $revision ? 'unsaved' : 'no' ) . ' revision'
			);
			return;
		}
		$userIdentity = $revision->getUser( RevisionRecord::RAW );

		$updater = $this->getDerivedDataUpdater( $userIdentity, $revision );
		$updater->prepareUpdate( $revision, $options );
		$updater->doSecondaryDataUpdates( $options );
	}

	/**
	 * Update the article's restriction field, and leave a log entry.
	 * This works for protection both existing and non-existing pages.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param bool &$cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param UserIdentity $user The user updating the restrictions
	 * @param string[] $tags Change tags to add to the pages and protection log entries
	 *   ($user should be able to add the specified tags before this is called)
	 * @return Status Status object; if action is taken, $status->value is the log_id of the
	 *   protection log entry.
	 */
	public function doUpdateRestrictions( array $limit, array $expiry,
		&$cascade, $reason, UserIdentity $user, $tags = []
	) {
		$readOnlyMode = MediaWikiServices::getInstance()->getReadOnlyMode();
		if ( $readOnlyMode->isReadOnly() ) {
			return Status::newFatal( wfMessage( 'readonlytext', $readOnlyMode->getReason() ) );
		}

		$this->loadPageData( 'fromdbmaster' );
		$restrictionStore = MediaWikiServices::getInstance()->getRestrictionStore();
		$restrictionStore->loadRestrictions( $this->mTitle, IDBAccessObject::READ_LATEST );
		$restrictionTypes = $restrictionStore->listApplicableRestrictionTypes( $this->mTitle );
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

		$dbw = wfGetDB( DB_PRIMARY );

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
			$current = implode( '', $restrictionStore->getRestrictions( $this->mTitle, $action ) );
			if ( $current != '' ) {
				$isProtected = true;
			}

			if ( $limit[$action] != $current ) {
				$changed = true;
			} elseif ( $limit[$action] != '' ) {
				// Only check expiry change if the action is actually being
				// protected, since expiry does nothing on an not-protected
				// action.
				if ( $restrictionStore->getRestrictionExpiry( $this->mTitle, $action ) != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		if ( !$changed && $protect && $restrictionStore->areRestrictionsCascading( $this->mTitle ) != $cascade ) {
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
		$nullRevisionRecord = null;

		if ( $id ) { // Protection of existing page
			$legacyUser = MediaWikiServices::getInstance()->getUserFactory()->newFromUserIdentity( $user );
			if ( !$this->getHookRunner()->onArticleProtect( $this, $legacyUser, $limit, $reason ) ) {
				return Status::newGood();
			}

			// Only certain restrictions can cascade...
			$editrestriction = isset( $limit['edit'] )
				? [ $limit['edit'] ]
				: $restrictionStore->getRestrictions( $this->mTitle, 'edit' );
			foreach ( array_keys( $editrestriction, 'sysop' ) as $key ) {
				$editrestriction[$key] = 'editprotected'; // backwards compatibility
			}
			foreach ( array_keys( $editrestriction, 'autoconfirmed' ) as $key ) {
				$editrestriction[$key] = 'editsemiprotected'; // backwards compatibility
			}

			$cascadingRestrictionLevels = MediaWikiServices::getInstance()->getMainConfig()
				->get( MainConfigNames::CascadingRestrictionLevels );

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
			$nullRevisionRecord = $this->insertNullProtectionRevision(
				$revCommentMsg,
				$limit,
				$expiry,
				$cascade,
				$reason,
				$user
			);

			if ( $nullRevisionRecord === null ) {
				return Status::newFatal( 'no-null-revision', $this->mTitle->getPrefixedText() );
			}

			$logRelationsField = 'pr_id';

			// T214035: Avoid deadlock on MySQL.
			// Do a DELETE by primary key (pr_id) for any existing protection rows.
			// On MySQL and derivatives, unconditionally deleting by page ID (pr_page) would.
			// place a gap lock if there are no matching rows. This can deadlock when another
			// thread modifies protection settings for page IDs in the same gap.
			$existingProtectionIds = $dbw->selectFieldValues(
				'page_restrictions',
				'pr_id',
				[
					'pr_page' => $id,
					'pr_type' => array_map( 'strval', array_keys( $limit ) )
				],
				__METHOD__
			);

			if ( $existingProtectionIds ) {
				$dbw->delete(
					'page_restrictions',
					[ 'pr_id' => $existingProtectionIds ],
					__METHOD__
				);
			}

			// Update restrictions table
			foreach ( $limit as $action => $restrictions ) {
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

			$this->getHookRunner()->onRevisionFromEditComplete(
				$this, $nullRevisionRecord, $latest, $user, $tags );

			$this->getHookRunner()->onArticleProtectComplete( $this, $legacyUser, $limit, $reason );
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
		if ( $nullRevisionRecord !== null ) {
			$logEntry->setAssociatedRevId( $nullRevisionRecord->getId() );
		}
		$logEntry->addTags( $tags );
		if ( $logRelationsField !== null && count( $logRelationsValues ) ) {
			$logEntry->setRelations( [ $logRelationsField => $logRelationsValues ] );
		}
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		return Status::newGood( $logId );
	}

	/**
	 * Get the state of an ongoing update, shortly before or just after it is saved to the database.
	 * If there is no ongoing edit tracked by this WikiPage instance, this methods throws a
	 * PreconditionException.
	 *
	 * If possible, state is shared with subsequent calls of getPreparedUpdate(),
	 * prepareContentForEdit(), and newPageUpdater().
	 *
	 * @note This method should generally be avoided, since it forces WikiPage to maintain state
	 *       representing ongoing edits. Code that initiates an edit should use newPageUpdater()
	 *       instead. Hooks that interact with the edit should have a the relevant
	 *       information provided as a PageUpdater, PreparedUpdate, or RenderedRevision.
	 *
	 * @throws PreconditionException if there is no ongoing update. This method must only be
	 *         called after newPageUpdater() had already been called, typically while executing
	 *         a handler for a hook that is triggered during a page edit.
	 * @return PreparedUpdate
	 *
	 * @since 1.38
	 */
	public function getCurrentUpdate(): PreparedUpdate {
		Assert::precondition(
			$this->derivedDataUpdater !== null,
			'There is no ongoing update tracked by this instance of WikiPage!'
		);

		return $this->derivedDataUpdater;
	}

	/**
	 * Insert a new null revision for this page.
	 *
	 * @since 1.35
	 *
	 * @param string $revCommentMsg Comment message key for the revision
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param bool $cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param UserIdentity $user User to attribute to
	 * @return RevisionRecord|null Null on error
	 */
	public function insertNullProtectionRevision(
		string $revCommentMsg,
		array $limit,
		array $expiry,
		bool $cascade,
		string $reason,
		UserIdentity $user
	): ?RevisionRecord {
		$dbw = wfGetDB( DB_PRIMARY );

		// Prepare a null revision to be added to the history
		$editComment = wfMessage(
			$revCommentMsg,
			$this->mTitle->getPrefixedText(),
			$user->getName()
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

		$revStore = $this->getRevisionStore();
		$comment = CommentStoreComment::newUnsavedComment( $editComment );
		$nullRevRecord = $revStore->newNullRevision(
			$dbw,
			$this->getTitle(),
			$comment,
			true,
			$user
		);

		if ( $nullRevRecord ) {
			$inserted = $revStore->insertRevisionOn( $nullRevRecord, $dbw );

			// Update page record and touch page
			$oldLatest = $inserted->getParentId();

			$this->updateRevisionOn( $dbw, $inserted, $oldLatest );

			return $inserted;
		} else {
			return null;
		}
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
	 * Determines if deletion of this page would be batched (executed over time by the job queue)
	 * or not (completed in the same request as the delete call).
	 *
	 * It is unlikely but possible that an edit from another request could push the page over the
	 * batching threshold after this function is called, but before the caller acts upon the
	 * return value.  Callers must decide for themselves how to deal with this.  $safetyMargin
	 * is provided as an unreliable but situationally useful help for some common cases.
	 *
	 * @deprecated since 1.37 Use DeletePage::isBatchedDelete instead.
	 *
	 * @param int $safetyMargin Added to the revision count when checking for batching
	 * @return bool True if deletion would be batched, false otherwise
	 */
	public function isBatchedDelete( $safetyMargin = 0 ) {
		$deleteRevisionsBatchSize = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::DeleteRevisionsBatchSize );

		$dbr = wfGetDB( DB_REPLICA );
		$revCount = $this->getRevisionStore()->countRevisionsByPageId( $dbr, $this->getId() );
		$revCount += $safetyMargin;

		return $revCount >= $deleteRevisionsBatchSize;
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @since 1.19
	 * @since 1.35 Signature changed, user moved to second parameter to prepare for requiring
	 *             a user to be passed
	 * @since 1.36 User second parameter is required
	 * @deprecated since 1.37 Use DeletePage instead. Calling ::deleteIfAllowed and letting DeletePage handle
	 * permission checks is preferred over doing permission checks yourself and then calling ::deleteUnsafe.
	 * Note that DeletePage returns a good status with false value in case of scheduled deletion, instead of
	 * a status with a warning. Also, the new method doesn't have an $error parameter, since any error is
	 * added to the returned Status.
	 *
	 * @param string $reason Delete reason for deletion log
	 * @param UserIdentity $deleter The deleting user
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *   the suppression log instead of the deletion log
	 * @param bool|null $u1 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param mixed $u2 Unused
	 * @param string[]|null $tags Tags to apply to the deletion action
	 * @param string $logsubtype
	 * @param bool $immediate false allows deleting over time via the job queue
	 * @return Status Status object; if successful, $status->value is the log_id of the
	 *   deletion log entry. If the page couldn't be deleted because it wasn't
	 *   found, $status is a non-fatal 'cannotdelete' error
	 * @throws FatalError
	 * @throws MWException
	 */
	public function doDeleteArticleReal(
		$reason, UserIdentity $deleter, $suppress = false, $u1 = null, &$error = '', $u2 = null,
		$tags = [], $logsubtype = 'delete', $immediate = false
	) {
		$services = MediaWikiServices::getInstance();
		$deletePage = $services->getDeletePageFactory()->newDeletePage(
			$this,
			$services->getUserFactory()->newFromUserIdentity( $deleter )
		);

		$status = $deletePage
			->setSuppress( $suppress )
			->setTags( $tags ?: [] )
			->setLogSubtype( $logsubtype )
			->forceImmediate( $immediate )
			->keepLegacyHookErrorsSeparate()
			->deleteUnsafe( $reason );
		$error = $deletePage->getLegacyHookErrors();
		if ( $status->isGood() ) {
			// BC with old return format
			if ( $deletePage->deletionsWereScheduled()[DeletePage::PAGE_BASE] ) {
				$status->warning( 'delete-scheduled', wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) );
			} else {
				$status->value = $deletePage->getSuccessfulDeletionsIDs()[DeletePage::PAGE_BASE];
			}
		}
		return $status;
	}

	/**
	 * Back-end article deletion
	 *
	 * Only invokes batching via the job queue if necessary per $wgDeleteRevisionsBatchSize.
	 * Deletions can often be completed inline without involving the job queue.
	 *
	 * Potentially called many times per deletion operation for pages with many revisions.
	 * @deprecated since 1.37 No external caller besides DeletePageJob should use this.
	 *
	 * @param string $reason
	 * @param bool $suppress
	 * @param UserIdentity $deleter
	 * @param string[] $tags
	 * @param string $logsubtype
	 * @param bool $immediate
	 * @param string|null $webRequestId
	 * @return Status
	 */
	public function doDeleteArticleBatched(
		$reason, $suppress, UserIdentity $deleter, $tags,
		$logsubtype, $immediate = false, $webRequestId = null
	) {
		$services = MediaWikiServices::getInstance();
		$deletePage = $services->getDeletePageFactory()->newDeletePage(
			$this,
			$services->getUserFactory()->newFromUserIdentity( $deleter )
		);

		$status = $deletePage
			->setSuppress( $suppress )
			->setTags( $tags )
			->setLogSubtype( $logsubtype )
			->forceImmediate( $immediate )
			->deleteInternal( $this, DeletePage::PAGE_BASE, $reason, $webRequestId );
		if ( $status->isGood() ) {
			// BC with old return format
			if ( $deletePage->deletionsWereScheduled()[DeletePage::PAGE_BASE] ) {
				$status->warning( 'delete-scheduled', wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) );
			} else {
				$status->value = $deletePage->getSuccessfulDeletionsIDs()[DeletePage::PAGE_BASE];
			}
		}
		return $status;
	}

	/**
	 * Lock the page row for this title+id and return page_latest (or 0)
	 *
	 * @return int Returns 0 if no row was found with this title+id
	 * @since 1.27
	 */
	public function lockAndGetLatest() {
		return (int)wfGetDB( DB_PRIMARY )->selectField(
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
	 * @deprecated since 1.37 With no replacement.
	 *
	 * @param int $id The page_id value of the page being deleted
	 * @param Content|null $content Page content to be used when determining
	 *   the required updates. This may be needed because $this->getContent()
	 *   may already return null when the page proper was deleted.
	 * @param RevisionRecord|null $revRecord The current page revision at the time of
	 *   deletion, used when determining the required updates. This may be needed because
	 *   $this->getRevisionRecord() may already return null when the page proper was deleted.
	 * @param UserIdentity|null $user The user that caused the deletion
	 */
	public function doDeleteUpdates(
		$id,
		Content $content = null,
		RevisionRecord $revRecord = null,
		UserIdentity $user = null
	) {
		wfDeprecated( __METHOD__, '1.37' );
		if ( !$revRecord ) {
			throw new BadMethodCallException( __METHOD__ . ' now requires a RevisionRecord' );
		}
		if ( $id !== $this->getId() ) {
			throw new InvalidArgumentException( 'Mismatching page ID' );
		}

		$user = $user ?? new UserIdentityValue( 0, 'unknown' );
		$services = MediaWikiServices::getInstance();
		$deletePage = $services->getDeletePageFactory()->newDeletePage(
			$this,
			$services->getUserFactory()->newFromUserIdentity( $user )
		);

		$deletePage->doDeleteUpdates( $this, $revRecord );
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

		$services = MediaWikiServices::getInstance();
		$hcu = $services->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( [ $title, $other ], $hcu::PURGE_INTENT_TXROUND_REFLECTED );

		$title->touchLinks();
		$title->deleteTitleProtection();

		$services->getLinkCache()->invalidateTitle( $title );

		// Invalidate caches of articles which include this page
		$job = HTMLCacheUpdateJob::newForBacklinks(
			$title,
			'templatelinks',
			[ 'causeAction' => 'page-create' ]
		);
		$services->getJobQueueGroup()->lazyPush( $job );

		if ( $title->getNamespace() === NS_CATEGORY ) {
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
		$other = $title->getOtherPage();

		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( [ $title, $other ], $hcu::PURGE_INTENT_TXROUND_REFLECTED );

		$title->touchLinks();

		$services = MediaWikiServices::getInstance();
		$services->getLinkCache()->invalidateTitle( $title );

		InfoAction::invalidateCache( $title );

		// Messages
		if ( $title->getNamespace() === NS_MEDIAWIKI ) {
			$services->getMessageCache()->updateMessageOverride( $title, null );
		}

		// Images
		if ( $title->getNamespace() === NS_FILE ) {
			$job = HTMLCacheUpdateJob::newForBacklinks(
				$title,
				'imagelinks',
				[ 'causeAction' => 'page-delete' ]
			);
			$services->getJobQueueGroup()->lazyPush( $job );
		}

		// User talk pages
		if ( $title->getNamespace() === NS_USER_TALK ) {
			$user = User::newFromName( $title->getText(), false );
			if ( $user ) {
				MediaWikiServices::getInstance()
					->getTalkPageNotificationManager()
					->removeUserHasNewMessages( $user );
			}
		}

		// Image redirects
		$services->getRepoGroup()->getLocalRepo()->invalidateImageRedirect( $title );

		// Purge cross-wiki cache entities referencing this page
		self::purgeInterwikiCheckKey( $title );
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param Title $title
	 * @param RevisionRecord|null $revRecord revision that was just saved, may be null
	 * @param string[]|null $slotsChanged The role names of the slots that were changed.
	 *        If not given, all slots are assumed to have changed.
	 */
	public static function onArticleEdit(
		Title $title,
		RevisionRecord $revRecord = null,
		$slotsChanged = null
	) {
		// TODO: move this into a PageEventEmitter service

		$jobs = [];
		if ( $slotsChanged === null || in_array( SlotRecord::MAIN, $slotsChanged ) ) {
			// Invalidate caches of articles which include this page.
			// Only for the main slot, because only the main slot is transcluded.
			// TODO: MCR: not true for TemplateStyles! [SlotHandler]
			$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
				$title,
				'templatelinks',
				[ 'causeAction' => 'page-edit' ]
			);
		}
		// Invalidate the caches of all pages which redirect here
		$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
			$title,
			'redirect',
			[ 'causeAction' => 'page-edit' ]
		);
		$services = MediaWikiServices::getInstance();
		$services->getJobQueueGroup()->lazyPush( $jobs );

		$services->getLinkCache()->invalidateTitle( $title );

		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( $title, $hcu::PURGE_INTENT_TXROUND_REFLECTED );

		// Purge ?action=info cache
		$revid = $revRecord ? $revRecord->getId() : null;
		DeferredUpdates::addCallableUpdate( static function () use ( $title, $revid ) {
			InfoAction::invalidateCache( $title, $revid );
		} );

		// Purge cross-wiki cache entities referencing this page
		self::purgeInterwikiCheckKey( $title );
	}

	/** #@- */

	/**
	 * Purge the check key for cross-wiki cache entries referencing this page
	 *
	 * @param Title $title
	 */
	private static function purgeInterwikiCheckKey( Title $title ) {
		$enableScaryTranscluding = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::EnableScaryTranscluding );

		if ( !$enableScaryTranscluding ) {
			return; // @todo: perhaps this wiki is only used as a *source* for content?
		}

		DeferredUpdates::addCallableUpdate( static function () use ( $title ) {
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
			[ 'page_title' => 'cl_to', 'page_namespace' => NS_CATEGORY ],
			[ 'cl_from' => $id ],
			__METHOD__
		);

		return TitleArray::newFromResult( $res );
	}

	/**
	 * Returns a list of hidden categories this page is a member of.
	 * Uses the page_props and categorylinks tables.
	 *
	 * @return Title[]
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
	public function getAutoDeleteReason( &$hasHistory = false ) {
		if ( func_num_args() === 1 ) {
			wfDeprecated( __METHOD__ . ': $hasHistory parameter', '1.38' );
			return $this->getContentHandler()->getAutoDeleteReason( $this->getTitle(), $hasHistory );
		}
		return $this->getContentHandler()->getAutoDeleteReason( $this->getTitle() );
	}

	/**
	 * Update all the appropriate counts in the category table, given that
	 * we've added the categories $added and deleted the categories $deleted.
	 *
	 * This should only be called from deferred updates or jobs to avoid contention.
	 *
	 * @param string[] $added The names of categories that were added
	 * @param string[] $deleted The names of categories that were deleted
	 * @param int $id Page ID (this should be the original deleted page ID)
	 */
	public function updateCategoryCounts( array $added, array $deleted, $id = 0 ) {
		$id = $id ?: $this->getId();
		// Guard against data corruption T301433
		$added = array_map( 'strval', $added );
		$deleted = array_map( 'strval', $deleted );
		$type = MediaWikiServices::getInstance()->getNamespaceInfo()->
			getCategoryLinkType( $this->getTitle()->getNamespace() );

		$addFields = [ 'cat_pages = cat_pages + 1' ];
		$removeFields = [ 'cat_pages = cat_pages - 1' ];
		if ( $type !== 'page' ) {
			$addFields[] = "cat_{$type}s = cat_{$type}s + 1";
			$removeFields[] = "cat_{$type}s = cat_{$type}s - 1";
		}

		$dbw = wfGetDB( DB_PRIMARY );

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
					'cat_title',
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
			$this->getHookRunner()->onCategoryAfterPageAdded( $cat, $this );
		}

		foreach ( $deleted as $catName ) {
			$cat = Category::newFromName( $catName );
			$this->getHookRunner()->onCategoryAfterPageRemoved( $cat, $this, $id );
			// Refresh counts on categories that should be empty now (after commit, T166757)
			DeferredUpdates::addCallableUpdate( static function () use ( $cat ) {
				$cat->refreshCountsIfEmpty();
			} );
		}
	}

	/**
	 * Opportunistically enqueue link update jobs after a fresh parser output was generated.
	 *
	 * This method should only be called by PoolWorkArticleViewCurrent, after a page view
	 * experienced a miss from the ParserCache, and a new ParserOutput was generated.
	 * Specifically, for load reasons, this method must not get called during page views that
	 * use a cached ParserOutput.
	 *
	 * @since 1.25
	 * @internal For use by PoolWorkArticleViewCurrent
	 * @param ParserOutput $parserOutput Current version page output
	 */
	public function triggerOpportunisticLinksUpdate( ParserOutput $parserOutput ) {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			return;
		}

		if ( !$this->getHookRunner()->onOpportunisticLinksUpdate( $this,
			$this->mTitle, $parserOutput )
		) {
			return;
		}

		$config = MediaWikiServices::getInstance()->getMainConfig();

		$params = [
			'isOpportunistic' => true,
			'rootJobTimestamp' => $parserOutput->getCacheTime()
		];

		if ( MediaWikiServices::getInstance()->getRestrictionStore()->areRestrictionsCascading( $this->mTitle ) ) {
			// In general, MediaWiki does not re-run LinkUpdate (e.g. for search index, category
			// listings, and backlinks for Whatlinkshere), unless either the page was directly
			// edited, or was re-generate following a template edit propagating to an affected
			// page. As such, during page views when there is no valid ParserCache entry,
			// we re-parse and save, but leave indexes as-is.
			//
			// We make an exception for pages that have cascading protection (perhaps for a wiki's
			// "Main Page"). When such page is re-parsed on-demand after a parser cache miss, we
			// queue a high-priority LinksUpdate job, to ensure that we really protect all
			// content that is currently transcluded onto the page. This is important, because
			// wikitext supports conditional statements based on the current time, which enables
			// transcluding of a different sub page based on which day it is, and then show that
			// information on the Main Page, without the Main Page itself being edited.
			MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush(
				RefreshLinksJob::newPrioritized( $this->mTitle, $params )
			);
		} elseif ( !$config->get( MainConfigNames::MiserMode ) &&
			$parserOutput->hasReducedExpiry()
		) {
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
					MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush(
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
	 * @deprecated since 1.37 With no replacement.
	 *
	 * @param RevisionRecord|Content|null $rev The revision being deleted. Also accepts a Content
	 *       object for backwards compatibility.
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( $rev = null ) {
		wfDeprecated( __METHOD__, '1.37' );
		$user = new UserIdentityValue( 0, 'Legacy code hater' );
		$services = MediaWikiServices::getInstance();
		$deletePage = $services->getDeletePageFactory()->newDeletePage(
			$this,
			$services->getUserFactory()->newFromUserIdentity( $user )
		);

		if ( !$rev ) {
			wfDeprecated( __METHOD__ . ' without a RevisionRecord', '1.32' );

			try {
				$rev = $this->getRevisionRecord();
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $ex ) {
				// If we can't load the content, something is wrong. Perhaps that's why
				// the user is trying to delete the page, so let's not fail in that case.
				// Note that doDeleteArticleReal() will already have logged an issue with
				// loading the content.
				wfDebug( __METHOD__ . ' failed to load current revision of page ' . $this->getId() );
			}
		}
		if ( !$rev ) {
			// Use an empty RevisionRecord
			$newRev = new MutableRevisionRecord( $this );
		} elseif ( $rev instanceof Content ) {
			wfDeprecated( __METHOD__ . ' with a Content object instead of a RevisionRecord', '1.32' );
			$newRev = new MutableRevisionRecord( $this );
			$newRev->setSlot( SlotRecord::newUnsaved( SlotRecord::MAIN, $rev ) );
		} else {
			$newRev = $rev;
		}
		return $deletePage->getDeletionUpdates( $this, $newRev );
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
		$sitename = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::Sitename );
		return $sitename;
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

	/**
	 * Ensure consistency when unserializing.
	 * @note WikiPage objects should never be serialized in the first place.
	 * But some extensions like AbuseFilter did (see T213006),
	 * and we need to be able to read old data (see T187153).
	 */
	public function __wakeup() {
		// Make sure we re-fetch the latest state from the database.
		// In particular, the latest revision may have changed.
		// As a side-effect, this makes sure mLastRevision doesn't
		// end up being an instance of the old Revision class (see T259181),
		// especially since that class was removed entirely in 1.37.
		$this->clear();
	}

	/**
	 * @inheritDoc
	 * @since 1.36
	 */
	public function getNamespace(): int {
		return $this->getTitle()->getNamespace();
	}

	/**
	 * @inheritDoc
	 * @since 1.36
	 */
	public function getDBkey(): string {
		return $this->getTitle()->getDBkey();
	}

	/**
	 * @return false self::LOCAL
	 * @since 1.36
	 */
	public function getWikiId() {
		return $this->getTitle()->getWikiId();
	}

	/**
	 * @return true
	 * @since 1.36
	 */
	public function canExist(): bool {
		return true;
	}

	/**
	 * @inheritDoc
	 * @since 1.36
	 */
	public function __toString(): string {
		return $this->mTitle->__toString();
	}

	/**
	 * @inheritDoc
	 * @since 1.36
	 *
	 * @param PageReference $other
	 * @return bool
	 */
	public function isSamePageAs( PageReference $other ): bool {
		// NOTE: keep in sync with PageIdentityValue::isSamePageAs()!

		if ( $other->getWikiId() !== $this->getWikiId() ) {
			return false;
		}

		if ( $other->getNamespace() !== $this->getNamespace()
			|| $other->getDBkey() !== $this->getDBkey() ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the page represented by this WikiPage as a PageStoreRecord.
	 * The PageRecord returned by this method is guaranteed to be immutable.
	 *
	 * It is preferred to use this method rather than using the WikiPage as a PageIdentity directly.
	 * @since 1.36
	 *
	 * @throws PreconditionException if the page does not exist.
	 *
	 * @return ExistingPageRecord
	 */
	public function toPageRecord(): ExistingPageRecord {
		// TODO: replace individual member fields with a PageRecord instance that is always present

		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		Assert::precondition(
			$this->exists(),
			'This WikiPage instance does not represent an existing page: ' . $this->mTitle
		);

		return new PageStoreRecord(
			(object)[
				'page_id' => $this->getId(),
				'page_namespace' => $this->mTitle->getNamespace(),
				'page_title' => $this->mTitle->getDBkey(),
				'page_latest' => $this->mLatest,
				'page_is_new' => $this->mIsNew ? 1 : 0,
				'page_is_redirect' => $this->mIsRedirect ? 1 : 0,
				'page_touched' => $this->getTouched(),
				'page_lang' => $this->getLanguage()
			],
			PageIdentity::LOCAL
		);
	}

}
