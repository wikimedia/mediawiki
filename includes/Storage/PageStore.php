<?php

namespace MediaWiki\Storage;

/**
 * FIXME: document!
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PageStore {
	/**
	 * Constructor from a page id
	 *
	 * @param int $id Article ID to load
	 * @param string|int $from One of the following values:
	 *        - "fromdb" or WikiPage::READ_NORMAL to select from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST to select from the master database
	 *
	 * @return PageRecord|null
	 */
	public function newPageRecordFromID( $id, $from = 'fromdb' ) {
		// page ids are never 0 or negative, see T63166
		if ( $id < 1 ) {
			return null;
		}

		$from = $this->convertSelectType( $from );
		$db = wfGetDB( $from === self::READ_LATEST ? DB_MASTER : DB_REPLICA );
		$pageQuery = $this->getQueryInfo();
		$row = $db->selectRow(
			$pageQuery['tables'], $pageQuery['fields'], [ 'page_id' => $id ], __METHOD__,
			[], $pageQuery['joins']
		);
		if ( !$row ) {
			return null;
		}
		return $this->newPagewRecordFromRow( $row, $from );
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
	 * @return PageRecord
	 */
	public function newPagewRecordFromRow( $row, $from = 'fromdb' ) {
		$page = new PageRecord( Title::newFromRow( $row ) );
		$page->loadFromRow( $row, $from );
		return $page;
	}

	/**
	 * Convert 'fromdb', 'fromdbmaster' and 'forupdate' to READ_* constants.
	 *
	 * @param object|string|int $type
	 * @return mixed
	 */
	private function convertSelectType( $type ) {
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
			$data = $this->pageDataFromTitle( wfGetDB( $index ), $this->mTitle, $opts );
			$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();

			if ( !$data
				&& $index == DB_REPLICA
				&& $loadBalancer->getServerCount() > 1
				&& $loadBalancer->hasOrMadeRecentMasterChanges()
			) {
				$from = self::READ_LATEST;
				list( $index, $opts ) = DBAccessObjectUtils::getDBOptions( $from );
				$data = $this->pageDataFromTitle( wfGetDB( $index ), $this->mTitle, $opts );
			}
		} else {
			// No idea from where the caller got this data, assume replica DB.
			$data = $from;
			$from = self::READ_NORMAL;
		}

		$this->loadFromRow( $data, $from );
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
		$lc = LinkCache::singleton();
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
	 * Get the User object of the user who created the page
	 * @param int $audience One of:
	 *   Revision::FOR_PUBLIC       to be displayed to all users
	 *   Revision::FOR_THIS_USER    to be displayed to the given user
	 *   Revision::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
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
			$this->mRedirectTarget = Title::makeTitle(
				$row->rd_namespace, $row->rd_title,
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
			$dbw->upsert(
				'redirect',
				[
					'rd_from' => $this->getId(),
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
		// @todo FIXME: This is expensive; cache this info somewhere.

		$dbr = wfGetDB( DB_REPLICA );

		$tables = [ 'revision', 'user' ];

		$fields = [
			'user_id' => 'rev_user',
			'user_name' => 'rev_user_text',
			'user_real_name' => 'MIN(user_real_name)',
			'timestamp' => 'MAX(rev_timestamp)',
		];

		$conds = [ 'rev_page' => $this->getId() ];

		// The user who made the top revision gets credited as "this page was last edited by
		// John, based on contributions by Tom, Dick and Harry", so don't include them twice.
		$user = $this->getUser();
		if ( $user ) {
			$conds[] = "rev_user != $user";
		} else {
			$conds[] = "rev_user_text != {$dbr->addQuotes( $this->getUserText() )}";
		}

		// Username hidden?
		$conds[] = "{$dbr->bitAnd( 'rev_deleted', Revision::DELETED_USER )} = 0";

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
	 * Do standard deferred updates after page view (existing or missing page)
	 * @param User $user The relevant user
	 * @param int $oldid Revision id being viewed; if not given or 0, latest revision is assumed
	 */
	public function doViewUpdates( User $user, $oldid = 0 ) {
		if ( wfReadOnly() ) {
			return;
		}

		Hooks::run( 'PageViewUpdates', [ $this, $user ] );
		// Update newtalk / watchlist notification status
		try {
			$user->clearNotification( $this->mTitle, $oldid );
		} catch ( DBError $e ) {
			// Avoid outage if the master is not reachable
			MWExceptionHandler::logException( $e );
		}
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
	 * Update the article's restriction field, and leave a log entry.
	 * This works for protection both existing and non-existing pages.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param int &$cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param User $user The user updating the restrictions
	 * @param string|string[] $tags Change tags to add to the pages and protection log entries
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
		$restrictionTypes = $this->mTitle->getRestrictionTypes();
		$id = $this->getId();

		if ( !$cascade ) {
			$cascade = false;
		}

		// Take this opportunity to purge out expired restrictions
		Title::purgeExpiredRestrictions();

		// @todo FIXME: Same limitations as described in ProtectionForm.php (line 37);
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
				$commentFields = CommentStore::newKey( 'pt_reason' )->insert( $dbw, $reason );
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
	 * @param string $expiry 14-char timestamp or "infinity", or false if the input was invalid
	 * @return string
	 */
	protected function formatExpiry( $expiry ) {
		global $wgContLang;

		if ( $expiry != 'infinity' ) {
			return wfMessage(
				'protect-expiring',
				$wgContLang->timeanddate( $expiry, false, false ),
				$wgContLang->date( $expiry, false, false ),
				$wgContLang->time( $expiry, false, false )
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
		global $wgContLang;

		$protectDescriptionLog = '';

		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			$expiryText = $this->formatExpiry( $expiry[$action] );
			$protectDescriptionLog .= $wgContLang->getDirMark() .
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
	 * Same as doDeleteArticleReal(), but returns a simple boolean. This is kept around for
	 * backwards compatibility, if you care about error reporting you should use
	 * doDeleteArticleReal() instead.
	 *
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param string $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *        the suppression log instead of the deletion log
	 * @param int $u1 Unused
	 * @param bool $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User $user The deleting user
	 * @return bool True if successful
	 */
	public function doDeleteArticle(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $user = null
	) {
		$status = $this->doDeleteArticleReal( $reason, $suppress, $u1, $u2, $error, $user );
		return $status->isGood();
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
	 * @param int $u1 Unused
	 * @param bool $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User $user The deleting user
	 * @param array $tags Tags to apply to the deletion action
	 * @param string $logsubtype
	 * @return Status Status object; if successful, $status->value is the log_id of the
	 *   deletion log entry. If the page couldn't be deleted because it wasn't
	 *   found, $status is a non-fatal 'cannotdelete' error
	 */
	public function doDeleteArticleReal(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $user = null,
		$tags = [], $logsubtype = 'delete'
	) {
		global $wgUser, $wgContentHandlerUseDB, $wgCommentTableSchemaMigrationStage;

		wfDebug( __METHOD__ . "\n" );

		$status = Status::newGood();

		if ( $this->mTitle->getDBkey() === '' ) {
			$status->error( 'cannotdelete',
				wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) );
			return $status;
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		$user = is_null( $user ) ? $wgUser : $user;
		if ( !Hooks::run( 'ArticleDelete',
			[ &$wikiPage, &$user, &$reason, &$error, &$status, $suppress ]
		) ) {
			if ( $status->isOK() ) {
				// Hook aborted but didn't set a fatal status
				$status->fatal( 'delete-hook-aborted' );
			}
			return $status;
		}

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

		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $this->getTitle()->getNamespace();
		$dbKey = $this->getTitle()->getDBkey();

		// At this point we are now comitted to returning an OK
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

		$revCommentStore = new CommentStore( 'rev_comment' );
		$arCommentStore = new CommentStore( 'ar_comment' );

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

		// Get all of the page revisions
		$res = $dbw->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_page' => $id ],
			__METHOD__,
			'FOR UPDATE',
			$revQuery['joins']
		);

		// Build their equivalent archive rows
		$rowsInsert = [];
		$revids = [];

		/** @var int[] Revision IDs of edits that were made by IPs */
		$ipRevIds = [];

		foreach ( $res as $row ) {
			$comment = $revCommentStore->getComment( $row );
			$rowInsert = [
					'ar_namespace'  => $namespace,
					'ar_title'      => $dbKey,
					'ar_user'       => $row->rev_user,
					'ar_user_text'  => $row->rev_user_text,
					'ar_timestamp'  => $row->rev_timestamp,
					'ar_minor_edit' => $row->rev_minor_edit,
					'ar_rev_id'     => $row->rev_id,
					'ar_parent_id'  => $row->rev_parent_id,
					'ar_text_id'    => $row->rev_text_id,
					'ar_text'       => '',
					'ar_flags'      => '',
					'ar_len'        => $row->rev_len,
					'ar_page_id'    => $id,
					'ar_deleted'    => $suppress ? $bitfield : $row->rev_deleted,
					'ar_sha1'       => $row->rev_sha1,
				] + $arCommentStore->insert( $dbw, $comment );
			if ( $wgContentHandlerUseDB ) {
				$rowInsert['ar_content_model'] = $row->rev_content_model;
				$rowInsert['ar_content_format'] = $row->rev_content_format;
			}
			$rowsInsert[] = $rowInsert;
			$revids[] = $row->rev_id;

			// Keep track of IP edits, so that the corresponding rows can
			// be deleted in the ip_changes table.
			if ( (int)$row->rev_user === 0 && IP::isValid( $row->rev_user_text ) ) {
				$ipRevIds[] = $row->rev_id;
			}
		}
		// Copy them into the archive table
		$dbw->insert( 'archive', $rowsInsert, __METHOD__ );
		// Save this so we can pass it to the ArticleDeleteComplete hook.
		$archivedRevisionCount = $dbw->affectedRows();

		// Clone the title and wikiPage, so we have the information we need when
		// we log and run the ArticleDeleteComplete hook.
		$logTitle = clone $this->mTitle;
		$wikiPageBeforeDelete = clone $this;

		// Now that it's safely backed up, delete it
		$dbw->delete( 'page', [ 'page_id' => $id ], __METHOD__ );
		$dbw->delete( 'revision', [ 'rev_page' => $id ], __METHOD__ );
		if ( $wgCommentTableSchemaMigrationStage > MIGRATION_OLD ) {
			$dbw->delete( 'revision_comment_temp', [ 'revcomment_rev' => $revids ], __METHOD__ );
		}

		// Also delete records from ip_changes as applicable.
		if ( count( $ipRevIds ) > 0 ) {
			$dbw->delete( 'ip_changes', [ 'ipc_rev_id' => $ipRevIds ], __METHOD__ );
		}

		// Log the deletion, if the page was suppressed, put it in the suppression log instead
		$logtype = $suppress ? 'suppress' : 'delete';

		$logEntry = new ManualLogEntry( $logtype, $logsubtype );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $logTitle );
		$logEntry->setComment( $reason );
		$logEntry->setTags( $tags );
		$logid = $logEntry->insert();

		$dbw->onTransactionPreCommitOrIdle(
			function () use ( $dbw, $logEntry, $logid ) {
				// T58776: avoid deadlocks (especially from FileDeleteForm)
				$logEntry->publish( $logid );
			},
			__METHOD__
		);

		$dbw->endAtomic( __METHOD__ );

		$this->doDeleteUpdates( $id, $content, $revision, $user );

		Hooks::run( 'ArticleDeleteComplete', [
			&$wikiPageBeforeDelete,
			&$user,
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

		return $status;
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
	 * @param Content|null $content Optional page content to be used when determining
	 *   the required updates. This may be needed because $this->getContent()
	 *   may already return null when the page proper was deleted.
	 * @param Revision|null $revision The latest page revision
	 * @param User|null $user The user that caused the deletion
	 */
	public function doDeleteUpdates(
		$id, Content $content = null, Revision $revision = null, User $user = null
	) {
		try {
			$countable = $this->isCountable();
		} catch ( Exception $ex ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 1, - (int)$countable, -1 ) );

		// Delete pagelinks, update secondary indexes, etc
		$updates = $this->getDeletionUpdates( $content );
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
			$this->mTitle, $revision, null, wfWikiID()
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
	 * @return array
	 */
	public function commitRollback( $fromP, $summary, $bot,
		&$resultDetails, User $guser, $tags = null
	) {
		global $wgUseRCPatrol, $wgContLang;

		$dbw = wfGetDB( DB_MASTER );

		if ( wfReadOnly() ) {
			return [ [ 'readonlytext' ] ];
		}

		// Get the last editor
		$current = $this->getRevision();
		if ( is_null( $current ) ) {
			// Something wrong... no page?
			return [ [ 'notanarticle' ] ];
		}

		$from = str_replace( '_', ' ', $fromP );
		// User name given should match up with the top revision.
		// If the user was deleted then $from should be empty.
		if ( $from != $current->getUserText() ) {
			$resultDetails = [ 'current' => $current ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $current->getUserText() )
			] ];
		}

		// Get the last edit not by this person...
		// Note: these may not be public values
		$user = intval( $current->getUser( Revision::RAW ) );
		$user_text = $dbw->addQuotes( $current->getUserText( Revision::RAW ) );
		$s = $dbw->selectRow( 'revision',
			[ 'rev_id', 'rev_timestamp', 'rev_deleted' ],
			[ 'rev_page' => $current->getPage(),
				"rev_user != {$user} OR rev_user_text != {$user_text}"
			], __METHOD__,
			[ 'USE INDEX' => 'page_timestamp',
			  'ORDER BY' => 'rev_timestamp DESC' ]
		);
		if ( $s === false ) {
			// No one else ever edited this page
			return [ [ 'cantrollback' ] ];
		} elseif ( $s->rev_deleted & Revision::DELETED_TEXT
			|| $s->rev_deleted & Revision::DELETED_USER
		) {
			// Only admins can see this text
			return [ [ 'notvisiblerev' ] ];
		}

		// Generate the edit summary if necessary
		$target = Revision::newFromId( $s->rev_id, Revision::READ_LATEST );
		if ( empty( $summary ) ) {
			if ( $from == '' ) { // no public user name
				$summary = wfMessage( 'revertpage-nouser' );
			} else {
				$summary = wfMessage( 'revertpage' );
			}
		}

		// Allow the custom summary to use the same args as the default message
		$args = [
			$target->getUserText(), $from, $s->rev_id,
			$wgContLang->timeanddate( wfTimestamp( TS_MW, $s->rev_timestamp ) ),
			$current->getId(), $wgContLang->timeanddate( $current->getTimestamp() )
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

		$targetContent = $target->getContent();
		$changingContentModel = $targetContent->getModel() !== $current->getContentModel();

		// Actually store the edit
		$status = $this->doEditContent(
			$targetContent,
			$summary,
			$flags,
			$target->getId(),
			$guser,
			null,
			$tags
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
			$set['rc_patrolled'] = 1;
		}

		if ( count( $set ) ) {
			$dbw->update( 'recentchanges', $set,
				[ /* WHERE */
					'rc_cur_id' => $current->getPage(),
					'rc_user_text' => $current->getUserText(),
					'rc_timestamp > ' . $dbw->addQuotes( $s->rev_timestamp ),
				],
				__METHOD__
			);
		}

		if ( !$status->isOK() ) {
			return $status->getErrorsArray();
		}

		// raise error, when the edit is an edit without a new version
		$statusRev = isset( $status->value['revision'] )
			? $status->value['revision']
			: null;
		if ( !( $statusRev instanceof Revision ) ) {
			$resultDetails = [ 'current' => $current ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $current->getUserText() )
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
				'4::oldmodel' => $current->getContentModel(),
				'5::newmodel' => $targetContent->getModel(),
			] );

			$logId = $log->insert( $dbw );
			$log->publish( $logId );
		}

		$revId = $statusRev->getId();

		Hooks::run( 'ArticleRollbackComplete', [ $this, $guser, $target, $current ] );

		$resultDetails = [
			'summary' => $summary,
			'current' => $current,
			'target' => $target,
			'newid' => $revId
		];

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
		// Update existence markers on article/talk tabs...
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
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param Title $title
	 * @param Revision|null $revision Revision that was just saved, may be null
	 */
	public static function onArticleEdit( Title $title, Revision $revision = null ) {
		// Invalidate caches of articles which include this page
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'templatelinks', 'page-edit' )
		);

		// Invalidate the caches of all pages which redirect here
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'redirect', 'page-edit' )
		);

		MediaWikiServices::getInstance()->getLinkCache()->invalidateTitle( $title );

		// Purge CDN for this page only
		$title->purgeSquid();
		// Clear file cache for this page only
		HTMLFileCache::clearFileCache( $title );

		$revid = $revision ? $revision->getId() : null;
		DeferredUpdates::addCallableUpdate( function () use ( $title, $revid ) {
			InfoAction::invalidateCache( $title, $revid );
		} );
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
		$ns = $this->getTitle()->getNamespace();

		$addFields = [ 'cat_pages = cat_pages + 1' ];
		$removeFields = [ 'cat_pages = cat_pages - 1' ];
		if ( $ns == NS_CATEGORY ) {
			$addFields[] = 'cat_subcats = cat_subcats + 1';
			$removeFields[] = 'cat_subcats = cat_subcats - 1';
		} elseif ( $ns == NS_FILE ) {
			$addFields[] = 'cat_files = cat_files + 1';
			$removeFields[] = 'cat_files = cat_files - 1';
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
						'cat_subcats' => ( $ns == NS_CATEGORY ) ? 1 : 0,
						'cat_files'   => ( $ns == NS_FILE ) ? 1 : 0,
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
		}

		// Refresh counts on categories that should be empty now, to
		// trigger possible deletion. Check master for the most
		// up-to-date cat_pages.
		if ( count( $deleted ) ) {
			$rows = $dbw->select(
				'category',
				[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
				[ 'cat_title' => $deleted, 'cat_pages <= 0' ],
				__METHOD__
			);
			foreach ( $rows as $row ) {
				$cat = Category::newFromRow( $row );
				// T166757: do the update after this DB commit
				DeferredUpdates::addCallableUpdate( function () use ( $cat ) {
					$cat->refreshCounts();
				} );
			}
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
	 * @param Content|null $content Optional Content object for determining the
	 *   necessary updates.
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( Content $content = null ) {
		if ( !$content ) {
			// load content object, which may be used to determine the necessary updates.
			// XXX: the content may not be needed to determine the updates.
			try {
				$content = $this->getContent( Revision::RAW );
			} catch ( Exception $ex ) {
				// If we can't load the content, something is wrong. Perhaps that's why
				// the user is trying to delete the page, so let's not fail in that case.
				// Note that doDeleteArticleReal() will already have logged an issue with
				// loading the content.
			}
		}

		if ( !$content ) {
			$updates = [];
		} else {
			$updates = $content->getDeletionUpdates( $this );
		}

		Hooks::run( 'WikiPageDeletionUpdates', [ $this, $content, &$updates ] );
		return $updates;
	}

	/**
	 * @param WANObjectCache $cache
	 * @return string[]
	 * @since 1.28
	 */
	public function getMutableCacheKeys( WANObjectCache $cache ) {
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();

		return $linkCache->getMutableCacheKeys( $cache, $this->getTitle()->getTitleValue() );
	}

}
