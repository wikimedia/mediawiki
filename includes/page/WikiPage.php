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

namespace MediaWiki\Page;

use BadMethodCallException;
use InvalidArgumentException;
use MediaWiki\Actions\InfoAction;
use MediaWiki\Category\Category;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Context\IContextSource;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\JobQueue\Jobs\HTMLCacheUpdateJob;
use MediaWiki\JobQueue\Jobs\RefreshLinksJob;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Event\PageProtectionChangedEvent;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Storage\PageUpdateStatus;
use MediaWiki\Storage\PreparedUpdate;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;
use MediaWiki\User\User;
use MediaWiki\User\UserArray;
use MediaWiki\User\UserArrayFromResult;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\WikiMap\WikiMap;
use RuntimeException;
use stdClass;
use Stringable;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\NonSerializable\NonSerializableTrait;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @defgroup Page Page
 */

/**
 * Base representation for an editable wiki page.
 *
 * Some fields are public only for backwards-compatibility. Use accessor methods.
 * In the past, this class was part of Article.php and everything was public.
 *
 * @ingroup Page
 */
class WikiPage implements Stringable, Page, PageRecord {
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
	 * @var bool
	 */
	private $mIsNew = false;

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
	protected $mDataLoadedFrom = IDBAccessObject::READ_NONE;

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

	public function __construct( PageIdentity $pageIdentity ) {
		$pageIdentity->assertWiki( PageIdentity::LOCAL );

		// TODO: remove the need for casting to Title.
		$title = Title::newFromPageIdentity( $pageIdentity );
		if ( !$title->canExist() ) {
			throw new InvalidArgumentException( "WikiPage constructed on a Title that cannot exist as a page: $title" );
		}

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
	 * Convert 'fromdb', 'fromdbmaster' and 'forupdate' to READ_* constants.
	 *
	 * @param stdClass|string|int $type
	 * @return mixed
	 */
	public static function convertSelectType( $type ) {
		switch ( $type ) {
			case 'fromdb':
				return IDBAccessObject::READ_NORMAL;
			case 'fromdbmaster':
				return IDBAccessObject::READ_LATEST;
			case 'forupdate':
				return IDBAccessObject::READ_LOCKING;
			default:
				// It may already be an integer or whatever else
				return $type;
		}
	}

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
		$this->mDataLoadedFrom = IDBAccessObject::READ_NONE;

		$this->clearCacheFields();
	}

	/**
	 * Clear the object cache fields
	 * @return void
	 */
	protected function clearCacheFields() {
		$this->mId = null;
		$this->mPageIsRedirectField = false;
		$this->mLastRevision = null; // Latest revision
		$this->mTouched = '19700101000000';
		$this->mLanguage = null;
		$this->mLinksUpdated = '19700101000000';
		$this->mTimestamp = '';
		$this->mIsNew = false;
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
	 *   - tables: (string[]) to include in the `$table` to `IReadableDatabase->select()` or
	 *     `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IReadableDatabase->select()` or
	 *     `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IReadableDatabase->select()` or
	 *     `SelectQueryBuilder::joinConds`
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
	 * @param IReadableDatabase $dbr
	 * @param array $conditions
	 * @param array $options
	 * @return stdClass|false Database result resource, or false on failure
	 */
	protected function pageData( $dbr, $conditions, $options = [] ) {
		$pageQuery = self::getQueryInfo();

		$this->getHookRunner()->onArticlePageDataBefore(
			$this, $pageQuery['fields'], $pageQuery['tables'], $pageQuery['joins'] );

		$row = $dbr->newSelectQueryBuilder()
			->queryInfo( $pageQuery )
			->where( $conditions )
			->caller( __METHOD__ )
			->options( $options )
			->fetchRow();

		$this->getHookRunner()->onArticlePageDataAfter( $this, $row );

		return $row;
	}

	/**
	 * Fetch a page record matching the Title object's namespace and title
	 * using a sanitized title string
	 *
	 * @param IReadableDatabase $dbr
	 * @param Title $title
	 * @param int $recency
	 * @return stdClass|false Database result resource, or false on failure
	 */
	public function pageDataFromTitle( $dbr, $title, $recency = IDBAccessObject::READ_NORMAL ) {
		if ( !$title->canExist() ) {
			return false;
		}
		$options = [];
		if ( ( $recency & IDBAccessObject::READ_EXCLUSIVE ) == IDBAccessObject::READ_EXCLUSIVE ) {
			$options[] = 'FOR UPDATE';
		} elseif ( ( $recency & IDBAccessObject::READ_LOCKING ) == IDBAccessObject::READ_LOCKING ) {
			$options[] = 'LOCK IN SHARE MODE';
		}

		return $this->pageData( $dbr, [
			'page_namespace' => $title->getNamespace(),
			'page_title' => $title->getDBkey() ], $options );
	}

	/**
	 * Fetch a page record matching the requested ID
	 *
	 * @param IReadableDatabase $dbr
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
	 *   - "fromdb" or IDBAccessObject::READ_NORMAL to get from a replica DB.
	 *   - "fromdbmaster" or IDBAccessObject::READ_LATEST to get from the primary DB.
	 *   - "forupdate"  or IDBAccessObject::READ_LOCKING to get from the primary DB
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
			$loadBalancer = $this->getDBLoadBalancer();
			if ( ( $from & IDBAccessObject::READ_LATEST ) == IDBAccessObject::READ_LATEST ) {
				$index = DB_PRIMARY;
			} else {
				$index = DB_REPLICA;
			}
			$db = $loadBalancer->getConnection( $index );
			$data = $this->pageDataFromTitle( $db, $this->mTitle, $from );

			if ( !$data
				&& $index == DB_REPLICA
				&& $loadBalancer->hasReplicaServers()
				&& $loadBalancer->hasOrMadeRecentPrimaryChanges()
			) {
				$from = IDBAccessObject::READ_LATEST;
				$db = $loadBalancer->getConnection( DB_PRIMARY );
				$data = $this->pageDataFromTitle( $db, $this->mTitle, $from );
			}
		} else {
			// No idea from where the caller got this data, assume replica DB.
			$data = $from;
			$from = IDBAccessObject::READ_NORMAL;
		}

		$this->loadFromRow( $data, $from );
	}

	/**
	 * Checks whether the page data was loaded using the given database access mode (or better).
	 *
	 * @since 1.32
	 *
	 * @param string|int $from One of the following:
	 *   - "fromdb" or IDBAccessObject::READ_NORMAL to get from a replica DB.
	 *   - "fromdbmaster" or IDBAccessObject::READ_LATEST to get from the primary DB.
	 *   - "forupdate"  or IDBAccessObject::READ_LOCKING to get from the primary DB
	 *     using SELECT FOR UPDATE.
	 *
	 * @return bool
	 */
	public function wasLoadedFrom( $from ) {
		$from = self::convertSelectType( $from );

		if ( !is_int( $from ) ) {
			// No idea from where the caller got this data, assume replica DB.
			$from = IDBAccessObject::READ_NORMAL;
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
	 * @param stdClass|false $data DB row containing fields returned by getQueryInfo() or false
	 * @param string|int $from One of the following:
	 *        - "fromdb" or IDBAccessObject::READ_NORMAL if the data comes from a replica DB
	 *        - "fromdbmaster" or IDBAccessObject::READ_LATEST if the data comes from the primary DB
	 *        - "forupdate"  or IDBAccessObject::READ_LOCKING if the data comes from
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
		$this->loadPageData();
		if ( $this->mPageIsRedirectField ) {
			return MediaWikiServices::getInstance()->getRedirectLookup()
					->getRedirectTarget( $this->getTitle() ) !== null;
		}

		return false;
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
	 * @return string Timestamp in TS_MW format
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
	 * @return string|null Timestamp in TS_MW format
	 */
	public function getLinksTimestamp() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mLinksUpdated;
	}

	/**
	 * Get the page_latest field
	 * @param string|false $wikiId
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

		if ( $this->mDataLoadedFrom == IDBAccessObject::READ_LOCKING ) {
			// T39225: if session S1 loads the page row FOR UPDATE, the result always
			// includes the latest changes committed. This is true even within REPEATABLE-READ
			// transactions, where S1 normally only sees changes committed before the first S1
			// SELECT. Thus we need S1 to also gets the revision row FOR UPDATE; otherwise, it
			// may not find it since a page row UPDATE and revision row INSERT by S2 may have
			// happened after the first S1 SELECT.
			// https://dev.mysql.com/doc/refman/5.7/en/set-transaction.html#isolevel_repeatable-read
			$revision = $this->getRevisionStore()
				->getRevisionByPageId( $this->getId(), $latest, IDBAccessObject::READ_LOCKING );
		} elseif ( $this->mDataLoadedFrom == IDBAccessObject::READ_LATEST ) {
			// Bug T93976: if page_latest was loaded from the primary DB, fetch the
			// revision from there as well, as it may not exist yet on a replica DB.
			// Also, this keeps the queries in the same REPEATABLE-READ snapshot.
			$revision = $this->getRevisionStore()
				->getRevisionByPageId( $this->getId(), $latest, IDBAccessObject::READ_LATEST );
		} else {
			$revision = $this->getRevisionStore()->getKnownCurrentRevision( $this->getTitle(), $latest );
		}

		if ( $revision ) {
			$this->setLastEdit( $revision );
		}
	}

	/**
	 * Set the latest revision
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
		return $this->mLastRevision;
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
	public function getContent( $audience = RevisionRecord::FOR_PUBLIC, ?Authority $performer = null ) {
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
	public function getUser( $audience = RevisionRecord::FOR_PUBLIC, ?Authority $performer = null ) {
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
	public function getCreator( $audience = RevisionRecord::FOR_PUBLIC, ?Authority $performer = null ) {
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
	public function getUserText( $audience = RevisionRecord::FOR_PUBLIC, ?Authority $performer = null ) {
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
	public function getComment( $audience = RevisionRecord::FOR_PUBLIC, ?Authority $performer = null ) {
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
	 * Whether the page may count towards the the site's number of "articles".
	 *
	 * This is tracked in the `site_stats` table, and calculated based on the
	 * namespace, page metadata, and content.
	 *
	 * @see $wgArticleCountMethod
	 * @see SlotRoleHandler::supportsArticleCount
	 * @see Content::isCountable
	 * @see WikitextContent::isCountable
	 * @param PreparedEdit|PreparedUpdate|false $editInfo (false):
	 *   An object returned by prepareTextForEdit() or getCurrentUpdate() respectively;
	 *   If false is given, the current database state will be used.
	 *
	 * @return bool
	 */
	public function isCountable( $editInfo = false ) {
		$mwServices = MediaWikiServices::getInstance();
		$articleCountMethod = $mwServices->getMainConfig()->get( MainConfigNames::ArticleCountMethod );

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
				$hasLinks = $editInfo->output->hasLinks();
			} else {
				// NOTE: keep in sync with RevisionRenderer::getLinkCount
				// NOTE: keep in sync with DerivedPageDataUpdater::isCountable
				$dbr = $mwServices->getConnectionProvider()->getReplicaDatabase();
				$hasLinks = (bool)$dbr->newSelectQueryBuilder()
					->select( '1' )
					->from( 'pagelinks' )
					->where( [ 'pl_from' => $this->getId() ] )
					->caller( __METHOD__ )->fetchField();
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
	 *
	 * @deprecated since 1.38 Use RedirectLookup::getRedirectTarget() instead.
	 *
	 * @return Title|null Title object, or null if this page is not a redirect
	 */
	public function getRedirectTarget() {
		$target = MediaWikiServices::getInstance()->getRedirectLookup()->getRedirectTarget( $this );
		return Title::castFromLinkTarget( $target );
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate it redirects to $rt
	 * @deprecated since 1.43; use {@link RedirectStore::updateRedirectTarget()} instead.
	 * @param LinkTarget $rt Redirect target
	 * @param int|null $oldLatest Prior page_latest for check and set
	 * @return bool Success
	 */
	public function insertRedirectEntry( LinkTarget $rt, $oldLatest = null ) {
		return MediaWikiServices::getInstance()->getRedirectStore()
			->updateRedirectTarget( $this, $rt );
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
	 * @return Title|string|false False, Title object of local target, or string with URL
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
	 * @return UserArray
	 */
	public function getContributors() {
		// @todo: This is expensive; cache this info somewhere.

		$services = MediaWikiServices::getInstance();
		$dbr = $services->getConnectionProvider()->getReplicaDatabase();
		$actorNormalization = $services->getActorNormalization();
		$userIdentityLookup = $services->getUserIdentityLookup();

		$user = $this->getUser()
			? User::newFromId( $this->getUser() )
			: User::newFromName( $this->getUserText(), false );

		$res = $dbr->newSelectQueryBuilder()
			->select( [
				'user_id' => 'actor_user',
				'user_name' => 'actor_name',
				'actor_id' => 'MIN(rev_actor)',
				'user_real_name' => 'MIN(user_real_name)',
				'timestamp' => 'MAX(rev_timestamp)',
			] )
			->from( 'revision' )
			->join( 'actor', null, 'rev_actor = actor_id' )
			->leftJoin( 'user', null, 'actor_user = user_id' )
			->where( [
				'rev_page' => $this->getId(),
				// The user who made the top revision gets credited as "this page was last edited by
				// John, based on contributions by Tom, Dick and Harry", so don't include them twice.
				$dbr->expr( 'rev_actor', '!=', $actorNormalization->findActorId( $user, $dbr ) ),
				// Username hidden?
				$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER ) . ' = 0',
			] )
			->groupBy( [ 'actor_user', 'actor_name' ] )
			->orderBy( 'timestamp', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchResultSet();
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
	 * @return ParserOutput|false ParserOutput or false if the revision was not found or is not public
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
		?RevisionRecord $oldRev = null
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
		InfoAction::invalidateCache( $this->mTitle, $this->getLatest() );

		return true;
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateRevisionOn( ... );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * @internal Low level interface, not safe for use in extensions!
	 *
	 * @todo Factor out into a PageStore service, to be used by PageUpdater.
	 *
	 * @param IDatabase $dbw
	 * @param int|null $pageId Custom page ID that will be used for the insert statement
	 *
	 * @return int|false The newly created page_id key; false if the row was not
	 *   inserted, e.g. because the title already existed or because the specified
	 *   page ID is already in use.
	 */
	public function insertOn( $dbw, $pageId = null ) {
		$pageIdForInsert = $pageId ? [ 'page_id' => $pageId ] : [];
		$dbw->newInsertQueryBuilder()
			->insertInto( 'page' )
			->ignore()
			->row( [
				'page_namespace'    => $this->mTitle->getNamespace(),
				'page_title'        => $this->mTitle->getDBkey(),
				'page_is_redirect'  => 0, // Will set this shortly...
				'page_is_new'       => 1,
				'page_random'       => wfRandom(),
				'page_touched'      => $dbw->timestamp(),
				'page_latest'       => 0, // Fill this in shortly...
				'page_len'          => 0, // Fill this in shortly...
			] + $pageIdForInsert )
			->caller( __METHOD__ )->execute();

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
	 * @internal Low level interface, not safe for use in extensions!
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

		$revId = $revision->getId();
		Assert::parameter( $revId > 0, '$revision->getId()', 'must be > 0' );

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

		$model = $revision->getMainContentModel();

		$row = [ /* SET */
			'page_latest'        => $revId,
			'page_touched'       => $dbw->timestamp( $revision->getTimestamp() ),
			'page_is_new'        => $isNew ? 1 : 0,
			'page_is_redirect'   => $isRedirect ? 1 : 0,
			'page_len'           => $len,
			'page_content_model' => $model,
		];

		$dbw->newUpdateQueryBuilder()
			->update( 'page' )
			->set( $row )
			->where( $conditions )
			->caller( __METHOD__ )->execute();

		$result = $dbw->affectedRows() > 0;
		if ( $result ) {
			$insertedRow = $this->pageData( $dbw, [ 'page_id' => $this->getId() ] );

			if ( !$insertedRow ) {
				throw new RuntimeException( 'Failed to load freshly inserted row' );
			}

			$this->mTitle->loadFromRow( $insertedRow );
			MediaWikiServices::getInstance()->getRedirectStore()
				->updateRedirectTarget( $this, $rt, $lastRevIsRedirect );
			$this->setLastEdit( $revision );
			$this->mPageIsRedirectField = (bool)$rt;
			$this->mIsNew = $isNew;

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
	 * Helper method for checking whether two revisions have differences that go
	 * beyond the main slot.
	 *
	 * MCR migration note: this method should go away!
	 *
	 * @deprecated since 1.43; Use only as a stop-gap before refactoring to support MCR.
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
	 * @param string|int|null|false $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param string $edittime Revision timestamp or null to use the current revision.
	 *
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
				&& $lb->hasReplicaServers()
				&& $lb->hasOrMadeRecentPrimaryChanges()
			) {
				$rev = $this->getRevisionStore()->getRevisionByTimestamp(
					$this->mTitle, $edittime, IDBAccessObject::READ_LATEST );
			}
			if ( $rev ) {
				$baseRevId = $rev->getId();
			}
		}

		return $this->replaceSectionAtRev( $sectionId, $sectionContent, $sectionTitle, $baseRevId );
	}

	/**
	 * @param string|int|null|false $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param int|null $baseRevId
	 *
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
				throw new BadMethodCallException( "sections not supported for content model " .
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
		?UserIdentity $forUser = null,
		?RevisionRecord $forRevision = null,
		?RevisionSlotsUpdate $forUpdate = null,
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
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * @param Content $content New content
	 * @param Authority $performer doing the edit
	 * @param string|CommentStoreComment $summary Edit summary
	 * @param int $flags Bitfield, see the EDIT_XXX constants such as EDIT_NEW
	 *        or EDIT_FORCE_BOT.
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the
	 * article will be detected. If EDIT_UPDATE is specified and the article
	 * doesn't exist, the function will return an edit-gone-missing error. If
	 * EDIT_NEW is specified and the article does exist, an edit-already-exists
	 * error will be returned. These two conditions are also possible with
	 * auto-detection due to MediaWiki's performance-optimised locking strategy.
	 *
	 * @param int|false $originalRevId: The ID of an original revision that the edit
	 * restores or repeats. The new revision is expected to have the exact same content as
	 * the given original revision. This is used with rollbacks and with dummy "null" revisions
	 * which are created to record things like page moves. Default is false, meaning we are not
	 * making a rollback edit.
	 * @param array|null $tags Change tags to apply to this edit
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 * @param int $undidRevId Id of revision that was undone or 0
	 *
	 * @return PageUpdateStatus Possible errors:
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
	 * @deprecated since 1.36, use PageUpdater::saveRevision instead. Note that the new method
	 * expects callers to take care of checking EDIT_MINOR against the minoredit right, and to
	 * apply the autopatrol right as appropriate.
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
	): PageUpdateStatus {
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
			$updater->setCause( PageUpdateCauses::CAUSE_UNDO );
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
	public function newPageUpdater( $performer, ?RevisionSlotsUpdate $forUpdate = null ) {
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

		$title = Title::newFromPageReference( $pageRef );
		if ( $title->isConversionTable() ) {
			// @todo ConversionTable should become a separate content model, so
			// we don't need special cases like this one, but see T313455.
			$options->disableContentConversion();
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
	 * Does not emit domain events.
	 *
	 * @deprecated since 1.32, use DerivedPageDataUpdater::doUpdates instead.
	 *             Emitting warnings since 1.44
	 *
	 * @param RevisionRecord $revisionRecord (Switched from the old Revision class to
	 *    RevisionRecord since 1.35)
	 * @param UserIdentity $user User object that did the revision
	 * @param array $options Array of options, see DerivedPageDataUpdater::prepareUpdate.
	 */
	public function doEditUpdates(
		RevisionRecord $revisionRecord,
		UserIdentity $user,
		array $options = []
	) {
		wfDeprecated( __METHOD__, '1.32' ); // emitting warnings since 1.44

		$options += [
			'causeAction' => 'edit-page',
			'causeAgent' => $user->getName(),
			'emitEvents' => false // prior page state is unknown, can't emit events
		];

		$updater = $this->getDerivedDataUpdater( $user, $revisionRecord );

		$updater->prepareUpdate( $revisionRecord, $options );

		$updater->doUpdates();
	}

	/**
	 * Update the parser cache.
	 *
	 * @note This does not update links tables. Use doSecondaryDataUpdates() for that.
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
	 * @note This does not update the parser cache. Use updateParserCache() for that.
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
		$options['recursive'] ??= true;
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
		$services = MediaWikiServices::getInstance();
		$readOnlyMode = $services->getReadOnlyMode();
		if ( $readOnlyMode->isReadOnly() ) {
			return Status::newFatal( wfMessage( 'readonlytext', $readOnlyMode->getReason() ) );
		}

		$this->loadPageData( 'fromdbmaster' );
		$restrictionStore = $services->getRestrictionStore();
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

		$dbw = $services->getConnectionProvider()->getPrimaryDatabase();
		$restrictionMapBefore = [];
		$restrictionMapAfter = [];

		foreach ( $restrictionTypes as $action ) {
			if ( !isset( $expiry[$action] ) || $expiry[$action] === $dbw->getInfinity() ) {
				$expiry[$action] = 'infinity';
			}

			// Get current restrictions on $action
			$restrictionMapBefore[$action] = $restrictionStore->getRestrictions( $this->mTitle, $action );
			$limit[$action] ??= '';

			if ( $limit[$action] === '' ) {
				$restrictionMapAfter[$action] = [];
			} else {
				$protect = true;
				$restrictionMapAfter[$action] = explode( ',', $limit[$action] );
			}

			$current = implode( ',', $restrictionMapBefore[$action] );
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

		$legacyUser = $services->getUserFactory()->newFromUserIdentity( $user );
		if ( !$this->getHookRunner()->onArticleProtect( $this, $legacyUser, $limit, $reason ) ) {
			return Status::newGood();
		}

		if ( $id ) { // Protection of existing page
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

			$cascadingRestrictionLevels = $services->getMainConfig()
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

			// insert dummy revision to identify the page protection change as edit summary
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
			$existingProtectionIds = $dbw->newSelectQueryBuilder()
				->select( 'pr_id' )
				->from( 'page_restrictions' )
				->where( [ 'pr_page' => $id, 'pr_type' => array_map( 'strval', array_keys( $limit ) ) ] )
				->caller( __METHOD__ )->fetchFieldValues();

			if ( $existingProtectionIds ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'page_restrictions' )
					->where( [ 'pr_id' => $existingProtectionIds ] )
					->caller( __METHOD__ )->execute();
			}

			// Update restrictions table
			foreach ( $limit as $action => $restrictions ) {
				if ( $restrictions != '' ) {
					$cascadeValue = ( $cascade && $action == 'edit' ) ? 1 : 0;
					$dbw->newInsertQueryBuilder()
						->insertInto( 'page_restrictions' )
						->row( [
							'pr_page' => $id,
							'pr_type' => $action,
							'pr_level' => $restrictions,
							'pr_cascade' => $cascadeValue,
							'pr_expiry' => $dbw->encodeExpiry( $expiry[$action] )
						] )
						->caller( __METHOD__ )->execute();
					$logRelationsValues[] = $dbw->insertId();
					$logParamsDetails[] = [
						'type' => $action,
						'level' => $restrictions,
						'expiry' => $expiry[$action],
						'cascade' => (bool)$cascadeValue,
					];
				}
			}
		} else { // Protection of non-existing page (also known as "title protection")
			// Cascade protection is meaningless in this case
			$cascade = false;

			if ( $limit['create'] != '' ) {
				$commentFields = $services->getCommentStore()->insert( $dbw, 'pt_reason', $reason );
				$dbw->newReplaceQueryBuilder()
					->table( 'protected_titles' )
					->uniqueIndexFields( [ 'pt_namespace', 'pt_title' ] )
					->rows( [
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey(),
						'pt_create_perm' => $limit['create'],
						'pt_timestamp' => $dbw->timestamp(),
						'pt_expiry' => $dbw->encodeExpiry( $expiry['create'] ),
						'pt_user' => $user->getId(),
					] + $commentFields )
					->caller( __METHOD__ )->execute();
				$logParamsDetails[] = [
					'type' => 'create',
					'level' => $limit['create'],
					'expiry' => $expiry['create'],
				];
			} else {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'protected_titles' )
					->where( [
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey()
					] )
					->caller( __METHOD__ )->execute();
			}
		}

		$this->getHookRunner()->onArticleProtectComplete( $this, $legacyUser, $limit, $reason );

		$services->getRestrictionStore()->flushRestrictions( $this->mTitle );

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

		$event = new PageProtectionChangedEvent(
			$this,
			$restrictionMapBefore,
			$restrictionMapAfter,
			$expiry,
			$cascade,
			$user,
			$reason,
			$tags
		);

		$dispatcher = MediaWikiServices::getInstance()->getDomainEventDispatcher();
		$dispatcher->dispatch( $event, $services->getConnectionProvider() );

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
	 * Insert a new dummy revision (aka null revision) for this page,
	 * to mark a change in page protection.
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

		return $this->newPageUpdater( $user )
			->setCause( PageUpdater::CAUSE_PROTECTION_CHANGE )
			->saveDummyRevision( $editComment, EDIT_SILENT | EDIT_MINOR );
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

		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
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
	 * Lock the page row for this title+id and return page_latest (or 0)
	 *
	 * @return int Returns 0 if no row was found with this title+id
	 * @since 1.27
	 */
	public function lockAndGetLatest() {
		$dbw = $this->getConnectionProvider()->getPrimaryDatabase();
		return (int)$dbw->newSelectQueryBuilder()
			->select( 'page_latest' )
			->forUpdate()
			->from( 'page' )
			->where( [
				'page_id' => $this->getId(),
				// Typically page_id is enough, but some code might try to do
				// updates assuming the title is the same, so verify that
				'page_namespace' => $this->getTitle()->getNamespace(),
				'page_title' => $this->getTitle()->getDBkey()
			] )
			->caller( __METHOD__ )->fetchField();
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
	 * @param bool $maybeIsRedirect True if the page may have been created as a redirect.
	 *   If false, this is used as a hint to skip some unnecessary updates.
	 */
	public static function onArticleCreate( Title $title, $maybeIsRedirect = true ) {
		// TODO: move this into a PageEventEmitter service

		// Update existence markers on article/talk tabs...
		$other = $title->getOtherPage();

		$services = MediaWikiServices::getInstance();
		$hcu = $services->getHtmlCacheUpdater();
		$hcu->purgeTitleUrls( [ $title, $other ], $hcu::PURGE_INTENT_TXROUND_REFLECTED );

		$title->touchLinks();
		$services->getRestrictionStore()->deleteCreateProtection( $title );

		$services->getLinkCache()->invalidateTitle( $title );

		DeferredUpdates::addCallableUpdate(
			static function () use ( $title, $maybeIsRedirect ) {
				self::queueBacklinksJobs( $title, true, $maybeIsRedirect, 'create-page' );
			}
		);

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
	 * @internal for use by DeletePage and MovePage.
	 * @todo pull this into DeletePage
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

		// Invalidate caches of articles which include this page
		DeferredUpdates::addCallableUpdate( static function () use ( $title ) {
			self::queueBacklinksJobs( $title, true, true, 'delete-page' );
		} );

		// TODO: Move to ChangeTrackingEventIngress when ready,
		// but make sure it happens on deletions and page moves by adding
		// the appropriate assertions to ChangeTrackingEventIngressSpyTrait.
		// Messages
		// User talk pages
		if ( $title->getNamespace() === NS_USER_TALK ) {
			$user = User::newFromName( $title->getText(), false );
			if ( $user ) {
				MediaWikiServices::getInstance()
					->getTalkPageNotificationManager()
					->removeUserHasNewMessages( $user );
			}
		}

		// TODO: Create MediaEventIngress and move this there.
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
	 * @param bool $maybeRedirectChanged True if the page's redirect target may have changed in the
	 *   latest revision. If false, this is used as a hint to skip some unnecessary updates.
	 */
	public static function onArticleEdit(
		Title $title,
		?RevisionRecord $revRecord = null,
		$slotsChanged = null,
		$maybeRedirectChanged = true
	) {
		// TODO: move this into a PageEventEmitter service

		DeferredUpdates::addCallableUpdate(
			static function () use ( $title, $slotsChanged, $maybeRedirectChanged ) {
				self::queueBacklinksJobs(
					$title,
					$slotsChanged === null || in_array( SlotRecord::MAIN, $slotsChanged ),
					$maybeRedirectChanged,
					'edit-page'
				);
			}
		);

		$services = MediaWikiServices::getInstance();
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

	private static function queueBacklinksJobs(
		Title $title, bool $mainSlotChanged, bool $maybeRedirectChanged, string $causeAction
	) {
		$services = MediaWikiServices::getInstance();
		$backlinkCache = $services->getBacklinkCacheFactory()->getBacklinkCache( $title );

		$jobs = [];
		if ( $mainSlotChanged
			&& $backlinkCache->hasLinks( 'templatelinks' )
		) {
			// Invalidate caches of articles which include this page.
			// Only for the main slot, because only the main slot is transcluded.
			// TODO: MCR: not true for TemplateStyles! [SlotHandler]
			$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
				$title,
				'templatelinks',
				[ 'causeAction' => $causeAction ]
			);
		}
		// Images
		if ( $maybeRedirectChanged && $title->getNamespace() === NS_FILE
			&& $backlinkCache->hasLinks( 'imagelinks' )
		) {
			// Process imagelinks in case the redirect target has changed
			$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
				$title,
				'imagelinks',
				[ 'causeAction' => $causeAction ]
			);
		}
		// Invalidate the caches of all pages which redirect here
		if ( $backlinkCache->hasLinks( 'redirect' ) ) {
			$jobs[] = HTMLCacheUpdateJob::newForBacklinks(
				$title,
				'redirect',
				[ 'causeAction' => $causeAction ]
			);
		}
		if ( $jobs ) {
			$services->getJobQueueGroup()->push( $jobs );
		}
	}

	/**
	 * Purge the check key for cross-wiki cache entries referencing this page
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
	 * @return TitleArrayFromResult
	 */
	public function getCategories() {
		$services = MediaWikiServices::getInstance();
		$id = $this->getId();
		if ( $id == 0 ) {
			return $services->getTitleFactory()->newTitleArrayFromResult( new FakeResultWrapper( [] ) );
		}

		$dbr = $services->getConnectionProvider()->getReplicaDatabase();
		$qb = $dbr->newSelectQueryBuilder()
			->from( 'categorylinks' );

		$migrationStage = $services->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);

		if ( $migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$qb->select( [ 'page_title' => 'cl_to', 'page_namespace' => (string)NS_CATEGORY ] );
		} else {
			$qb->select( [ 'page_title' => 'lt_title', 'page_namespace' => (string)NS_CATEGORY ] )
				->join( 'linktarget', null, [ 'cl_target_id = lt_id', 'lt_namespace = ' . NS_CATEGORY ] );
		}

		$res = $qb->where( [ 'cl_from' => $id ] )
			->caller( __METHOD__ )->fetchResultSet();

		return $services->getTitleFactory()->newTitleArrayFromResult( $res );
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

		$dbr = $this->getConnectionProvider()->getReplicaDatabase();
		$qb = $dbr->newSelectQueryBuilder()
			->from( 'categorylinks' );

		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);

		if ( $migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$qb->select( [ 'cl_to' ] )
				->join( 'page', null, 'page_title = cl_to' );
		} else {
			$qb->select( [ 'cl_to' => 'lt_title' ] )
				->join( 'linktarget', null, 'cl_target_id = lt_id' )
				->join( 'page', null, [ 'page_title = lt_title', 'page_namespace = lt_namespace' ] );
		}

		$res = $qb->join( 'page_props', null, 'pp_page=page_id' )
			->where( [ 'cl_from' => $id, 'pp_propname' => 'hiddencat', 'page_namespace' => NS_CATEGORY ] )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $res as $row ) {
			$result[] = Title::makeTitle( NS_CATEGORY, $row->cl_to );
		}

		return $result;
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @param bool &$hasHistory Whether the page has a history
	 * @return string|false String containing deletion reason or empty string, or boolean false
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

		$addFields = [ 'cat_pages' => new RawSQLValue( 'cat_pages + 1' ) ];
		$removeFields = [ 'cat_pages' => new RawSQLValue( 'cat_pages - 1' ) ];
		if ( $type !== 'page' ) {
			$addFields["cat_{$type}s"] = new RawSQLValue( "cat_{$type}s + 1" );
			$removeFields["cat_{$type}s"] = new RawSQLValue( "cat_{$type}s - 1" );
		}

		$dbw = $this->getConnectionProvider()->getPrimaryDatabase();
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'cat_id', 'cat_title' ] )
			->from( 'category' )
			->where( [ 'cat_title' => array_merge( $added, $deleted ) ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$existingCategories = [];
		foreach ( $res as $row ) {
			$existingCategories[$row->cat_id] = $row->cat_title;
		}
		$existingAdded = array_intersect( $existingCategories, $added );
		$existingDeleted = array_intersect( $existingCategories, $deleted );
		$missingAdded = array_diff( $added, $existingAdded );

		// For category rows that already exist, do a plain
		// UPDATE instead of INSERT...ON DUPLICATE KEY UPDATE
		// to avoid creating gaps in the cat_id sequence.
		if ( $existingAdded ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'category' )
				->set( $addFields )
				->where( [ 'cat_id' => array_keys( $existingAdded ) ] )
				->caller( __METHOD__ )->execute();
		}

		if ( $missingAdded ) {
			$queryBuilder = $dbw->newInsertQueryBuilder()
				->insertInto( 'category' )
				->onDuplicateKeyUpdate()
				->uniqueIndexFields( [ 'cat_title' ] )
				->set( $addFields );
			foreach ( $missingAdded as $cat ) {
				$queryBuilder->row( [
					'cat_title'   => $cat,
					'cat_pages'   => 1,
					'cat_subcats' => ( $type === 'subcat' ) ? 1 : 0,
					'cat_files'   => ( $type === 'file' ) ? 1 : 0,
				] );
			}
			$queryBuilder->caller( __METHOD__ )->execute();
		}

		if ( $existingDeleted ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'category' )
				->set( $removeFields )
				->where( [ 'cat_id' => array_keys( $existingDeleted ) ] )
				->caller( __METHOD__ )->execute();
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
			// transcluding of a different subpage based on which day it is, and then show that
			// information on the Main Page, without the Main Page itself being edited.
			MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush(
				RefreshLinksJob::newPrioritized( $this->mTitle, $params )
			);
		} elseif (
			(
				// "Dynamic" content (eg time/random magic words)
				!$config->get( MainConfigNames::MiserMode ) &&
				$parserOutput->hasReducedExpiry()
			)
			||
			(
				// Asynchronous content
				$config->get( MainConfigNames::ParserCacheAsyncRefreshJobs ) &&
				$parserOutput->getOutputFlag( ParserOutputFlags::HAS_ASYNC_CONTENT ) &&
				!$parserOutput->getOutputFlag( ParserOutputFlags::ASYNC_NOT_READY )
			)
		) {
			// Assume the output contains "dynamic" time/random based magic words
			// or asynchronous content that wasn't "ready" the first time the
			// page was parsed.
			// Only update pages that expired due to dynamic content and NOT due to edits
			// to referenced templates/files. When the cache expires due to dynamic content,
			// page_touched is unchanged. We want to avoid triggering redundant jobs due to
			// views of pages that were just purged via HTMLCacheUpdateJob. In that case, the
			// template/file edit already triggered recursive RefreshLinksJob jobs.
			if ( $this->getLinksTimestamp() > $this->getTouched() ) {
				// If a page is uncacheable, do not keep spamming a job for it.
				// Although it would be de-duplicated, it would still waste I/O.
				$services = MediaWikiServices::getInstance()->getObjectCacheFactory();
				$cache = $services->getLocalClusterInstance();
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
	 */
	public function isSamePageAs( PageReference $other ): bool {
		// NOTE: keep in sync with PageReferenceValue::isSamePageAs()!
		return $this->getWikiId() === $other->getWikiId()
			&& $this->getNamespace() === $other->getNamespace()
			&& $this->getDBkey() === $other->getDBkey();
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
				'page_is_redirect' => $this->mPageIsRedirectField ? 1 : 0,
				'page_touched' => $this->getTouched(),
				'page_lang' => $this->getLanguage()
			],
			PageIdentity::LOCAL
		);
	}

	/**
	 * @return \Wikimedia\Rdbms\IConnectionProvider
	 */
	private function getConnectionProvider(): \Wikimedia\Rdbms\IConnectionProvider {
		return MediaWikiServices::getInstance()->getConnectionProvider();
	}

}

/** @deprecated class alias since 1.44 */
class_alias( WikiPage::class, 'WikiPage' );
