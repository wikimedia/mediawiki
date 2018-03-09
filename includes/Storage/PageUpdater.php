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

use AtomicSectionUpdate;
use ChangeTags;
use CommentStoreComment;
use Content;
use ContentHandler;
use DeferredUpdates;
use Hooks;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;
use MWException;
use RecentChange;
use Revision;
use RuntimeException;
use Status;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\LoadBalancer;
use WikiPage;

/**
 * Controller-like object for creating and updating pages by creating new revisions.
 *
 * PageUpdater instances provide compare-and-swap (CAS) protection against concurrent updates
 * between the time grabParentRevision() is called and doEdit() inserts a new revision.
 * This allows application logic to safely perform edit conflict resolution using the parent
 * revision's content.
 *
 * @see docs/pageupdater.txt for more information.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage.
 *
 * @since 1.31
 * @ingroup Page
 */
class PageUpdater {

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var PageMetaDataUpdater
	 */
	private $metaDataUpdater;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

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
	 * The ID of the logical base revision of this edit. Not to be confused with the
	 * immediate parent revision. The base revision is defined by the client, the parent
	 * revision is determined by grabParentRevision().
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

	/**
	 * @var MutableRevisionSlots
	 */
	private $newContentSlots;

	/**
	 * @var string[]
	 */
	private $stopSlots = [];

	/**
	 * @var Status|null
	 */
	private $status = null;

	/**
	 * @param User $user
	 * @param WikiPage $wikiPage
	 * @param PageMetaDataUpdater $metaDataUpdater
	 * @param LoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 */
	public function __construct(
		User $user,
		WikiPage $wikiPage,
		PageMetaDataUpdater $metaDataUpdater,
		LoadBalancer $loadBalancer,
		RevisionStore $revisionStore
	) {
		$this->user = $user;
		$this->wikiPage = $wikiPage;
		$this->metaDataUpdater = $metaDataUpdater;

		$this->loadBalancer = $loadBalancer;
		$this->revisionStore = $revisionStore;

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
	 * it should not be confused with a "late" conflict indicated by the edit-conflict status,
	 * caused by an edit being performed between the time grabParentRevision() was called and the
	 * time doEdit() trying to insert the new revision.
	 *
	 * @note This method calls grabParentRevision(), and thus causes the effective parent revision
	 * for the edit to be fixed to the page's current revision at this point in time.
	 * It acts as a compare-and-swap (CAS) token in that it is guaranteed that doEdit() will fail
	 * with the edit-conflict status if the current revision of the page changes after
	 * hasEditConflict() was called and before doEdit() could insert a new revision.
	 *
	 * @see grabParentRevision()
	 *
	 * @return bool
	 */
	public function hasEditConflict() {
		$baseId = $this->getBaseRevisionId();
		if ( $baseId === false ) {
			return false;
		}

		$parent = $this->grabParentRevision();
		$parentId = $parent ? $parent->getId() : 0;

		return $parentId !== $baseId;
	}

	/**
	 * Returns the immediate parent revision of the edit, which is the current revision of the page
	 * at the time when grabParentRevision() was first called. Not to be confused with the logical
	 * base revision. The base revision is specified by the client, the parent revision is
	 * determined from the database. If base revision and parent revision are not the same,
	 * the edit is considered to require edit conflict resolution.
	 *
	 * @note Application code should call this method before applying transformations to the new
	 * content that depend on the base revision, e.g. adding/replacing sections, or resolving
	 * conflicts via a 3-way merge. This protects against race conditions triggered by concurrent:
	 * edits This method effectively grabs a compare-and-swap (CAS) token which is checked by,
	 * doEdit() so the edit fails if the revision returned by grabParentrevision() is no longer
	 * the current revision when the edit is about to be saved.
	 *
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function grabParentRevision() {
		return $this->metaDataUpdater->grabParentRevision();
	}

	/**
	 * @return string
	 */
	private function getTimestampNow() {
		// TODO: allow an override to be injected for testing
		return wfTimestampNow();
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
					'doEdit called with EDIT_NEW but the base revision is not zero: '
						. $this->baseRevId . '!'
				);
			}

			if ( $flags & EDIT_UPDATE && $this->baseRevId === 0 ) {
				throw new RuntimeException(
					'doEdit called with EDIT_UPDATE but the base revision is zero!'
				);
			}
		}

		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->metaDataUpdater->pageExisted() ) {
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
	 * Removes a slot, stopping it from being inherited from the parent revision.
	 * This effectively discontinues the stream of slots with the given role.
	 *
	 * @param string $role
	 */
	public function stopSlot( $role ) {
		Assert::parameterType( 'string', $role, '$role' );

		if ( $role === 'main' ) {
			throw new InvalidArgumentException( 'Cannot stop the main slot from being inherited.' );
		}

		$this->stopSlots[] = $role;
	}

	/**
	 * Returns the ID of the logical base revision of the edit. Not to be confused with the
	 * immediate parent revision. The base revision is set via setBaseRevisionId(),
	 * the parent revision is determined by grabParentRevision().
	 *
	 * Application may use this information to detect edit conflicts. Edit conflicts can be
	 * resolved by performing a 3-way merge, using the revision returned by this method as
	 * the commons base of the conflicting revisions, namely the new revision being saved,
	 * and the revision returned by grabParentRevision().
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
	 * @param int|bool $baseRevId The ID of the base revision, or 0 if the edit is expected to be
	 *        based on a non-existing page. false can be used to indicate that the caller doesn't
	 *        care about the base revision.
	 */
	public function setBaseRevisionId( $baseRevId ) {
		Assert::parameterType( 'integer|boolean', $baseRevId, '$baseRevId' );
		$this->baseRevId = $baseRevId;
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
	public function getExplicitTags() {
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
	 * Returns the content of the given slot of the parent revision, with no audience checks applied.
	 * If there is no parent revision or the slot is not defined, this returns null.
	 *
	 * @param string $role slot role name
	 * @return Content
	 */
	private function getParentContent( $role ) {
		$parent = $this->grabParentRevision();

		if ( $parent && $parent->hasSlot( $role ) ) {
			return $parent->getContent( $role, RevisionRecord::RAW );
		}

		return null;
	}

	/**
	 * @param string $role slot role name
	 * @return ContentHandler
	 */
	private function getContentHandler( $role ) {
		// TODO: inject something like a ContentHandlerRegistry
		if ( $this->newContentSlots->hasSlot( $role ) ) {
			$slot = $this->newContentSlots->getSlot( $role );
		} else {
			$parent = $this->grabParentRevision();

			if ( $parent ) {
				$slot = $parent->getSlot( $role, RevisionRecord::RAW );
			} else {
				throw new RevisionAccessException( 'No such slot: ' . $role );
			}
		}

		return ContentHandler::getForModelID( $slot->getModel() );
	}

	/**
	 * @param int $flags Bit mask: a bit mask of flags submitted for the edit.
	 *
	 * @return CommentStoreComment
	 */
	private function makeAutoSummary( $flags ) {
		if ( !$this->useAutomaticEditSummaries || ( $flags & EDIT_AUTOSUMMARY ) === 0 ) {
			return CommentStoreComment::newUnsavedComment( '' );
		}

		// NOTE: this generates an auto-summary for SOME RANDOM changed slot!
		// TODO: combine auto-summaries for multiple slots!
		// TODO: move this logic out of the storage layer!
		$roles = $this->newContentSlots->getSlotRoles();
		$role = reset( $roles );

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
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * It is guaranteed that doEdit() will fail if the current revision of the page
	 * changes after grabParentRevision() was called and before doEdit() can insert a new revision.
	 *
	 * MCR migration note: this replaces WikiPage::doEditContent.
	 *
	 * @param CommentStoreComment $summary Edit summary
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
	 * @throws MWException
	 * @throws RuntimeException
	 */
	public function doEdit( CommentStoreComment $summary, $flags = 0 ) {
		// Defend against mistakes caused by differences with the
		// signature of WikiPage::doEditContent.
		Assert::parameterType( 'integer', $flags, '$flags' );
		Assert::parameterType( 'string|CommentStoreComment', $summary, '$summary' );

		if ( $this->wasCommitted() ) {
			throw new RuntimeException( 'doEdit() has already been called on this PageUpdater!' );
		}

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

		// Load the data from the master database if needed. Needed to check flags.
		// NOTE: This garbs the parent revision as the CAS token, if grabParentRevision
		// wasn't called yet. If the page is modified by another process before we are done with
		// it, this method must fail (with status 'edit-conflict')!
		$this->grabParentRevision();
		$flags = $this->checkFlags( $flags );

		// Avoid statsd noise and wasted cycles check the edit stash (T136678)
		if ( ( $flags & EDIT_INTERNAL ) || ( $flags & EDIT_FORCE_BOT ) ) {
			$useStashed = false;
		} else {
			$useStashed = $this->ajaxEditStash;
		}

		// TODO: use this only for the legacy hook, and only if something uses the legacy hook
		$wikiPage = $this->getWikiPage();

		$user = $this->user;

		// Prepare the edit. This performs PST and generates the canonical ParserOutput.
		$this->metaDataUpdater->prepareEdit(
			$this->user,
			$this->newContentSlots,
			$this->stopSlots,
			$useStashed
		);

		// FIXME: double-check that the hook should get the post-PST content!
		$mainContent = $this->metaDataUpdater->getSlots()->getContent( 'main' );

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
		// XXX: $summary == null seems logical, but the empty string may actually come from the user
		// XXX: Move this logic out of the storage layer! It does not belong here! Use a callback?
		if ( $summary->text === '' && $summary->data === null ) {
			$summary = $this->makeAutoSummary( $flags );
		}

		// Get the pre-save transform content and final parser output
		// TODO: We check minoredit here, but we check the bot right elsewhere. Fix that.
		$meta = [
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'tags' => $tags
		];

		// Actually create the revision and create/update the page.
		// Do NOT yet set $this->status!
		if ( $flags & EDIT_UPDATE ) {
			$status = $this->doModify( $summary, $this->user, $flags, $meta );
		} else {
			$status = $this->doCreate( $summary, $this->user, $flags, $meta );
		}

		// Promote user to any groups they meet the criteria for
		DeferredUpdates::addCallableUpdate( function () use ( $user ) {
			$user->addAutopromoteOnceGroups( 'onEdit' );
			$user->addAutopromoteOnceGroups( 'onView' ); // b/c
		} );

		// NOTE: set $this->status only after all hooks have been called,
		// so wasCommitted doesn't return true wehn called indirectly from a hook handler!
		$this->status = $status;

		// TODO: replace bad status with Exceptions!
		return ( $this->status && $this->status->isOK() )
			? $this->status->value['revision-record']
			: null;
	}

	/**
	 * Whether doEdit() has been called on this instance
	 *
	 * @return bool
	 */
	public function wasCommitted() {
		return $this->status !== null;
	}

	/**
	 * The Status object indicating whether doEdit() was successful, or null if
	 * doEdit() was not yet called on this instance.
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
	 * Whether doEdit() completed successfully
	 *
	 * @return bool
	 */
	public function wasSuccessful() {
		return $this->status && $this->status->isOK();
	}

	/**
	 * Whether doEdit() was called and created a new page.
	 *
	 * @return bool
	 */
	public function isNew() {
		return $this->status && $this->status->value['new'];
	}

	/**
	 * Whether doEdit() did not create a revision because the content didn't change
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
	 * The new revision created by doEdit(), or null if doEdit() has not yet been
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
	 * @param MutableRevisionRecord $rec
	 * @param Status $status
	 * @param int $flags
	 * @param int $oldid
	 * @param User $user
	 */
	private function addAllContentForEdit(
		MutableRevisionRecord $rec,
		Status $status,
		$flags,
		$oldid,
		User $user
	) {
		$wikiPage = $this->getWikiPage();

		$transformedSlots = $this->metaDataUpdater->getSlots();

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

		$oldRev = $this->grabParentRevision();
		$oldid = $oldRev ? $oldRev->getId() : 0;

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

		$this->addAllContentForEdit(
			$newRevisionRecord,
			$status,
			$flags,
			$oldid,
			$user
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$changed = $this->metaDataUpdater->isChange();
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
			$wasRedirect = $this->metaDataUpdater->wasRedirect();
			if ( !$wikiPage->updateRevisionOn( $dbw, $newLegacyRevision, null, $wasRedirect ) ) {
				throw new PageUpdateException( "Failed to update page row to use new revision." );
			}

			// TODO: replace legacy hook!
			$tags = $meta['tags'];
			Hooks::run(
				'NewRevisionFromEditComplete',
				[ $wikiPage, $newLegacyRevision, $this->baseRevId, $user, &$tags ]
			);

			// Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				// Mark as patrolled if the user can do so
				// TODO: move this logic to out of the storage layer
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
			$newRevisionRecord = $oldRev;
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
					$summary, &$flags, $changed, &$status
				) {
					// Update links tables, site stats, etc.
					$this->metaDataUpdater->prepareUpdate(
						$newRevisionRecord,
						[
							'changed' => $changed,
						]
					);
					$this->metaDataUpdater->doUpdates();

					// Trigger post-save hook
					// TODO: replace legacy hook!
					$params = [ &$wikiPage, &$user, $mainContent, $summary->text, $flags & EDIT_MINOR,
						null, null, &$flags, $newLegacyRevision, &$status, $this->baseRevId,
						$this->undidRevId ];
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

		$this->addAllContentForEdit(
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
			// TODO: move this logic to out of the storage layer
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
					&$status
				) {
					// Update links, etc.
					$this->metaDataUpdater->prepareUpdate(
						$newRevisionRecord,
						[
							'created' => true,
						]
					);
					$this->metaDataUpdater->doUpdates();

					// Trigger post-create hook
					// TODO: replace legacy hook!
					$params = [ &$wikiPage, &$user, $mainContent, $summary,
						$flags & EDIT_MINOR, null, null, &$flags, $newLegacyRevision ];
					Hooks::run( 'PageContentInsertComplete', $params );
					// Trigger post-save hook
					// TODO: replace legacy hook!
					$params = array_merge( $params, [ &$status, $this->baseRevId, 0 ] );
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

}
