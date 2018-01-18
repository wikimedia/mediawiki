<?php

namespace MediaWiki\Storage;

use ApiStashEdit;
use AtomicSectionUpdate;
use CategoryMembershipChangeJob;
use ChangeTags;
use CommentStoreComment;
use Content;
use ContentHandler;
use DeferredUpdates;
use Hooks;
use IDBAccessObject;
use JobQueueGroup;
use Language;
use LinksUpdate;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Linker\LinkTarget;
use MessageCache;
use MWException;
use ParserCache;
use ParserOptions;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecentChange;
use RecentChangesUpdateJob;
use ResourceLoaderWikiModule;
use Revision;
use RuntimeException;
use SearchUpdate;
use SiteStatsUpdate;
use Status;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\LoadBalancer;
use WikiPage;

/**
 * FIXME: header
 * FIXME: document!
 */
class PageUpdater implements IDBAccessObject {

	/**
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var ParserCache
	 */
	private $parserCache;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * @var LoggerInterface
	 */
	private $saveParseLogger;

	/**
	 * @var JobQueueGroup
	 */
	private $jobQueueGroup;

	/**
	 * @var MessageCache
	 */
	private $messageCache;

	/**
	 * @var boolean
	 */
	private $useAutomaticEditSummaries = true; // TODO: setters, wiring!

	/**
	 * @var boolean,
	 */
	private $useRCPatrol = true;

	/**
	 * @var boolean
	 */
	private $useNPPatrol = true;

	/**
	 * @var boolean
	 */
	private $ajaxEditStash = true;

	/**
	 * @var boolean
	 */
	private $rcWatchCategoryMembership = false;

	/**
	 * @var Content[] Array, indexed by slot name
	 */
	private $content = [];

	/**
	 * @var bool
	 */
	private $baseRevId = false;

	/**
	 * @var array
	 */
	private $tags = [];

	/**
	 * @var int
	 */
	private $undidRevId = 0;

	/** @var PreparedEdit[] PreparedEdit objects, with slot roles as keys */
	private $preparedEdit = [];

	/**
	 * @var null|Status
	 */
	private $status;

	/**
	 * @param WikiPage $wikiPage ,
	 * @param LoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 * @param ParserCache $parserCache
	 * @param JobQueueGroup $jobQueueGroup
	 * @param MessageCache $messageCache
	 * @param Language $contentLanguage
	 * @param LoggerInterface $saveParseLogger
	 */
	public function __construct(
		WikiPage $wikiPage,
		LoadBalancer $loadBalancer,
		RevisionStore $revisionStore,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		MessageCache $messageCache,
		Language $contentLanguage,
		LoggerInterface $saveParseLogger = null
	) {
		$this->wikiPage = $wikiPage;

		$this->loadBalancer = $loadBalancer;
		$this->parserCache = $parserCache;
		$this->revisionStore = $revisionStore;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->messageCache = $messageCache;
		$this->contentLanguage = $contentLanguage;
		$this->saveParseLogger = $saveParseLogger ?: new NullLogger();
	}

	/**
	 * @param bool $useAutomaticEditSummaries
	 */
	public function setUseAutomaticEditSummaries( $useAutomaticEditSummaries ) {
		$this->useAutomaticEditSummaries = $useAutomaticEditSummaries;
	}

	/**
	 * @param bool $useRCPatrol
	 */
	public function setUseRCPatrol( $useRCPatrol ) {
		$this->useRCPatrol = $useRCPatrol;
	}

	/**
	 * @param bool $useNPPatrol
	 */
	public function setUseNPPatrol( $useNPPatrol ) {
		$this->useNPPatrol = $useNPPatrol;
	}

	/**
	 * @param bool $ajaxEditStash
	 */
	public function setAjaxEditStash( $ajaxEditStash ) {
		$this->ajaxEditStash = $ajaxEditStash;
	}

	/**
	 * @param bool $rcWatchCategoryMembership
	 */
	public function setRcWatchCategoryMembership( $rcWatchCategoryMembership ) {
		$this->rcWatchCategoryMembership = $rcWatchCategoryMembership;
	}

	private function getWikiId() {
		return false; // TODO: get from RevisionStore!
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	private function getDBConnectionRef( $mode ) {
		return $this->loadBalancer->getConnectionRef( $mode, [], $this->getWikiId() );
	}

	/**
	 * @return LinkTarget
	 */
	private function getLinkTarget() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage->getTitle();
	}

	/**
	 * @return Title
	 */
	private function getTitle() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage->getTitle();
	}

	/**
	 * @return WikiPage
	 */
	private function getWikiPage() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage;
	}

	/**
	 * @return RevisionRecord|null
	 */
	private function getBaseRevision( $from = self::READ_LATEST ) {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$this->loadPageData( $from );
		$rev = $this->wikiPage->getRevision();
		return $rev ? $rev->getRevisionRecord() : $rev;
	}

	/**
	 * @param int $from
	 */
	private function loadPageData( $from = self::READ_LATEST ) {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$this->wikiPage->loadPageData( $from );
	}

	/**
	 * @return int
	 */
	private function getPageId() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$this->wikiPage->getId();
	}

	/**
	 * @return string
	 */
	private function getTimestampNow() {
		// TODO: allow an override to be injected for testing
		return wfTimestampNow();
	}

	/**
	 * @param string $role slot role name
	 * @return ContentHandler
	 */
	private function getContentHandler( $role ) {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$this->wikiPage->getContentHandler( $role );
	}

	/**
	 * @return boolean
	 */
	private function exists() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$this->wikiPage->exists();
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 * @param int $flags
	 * @return int Updated $flags
	 */
	private function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->getTitle()->exists() ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		return $flags;
	}

	/**
	 * Set the new content for the given slot role
	 *
	 * @param string $role A slot role name (such as "main")
	 * @param Content $content
	 */
	public function setContent( $role, Content $content ) {
		$this->content[$role] = $content;

		unset( $this->preparedEdit[$role] );
	}

	/**
	 * @return bool|int
	 */
	public function getBaseRevisionId() {
		return $this->baseRevId;
	}

	/**
	 * Sets the revision ID this edit was based off, if any.
	 * This is not the parent revision ID, rather the revision ID for older
	 * content used as the source for a rollback, for example.
	 *
	 * @param int $baseRevId
	 */
	public function setBaseRevisionId( $baseRevId ) {
		Assert::parameterType( 'integer|boolean', $baseRevId, '$baseRevId' );
		$this->baseRevId = $baseRevId;
	}

	/**
	 * @return string[]
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Sets a tag to apply to this edit.
	 * Callers are responsible for permission checks,
	 * using ChangeTags::canAddTagsAccompanyingChange.
	 * @param string $tag
	 */
	public function addTag( $tag ) {
		Assert::parameterType( 'string', $tag, '$tag' );
		$this->tags[] = $tag;
	}

	/**
	 * @return int
	 */
	public function getUndidRevisionId() {
		return $this->undidRevId;
	}

	/**
	 * Sets the ID of revision that was undone by the present update.
	 * @param int $undidRevId
	 */
	public function setUndidRevisionId( $undidRevId ) {
		Assert::parameterType( 'integer', $undidRevId, '$undidRevId' );
		$this->undidRevId = $undidRevId;
	}

	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * MCR migration note: this replaces WikiPage::doEditContent.
	 *
	 * @param CommentStoreComment $summary Edit summary
	 * @param User $user The user doing the edit
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
	 * @throws MWException
	 * @return Revision|null
	 *
	 * @throws MWException
	 */
	public function commitUpdate( CommentStoreComment $summary, User $user, $flags = 0 ) {
		if ( $this->wasCommitted() ) {
			throw new RuntimeException( 'commitUpdate() has already been called on this PageUpdater!' );
		}

		// Defend against mistakes caused by differences with the
		// signature of WikiPage::doEditContent.
		Assert::parameterType( 'integer', $flags, '$flags' );
		Assert::parameterType( 'string|CommentStoreComment', $summary, '$summary' );

		if ( count( $this->content ) > 1 ) {
			throw new RuntimeException(
				'Only content for the "main" slot is currently supported'
			);
		}

		/** @var Content $content */
		$content = $this->content['main']; // TODO: MCR!

		// Low-level sanity check
		if ( $this->getLinkTarget()->getText() === '' ) {
			throw new MWException( 'Something is trying to edit an article with an empty title' );
		}
		// Make sure the given content type is allowed for this page
		if ( !$content->getContentHandler()->canBeUsedOn( $this->getTitle() ) ) {
			return Status::newFatal( 'content-not-allowed-here',
				ContentHandler::getLocalizedName( $content->getModel() ),
				$this->getTitle()->getPrefixedText()
			);
		}

		// Load the data from the master database if needed.
		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$this->loadPageData( self::READ_LATEST );

		$flags = $this->checkFlags( $flags );

		// TODO: use this only for the legacy hook, and only if something uses the legacy hook
		$wikiPage = $this->getWikiPage();

		// Trigger pre-save hook (using provided edit summary)
		$hookStatus = Status::newGood( [] );
		// TODO: replace legacy hook!
		$hook_args = [ &$wikiPage, &$user, &$content, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$hookStatus ];
		// Check if the hook rejected the attempted save
		if ( !Hooks::run( 'PageContentSave', $hook_args ) ) {
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			return $hookStatus;
		}

		// current revision's content
		$old_revision = $this->getBaseRevision();
		$old_content = $old_revision ? $old_revision->getContent( 'main', RevisionRecord::RAW ) : null;

		$handler = $content->getContentHandler();

		$tags = $this->tags;
		$tag = $handler->getChangeTag( $old_content, $content, $flags ); // TODO: MCR!
		// If there is no applicable tag, null is returned, so we need to check
		if ( $tag ) {
			$tags[] = $tag;
		}

		// Check for undo tag
		if ( $this->undidRevId !== 0 && in_array( 'mw-undo', ChangeTags::getSoftwareTags() ) ) {
			$tags[] = 'mw-undo';
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		if ( $this->useAutomaticEditSummaries && ( $flags & EDIT_AUTOSUMMARY ) && $summary == '' ) {
			$summary = $handler->getAutosummary( $old_content, $content, $flags );
		}

		// Avoid statsd noise and wasted cycles check the edit stash (T136678)
		if ( ( $flags & EDIT_INTERNAL ) || ( $flags & EDIT_FORCE_BOT ) ) {
			$useCache = false;
		} else {
			$useCache = true;
		}

		// Get the pre-save transform content and final parser output
		$meta = [
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'baseRevId' => $this->baseRevId,
			'oldRevision' => $old_revision,
			'oldId' => $old_revision ? $old_revision->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(),
			'oldCountable' => $wikiPage->isCountable(),
			'tags' => ( $tags !== null ) ? (array)$tags : [],
			'undidRevId' => $this->undidRevId,
			'useCache' => $useCache
		];

		// Actually create the revision and create/update the page
		if ( $flags & EDIT_UPDATE ) {
			$this->status = $this->doModify( $summary, $user, $flags, $meta );
		} else {
			$this->status = $this->doCreate( $summary, $user, $flags, $meta );
		}

		// Promote user to any groups they meet the criteria for
		DeferredUpdates::addCallableUpdate( function () use ( $user ) {
			$user->addAutopromoteOnceGroups( 'onEdit' );
			$user->addAutopromoteOnceGroups( 'onView' ); // b/c
		} );

		// FIXME: replace bad status with Exceptions!
		return $this->status && $this->status->isOK() ? $this->status->value['revision'] : null;
	}

	/**
	 * Whether commitUpdate() has been called on this instance
	 *
	 * @return bool
	 */
	public function wasCommitted() {
		return $this->status !== null;
	}

	/**
	 * The Status object indicating whether commitUpdate() was successful, or null if
	 * commitUpdate() was not yet called on this instance.
	 *
	 * @note This is here for compatibility with WikiPage::doEditContent. It may be deprecated
	 * soon.
	 *
	 * Possible status errors:
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
	 * @return null|Status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Whether commitUpdate() completed successfully
	 *
	 * @return bool
	 */
	public function wasSuccess() {
		return $this->status && $this->status->isOK();
	}

	/**
	 * Whether commitUpdate() was called and created a new page.
	 *
	 * @return bool
	 */
	public function isNew() {
		return $this->status && $this->status->value['new'];
	}

	/**
	 * Whether commitUpdate() did not create a revision because the content didn't change
	 * (null-edit).
	 *
	 * @return bool
	 */
	public function isUnchanged() {
		return $this->status && $this->status->isOK() && $this->status->value['revision'] === null;
	}

	/**
	 * The new revision created by commitUpdate(), or null if commitUpdate() has not yet been
	 * called, failed, or did not create a new revision because the content did not change.
	 *
	 * @return RevisionRecord|null
	 */
	public function getNewRevision() {
		return $this->status && $this->status->isOK() ? $this->status->value['revision'] : null;
	}

	/**
	 * Adds all content to $rec, after applying pre-save-transofmrations and calling
	 * prepareSave().
	 *
	 * @param MutableRevisionRecord $rec
	 * @param User $user
	 * @param $flags
	 * @param array $meta
	 * @return Status
	 */
	private function addAllContentForEdit(
		MutableRevisionRecord $rec,
		User $user,
		$flags,
		array $meta
	) {
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		$status = Status::newGood( [ 'new' => false, 'revision' => null ] );

		$oldid = $meta['oldId'];
		$useCache = $meta['useCache'];

		foreach ( $this->content as $role => $content ) {
			// FIXME: MCR: old content and new content will have to be combined *before* parsing!
			$editInfo = $this->prepareContentForEdit( $role, $content, null, $user, $useCache );
			$content = $editInfo->pstContent;

			// TODO: change signature of prepareSave to not require a WikiPage
			$prepStatus = $content->prepareSave( $wikiPage, $flags, $oldid, $user );

			if ( $prepStatus->isOK() ) {
				$rec->setContent( $role, $content );
			} else {
				$status->merge( $prepStatus );
			}
		}

		return $status;
	}

	/**
	 * @param CommentStoreComment $summary
	 * @param User $user
	 * @param int $flags
	 * @param array $meta
	 * @return Status
	 * @throws MWException
	 */
	private function doModify( CommentStoreComment $summary, User $user, $flags, array $meta ) {
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		// Update article, but only if changed.
		$status = Status::newGood( [ 'new' => false, 'revision' => null ] );

		// Convenience variables
		$now = $this->getTimestampNow();

		/** @var RevisionRecord $baseRev */
		$baseRev = $meta['oldRevision'];
		$oldid = $meta['oldId'];

		if ( !$baseRev ) {
			// Article gone missing
			$status->fatal( 'edit-gone-missing' );

			return $status;
		}

		$newRevisionRecord = MutableRevisionRecord::newFromParentRevision(
			$this->getBaseRevision(), // XXX: pass as parameter for clear information flow?
			$summary,
			$user,
			$now
		);
		$newRevisionRecord->setMinorEdit( $meta['minor'] );

		$contentStatus = $this->addAllContentForEdit( $newRevisionRecord, $user, $flags, $meta );
		$status->merge( $contentStatus );

		if ( !$status->isOK() ) {
			return $status;
		}

		$changed = $newRevisionRecord->getSha1() !== $baseRev->getSha1();
		$mainContent = $newRevisionRecord->getContent( 'main' );

		$dbw = $this->getDBConnectionRef( DB_MASTER );

		if ( $changed ) {
			$dbw->startAtomic( __METHOD__ );

			// Get the latest page_latest value while locking it.
			// Do a CAS style check to see if it's the same as when this method
			// started. If it changed then bail out before touching the DB.
			$latestNow = $wikiPage->lockAndGetLatest(); // TODO: move to storage service, pass DB
			if ( $latestNow != $oldid ) {
				$dbw->endAtomic( __METHOD__ ); // XXX: don't we want somethinhg like abortAtomic here?
				// Page updated or deleted in the mean time
				$status->fatal( 'edit-conflict' );

				return $status;
			}

			// At this point we are now comitted to returning an OK
			// status unless some DB query error or other exception comes up.
			// This way callers don't have to call rollback() if $status is bad
			// unless they actually try to catch exceptions (which is rare).

			// Save revision content and meta-data
			$newRevisionRecord = $this->revisionStore->insertRevisionOn( $newRevisionRecord, $dbw );
			$newLegacyRevision = new Revision( $newRevisionRecord );

			// Update page_latest and friends to reflect the new revision
			if ( !$wikiPage->updateRevisionOn( $dbw, $newLegacyRevision, null, $meta['oldIsRedirect'] ) ) {
				throw new MWException( "Failed to update page row to use new revision." );
			}

			// TODO: replace legacy hook!
			Hooks::run( 'NewRevisionFromEditComplete',
				[ $wikiPage, $newLegacyRevision, $meta['baseRevId'], $user ] );

			// Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				// Mark as patrolled if the user can do so
				$patrolled = $this->useRCPatrol && !count(
						$this->getTitle()->getUserPermissionsErrors( 'autopatrol', $user ) );
				// Add RC row to the DB
				RecentChange::notifyEdit(
					$now,
					$this->getTitle(),
					$newRevisionRecord->isMinor(),
					$user,
					$summary->text, // TODO: pass object when that becomes possible
					$oldid,
					$newRevisionRecord->getTimestamp(),
					$meta['bot'],
					'',
					$baseRev->getSize(),
					$newRevisionRecord->getSize(),
					$newRevisionRecord->getId(),
					$patrolled,
					$meta['tags']
				);
			}

			$user->incEditCount();

			$dbw->endAtomic( __METHOD__ );
		} else {
			// T34948: revision ID must be set to page {{REVISIONID}} and
			// related variables correctly. Likewise for {{REVISIONUSER}} (T135261).
			// Since we don't insert a new revision into the database, the least
			// error-prone way is to reuse given old revision.
			$newRevisionRecord = $meta['oldRevision'];
			$newLegacyRevision = new Revision( $newRevisionRecord );
		}

		if ( $changed ) {
			// Return the new revision to the caller
			$status->value['revision'] = $newRevisionRecord;
		} else {
			$status->warning( 'edit-no-change' );
			// Update page_touched as updateRevisionOn() was not called.
			// Other cache updates are managed in onArticleEdit() via doEditUpdates().
			$this->getTitle()->invalidateCache( $now );
		}

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$wikiPage, $newRevisionRecord, $newLegacyRevision, &$user, $mainContent,
					$summary, &$flags, $changed, $meta, &$status
				) {
					// Update links tables, site stats, etc.
					$this->doEditUpdates(
						$newRevisionRecord,
						$user,
						[
							'changed' => $changed,
							'oldcountable' => $meta['oldCountable'],
							'oldrevision' => $meta['oldRevision']
						]
					);
					// Trigger post-save hook
					// TODO: replace legacy hook!
					// FIXME: $status has the wrong kind of Revision in the value field!
					$params = [ &$wikiPage, &$user, $mainContent, $summary->text, $flags & EDIT_MINOR,
						null, null, &$flags, $newLegacyRevision, &$status, $meta['baseRevId'],
						$meta['undidRevId'] ];
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * @param CommentStoreComment $summary
	 * @param User $user
	 * @param int $flags
	 * @param array $meta
	 * @throws DBUnexpectedError
	 * @throws MWException
	 * @return Status
	 */
	private function doCreate( CommentStoreComment $summary, User $user, $flags, array $meta ) {
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		$status = Status::newGood( [ 'new' => true, 'revision' => null ] );

		$now = $this->getTimestampNow();

		$newRevisionRecord = new MutableRevisionRecord( $this->getTitle(), $this->getWikiId() );
		$newRevisionRecord->setComment( $summary );
		$newRevisionRecord->setMinorEdit( $meta['minor'] );
		$newRevisionRecord->setUser( $user );
		$newRevisionRecord->setTimestamp( $now );

		$contentStatus = $this->addAllContentForEdit( $newRevisionRecord, $user, $flags, $meta );
		$status->merge( $contentStatus );

		if ( !$status->isOK() ) {
			return $status;
		}

		$dbw = $this->getDBConnectionRef( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		$newid = $wikiPage->insertOn( $dbw );
		if ( $newid === false ) {
			$dbw->endAtomic( __METHOD__ ); // nothing inserted
			$status->fatal( 'edit-already-exists' );

			return $status; // nothing done
		}

		// At this point we are now comitted to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).
		$newRevisionRecord->setPageId( $newid );

		// Save the revision text...
		$newRevisionRecord = $this->revisionStore->insertRevisionOn( $newRevisionRecord, $dbw );
		$newLegacyRevision = new Revision( $newRevisionRecord );

		// Update the page record with revision data
		if ( !$wikiPage->updateRevisionOn( $dbw, $newLegacyRevision, 0 ) ) {
			throw new MWException( "Failed to update page row to use new revision." );
		}

		// TODO: replace legacy hook!
		Hooks::run( 'NewRevisionFromEditComplete', [ $wikiPage, $newLegacyRevision, false, $user ] );

		// Update recentchanges
		if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
			// Mark as patrolled if the user can do so
			$patrolled = ( $this->useRCPatrol || $this->useNPPatrol ) &&
				!count( $this->getTitle()->getUserPermissionsErrors( 'autopatrol', $user ) );
			// Add RC row to the DB
			RecentChange::notifyNew(
				$now,
				$this->getTitle(),
				$newRevisionRecord->isMinor(),
				$user,
				$summary->text, // TODO: pass object when that becomes possible
				$meta['bot'],
				'',
				$newRevisionRecord->getSize(),
				$newRevisionRecord->getId(),
				$patrolled,
				$meta['tags']
			);
		}

		$user->incEditCount();

		$dbw->endAtomic( __METHOD__ );

		// Return the new revision to the caller
		$status->value['revision'] = $newRevisionRecord;

		// XXX: make sure we are not loading the Content from the DB
		$mainContent = $newRevisionRecord->getContent( 'main' );

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$wikiPage, $newLegacyRevision, &$user, $mainContent, $summary, &$flags, $meta, &$status
				) {
					// Update links, etc.
					$wikiPage->doEditUpdates( $newLegacyRevision, $user, [ 'created' => true ] );
					// Trigger post-create hook
					// TODO: replace legacy hook!
					$params = [ &$wikiPage, &$user, $mainContent, $summary,
						$flags & EDIT_MINOR, null, null, &$flags, $newLegacyRevision ];
					Hooks::run( 'PageContentInsertComplete', $params );
					// Trigger post-save hook
					// TODO: replace legacy hook!
					// FIXME: $status has the wrong kind of Revision in the value field!
					$params = array_merge( $params, [ &$status, $meta['baseRevId'], 0 ] );
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * Prepare content which is about to be saved.
	 *
	 * @todo This should not be part of the public interface.
	 *
	 * @param string $role slot role name, suche as "main".
	 * @param Content $content
	 * @param RevisionRecord|int|null $revision Revision object. For backwards compatibility, a
	 *        revision ID is also accepted, but this is deprecated.
	 * @param User|null $user
	 * @param bool $useCache Check shared prepared edit cache
	 * @return PreparedEdit
	 * @throws MWException
	 */
	public function prepareContentForEdit(
		$role, Content $content, $revision = null, User $user,
		$useCache = true
	) {
		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!
		$title = $this->getTitle();

		if ( is_object( $revision ) ) {
			$revid = $revision->getId();
		} else {
			$revid = $revision;
			// This code path is deprecated, and nothing is known to
			// use it, so performance here shouldn't be a worry.
			if ( $revid !== null ) {
				wfDeprecated( __METHOD__ . ' with $revision = revision ID', '1.25' );
				$revision = $this->revisionStore->getRevisionById( $revid, Revision::READ_LATEST );
			} else {
				$revision = null;
			}
		}

		// XXX: check $user->getId() here???

		// Use a sane default for $serialFormat, see T59026
		$serialFormat = $content->getContentHandler()->getDefaultFormat();

		if ( isset( $this->preparedEdit[$role] )
			&& isset( $this->preparedEdit[$role]->newContent )
			&& $this->preparedEdit[$role]->newContent->equals( $content )
			&& $this->preparedEdit[$role]->revid == $revid
			&& $this->preparedEdit[$role]->format == $serialFormat
			// XXX: also check $user here?
		) {
			// Already prepared
			return $this->preparedEdit[$role];
		}

		// The edit may have already been prepared via api.php?action=stashedit
		$cachedEdit = $useCache && $this->ajaxEditStash
			? ApiStashEdit::checkCache( $title, $content, $user )
			: false;

		// TODO: replace legacy hook!
		$popts = ParserOptions::newFromUserAndLang( $user, $this->contentLanguage );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $popts ] );

		$edit = new PreparedEdit();
		if ( $cachedEdit ) {
			$edit->timestamp = $cachedEdit->timestamp;
		} else {
			$edit->timestamp = $this->getTimestampNow();
		}
		// @note: $cachedEdit is safely not used if the rev ID was referenced in the text
		$edit->revid = $revid;

		if ( $cachedEdit ) {
			$edit->pstContent = $cachedEdit->pstContent;
		} else {
			$edit->pstContent = $content
				? $content->preSaveTransform( $title, $user, $popts )
				: null;
		}

		$edit->format = $serialFormat;
		$edit->popts = $wikiPage->makeParserOptions( 'canonical' );
		if ( $cachedEdit ) {
			$edit->output = $cachedEdit->output;
		} else {
			if ( $revision ) {
				// We get here if vary-revision is set. This means that this page references
				// itself (such as via self-transclusion). In this case, we need to make sure
				// that any such self-references refer to the newly-saved revision, and not
				// to the previous one, which could otherwise happen due to replica DB lag.
				$oldCallback = $edit->popts->getCurrentRevisionCallback();
				$edit->popts->setCurrentRevisionCallback(
					function ( Title $title, $parser = false ) use ( $revision, &$oldCallback ) {
						// FIXME: $title never equals LinkTarget!!!!
						if ( $title->equals( $revision->getPageAsLinkTarget() ) ) {
							return $revision;
						} else {
							return call_user_func( $oldCallback, $title, $parser );
						}
					}
				);
			} else {
				// XXX: how and when do we get here without READ_LATEST??
				// Try to avoid a second parse if {{REVISIONID}} is used
				$dbIndex = $wikiPage->wasLoadedFrom( self::READ_LATEST )
					? DB_MASTER // use the best possible guess
					: DB_REPLICA; // T154554

				$edit->popts->setSpeculativeRevIdCallback( function () use ( $dbIndex ) {
					return 1 + (int)$this->getDBConnectionRef( $dbIndex )->selectField(
						'revision',
						'MAX(rev_id)',
						[],
						__METHOD__
					);
				} );
			}
			$edit->output = $edit->pstContent
				? $edit->pstContent->getParserOutput( $title, $revid, $edit->popts )
				: null;
		}

		$baseRev = $this->getBaseRevision();
		$edit->newContent = $content; // TODO: MCR!
		$edit->oldContent = $baseRev ? $baseRev->getContent( $role, RevisionRecord::RAW ) : null;

		// NOTE: B/C for hooks! don't use these fields!
		$edit->newText = $edit->newContent
			? ContentHandler::getContentText( $edit->newContent )
			: '';
		$edit->oldText = $edit->oldContent
			? ContentHandler::getContentText( $edit->oldContent )
			: '';
		$edit->pst = $edit->pstContent ? $edit->pstContent->serialize( $serialFormat ) : '';

		if ( $edit->output ) {
			$edit->output->setCacheTime( $this->getTimestampNow() );
		}

		// Process cache the result
		$this->preparedEdit[$role] = $edit;

		return $edit;
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @param RevisionRecord $revision
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
	 */
	public function doEditUpdates( RevisionRecord $revision, User $user, array $options = [] ) {
		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!
		$role = 'main'; // FIXME: MCR!

		$options += [
			'changed' => true,
			'created' => false,
			'moved' => false,
			'restored' => false,
			'oldrevision' => null,
			'oldcountable' => null
		];

		$content = $revision->getContent( $role );

		// See if the parser output before $revision was inserted is still valid
		$editInfo = false;
		if ( !isset( $this->preparedEdit[$role] ) ) {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared edit...\n" );
		} elseif ( $this->preparedEdit[$role]->output->getFlag( 'vary-revision' ) ) {
			$this->saveParseLogger->info( __METHOD__ . ": Prepared edit has vary-revision...\n" );
		} elseif ( $this->preparedEdit[$role]->output->getFlag( 'vary-revision-id' )
			&& $this->preparedEdit[$role]->output->getSpeculativeRevIdUsed() !== $revision->getId()
		) {
			$this->saveParseLogger->info(
				__METHOD__ . ": Prepared edit has vary-revision-id with wrong ID...\n"
			);
		} elseif ( $this->preparedEdit[$role]->output->getFlag( 'vary-user' )
			&& !$options['changed']
		) {
			$this->saveParseLogger->info(
				__METHOD__ . ": Prepared edit has vary-user and is null...\n"
			);
		} else {
			wfDebug( __METHOD__ . ": Using prepared edit...\n" );
			$editInfo = isset( $this->preparedEdit[$role] ) ? $this->preparedEdit[$role] : null;
		}

		if ( !$editInfo ) {
			// Parse the text again if needed. Be careful not to do pre-save transform twice:
			// $text is usually already pre-save transformed once. Avoid using the edit stash
			// as any prepared content from there or in doEditContent() was already rejected.
			$editInfo = $this->prepareContentForEdit( $role, $content, $revision, $user, false );
		}

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		$this->parserCache->save(
			$editInfo->output, $wikiPage, $editInfo->popts,
			$revision->getTimestamp(), $editInfo->revid
		);

		$legacyRevision = new Revision( $revision );

		// Update the links tables and other secondary data
		if ( $content ) {
			$recursive = $options['changed']; // T52785
			$updates = $content->getSecondaryDataUpdates(
				$this->getTitle(), null, $recursive, $editInfo->output
			);
			foreach ( $updates as $update ) {
				$update->setCause( 'edit-page', $user->getName() );
				if ( $update instanceof LinksUpdate ) {
					$update->setRevision( $legacyRevision );
					$update->setTriggeringUser( $user );
				}
				DeferredUpdates::addUpdate( $update );
			}
			if ( $this->rcWatchCategoryMembership
				&& $this->getContentHandler( $role )->supportsCategories() === true
				&& ( $options['changed'] || $options['created'] )
				&& !$options['restored']
			) {
				// Note: jobs are pushed after deferred updates, so the job should be able to see
				// the recent change entry (also done via deferred updates) and carry over any
				// bot/deletion/IP flags, ect.
				$this->jobQueueGroup->lazyPush( new CategoryMembershipChangeJob(
														  $this->getTitle(),
														  [
															  'pageId' => $this->getPageId(),
															  'revTimestamp' => $revision->getTimestamp()
														  ]
													  ) );
			}
		}

		// TODO: replace legacy hook!
		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $options['changed'] ] );

		// TODO: replace legacy hook!
		if ( Hooks::run( 'ArticleEditUpdatesDeleteFromRecentchanges', [ &$wikiPage ] ) ) {
			// Flush old entries from the `recentchanges` table
			if ( mt_rand( 0, 9 ) == 0 ) {
				$this->jobQueueGroup->lazyPush( RecentChangesUpdateJob::newPurgeJob() );
			}
		}

		if ( !$this->exists() ) {
			return;
		}

		$id = $this->getPageId();
		$title = $this->getTitle();
		$dbKey = $title->getPrefixedDBkey();
		$shortTitle = $title->getDBkey();

		if ( $options['oldcountable'] === 'no-change' ||
			( !$options['changed'] && !$options['moved'] )
		) {
			$good = 0;
		} elseif ( $options['created'] ) {
			$good = (int)$wikiPage->isCountable( $editInfo );
		} elseif ( $options['oldcountable'] !== null ) {
			$good = (int)$wikiPage->isCountable( $editInfo ) - (int)$options['oldcountable'];
		} else {
			$good = 0;
		}
		$edits = $options['changed'] ? 1 : 0;
		$total = $options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, $edits, $good, $total ) );
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $dbKey, $content ) );

		// If this is another user's talk page, update newtalk.
		// Don't do this if $options['changed'] = false (null-edits) nor if
		// it's a minor edit and the user doesn't want notifications for those.
		if ( $options['changed']
			&& $title->getNamespace() == NS_USER_TALK
			&& $shortTitle != $user->getTitleKey()
			&& !( $revision->isMinor() && $user->isAllowed( 'nominornewtalk' ) )
		) {
			$recipient = User::newFromName( $shortTitle, false );
			if ( !$recipient ) {
				wfDebug( __METHOD__ . ": invalid username\n" );
			} else {
				// Allow extensions to prevent user notification
				// when a new message is added to their talk page
				// TODO: replace legacy hook!
				if ( Hooks::run( 'ArticleEditUpdateNewTalk', [ &$wikiPage, $recipient ] ) ) {
					if ( User::isIP( $shortTitle ) ) {
						// An anonymous user
						$recipient->setNewtalk( true, $legacyRevision );
					} elseif ( $recipient->isLoggedIn() ) {
						$recipient->setNewtalk( true, $legacyRevision );
					} else {
						wfDebug( __METHOD__ . ": don't need to notify a nonexistent user\n" );
					}
				}
			}
		}

		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			$this->messageCache->updateMessageOverride( $title, $content );
		}

		if ( $options['created'] ) {
			WikiPage::onArticleCreate( $title );
		} elseif ( $options['changed'] ) { // T52785
			WikiPage::onArticleEdit( $title, $legacyRevision );
		}

		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $options['oldrevision'], $legacyRevision, wfWikiID()
		);
	}

}
