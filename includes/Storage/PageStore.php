<?php

namespace MediaWiki\Storage;

use IDBAccessObject;
use InvalidArgumentException;
use LinkCache;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\User\UserIdentity;
use Title;
use TitleArray;
use User;
use UserArrayFromResult;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * FIXME: header
 * Storage service for page meta-data.
 */
class PageStore implements IDBAccessObject {

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var bool|string
	 */
	private $wikiId;

	/**
	 * @var boolean
	 */
	private $contentHandlerUseDB = true;

	/**
	 * @var string|null
	 */
	private $pageLanguageOverride = null;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var callable|null Callback for inserting any missing record into the redirect table.
	 *      Will be called with the following signature:
	 *      ( PageStore $store, PageRecord $page, LinkTarget|null $target ).
	 */
	private $redirectPopulationCallback;

	/**
	 * @todo $blobStore should be allowed to be any BlobStore!
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 * @param LinkCache $linkCache
	 * @param bool|string $wikiId
	 */
	public function __construct(
		LoadBalancer $loadBalancer,
		RevisionStore $revisionStore,
		LinkCache $linkCache,
		$wikiId = false
	) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );

		$this->loadBalancer = $loadBalancer;
		$this->revisionStore = $revisionStore;
		$this->linkCache = $linkCache;
		$this->wikiId = $wikiId;

		$this->redirectPopulationCallback = [ $this, 'insertRedirectEntry' ];
	}

	/**
	 * Sets the callback for inserting any missing record into the redirect table.
	 * This can be used to defer the insertion.
	 *
	 * The callback will be invoked with the following signature:
	 * ( PageStore $store, PageRecord $page, LinkTarget|null $target ).
	 * @param callable|null $redirectPopulationCallback
	 */
	public function setRedirectPopulationCallback( $redirectPopulationCallback ) {
		// FIXME: make sure we call this from the instantiator in the wiring file!

		Assert::parameterType(
			'callable|null',
			$redirectPopulationCallback,
			'$redirectPopulationCallback'
		);

		$this->redirectPopulationCallback = $redirectPopulationCallback;
	}

	/**
	 * @return bool
	 */
	public function getContentHandlerUseDB() {
		return $this->contentHandlerUseDB;
	}

	/**
	 * @param bool $contentHandlerUseDB
	 */
	public function setContentHandlerUseDB( $contentHandlerUseDB ) {
		Assert::parameterType( 'boolean', $contentHandlerUseDB, '$contentHandlerUseDB' );
		$this->contentHandlerUseDB = $contentHandlerUseDB;
	}

	/**
	 * @return string|null
	 */
	public function getPageLanguageOverride() {
		return $this->pageLanguageOverride;
	}

	/**
	 * @param string|null $pageLanguageOverride
	 */
	public function setPageLanguageOverride( $pageLanguageOverride ) {
		Assert::parameterType( 'string|null', $pageLanguageOverride, '$pageLanguageOverride' );
		$this->pageLanguageOverride = $pageLanguageOverride;
	}

	/**
	 * @return LoadBalancer
	 */
	private function getDBLoadBalancer() {
		return $this->loadBalancer;
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	private function getDBConnectionRef( $mode ) {
		$lb = $this->getDBLoadBalancer();
		return $lb->getConnectionRef( $mode, [], $this->wikiId );
	}

	/**
	 * Construct a PageRecord from a page id
	 *
	 * @param int $id Article ID to load
	 * @param int $from Source of $data:
	 *        - self::READ_NORMAL: from a replica DB
	 *        - self::READ_LATEST: from the master DB
	 *        - self::READ_LOCKING: from the master DB using SELECT FOR UPDATE
	 *
	 * @return PageRecord|null
	 */
	public function loadPageRecordFromID( $id, $from = self::READ_NORMAL ) {
		Assert::parameterType( 'integer', $from, '$from' ); // defend against 'fromdb' etc here.
		Assert::parameter( $id >= 0, $id, '$id must not be negative' );

		$conditions = [
			'page_id' => $id
		];

		return $this->loadPageRecordFromConditions( $conditions, $from );
	}

	/**
	 * Create a PageRecord object for the given title.
	 *
	 * MCR migration note: this replaces Wikisome use cases of Page::factory().
	 *
	 * @param LinkTarget $target
	 * @param int $from Source of $data:
	 *        - self::READ_NORMAL: from a replica DB
	 *        - self::READ_LATEST: from the master DB
	 *        - self::READ_LOCKING: from the master DB using SELECT FOR UPDATE
	 *
	 * @return PageRecord|null
	 */
	public function loadPageRecordFromLinkTarget( LinkTarget $target, $from = self::READ_NORMAL ) {
		Assert::parameter(
			!$target->isExternal(),
			$target,
			'$target->isExternal() must return false'
		);

		Assert::parameter(
			$target->getNamespace() >= 0,
			$target,
			'$target->getNamespace() must not be negative'
		);

		$conditions = [
			'page_namespace' => $target->getNamespace(),
			'page_title' => $target->getDBkey(),
		];

		return $this->loadPageRecordFromConditions( $conditions, $from );
	}

	/**
	 * Create a PageRecord object for the given title.
	 *
	 * MCR migration note: this replaces Wikisome use cases of Page::factory().
	 *
	 * @param array $conditions
	 * @param int $from Source of $data:
	 *        - self::READ_NORMAL: from a replica DB
	 *        - self::READ_LATEST: from the master DB
	 *        - self::READ_LOCKING: from the master DB using SELECT FOR UPDATE
	 *
	 * @return PageRecord|null
	 */
	private function loadPageRecordFromConditions( array $conditions, $from = self::READ_NORMAL ) {
		$db = $this->getDBConnectionRef( $from === self::READ_LATEST ? DB_MASTER : DB_REPLICA );
		$pageQuery = $this->getQueryInfo();
		$row = $db->selectRow(
			$pageQuery['tables'], $pageQuery['fields'], $conditions, __METHOD__,
			[], $pageQuery['joins']
		);

		if ( !$row ) {
			return null;
		}

		return $this->newPagewRecordFromRow( $row );
	}

	/**
	 * @param object $row A row from the page table, as a raw object.
	 *        If this contains fields from the revision table, the RevisionRecord
	 *        is constructed from these fields.
	 * @param int $from
	 *
	 * @return RevisionRecord|callable|null
	 */
	private function getRevisionFromRowInternal( $row, $from = self::READ_NORMAL ) {
		if ( !isset( $row->page_id ) || $row->page_id === 0 ) {
			// there is no current revision
			return null;
		}

		if ( isset( $row->rev_id ) ) {
			// the current revision is part of the database result row
			return $this->revisionStore->newRevisionFromRow( $row, $from );
		}

		// return a callback that loads the revision
		return function( PageRecord $page ) use ( $from ) {
			$page->getLatest();
			return $this->loadLatestRevision( $page, $from );
		};
	}

	/**
	 * Construct a PageRecord from a database row
	 *
	 * @param object $row Database row containing at least fields returned by selectFields().
	 *
	 * @return PageRecord
	 */
	public function newPagewRecordFromRow( $row ) {
		if ( $this->pageLanguageOverride ) {
			$row->page_lang = $this->pageLanguageOverride;
		}

		if ( !isset( $row->page_lang ) ) {
			throw new InvalidArgumentException( 'Missing page_lang field' );
		}

		return new PageRecord( $row, $this->getRevisionFromRowInternal( $row ) );
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new page object.
	 *
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public function getQueryInfo() {
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

		if ( $this->contentHandlerUseDB ) {
			$ret['fields'][] = 'page_content_model';
		}

		if ( !$this->pageLanguageOverride ) {
			$ret['fields'][] = 'page_lang';
		}

		return $ret;
	}

	/**
	 * @param int|PageIdentity|PageRecord $page
	 * @param int $from see READ_XXX
	 *
	 * @return RevisionRecord|null The page's latest revision, or null if the page does not exist.
	 */
	public function loadLatestRevision( $page, $from = self::READ_NORMAL ) {
		// FIXME: decide: should this be extended to support "latest approved", etc?

		// NOTE: even if $page is a PageRecord, we must not call $page->getCurrentRevision() here,
		// since this method may be used by a callback from inside getCurrentRevision().

		if ( is_object( $page ) ) {
			$identity = $page;
			$id = $identity->getId();
		} else {
			$identity = null;
			$id = $page;
		}

		if ( $identity && !$identity->exists() ) {
			return null; // page doesn't exist
		}

		if ( $identity instanceof PageRecord ) {
			// NOTE: we may be in a call from within $page->getCurrentRevision(), so make sure
			// we use the latest revision as recorded by that object.
			$latest = $identity->getLatest();
		} else {
			$latest = 0;
		}

		if ( $from === self::READ_NORMAL ) {
			$dbr = $this->getDBConnectionRef( DB_REPLICA );

			return $this->revisionStore->getKnownCurrentRevision( $dbr, $page, $latest );
		} else {
			// T39225: if session S1 loads the page row FOR UPDATE, the result always
			// includes the latest changes committed. This is true even within REPEATABLE-READ
			// transactions, where S1 normally only sees changes committed before the first S1
			// SELECT. Thus we need S1 to also gets the revision row FOR UPDATE; otherwise, it
			// may not find it since a page row UPDATE and revision row INSERT by S2 may have
			// happened after the first S1 SELECT.
			// https://dev.mysql.com/doc/refman/5.0/en/set-transaction.html#isolevel_repeatable-read

			// Bug T93976: if page_latest was loaded from the master, fetch the
			// revision from there as well, as it may not exist yet on a replica DB.
			// Also, this keeps the queries in the same REPEATABLE-READ snapshot.
			$dbw = $this->getDBConnectionRef( DB_MASTER );
			return $this->revisionStore->loadRevisionFromPageId( $dbw, $id, $latest, $from );
		}
	}

	/**
	 * Get the User object of the user who created the page
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 *
	 * @return UserIdentity|null The given page's creator, or null if the page does not exist,
	 *         or the creator's identity is not visible to the given audience.
	 */
	public function getCreator(
		PageIdentity $page,
		$audience = RevisionRecord::FOR_PUBLIC,
		User $user = null
	) {
		$title = Title::newFromPageIdentity( $page );

		// Try using the replica DB first, then try the master
		$revision = $title->getFirstRevision();  // FIXME: move from Title to RevisionStore
		if ( !$revision ) {
			$revision = $title->getFirstRevision( Title::GAID_FOR_UPDATE );
		}

		if ( $revision ) {
			return $revision->getUser( $audience, $user );
		} else {
			return null;
		}
	}

	/**
	 * If this page is a redirect, get its target
	 *
	 * The target will be fetched from the redirect table if possible.
	 * If this page doesn't have an entry there, call insertRedirect()
	 *
	 * @return LinkTarget|null the rediredct target, or null if the given page is not a redirect
	 */
	public function getRedirectTarget( PageRecord $page ) {
		// XXX: shouldn't we try to get this from the parser cache first?

		if ( !$page->isRedirect() ) {
			return null;
		}

		// Query the redirect table
		$dbr = $this->getDBConnectionRef( DB_REPLICA );
		$row = $dbr->selectRow( 'redirect',
			[ 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ],
			[ 'rd_from' => $page->getId() ],
			__METHOD__
		);

		// rd_fragment and rd_interwiki were added later, populate them if empty
		if ( $row && !is_null( $row->rd_fragment ) && !is_null( $row->rd_interwiki ) ) {
			$target = Title::makeTitle(
				$row->rd_namespace, $row->rd_title,
				$row->rd_fragment, $row->rd_interwiki
			);
		} else {
			$target = $this->getPageRedirectTarget( $page );

			// This page doesn't have an entry in the redirect table, so create it, if possible.
			if ( $this->redirectPopulationCallback ) {
				call_user_func( $this->redirectPopulationCallback, $this, $page, $target );
			}
		}

		// XXX: shall we cache the redirect target by glueing it to $page?
		return $target;
	}

	/**
	 * @param PageRecord $page
	 *
	 * @return LinkTarget|null
	 */
	private function getPageRedirectTarget( PageRecord $page ) {
		$revision = $page->getCurrentRevision();
		return $revision ? $this->getRevisionRedirectTarget( $revision ) : null;
	}

	/**
	 * @param RevisionRecord $revision
	 *
	 * @return LinkTarget|null
	 */
	private function getRevisionRedirectTarget( RevisionRecord $revision ) {
		$content = $revision->getContent( 'main', RevisionRecord::RAW );
		$target = $content ? $content->getUltimateRedirectTarget() : null;
		return $target;
	}

	/**
	 * Get the LinkTarget object or URL to use for a redirect. We use Title
	 * objects for same-wiki, non-special redirects and URLs for everything
	 * else.
	 *
	 * @param LinkTarget $rt Redirect target
	 * @param PageIdentity $source
	 *
	 * @return bool|LinkTarget|string False, Title object of local target, or string with URL
	 */
	public function getRedirectURL( LinkTarget $rt, PageIdentity $source = null ) {
		if ( !$rt ) {
			return false;
		}

		if ( $rt->isExternal() ) {
			if ( !$rt->isExternal() ) {
				// Offsite wikis need an HTTP redirect.
				// This can be hard to reverse and may produce loops,
				// so they may be disabled in the site configuration.
				$sourceUrl = null;

				if ( $source ) {
					// TODO: move getFullURL logic out of Title!
					$sourceTitle = Title::newFromLinkTarget( $source->getAsLinkTarget() );
					$sourceUrl = $sourceTitle->getFullURL( 'redirect=no' );
				}

				$title = Title::newFromLinkTarget( $rt );
				return $title->getFullURL( [ 'rdfrom' => $sourceUrl ] );
			} else {
				// External pages without "local" bit set are not valid
				// redirect targets
				return false;
			}
		}

		if ( $rt->inNamespace( NS_SPECIAL ) ) {
			// Gotta handle redirects to special pages differently:
			// Fill the HTTP response "Location" header and ignore the rest of the page we're on.
			// Some pages are not valid targets.
			// TODO: move isValidRedirectTarget logic out of Title!
			$title = Title::newFromLinkTarget( $rt );
			if ( $title->isValidRedirectTarget() ) {
				return $title->getFullURL();
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
	public function getContributors( PageIdentity $page ) {
		// @todo This is expensive; cache this info somewhere.

		if ( $page instanceof PageRecord ) {
			// avoid re-loading the current revision.
			$revision = $page->getCurrentRevision();
		} else {
			$revision = $this->loadLatestRevision( $page );
		}

		if ( !$revision ) {
			return new UserArrayFromResult( new FakeResultWrapper( [] ) );
		}

		$dbr = $this->getDBConnectionRef( DB_REPLICA );

		$tables = [ 'revision', 'user' ];

		$fields = [
			'user_id' => 'rev_user',
			'user_name' => 'rev_user_text',
			'user_real_name' => 'MIN(user_real_name)',
			'timestamp' => 'MAX(rev_timestamp)',
		];

		$conds = [ 'rev_page' => $page->getId() ];

		// The user who made the top revision gets credited as "this page was last edited by
		// John, based on contributions by Tom, Dick and Harry", so don't include them twice.
		$user = $revision->getUser( RevisionRecord::RAW );
		if ( $user->getId() ) {
			$conds[] = "rev_user != " . (int)$user->getId();
		} else {
			$conds[] = "rev_user_text != {$dbr->addQuotes( $user->getName() )}";
		}

		// Username hidden?
		$conds[] = "{$dbr->bitAnd( 'rev_deleted', RevisionRecord::DELETED_USER )} = 0";

		$jconds = [
			'user' => [ 'LEFT JOIN', 'rev_user = user_id' ],
		];

		$options = [
			'GROUP BY' => [ 'rev_user', 'rev_user_text' ],
			'ORDER BY' => 'timestamp DESC',
		];

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $jconds );
		return new UserArrayFromResult( $res );
	}

	/**
	 * Returns a list of categories this page is a member of.
	 * Results will include hidden categories
	 *
	 * @param int $pageId
	 *
	 * @return TitleArray
	 */
	public function getCategories( $pageId ) {
		if ( $pageId == 0 ) {
			return TitleArray::newFromResult( new FakeResultWrapper( [] ) );
		}

		$dbr = $this->getDBConnectionRef( DB_REPLICA );
		$res = $dbr->select( 'categorylinks',
			[ 'cl_to AS page_title, ' . NS_CATEGORY . ' AS page_namespace' ],
			// Have to do that since Database::fieldNamesWithAlias treats numeric indexes
			// as not being aliases, and NS_CATEGORY is numeric
			[ 'cl_from' => $pageId ],
			__METHOD__ );

		return TitleArray::newFromResult( $res );
	}

	/**
	 * Returns a list of hidden categories this page is a member of.
	 * Uses the page_props and categorylinks tables.
	 *
	 * @param int $pageId
	 *
	 * @return array Array of Title objects
	 */
	public function getHiddenCategories( $pageId ) {
		$result = [];

		if ( $pageId == 0 ) {
			return [];
		}

		$dbr = $this->getDBConnectionRef( DB_REPLICA );
		$res = $dbr->select( [ 'categorylinks', 'page_props', 'page' ],
			[ 'cl_to' ],
			[ 'cl_from' => $pageId, 'pp_page=page_id', 'pp_propname' => 'hiddencat',
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
	 * Lock the page row for this title+id and return page_latest (or 0)
	 *
	 * @param IDatabase $dbw master database connection
	 * @param PageIdentity $page
	 *
	 * @return int Returns 0 if no row was found with this title+id
	*/
	public function getLatestForUpdate( IDatabase $dbw, PageIdentity $page ) {
		return (int)$dbw->selectField(
			'page',
			'page_latest',
			[
				'page_id' => $page->getId(),
				// Typically page_id is enough, but some code might try to do
				// updates assuming the title is the same, so verify that
				'page_namespace' => $page->getNamespace(),
				'page_title' => $page->getTitleDBkey()
			],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateRevisionOn( ... );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * MCR migration note: this replaces WikiPage::insertOn
	 *
	 * @param IDatabase $dbw
	 * @param PageIdentity $page
	 *
	 * @return PageRecord|null The newly created PageRecord; null if the row was not
	 *   inserted, e.g. because the title already existed or because the specified
	 *   page ID is already in use.
	 */
	public function insertPageOn( IDatabase $dbw, PageIdentity $page ) {
		$pageId = $page->getId();
		$pageIdForInsert = $pageId ? [ 'page_id' => $pageId ] : [];

		$row = [
				'page_id' => $page->getId(),
				'page_namespace'    => $page->getNamespace(),
				'page_title'        => $page->getTitleDBkey(),
				'page_restrictions' => '',
				'page_is_redirect'  => 0, // Will set this shortly...
				'page_is_new'       => 1,
				'page_random'       => wfRandom(),
				'page_touched'      => $dbw->timestamp(),
				'page_latest'       => 0, // Fill this in shortly...
				'page_len'          => 0, // Fill this in shortly...
			] + $pageIdForInsert;

		$dbw->insert(
			'page',
			$row,
			__METHOD__,
			'IGNORE'
		);

		if ( $dbw->affectedRows() > 0 ) {
			$row['page_id'] = $pageId ? (int)$pageId : $dbw->insertId();

			$rowObj = (object)$row;
			$rec = new PageRecord( $rowObj, $this->getRevisionFromRowInternal( $rowObj ) );

			return $rec;
		} else {
			return null; // nothing changed
		}
	}

	/**
	 * Update the page record to point to a newly saved revision.
	 *
	 * MCR migration note: this replaces WikiPage::updateRevisionOn
	 *
	 * @param IDatabase $dbw
	 * @param RevisionRecord $revision For ID number, and text used to set
	 *   length and redirect status fields
	 * @param int $lastRevision If given, will not overwrite the page field
	 *   when different from the currently set value.
	 *   Giving 0 indicates the new page flag should be set on.
	 * @param bool $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return PageRecord|null A new PageRecord on success, nullif the page
	 *   row was missing or page_latest changed
	 */
	public function updatePageOn(
		IDatabase $dbw,
		RevisionRecord $revision,
		$lastRevision = null,
		$lastRevIsRedirect = null
	) {
		// Assertion to try to catch T92046
		if ( (int)$revision->getId() === 0 ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': Revision has ID ' . var_export( $revision->getId(), 1 )
			);
		}

		// FIXME: check for extra slots

		$content = $revision->getContent( 'main' );
		$len = $content ? $content->getSize() : 0;
		$rt = $this->getRevisionRedirectTarget( $revision );

		// FIXME: fail on empty $revision->getPageId(), check empty for more fields!
		$conditions = [ 'page_id' => $revision->getPageId() ];

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

		if ( $this->contentHandlerUseDB ) {
			// FIXME: still needed?
			$row['page_content_model'] = $content->getModel();
		}

		$dbw->update( 'page',
			$row,
			$conditions,
			__METHOD__ );

		$result = $dbw->affectedRows() > 0;
		if ( !$result ) {
			return null;
		}

		$rowObj = (object)$row;
		$page = new PageRecord( $rowObj, $this->getRevisionFromRowInternal( $rowObj ) );
		$this->updateRedirectOn( $dbw, $page, $rt, $lastRevIsRedirect );

		// Update the LinkCache.
		LinkCache::singleton()->addGoodLinkObj(
			$page->getId(),
			$page->getAsLinkTarget(),
			$len,
			$page->isRedirect(),
			$page->getLatest(),
			$content->getModel() // FIXME: still needed?
		);

		return $page;
	}

	/**
	 * Add row to the redirect table if this is a redirect, remove otherwise.
	 *
	 * @param IDatabase $dbw
	 * @param PageIdentity $page
	 * @param LinkTarget $redirectTarget Title object pointing to the redirect target,
	 *   or NULL if this is not a redirect
	 * @param null|bool $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 *
	 * @return bool True on success, false on failure
	 * @private
	 */
	public function updateRedirectOn(
		IDatabase $dbw,
		PageIdentity $page,
		LinkTarget $redirectTarget = null,
		$lastRevIsRedirect = null
	) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = !is_null( $redirectTarget );

		if ( !$isRedirect && $lastRevIsRedirect === false ) {
			return true;
		}

		if ( $isRedirect ) {
			// FIXME: (smartly) inline insertRedirectEntryOn here?
			$this->insertRedirectEntryOn( $dbw, $page, $redirectTarget, $lastRevIsRedirect );
		} else {
			// This is not a redirect, remove row from redirect table
			$where = [ 'rd_from' => $page->getId() ];
			$dbw->delete( 'redirect', $where, __METHOD__ );
		}

		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate it redirects to $rt.
	 * This is the default handler for $this->redirectPopulationCallback.
	 *
	 * @param PageIdentity $page
	 * @param LinkTarget $rt Redirect target
	 * @param null $lastRevIsRedirect
	 *
	 * @internal param int|null $oldLatest Prior page_latest for check and set
	 */
	private function insertRedirectEntry(
		PageIdentity $page,
		LinkTarget $rt,
		$lastRevIsRedirect = null
	) {
		$dbw = $this->getDBConnectionRef( DB_MASTER );
		$this->insertRedirectEntryOn( $dbw, $page, $rt, $lastRevIsRedirect );
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate it redirects to $rt
	 *
	 * @param IDatabase $dbw
	 * @param PageIdentity $page
	 * @param LinkTarget $rt Redirect target
	 * @param null $lastRevIsRedirect
	 *
	 * @internal param int|null $oldLatest Prior page_latest for check and set
	 */
	private function insertRedirectEntryOn(
		IDatabase $dbw,
		PageIdentity $page,
		LinkTarget $rt,
		$lastRevIsRedirect = null
	) {
		$dbw->startAtomic( __METHOD__ );

		if ( !$lastRevIsRedirect
			|| $lastRevIsRedirect == $this->getLatestForUpdate( $dbw, $page )
		) {
			$dbw->upsert(
				'redirect',
				[
					'rd_from' => $page->getId(),
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $rt->getFragment(),
					'rd_interwiki' => $rt->getInterwiki(),
				],
				[ 'rd_from' ],
				[
					'rd_namespace' => $rt->getNamespace(),
					'rd_title' => $rt->getDBkey(),
					'rd_fragment' => $rt->getFragment(),
					'rd_interwiki' => $rt->getInterwiki(),
				],
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );
	}

	/**
	 * If the given revision is newer than the currently set page_latest,
	 * update the page record. Otherwise, do nothing.
	 *
	 * @deprecated since 1.24, use updateRevisionOn instead
	 *
	 * @param IDatabase $dbw
	 * @param RevisionRecord $revision
	 *
	 * @return bool
	 */
	public function updateIfNewerOn( IDatabase $dbw, RevisionRecord $revision ) {
		$row = $dbw->selectRow(
			[ 'revision', 'page' ],
			[ 'rev_id', 'rev_timestamp', 'page_is_redirect' ],
			[
				'page_id' => $revision->getPageId(),
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

		$ret = $this->updatePageOn( $dbw, $revision, $prev, $lastRevIsRedirect );

		return $ret;
	}

}
