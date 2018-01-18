<?php
/**
 * Content object for wiki text pages.
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
 *
 * @author Daniel Kinzler
 */

namespace MediaWiki\Storage;

use ApiStashEdit;
use AtomicSectionUpdate;
use CategoryMembershipChangeJob;
use ChangeTags;
use CommentStoreComment;
use Content;
use ContentHandler;
use DataUpdate;
use DeferredUpdates;
use Hooks;
use IDBAccessObject;
use JobQueueGroup;
use Language;
use LinksUpdate;
use LogicException;
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
 * Controller-like object for creating and updating pages by creating new revisions.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage.
 *
 * @since 1.31
 * @ingroup Content
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
	 * @var boolean see $wgUseAutomaticEditSummaries
	 */
	private $useAutomaticEditSummaries = true; // TODO: setters, wiring!

	/**
	 * @var boolean see $wgUseRCPatrol
	 */
	private $useRCPatrol = true;

	/**
	 * @var boolean see $wgUseNPPatrol
	 */
	private $useNPPatrol = true;

	/**
	 * @var boolean see $wgAjaxEditStash
	 */
	private $ajaxEditStash = true;

	/**
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod;

	/**
	 * @var boolean see $wgRCWatchCategoryMembership
	 */
	private $rcWatchCategoryMembership = false;

	/**
	 * State of the page at the time time beginEdit() was called.
	 * Null before beginEdit() was called.
	 *
	 * @var array|null
	 */
	private $pageState = null;

	/**
	 * @var MutableRevisionSlots
	 */
	private $newContentSlots;

	/**
	 * The ID of the logical base revision of this edit. Not to be confused with the
	 * immediate parent revision. The base revision is defined by the client, the parent
	 * revision is determined by beginEdit().
	 *
	 * @var bool|int
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

	/** @var PreparedEdit PreparedEdit object */
	private $preparedEdit;

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

		// XXX: replace all wfDebug calls with a Logger. Do we nede more than one logger here?
		$this->saveParseLogger = $saveParseLogger ?: new NullLogger();

		$this->newContentSlots = new MutableRevisionSlots();
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
	 * @param string $articleCountMethod
	 */
	public function setArticleCountMethod( $articleCountMethod ) {
		$this->articleCountMethod = $articleCountMethod;
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
	 * Returns the immediate parent revision of the edit, which is the current revision of the page
	 * at the time when beginEdit() was called. Not to be confused with the logical
	 * base revision. The base revision is specified by the client, the parent revision is
	 * determined by beginEdit(). If base revision and parent revision are not the same,
	 * the edit is considered to require edit conflict resolution.
	 *
	 * Application may use this method (after calling beginEdit()) to perform edit conflict
	 * resolution by performing a 3-way merge, using the revision returned by this method as
	 * the conflicting revision and the revision indicated by getBaseRevisionId() as the
	 * common base.
	 *
	 * @return RevisionRecord
	 * @throws LogicException if called before beginEdit() was called.
	 */
	public function getParentRevision() {
		if ( !$this->wasBegun() ) {
			throw new LogicException( 'Parent revision is not known before beginEdit() has been called' );
		}

		return $this->pageState['oldRevision'];
	}

	/**
	 * Checks whether this edit conflicts with another edit performed since the specified base
	 * revision. An edit conflict is detected when the base revision known to the client and
	 * specified via setBaseRevisionId() is not the ID of the base revision. If setBaseRevisionId()
	 * was not called, this method always returns false.
	 *
	 * Note that an edit expected to be based on a non-existing page will have base revision ID 0,
	 * and is considered to have a conflict if a parent revision exists (that is, the page was
	 * created since hte base revision was determined by the client).
	 *
	 * This method returning true indicates to calling code that edit conflict resolution should
	 * be applied before saving any data. It does not prevent the edit from being performed, and
	 * it should not be confused with a "late" conflict arising from another edit being performed
	 * between the time beginEdit() was called and the time commitEdit() trying to insert the
	 * new revision.
	 *
	 * @return bool
	 */
	public function hasEditConflict() {
		$baseId = $this->getBaseRevisionId();
		if ( $baseId === false ) {
			return false;
		}

		$parent = $this->getParentRevision();
		$parentId = $parent ? $parent->getId() : 0;

		return $parentId !== $baseId;
	}

	/**
	 * Determines whether the page being edited already exists.
	 * Only defined after calling beginEdit()!
	 *
	 * @return boolean
	 * @throws LogicException if called before beginEdit() was called.
	 */
	private function pageExists() {
		if ( !$this->wasBegun() ) {
			throw new LogicException( 'Existence is not known before beginEdit() has been called' );
		}

		return $this->pageState['oldId'] > 0;
	}

	/**
	 * Begin the edit transaction. This takes a snapshot of the relevant status of the page
	 * at the time of this call. Will be called implicitely by commitEdit() if not called
	 * explicitly before.
	 *
	 * It is guaranteed that commitEdit() will fail if the current revision of the page
	 * changes after beginEdit() was called and before commitEdit() can insert a new revision.
	 *
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function beginEdit() {
		if ( $this->wasBegun() ) {
			return $this->pageState['oldRevision'];
		}

		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$wikiPage = $this->getWikiPage();

		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$wikiPage->loadPageData( self::READ_LATEST );
		$rev = $wikiPage->getRevision();
		$parent = $rev ? $rev->getRevisionRecord() : null;

		$this->pageState = [
			'oldRevision' => $parent,
			'oldId' => $rev ? $rev->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(),
			'oldCountable' => $wikiPage->isCountable(),
		];

		return $this->pageState['oldRevision'];
	}

	public function wasBegun() {
		return $this->pageState !== null;
	}

	/**
	 * @return int
	 */
	private function getPageId() {
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage->getId();
	}

	/**
	 * @return string
	 */
	private function getTimestampNow() {
		// TODO: allow an override to be injected for testing
		return wfTimestampNow();
	}

	/**
	 * Returns the slot, new or inherited, with no audience checks applied.
	 *
	 * @param string $role slot role name
	 * @return SlotRecord
	 *
	 * @throws PageUpdateException If the slot is neither set for update nor inherited from the
	 *         parent revision.
	 */
	private function getRawSlot( $role ) {
		if ( $this->newContentSlots->hasSlot( $role ) ) {
			return $this->newContentSlots->getSlot( $role );
		}

		$parent = $this->getParentRevision();

		if ( $parent && $parent->hasSlot( $role ) ) {
			return $parent->getSlot( $role, RevisionRecord::RAW );
		}

		throw new PageUpdateException( 'Slot not set for update and not inherited: ' . $role );
	}

	/**
	 * Returns the content of the given slot of the parent revision, with no audience checks applied.
	 * If there is no parent revision or the slot is not defined, this returns null.
	 *
	 * @param string $role slot role name
	 * @return Content
	 */
	private function getParentContent( $role ) {
		$parent = $this->getParentRevision();

		if ( $parent && $parent->hasSlot( $role ) ) {
			return $parent->getContent( $role, RevisionRecord::RAW );
		}

		return null;
	}

	/**
	 * Returns the content model of the given slot
	 *
	 * @param string $role slot role name
	 * @return string
	 */
	private function getContentModel( $role ) {
		return $this->getRawSlot( $role )->getModel();
	}

	/**
	 * @param string $role slot role name
	 * @return ContentHandler
	 */
	private function getContentHandler( $role ) {
		// TODO: inject something like a ContentHandlerRegistry
		return ContentHandler::getForModelID( $this->getContentModel( $role ) );
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 * This also performs sanity checks against the base revision specified via setBaseRevisionId().
	 *
	 * @param int $flags
	 * @return int Updated $flags
	 */
	private function checkFlags( $flags ) {
		if ( $this->baseRevId !== false ) {
			if ( $flags & EDIT_NEW && $this->baseRevId > 0 ) {
				throw new RuntimeException(
					'commitEdit called with EDIT_NEW but the base revision is not zero: '
						. $this->baseRevId . '!'
				);
			}

			if ( $flags & EDIT_UPDATE && $this->baseRevId === 0 ) {
				throw new RuntimeException(
					'commitEdit called with EDIT_UPDATE but the base revision is zero!'
				);
			}
		}

		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->pageExists() ) {
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
		$this->newContentSlots->setContent( $role, $content );

		$this->preparedEdit = null;
	}

	/**
	 * Returns the ID of the logical base revision of the edit. Not to be confused with the
	 * immediate parent revision. The base revision is set via setBaseRevisionId(),
	 * the parent revision is determined by beginEdit().
	 *
	 * Application may use this information to detect edit conflicts. Edit conflicts can be
	 * resolved by performing a 3-way merge, using the revision returned by this method as
	 * the commons base of the conflicting revisions, namely the new revision being saved,
	 * and the revision returned by getParentRevision().
	 *
	 * @return bool|int The ID of the base revision, 0 if the base is a non-existing page, false
	 *         if no base revision was specified.
	 */
	public function getBaseRevisionId() {
		return $this->baseRevId;
	}

	/**
	 * Sets the revision ID this edit was based off, if any. The base revision ID is not to be
	 * confused with the edit's parent revision: the parent revision is the current immediately
	 * before the edit is saved; the base revision indicates what revision the client expects
	 * to be the parent. If base revision and parent revision are not the same, the edit is
	 * considered to require edit conflict resolution.
	 *
	 * The base revision this indicates what revision an edit was logically based on.
	 *
	 * @param int $baseRevId The ID of the base revision, or 0 if the edit is expected to be
	 *        based on a non-existing page.
	 */
	public function setBaseRevisionId( $baseRevId ) {
		Assert::parameterType( 'integer', $baseRevId, '$baseRevId' );
		$this->baseRevId = $baseRevId;
	}

	/**
	 * Sets a tag to apply to this edit.
	 * Callers are responsible for permission checks,
	 * using ChangeTags::canAddTagsAccompanyingChange.
	 * @param string $tag
	 */
	public function addTag( $tag ) {
		Assert::parameterType( 'string', $tag, '$tag' );
		$this->tags[] = trim( $tag );
	}

	/**
	 * @return string[]
	 */
	public function getExpliciteTags() {
		return $this->tags;
	}

	/**
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 * @return \string[]
	 */
	private function computeEffectiveTags( $flags ) {
		$tags = $this->tags;

		foreach ( $this->newContentSlots->getSlotRoles() as $role ) {
			$old_content = $this->getParentContent( $role );

			$handler = $this->getContentHandler( $role );
			$content = $this->newContentSlots->getContent( $role );

			$tag = $handler->getChangeTag( $old_content, $content, $flags ); // TODO: MCR!
			// If there is no applicable tag, null is returned, so we need to check
			if ( $tag ) {
				$tags[] = $tag;
			}
		}

		return $tags;
	}

	/**
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return CommentStoreComment
	 */
	private function makeAutoSummary( $flags ) {
		if ( !$this->useAutomaticEditSummaries && ( $flags & EDIT_AUTOSUMMARY ) ) {
			return CommentStoreComment::newUnsavedComment( '' );
		}

		// NOTE: this generates an auto-summary for SOME RANDOM changed slot!
		// TODO: combine auto-summaries for multiple slots!
		// TODO: move this logic out of the storage layer!
		$role = reset( $this->newContentSlots->getSlotRoles() );

		if ( !$role ) {
			return CommentStoreComment::newUnsavedComment( '' );
		}

		$handler = $this->getContentHandler( $role );
		$content = $this->newContentSlots->getContent( $role );
		$old_content = $this->getParentContent( $role );
		$summary = $handler->getAutosummary( $old_content, $content, $flags );

		return CommentStoreComment::newUnsavedComment( $summary );
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
	 * It is guaranteed that commitEdit() will fail if the current revision of the page
	 * changes after beginEdit() was called and before commitEdit() can insert a new revision.
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
	 * @return Revision|null
	 * @throw MWException
	 * @throw RuntimeException
	 */
	public function commitEdit( CommentStoreComment $summary, User $user, $flags = 0 ) {

		// Defend against mistakes caused by differences with the
		// signature of WikiPage::doEditContent.
		Assert::parameterType( 'integer', $flags, '$flags' );
		Assert::parameterType( 'string|CommentStoreComment', $summary, '$summary' );

		if ( $this->wasCommitted() ) {
			throw new RuntimeException( 'commitEdit() has already been called on this PageUpdater!' );
		}

		/** @var Content $mainContent */
		$mainContent = $this->newContentSlots->getContent( 'main' ); // TODO: MCR!

		// Low-level sanity check
		if ( $this->getLinkTarget()->getText() === '' ) {
			throw new RuntimeException( 'Something is trying to edit an article with an empty title' );
		}
		// Make sure the given content type is allowed for this page
		// TODO: decide: Extend check to other slots? Consider the role in check?
		$mainContentHandler = $this->getContentHandler( 'main' );
		if ( !$mainContentHandler->canBeUsedOn( $this->getTitle() ) ) {
			$this->status = Status::newFatal( 'content-not-allowed-here',
				ContentHandler::getLocalizedName( $mainContentHandler->getModelID() ),
				$this->getTitle()->getPrefixedText()
			);
			return null;
		}

		// Load the data from the master database if needed.
		if ( !$this->wasBegun() ) {
			$this->beginEdit();
		}

		$flags = $this->checkFlags( $flags );

		// TODO: use this only for the legacy hook, and only if something uses the legacy hook
		$wikiPage = $this->getWikiPage();

		// Trigger pre-save hook (using provided edit summary)
		$hookStatus = Status::newGood( [] );
		// TODO: replace legacy hook!
		$hook_args = [ &$wikiPage, &$user, &$mainContent, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$hookStatus ];
		// Check if the hook rejected the attempted save
		if ( !Hooks::run( 'PageContentSave', $hook_args ) ) {
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			$this->status = $hookStatus;
			return null;
		}

		// current revision's content
		$tags = $this->computeEffectiveTags( $flags );

		// Check for undo tag
		if ( $this->undidRevId !== 0 && in_array( 'mw-undo', ChangeTags::getSoftwareTags() ) ) {
			$tags[] = 'mw-undo';
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		// TODO: Move this logic out of the storage layer! It does not belong here!
		if ( $summary->text === '' && $summary->data === null ) {
			$summary = $this->makeAutoSummary( $flags );
		}

		// Avoid statsd noise and wasted cycles check the edit stash (T136678)
		if ( ( $flags & EDIT_INTERNAL ) || ( $flags & EDIT_FORCE_BOT ) ) {
			$useStashed = false;
		} else {
			$useStashed = true;
		}

		// Get the pre-save transform content and final parser output
		$meta = [
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'baseRevId' => $this->baseRevId,
			'tags' => ( $tags !== null ) ? (array)$tags : [],
			'undidRevId' => $this->undidRevId,
			'useStashed' => $useStashed
		] + $this->pageState;

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

		// TODO: replace bad status with Exceptions!
		return ( $this->status && $this->status->isOK() )
			? $this->status->value['revision-record']
			: null;
	}

	/**
	 * Whether commitEdit() has been called on this instance
	 *
	 * @return bool
	 */
	public function wasCommitted() {
		return $this->status !== null;
	}

	/**
	 * The Status object indicating whether commitEdit() was successful, or null if
	 * commitEdit() was not yet called on this instance.
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
	 * Whether commitEdit() completed successfully
	 *
	 * @return bool
	 */
	public function wasSuccessful() {
		return $this->status && $this->status->isOK();
	}

	/**
	 * Whether commitEdit() was called and created a new page.
	 *
	 * @return bool
	 */
	public function isNew() {
		return $this->status && $this->status->value['new'];
	}

	/**
	 * Whether commitEdit() did not create a revision because the content didn't change
	 * (null-edit).
	 *
	 * @return bool
	 */
	public function isUnchanged() {
		return $this->status
			&& $this->status->isOK()
			&& $this->status->value['revision-record'] === null;
	}

	/**
	 * The new revision created by commitEdit(), or null if commitEdit() has not yet been
	 * called, failed, or did not create a new revision because the content did not change.
	 *
	 * @return RevisionRecord|null
	 */
	public function getNewRevision() {
		return ( $this->status && $this->status->isOK() )
			? $this->status->value['revision-record']
			: null;
	}

	/**
	 * Adds all slots from $editInfo to $rec. Called
	 *
	 * @param PreparedEdit $editInfo
	 * @param MutableRevisionRecord $rec
	 * @param Status $status
	 * @param int $flags
	 * @param int $oldid
	 * @param User $user
	 */
	private function addAllContentForEdit(
		PreparedEdit $editInfo,
		MutableRevisionRecord $rec,
		Status $status,
		$flags,
		$oldid,
		User $user
	) {
		$wikiPage = $this->getWikiPage();

		$transformedSlots = $editInfo->getTransformedContentSlots();

		foreach ( $transformedSlots->getTouchedSlots() as $slot ) {
			$content = $slot->getContent();

			// TODO: change the signature of PrepareSave to not take a WikiPage!
			$prepStatus = $content->prepareSave( $wikiPage, $flags, $oldid, $user );

			if ( $prepStatus->isOK() ) {
				$rec->setSlot( $slot );
			} else {
				$status->merge( $prepStatus );
			}
		}
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
		$status = Status::newGood( [ 'new' => false, 'revision' => null, 'revision-record' => null ] );

		// Convenience variables
		$now = $this->getTimestampNow();

		/** @var RevisionRecord $oldRev */
		$oldRev = $meta['oldRevision'];
		$oldid = $meta['oldId'];

		if ( !$oldRev ) {
			// Article gone missing
			$status->fatal( 'edit-gone-missing' );

			return $status;
		}

		$newRevisionRecord = MutableRevisionRecord::newFromParentRevision(
			$oldRev,
			$summary,
			$user,
			$now
		);
		$newRevisionRecord->setMinorEdit( $meta['minor'] );

		$editInfo = $this->prepareContentForEdit(
			$this->newContentSlots,
			null,
			$user,
			[ 'use-stashed' => $meta['useStashed'] ]
		);

		$this->addAllContentForEdit(
			$editInfo,
			$newRevisionRecord,
			$status,
			$flags,
			$oldid,
			$user
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$changed = $newRevisionRecord->getSha1() !== $oldRev->getSha1();
		$mainContent = $newRevisionRecord->getContent( 'main' );

		$dbw = $this->getDBConnectionRef( DB_MASTER );

		if ( $changed ) {
			$dbw->startAtomic( __METHOD__ );

			// Get the latest page_latest value while locking it.
			// Do a CAS style check to see if it's the same as when this method
			// started. If it changed then bail out before touching the DB.
			$latestNow = $wikiPage->lockAndGetLatest(); // TODO: move to storage service, pass DB
			if ( $latestNow != $oldid ) {
				// We don't need to roll back, since we did not modify the database yet.
				// XXX: Or do we want to rollback, any transaction started by calling
				// code will fail? If we want that, we should probably throw an exception.
				$dbw->endAtomic( __METHOD__ );
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
			// TODO: move to storage service
			if ( !$wikiPage->updateRevisionOn( $dbw, $newLegacyRevision, null, $meta['oldIsRedirect'] ) ) {
				throw new PageUpdateException( "Failed to update page row to use new revision." );
			}

			// TODO: replace legacy hook!
			$tages = $meta['tags'];
			Hooks::run(
				'NewRevisionFromEditComplete',
				[ $wikiPage, $newLegacyRevision, $meta['baseRevId'], $user, &$tages ]
			);

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
					$oldRev->getSize(),
					$newRevisionRecord->getSize(),
					$newRevisionRecord->getId(),
					$patrolled,
					$tags
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
			$status->value['revision-record'] = $newRevisionRecord;

			// TODO: globally replace usages of 'revision' with getNewRevision()
			$status->value['revision'] = $newLegacyRevision;
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
							'oldrevision' => $meta['oldRevision'],
						]
					);
					// Trigger post-save hook
					// TODO: replace legacy hook!
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

		$status = Status::newGood( [ 'new' => true, 'revision' => null, 'revision-record' => null ] );

		$now = $this->getTimestampNow();

		$newRevisionRecord = new MutableRevisionRecord( $this->getTitle(), $this->getWikiId() );
		$newRevisionRecord->setComment( $summary );
		$newRevisionRecord->setMinorEdit( $meta['minor'] );
		$newRevisionRecord->setUser( $user );
		$newRevisionRecord->setTimestamp( $now );

		$editInfo = $this->prepareContentForEdit(
			$this->newContentSlots,
			null,
			$user,
			[ 'use-stashed' => $meta['useStashed'] ]
		);

		$this->addAllContentForEdit(
			$editInfo,
			$newRevisionRecord,
			$status,
			$flags,
			0,
			$user
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$dbw = $this->getDBConnectionRef( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		// TODO: move to storage service
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
		// TODO: move to storage service
		if ( !$wikiPage->updateRevisionOn( $dbw, $newLegacyRevision, 0 ) ) {
			throw new PageUpdateException( "Failed to update page row to use new revision." );
		}

		// TODO: replace legacy hook!
		$tags = $meta['tags'];
		Hooks::run(
			'NewRevisionFromEditComplete',
			[ $wikiPage, $newLegacyRevision, false, $user, &$tags ]
		);

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
				$tags
			);
		}

		$user->incEditCount();

		$dbw->endAtomic( __METHOD__ );

		// Return the new revision to the caller
		$status->value['revision'] = $newLegacyRevision;
		$status->value['revision-record'] = $newRevisionRecord;

		// XXX: make sure we are not loading the Content from the DB
		$mainContent = $newRevisionRecord->getContent( 'main' );

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$wikiPage,
					$newRevisionRecord,
					$newLegacyRevision,
					&$user,
					$mainContent,
					$summary,
					&$flags,
					$meta,
					&$status
				) {
					// Update links, etc.
					$this->doEditUpdates(
						$newRevisionRecord,
						$user,
						[
							'created' => true,
						]
					);
					// Trigger post-create hook
					// TODO: replace legacy hook!
					$params = [ &$wikiPage, &$user, $mainContent, $summary,
						$flags & EDIT_MINOR, null, null, &$flags, $newLegacyRevision ];
					Hooks::run( 'PageContentInsertComplete', $params );
					// Trigger post-save hook
					// TODO: replace legacy hook!
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
	 * MCR migration note: this replaces WikiPage::prepareContentForEdit.
	 *
	 * @param RevisionSlots $newContentSlots The new content of the slots to be updated
	 *        by this edit. Typically given before PST. If PST has already been applied,
	 *        $options['do-transform'] must be set to false to avoid double transformation.
	 * @param RevisionRecord|null $revision The revision of the edit, if already saved,
	 *        and relevant for the output.
	 * @param User $user
	 * @param array $options Array of options, following indexes are used:
	 * - use-stashed: bool, use shared prepared edit stash (see ApiStashEdit) (default: true).
	 * - do-transform: bool, whether to apply pre-safe transform (default: true).
	 *
	 * @return PreparedEdit
	 * @throws MWException
	 */
	public function prepareContentForEdit(
		RevisionSlots $newContentSlots,
		RevisionRecord $revision = null,
		User $user,
		$options = []
	) {
		// XXX: much of this could be taken from the state of the local object.
		$options += [
			'use-stashed' => true,
			'do-transform' => true,
		];

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!
		$title = $this->getTitle();

		$revid = $revision ? $revision->getId() : 0;
		$sigMod = [ 'revid' => $revid ];

		if ( !$options['do-transform'] ) {
			// Make sure the signature is different depending on whether PST
			// is applied to new slots content.
			$sigMod['transform'] = 'no';
		}

		$sig = PreparedEdit::makeSignature( $this->getTitle(),
			$newContentSlots,
			$user,
			$sigMod
		);

		if ( $this->preparedEdit && $this->preparedEdit->getSignature() === $sig ) {
			// Already prepared
			return $this->preparedEdit;
		}

		$mainContent = $newContentSlots->getContent( 'main' );

		if ( count( $newContentSlots->getSlotRoles() ) > 1 ) {
			// TODO: MCR!
			throw new RuntimeException( 'No slots besides the main slot are supported yet!' );
		}

		// The edit may have already been prepared via api.php?action=stashedit
		// TODO: MCR: only use stash for main slot, until ApiStashEdit can handle mutliple slots.
		$stashedEdit = $options['use-stashed'] && $this->ajaxEditStash
			? ApiStashEdit::checkCache( $this->getTitle(), $mainContent, $user )
			: false;

		$userPopts = ParserOptions::newFromUserAndLang( $user, $this->contentLanguage );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $userPopts ] );

		if ( $stashedEdit ) {
			$pstContent = $stashedEdit->pstContent;
		} elseif ( $options['do-transform'] )  {
			$pstContent = $mainContent->preSaveTransform( $title, $user, $userPopts );
		} else {
			$pstContent = $mainContent;
		}

		// TODO: MCR: combine parser options for all slots?! Include inherited (or use cached output)
		$canonPopts = $wikiPage->makeParserOptions( 'canonical' );
		if ( $stashedEdit ) {
			$output = $stashedEdit->output;
			$timestamp = $stashedEdit->timestamp;
		} else {
			if ( $revision ) {
				// We get here if vary-revision is set. This means that this page references
				// itself (such as via self-transclusion). In this case, we need to make sure
				// that any such self-references refer to the newly-saved revision, and not
				// to the previous one, which could otherwise happen due to replica DB lag.
				$oldCallback = $canonPopts->getCurrentRevisionCallback();
				$canonPopts->setCurrentRevisionCallback(
					function ( Title $parserTitle, $parser = false )
						use ( $title, $revision, &$oldCallback )
					{
						if ( $parserTitle->equals( $title ) ) {
							$legacyRevision = new Revision( $revision );
							return $legacyRevision;
						} else {
							return call_user_func( $oldCallback, $parserTitle, $parser );
						}
					}
				);
			} else {
				// NOTE: we only get here without READ_LATEST if called directly by application logic
				// XXX: if we calculate the reculative revid from a replica, can we still re-used
				//      the prepared edit for the actual edit?!
				// Try to avoid a second parse if {{REVISIONID}} is used
				$dbIndex = $wikiPage->wasLoadedFrom( self::READ_LATEST )
					? DB_MASTER // use the best possible guess
					: DB_REPLICA; // T154554

				$canonPopts->setSpeculativeRevIdCallback(
					function () use ( $dbIndex ) {
						return 1 + (int)wfGetDB( $dbIndex )->selectField(
							'revision',
							'MAX(rev_id)',
							[ ],
							__METHOD__
						);
					}
				);
			}

			// TODO: for each slot!
			// TODO: pass Revisionrecord to getParserOutput, for cross-slot access.
			$output = $pstContent->getParserOutput( $title, $revid, $canonPopts );
			$timestamp = $this->getTimestampNow();
		}

		if ( $timestamp ) {
			$output->setCacheTime( $timestamp );
		}

		// TODO: MCR: set all slots, inherit slots from base revision!
		$pstContentSlots = new MutableRevisionSlots();
		$pstContentSlots->setContent( 'main', $pstContent );

		$slotParserOutput = [ 'main' => $output ]; // TODO: MCR!
		$combinedOutput = $output;

		// Process cache the result
		$this->preparedEdit = new PreparedEdit(
			$title,
			$newContentSlots,
			$pstContentSlots,
			$user,
			$canonPopts,
			$slotParserOutput,
			$combinedOutput,
			$sigMod
		);

		return $this->preparedEdit;
	}

	/**
	 * @param PreparedEdit $editInfo
	 * @param bool $recursive
	 *
	 * @return DataUpdate[]
	 */
	private function getSecondaryDataUpdates( PreparedEdit $editInfo, $recursive = false ) {
		// TODO: MCR! Do this for all slots that were touched (but not inherited slots)!
		// NOTE: This is the main reason for per-slot ParserOutput to exist in EditInfo
		$content = $editInfo->getTransformedContentSlots()->getContent( 'main' );
		$output = $editInfo->getParserOutput( 'main' );

		$updates = $content->getSecondaryDataUpdates(
			$this->getTitle(), null, $recursive, $output
		);

		return $updates;
	}

	/**
	 * @param PreparedEdit $editInfo
	 *
	 * @return bool
	 */
	private function isCountable( PreparedEdit $editInfo ) {
		// TODO: MCR: how this is decided depends on the page type
		$content = $editInfo->getTransformedContentSlots()->getContent( 'main' );

		if ( $content->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $this->articleCountMethod  === 'link' ) {
			// Avoid re-parsing to detect links.
			// ParserOutput::getLinks() is a 2D array of page links, so
			// to be really correct we would need to recurse in the array
			// but the main array should only have items in it if there are
			// links.
			$hasLinks = (bool)count( $editInfo->getCombinedParserOutput()->getLinks() );
		}

		return $content->isCountable( $hasLinks );
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * MCR migration note: this replaces WikiPage::doEditUpdates.
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
	 *    - bool: whether the page was counted as an article before that
	 *      revision, only used in changed is true and created is false
	 *    - null: if created is false, don't update the article count; if created
	 *      is true, do update the article count
	 *    - 'no-change': don't update the article count, ever
	 */
	public function doEditUpdates( RevisionRecord $revision, User $user, array $options = [] ) {
		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!

		// XXX: much of this could be taken from the state of the local object.
		$options += [
			'changed' => true,
			'created' => false,
			'moved' => false,
			'restored' => false,
			'oldrevision' => null,
			'oldcountable' => null
		];

		// See if the parser output before $revision was inserted is still valid
		$editInfo = false;
		$output = null;
		if ( !isset( $this->preparedEdit ) ) {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared edit...\n" );
		} else {
			$output = $this->preparedEdit->getCombinedParserOutput();
			if ( $output->getFlag( 'vary-revision' ) ) {
				$this->saveParseLogger->info(
					__METHOD__ . ": Prepared edit has vary-revision...\n"
				);
			} elseif ( $output->getFlag( 'vary-revision-id' )
				&& $output->getSpeculativeRevIdUsed() !== $revision->getId()
			) {
				$this->saveParseLogger->info(
					__METHOD__ . ": Prepared edit has vary-revision-id with wrong ID...\n"
				);
			} elseif ( $output->getFlag( 'vary-user' )
				&& !$options['changed']
			) {
				$this->saveParseLogger->info(
					__METHOD__ . ": Prepared edit has vary-user and is null...\n"
				);
			} else {
				wfDebug( __METHOD__ . ": Using prepared edit...\n" );
				$editInfo = isset( $this->preparedEdit ) ? $this->preparedEdit : null;
			}
		}

		$touchedSlots = $revision->getTouchedSlots();

		if ( !$editInfo ) {
			// We get here if the existing PreparedEdit was discarded because it depended on
			// the revision ID, or when being called directly, without saving an edit.

			// Parse the text again if needed. Avoid using the edit stash. Avoid double transform.
			$editInfo = $this->prepareContentForEdit(
				$touchedSlots,
				$revision,
				$user,
				[ 'use-stashed' => false, 'do-transform' => false ]
			);
			$output = $editInfo->getCombinedParserOutput();
		}

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		$this->parserCache->save(
			$output, $wikiPage, $editInfo->getParserOptions(),
			$revision->getTimestamp(), $revision->getId()
		);

		$legacyRevision = new Revision( $revision );

		// Update the links tables and other secondary data
		$recursive = $options['changed']; // T52785
		$updates = $this->getSecondaryDataUpdates(
			$editInfo, $recursive
		);
		foreach ( $updates as $update ) {
			$update->setCause( 'edit-page', $user->getName() );
			if ( $update instanceof LinksUpdate ) {
				$update->setRevision( $legacyRevision );
				$update->setTriggeringUser( $user );
			}
			DeferredUpdates::addUpdate( $update );
		}

		// TODO: MCR: check if *any* changed slot supports categories!
		if ( $this->rcWatchCategoryMembership
			&& $this->getContentHandler( 'main' )->supportsCategories() === true
			&& ( $options['changed'] || $options['created'] )
			&& !$options['restored']
		) {
			// Note: jobs are pushed after deferred updates, so the job should be able to see
			// the recent change entry (also done via deferred updates) and carry over any
			// bot/deletion/IP flags, ect.
			$this->jobQueueGroup->lazyPush(
				new CategoryMembershipChangeJob(
					$this->getTitle(),
					[
						'pageId' => $this->getPageId(),
						'revTimestamp' => $revision->getTimestamp(),
					]
				)
			);
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

		$id = $this->getPageId();
		$title = $this->getTitle();
		$dbKey = $title->getPrefixedDBkey();
		$shortTitle = $title->getDBkey();

		if ( !$title->exists() ) {
			wfDebug( __METHOD__ . ": Page doesn't exist any more, bailing out\n" );
			return;
		}

		if ( $options['oldcountable'] === 'no-change' ||
			( !$options['changed'] && !$options['moved'] )
		) {
			$good = 0;
		} elseif ( $options['created'] ) {
			$good = (int)$this->isCountable( $editInfo );
		} elseif ( $options['oldcountable'] !== null ) {
			$good = (int)$this->isCountable( $editInfo ) - (int)$options['oldcountable'];
		} else {
			$good = 0;
		}
		$edits = $options['changed'] ? 1 : 0;
		$pages = $options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => $edits, 'articles' => $good, 'pages' => $pages ]
		) );

		// TODO: make search infrastructure aware of slots to avoid redundant updates!
		foreach ( $editInfo->getTransformedContentSlots()->getSlots() as $slot ) {
			if ( !$slot->isInherited() ) {
				DeferredUpdates::addUpdate( new SearchUpdate( $id, $dbKey, $slot->getContent() ) );
			}
		}

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

		if ( $title->getNamespace() == NS_MEDIAWIKI && $touchedSlots->hasSlot( 'main' ) ) {
			$mainContent = $touchedSlots->getContent( 'main' );
			$this->messageCache->updateMessageOverride( $title, $mainContent );
		}

		if ( $options['created'] ) {
			// TODO: move method here.
			WikiPage::onArticleCreate( $title );
		} elseif ( $options['changed'] ) { // T52785
			// TODO: move method here.
			WikiPage::onArticleEdit( $title, $legacyRevision );
		}

		$oldLegacyRevision = $options['oldrevision'] ? new Revision( $options['oldrevision'] ) : null;
		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $oldLegacyRevision, $legacyRevision, wfWikiID()
		);
	}

}
