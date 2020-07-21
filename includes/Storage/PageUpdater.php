<?php
/**
 * Controller-like object for creating and updating pages by creating new revisions.
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
use InvalidArgumentException;
use LogicException;
use ManualLogEntry;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Debug\DeprecatablePropertyArray;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MWException;
use RecentChange;
use Revision;
use RuntimeException;
use Status;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * Controller-like object for creating and updating pages by creating new revisions.
 *
 * PageUpdater instances provide compare-and-swap (CAS) protection against concurrent updates
 * between the time grabParentRevision() is called and saveRevision() inserts a new revision.
 * This allows application logic to safely perform edit conflict resolution using the parent
 * revision's content.
 *
 * @see docs/pageupdater.txt for more information.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage.
 *
 * @since 1.32
 * @ingroup Page
 * @phan-file-suppress PhanTypeArraySuspiciousNullable Cannot read type of $this->status->value
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
	 * @var DerivedPageDataUpdater
	 */
	private $derivedDataUpdater;

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var SlotRoleRegistry
	 */
	private $slotRoleRegistry;

	/**
	 * @var IContentHandlerFactory
	 */
	private $contentHandlerFactory;

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	/**
	 * @var HookContainer
	 */
	private $hookContainer;

	/**
	 * @var boolean see $wgUseAutomaticEditSummaries
	 * @see $wgUseAutomaticEditSummaries
	 */
	private $useAutomaticEditSummaries = true;

	/**
	 * @var int the RC patrol status the new revision should be marked with.
	 */
	private $rcPatrolStatus = RecentChange::PRC_UNPATROLLED;

	/**
	 * @var bool whether to create a log entry for new page creations.
	 */
	private $usePageCreationLog = true;

	/**
	 * @var boolean see $wgAjaxEditStash
	 */
	private $ajaxEditStash = true;

	/**
	 * @var array
	 */
	private $tags = [];

	/**
	 * @var RevisionSlotsUpdate
	 */
	private $slotsUpdate;

	/**
	 * @var Status|null
	 */
	private $status = null;

	/**
	 * @var EditResultBuilder
	 */
	private $editResultBuilder;

	/**
	 * @var EditResult|null
	 */
	private $editResult = null;

	/**
	 * @param User $user
	 * @param WikiPage $wikiPage
	 * @param DerivedPageDataUpdater $derivedDataUpdater
	 * @param ILoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		User $user,
		WikiPage $wikiPage,
		DerivedPageDataUpdater $derivedDataUpdater,
		ILoadBalancer $loadBalancer,
		RevisionStore $revisionStore,
		SlotRoleRegistry $slotRoleRegistry,
		IContentHandlerFactory $contentHandlerFactory,
		HookContainer $hookContainer
	) {
		$this->user = $user;
		$this->wikiPage = $wikiPage;
		$this->derivedDataUpdater = $derivedDataUpdater;

		$this->loadBalancer = $loadBalancer;
		$this->revisionStore = $revisionStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );

		$this->slotsUpdate = new RevisionSlotsUpdate();
		$this->editResultBuilder = new EditResultBuilder(
			$this->revisionStore,
			ChangeTags::getSoftwareTags()
		);
	}

	/**
	 * Can be used to enable or disable automatic summaries that are applied to certain kinds of
	 * changes, like completely blanking a page.
	 *
	 * @param bool $useAutomaticEditSummaries
	 * @see $wgUseAutomaticEditSummaries
	 */
	public function setUseAutomaticEditSummaries( $useAutomaticEditSummaries ) {
		$this->useAutomaticEditSummaries = $useAutomaticEditSummaries;
	}

	/**
	 * Sets the "patrolled" status of the edit.
	 * Callers should check the "patrol" and "autopatrol" permissions as appropriate.
	 *
	 * @see $wgUseRCPatrol
	 * @see $wgUseNPPatrol
	 *
	 * @param int $status RC patrol status, e.g. RecentChange::PRC_AUTOPATROLLED.
	 */
	public function setRcPatrolStatus( $status ) {
		$this->rcPatrolStatus = $status;
	}

	/**
	 * Whether to create a log entry for new page creations.
	 *
	 * @see $wgPageCreationLog
	 *
	 * @param bool $use
	 */
	public function setUsePageCreationLog( $use ) {
		$this->usePageCreationLog = $use;
	}

	/**
	 * @param bool $ajaxEditStash
	 * @see $wgAjaxEditStash
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
	 * Checks whether this update conflicts with another update performed between the client
	 * loading data to prepare an edit, and the client committing the edit. This is intended to
	 * detect user level "edit conflict" when the latest revision known to the client
	 * is no longer the current revision when processing the update.
	 *
	 * An update expected to create a new page can be checked by setting $expectedParentRevision = 0.
	 * Such an update is considered to have a conflict if a current revision exists (that is,
	 * the page was created since the edit was initiated on the client).
	 *
	 * This method returning true indicates to calling code that edit conflict resolution should
	 * be applied before saving any data. It does not prevent the update from being performed, and
	 * it should not be confused with a "late" conflict indicated by the "edit-conflict" status.
	 * A "late" conflict is a CAS failure caused by an update being performed concurrently between
	 * the time grabParentRevision() was called and the time saveRevision() trying to insert the
	 * new revision.
	 *
	 * @note A user level edit conflict is not the same as the "edit-conflict" status triggered by
	 * a CAS failure. Calling this method establishes the CAS token, it does not check against it:
	 * This method calls grabParentRevision(), and thus causes the expected parent revision
	 * for the update to be fixed to the page's current revision at this point in time.
	 * It acts as a compare-and-swap (CAS) token in that it is guaranteed that saveRevision()
	 * will fail with the "edit-conflict" status if the current revision of the page changes after
	 * hasEditConflict() (or grabParentRevision()) was called and before saveRevision() could insert
	 * a new revision.
	 *
	 * @see grabParentRevision()
	 *
	 * @param int $expectedParentRevision The ID of the revision the client expects to be the
	 *        current one. Use 0 to indicate that the page is expected to not yet exist.
	 *
	 * @return bool
	 */
	public function hasEditConflict( $expectedParentRevision ) {
		$parent = $this->grabParentRevision();
		$parentId = $parent ? $parent->getId() : 0;

		return $parentId !== $expectedParentRevision;
	}

	/**
	 * Returns the revision that was the page's current revision when grabParentRevision()
	 * was first called. This revision is the expected parent revision of the update, and will be
	 * recorded as the new revision's parent revision (unless no new revision is created because
	 * the content was not changed).
	 *
	 * This method MUST not be called after saveRevision() was called!
	 *
	 * The current revision determined by the first call to this method effectively acts a
	 * compare-and-swap (CAS) token which is checked by saveRevision(), which fails if any
	 * concurrent updates created a new revision.
	 *
	 * Application code should call this method before applying transformations to the new
	 * content that depend on the parent revision, e.g. adding/replacing sections, or resolving
	 * conflicts via a 3-way merge. This protects against race conditions triggered by concurrent
	 * updates.
	 *
	 * @see DerivedPageDataUpdater::grabCurrentRevision()
	 *
	 * @note The expected parent revision is not to be confused with the logical base revision.
	 * The base revision is specified by the client, the parent revision is determined from the
	 * database. If base revision and parent revision are not the same, the updates is considered
	 * to require edit conflict resolution.
	 *
	 * @throws LogicException if called after saveRevision().
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function grabParentRevision() {
		return $this->derivedDataUpdater->grabCurrentRevision();
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 *
	 * @param int $flags
	 * @return int Updated $flags
	 */
	private function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			$flags |= ( $this->derivedDataUpdater->pageExisted() ) ? EDIT_UPDATE : EDIT_NEW;
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
		$this->ensureRoleAllowed( $role );

		$this->slotsUpdate->modifyContent( $role, $content );
	}

	/**
	 * Set the new slot for the given slot role
	 *
	 * @param SlotRecord $slot
	 */
	public function setSlot( SlotRecord $slot ) {
		$this->ensureRoleAllowed( $slot->getRole() );

		$this->slotsUpdate->modifySlot( $slot );
	}

	/**
	 * Explicitly inherit a slot from some earlier revision.
	 *
	 * The primary use case for this is rollbacks, when slots are to be inherited from
	 * the rollback target, overriding the content from the parent revision (which is the
	 * revision being rolled back).
	 *
	 * This should typically not be used to inherit slots from the parent revision, which
	 * happens implicitly. Using this method causes the given slot to be treated as "modified"
	 * during revision creation, even if it has the same content as in the parent revision.
	 *
	 * @param SlotRecord $originalSlot A slot already existing in the database, to be inherited
	 *        by the new revision.
	 */
	public function inheritSlot( SlotRecord $originalSlot ) {
		// NOTE: slots can be inherited even if the role is not "allowed" on the title.
		// NOTE: this slot is inherited from some other revision, but it's
		// a "modified" slot for the RevisionSlotsUpdate and DerivedPageDataUpdater,
		// since it's not implicitly inherited from the parent revision.
		$inheritedSlot = SlotRecord::newInherited( $originalSlot );
		$this->slotsUpdate->modifySlot( $inheritedSlot );
	}

	/**
	 * Removes the slot with the given role.
	 *
	 * This discontinues the "stream" of slots with this role on the page,
	 * preventing the new revision, and any subsequent revisions, from
	 * inheriting the slot with this role.
	 *
	 * @param string $role A slot role name (but not "main")
	 */
	public function removeSlot( $role ) {
		$this->ensureRoleNotRequired( $role );

		$this->slotsUpdate->removeSlot( $role );
	}

	/**
	 * Sets the ID of an earlier revision that is being repeated or restored by this update.
	 * The new revision is expected to have the exact same content as the given original revision.
	 * This is used with rollbacks and with dummy "null" revisions which are created to record
	 * things like page moves.
	 *
	 * This value is passed to the PageContentSaveComplete and NewRevisionFromEditComplete hooks.
	 *
	 * @param int|bool $originalRevId The original revision id, or false if no earlier revision
	 * is known to be repeated or restored by this update.
	 */
	public function setOriginalRevisionId( $originalRevId ) {
		$this->editResultBuilder->setOriginalRevisionId( $originalRevId );
	}

	/**
	 * Marks this edit as a revert and applies relevant information.
	 * Will also cause the PageUpdater to add a relevant change tag when saving the edit.
	 * Will do nothing if $oldestRevertedRevId is 0.
	 *
	 * @param int $revertMethod The method used to make the revert:
	 *        REVERT_UNDO, REVERT_ROLLBACK or REVERT_MANUAL
	 * @param int $oldestRevertedRevId The ID of the oldest revision that was reverted.
	 * @param int $newestRevertedRevId The ID of the newest revision that was reverted. This
	 *        parameter is optional, default value is $oldestRevertedRevId
	 *
	 * @see EditResultBuilder::markAsRevert()
	 */
	public function markAsRevert(
		int $revertMethod,
		int $oldestRevertedRevId,
		int $newestRevertedRevId = 0
	) {
		$this->editResultBuilder->markAsRevert(
			$revertMethod, $oldestRevertedRevId, $newestRevertedRevId
		);
	}

	/**
	 * Returns the EditResult associated with this PageUpdater.
	 * Will return null if PageUpdater::saveRevision() wasn't called yet.
	 * Will also return null if the update was not successful.
	 *
	 * @return EditResult|null
	 */
	public function getEditResult() : ?EditResult {
		return $this->editResult;
	}

	/**
	 * Sets a tag to apply to this update.
	 * Callers are responsible for permission checks,
	 * using ChangeTags::canAddTagsAccompanyingChange.
	 * @param string $tag
	 */
	public function addTag( $tag ) {
		Assert::parameterType( 'string', $tag, '$tag' );
		$this->tags[] = trim( $tag );
	}

	/**
	 * Sets tags to apply to this update.
	 * Callers are responsible for permission checks,
	 * using ChangeTags::canAddTagsAccompanyingChange.
	 * @param string[] $tags
	 */
	public function addTags( array $tags ) {
		Assert::parameterElementType( 'string', $tags, '$tags' );
		foreach ( $tags as $tag ) {
			$this->addTag( $tag );
		}
	}

	/**
	 * Returns the list of tags set using the addTag() method.
	 *
	 * @return string[]
	 */
	public function getExplicitTags() {
		return $this->tags;
	}

	/**
	 * @param int $flags Bit mask: a bit mask of EDIT_XXX flags.
	 * @return string[]
	 */
	private function computeEffectiveTags( $flags ) {
		$tags = $this->tags;
		$editResult = $this->getEditResult();

		foreach ( $this->slotsUpdate->getModifiedRoles() as $role ) {
			$old_content = $this->getParentContent( $role );

			$handler = $this->getContentHandler( $role );
			$content = $this->slotsUpdate->getModifiedSlot( $role )->getContent();

			// TODO: MCR: Do this for all slots. Also add tags for removing roles!
			$tag = $handler->getChangeTag( $old_content, $content, $flags );
			// If there is no applicable tag, null is returned, so we need to check
			if ( $tag ) {
				$tags[] = $tag;
			}
		}

		$tags = array_merge( $tags, $editResult->getRevertTags() );

		return array_unique( $tags );
	}

	/**
	 * Returns the content of the given slot of the parent revision, with no audience checks applied.
	 * If there is no parent revision or the slot is not defined, this returns null.
	 *
	 * @param string $role slot role name
	 * @return Content|null
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
		if ( $this->slotsUpdate->isModifiedSlot( $role ) ) {
			$slot = $this->slotsUpdate->getModifiedSlot( $role );
		} else {
			$parent = $this->grabParentRevision();

			if ( $parent ) {
				$slot = $parent->getSlot( $role, RevisionRecord::RAW );
			} else {
				throw new RevisionAccessException( 'No such slot: ' . $role );
			}
		}

		return $this->contentHandlerFactory->getContentHandler( $slot->getModel() );
	}

	/**
	 * @param int $flags Bit mask: a bit mask of EDIT_XXX flags.
	 *
	 * @return CommentStoreComment
	 */
	private function makeAutoSummary( $flags ) {
		if ( !$this->useAutomaticEditSummaries || ( $flags & EDIT_AUTOSUMMARY ) === 0 ) {
			return CommentStoreComment::newUnsavedComment( '' );
		}

		// NOTE: this generates an auto-summary for SOME RANDOM changed slot!
		// TODO: combine auto-summaries for multiple slots!
		// XXX: this logic should not be in the storage layer!
		$roles = $this->slotsUpdate->getModifiedRoles();
		$role = reset( $roles );

		if ( $role === false ) {
			return CommentStoreComment::newUnsavedComment( '' );
		}

		$handler = $this->getContentHandler( $role );
		$content = $this->slotsUpdate->getModifiedSlot( $role )->getContent();
		$old_content = $this->getParentContent( $role );
		$summary = $handler->getAutosummary( $old_content, $content, $flags );

		return CommentStoreComment::newUnsavedComment( $summary );
	}

	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array. This does not check user permissions.
	 *
	 * It is guaranteed that saveRevision() will fail if the current revision of the page
	 * changes after grabParentRevision() was called and before saveRevision() can insert
	 * a new revision, as per the CAS mechanism described above.
	 *
	 * The caller is however responsible for calling hasEditConflict() to detect a
	 * user-level edit conflict, and to adjust the content of the new revision accordingly,
	 * e.g. by using a 3-way-merge.
	 *
	 * MCR migration note: this replaces WikiPage::doEditContent. Callers that change to using
	 * saveRevision() now need to check the "minoredit" themselves before using EDIT_MINOR.
	 *
	 * @param CommentStoreComment $summary Edit summary
	 * @param int $flags Bitfield:
	 *      EDIT_NEW
	 *          Create a new page, or fail with "edit-already-exists" if the page exists.
	 *      EDIT_UPDATE
	 *          Create a new revision, or fail with "edit-gone-missing" if the page does not exist.
	 *      EDIT_MINOR
	 *          Mark this revision as minor
	 *      EDIT_SUPPRESS_RC
	 *          Do not log the change in recentchanges
	 *      EDIT_FORCE_BOT
	 *          Mark the revision as automated ("bot edit")
	 *      EDIT_AUTOSUMMARY
	 *          Fill in blank summaries with generated text where possible
	 *      EDIT_INTERNAL
	 *          Signal that the page retrieve/save cycle happened entirely in this request.
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the expected state is detected
	 * automatically via grabParentRevision(). In this case, the "edit-already-exists" or
	 * "edit-gone-missing" errors may still be triggered due to race conditions, if the page
	 * was unexpectedly created or deleted while revision creation is in progress. This can be
	 * viewed as part of the CAS mechanism described above.
	 *
	 * @return RevisionRecord|null The new revision, or null if no new revision was created due
	 *         to a failure or a null-edit. Use isUnchanged(), wasSuccessful() and getStatus()
	 *         to determine the outcome of the revision creation.
	 *
	 * @throws MWException
	 * @throws RuntimeException
	 */
	public function saveRevision( CommentStoreComment $summary, $flags = 0 ) {
		// Defend against mistakes caused by differences with the
		// signature of WikiPage::doEditContent.
		Assert::parameterType( 'integer', $flags, '$flags' );

		if ( $this->wasCommitted() ) {
			throw new RuntimeException( 'saveRevision() has already been called on this PageUpdater!' );
		}

		// Low-level sanity check
		if ( $this->getLinkTarget()->getText() === '' ) {
			throw new RuntimeException( 'Something is trying to edit an article with an empty title' );
		}

		// NOTE: slots can be inherited even if the role is not "allowed" on the title.
		$status = Status::newGood();
		$this->checkAllRolesAllowed(
			$this->slotsUpdate->getModifiedRoles(),
			$status
		);
		$this->checkNoRolesRequired(
			$this->slotsUpdate->getRemovedRoles(),
			$status
		);

		if ( !$status->isOK() ) {
			return null;
		}

		// Make sure the given content is allowed in the respective slots of this page
		foreach ( $this->slotsUpdate->getModifiedRoles() as $role ) {
			$slot = $this->slotsUpdate->getModifiedSlot( $role );
			$roleHandler = $this->slotRoleRegistry->getRoleHandler( $role );

			if ( !$roleHandler->isAllowedModel( $slot->getModel(), $this->getTitle() ) ) {
				$contentHandler = $this->contentHandlerFactory
					->getContentHandler( $slot->getModel() );
				$this->status = Status::newFatal( 'content-not-allowed-here',
					ContentHandler::getLocalizedName( $contentHandler->getModelID() ),
					$this->getTitle()->getPrefixedText(),
					wfMessage( $roleHandler->getNameMessageKey() )
					// TODO: defer message lookup to caller
				);
				return null;
			}
		}

		// Load the data from the master database if needed. Needed to check flags.
		// NOTE: This grabs the parent revision as the CAS token, if grabParentRevision
		// wasn't called yet. If the page is modified by another process before we are done with
		// it, this method must fail (with status 'edit-conflict')!
		// NOTE: The parent revision may be different from $this->originalRevisionId.
		$this->grabParentRevision();
		$flags = $this->checkFlags( $flags );

		// Avoid statsd noise and wasted cycles check the edit stash (T136678)
		if ( ( $flags & EDIT_INTERNAL ) || ( $flags & EDIT_FORCE_BOT ) ) {
			$useStashed = false;
		} else {
			$useStashed = $this->ajaxEditStash;
		}

		$user = $this->user;

		// Prepare the update. This performs PST and generates the canonical ParserOutput.
		$this->derivedDataUpdater->prepareContent(
			$this->user,
			$this->slotsUpdate,
			$useStashed
		);

		// TODO: don't force initialization here!
		// This is a hack to work around the fact that late initialization of the ParserOutput
		// causes ApiFlowEditHeaderTest::testCache to fail. Whether that failure indicates an
		// actual problem, or is just an issue with the test setup, remains to be determined
		// [dk, 2018-03].
		// Anomie said in 2018-03:
		/*
			I suspect that what's breaking is this:

			The old version of WikiPage::doEditContent() called prepareContentForEdit() which
			generated the ParserOutput right then, so when doEditUpdates() gets called from the
			DeferredUpdate scheduled by WikiPage::doCreate() there's no need to parse. I note
			there's a comment there that says "Get the pre-save transform content and final
			parser output".
			The new version of WikiPage::doEditContent() makes a PageUpdater and calls its
			saveRevision(), which calls DerivedPageDataUpdater::prepareContent() and
			PageUpdater::doCreate() without ever having to actually generate a ParserOutput.
			Thus, when DerivedPageDataUpdater::doUpdates() is called from the DeferredUpdate
			scheduled by PageUpdater::doCreate(), it does find that it needs to parse at that point.

			And the order of operations in that Flow test is presumably:

			- Create a page with a call to WikiPage::doEditContent(), in a way that somehow avoids
			processing the DeferredUpdate.
			- Set up the "no set!" mock cache in Flow\Tests\Api\ApiTestCase::expectCacheInvalidate()
			- Then, during the course of doing that test, a $db->commit() results in the
			DeferredUpdates being run.
		 */
		$this->derivedDataUpdater->getCanonicalParserOutput();

		// Trigger pre-save hook (using provided edit summary)
		$renderedRevision = $this->derivedDataUpdater->getRenderedRevision();
		$hookStatus = Status::newGood( [] );
		$allowedByHook = $this->hookRunner->onMultiContentSave(
			$renderedRevision, $user, $summary, $flags, $hookStatus
		);
		if ( $allowedByHook && $this->hookContainer->isRegistered( 'PageContentSave' ) ) {
			// Also run the legacy hook.
			// NOTE: WikiPage should only be used for the legacy hook,
			// and only if something uses the legacy hook.
			$mainContent = $this->derivedDataUpdater->getSlots()->getContent( SlotRecord::MAIN );

			// Deprecated since 1.35.
			$allowedByHook = $this->hookRunner->onPageContentSave(
				$this->getWikiPage(), $user, $mainContent, $summary,
				$flags & EDIT_MINOR, null, null, $flags, $hookStatus
			);
		}

		if ( !$allowedByHook ) {
			// The hook has prevented this change from being saved.
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			$this->status = $hookStatus;
			return null;
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		// XXX: $summary == null seems logical, but the empty string may actually come from the user
		// XXX: Move this logic out of the storage layer! It does not belong here! Use a callback?
		if ( $summary->text === '' && $summary->data === null ) {
			$summary = $this->makeAutoSummary( $flags );
		}

		// Actually create the revision and create/update the page.
		// Do NOT yet set $this->status!
		if ( $flags & EDIT_UPDATE ) {
			$status = $this->doModify( $summary, $this->user, $flags );
		} else {
			$status = $this->doCreate( $summary, $this->user, $flags );
		}

		// Promote user to any groups they meet the criteria for
		DeferredUpdates::addCallableUpdate( function () use ( $user ) {
			$user->addAutopromoteOnceGroups( 'onEdit' );
			$user->addAutopromoteOnceGroups( 'onView' ); // b/c
		} );

		// NOTE: set $this->status only after all hooks have been called,
		// so wasCommitted doesn't return true when called indirectly from a hook handler!
		$this->status = $status;

		// TODO: replace bad status with Exceptions!
		return ( $this->status && $this->status->isOK() )
			? $this->status->value['revision-record']
			: null;
	}

	/**
	 * Whether saveRevision() has been called on this instance
	 *
	 * @return bool
	 */
	public function wasCommitted() {
		return $this->status !== null;
	}

	/**
	 * The Status object indicating whether saveRevision() was successful, or null if
	 * saveRevision() was not yet called on this instance.
	 *
	 * @note This is here for compatibility with WikiPage::doEditContent. It may be deprecated
	 * soon.
	 *
	 * Possible status errors:
	 *     edit-hook-aborted: The ArticleSave hook aborted the update but didn't
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
	 * Whether saveRevision() completed successfully
	 *
	 * @return bool
	 */
	public function wasSuccessful() {
		return $this->status && $this->status->isOK();
	}

	/**
	 * Whether saveRevision() was called and created a new page.
	 *
	 * @return bool
	 */
	public function isNew() {
		return $this->status && $this->status->isOK() && $this->status->value['new'];
	}

	/**
	 * Whether saveRevision() did not create a revision because the content didn't change
	 * (null-edit). Whether the content changed or not is determined by
	 * DerivedPageDataUpdater::isChange().
	 *
	 * @return bool
	 */
	public function isUnchanged() {
		return $this->status
			&& $this->status->isOK()
			&& $this->status->value['revision-record'] === null;
	}

	/**
	 * The new revision created by saveRevision(), or null if saveRevision() has not yet been
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
	 * Constructs a MutableRevisionRecord based on the Content prepared by the
	 * DerivedPageDataUpdater. This takes care of inheriting slots, updating slots
	 * with PST applied, and removing discontinued slots.
	 *
	 * This calls Content::prepareSave() to verify that the slot content can be saved.
	 * The $status parameter is updated with any errors or warnings found by Content::prepareSave().
	 *
	 * @param CommentStoreComment $comment
	 * @param User $user
	 * @param int $flags
	 * @param Status $status
	 *
	 * @return MutableRevisionRecord
	 */
	private function makeNewRevision(
		CommentStoreComment $comment,
		User $user,
		$flags,
		Status $status
	) {
		$wikiPage = $this->getWikiPage();
		$title = $this->getTitle();
		$parent = $this->grabParentRevision();

		// XXX: we expect to get a MutableRevisionRecord here, but that's a bit brittle!
		// TODO: introduce something like an UnsavedRevisionFactory service instead!
		/** @var MutableRevisionRecord $rev */
		$rev = $this->derivedDataUpdater->getRevision();
		'@phan-var MutableRevisionRecord $rev';

		// Avoid fatal error when the Title's ID changed, T204793
		if (
			$rev->getPageId() !== null && $title->exists()
			&& $rev->getPageId() !== $title->getArticleID()
		) {
			$titlePageId = $title->getArticleID();
			$revPageId = $rev->getPageId();
			$masterPageId = $title->getArticleID( Title::READ_LATEST );

			if ( $revPageId === $masterPageId ) {
				wfWarn( __METHOD__ . ": Encountered stale Title object: old ID was $titlePageId, "
					. "continuing with new ID from master, $masterPageId" );
			} else {
				throw new InvalidArgumentException(
					"Revision inherited page ID $revPageId from its parent, "
					. "but the provided Title object belongs to page ID $masterPageId"
				);
			}
		}

		$rev->setPageId( $title->getArticleID() );

		if ( $parent ) {
			$oldid = $parent->getId();
			$rev->setParentId( $oldid );
		} else {
			$oldid = 0;
		}

		$rev->setComment( $comment );
		$rev->setUser( $user );
		$rev->setMinorEdit( ( $flags & EDIT_MINOR ) > 0 );

		foreach ( $rev->getSlots()->getSlots() as $slot ) {
			$content = $slot->getContent();

			// XXX: We may push this up to the "edit controller" level, see T192777.
			// XXX: prepareSave() and isValid() could live in SlotRoleHandler
			// XXX: PrepareSave should not take a WikiPage!
			$prepStatus = $content->prepareSave( $wikiPage, $flags, $oldid, $user );

			// TODO: MCR: record which problem arose in which slot.
			$status->merge( $prepStatus );
		}

		$this->checkAllRequiredRoles(
			$rev->getSlotRoles(),
			$status
		);

		return $rev;
	}

	/**
	 * Builds the EditResult for this update.
	 * Should be called by either doModify or doCreate.
	 *
	 * @param RevisionRecord $revision
	 * @param bool $isNew
	 */
	private function buildEditResult( RevisionRecord $revision, bool $isNew ) {
		$this->editResultBuilder->setRevisionRecord( $revision );
		$this->editResultBuilder->setIsNew( $isNew );
		$this->editResult = $this->editResultBuilder->buildEditResult();
	}

	/**
	 * @param CommentStoreComment $summary The edit summary
	 * @param User $user The revision's author
	 * @param int $flags EXIT_XXX constants
	 *
	 * @throws MWException
	 * @return Status
	 */
	private function doModify( CommentStoreComment $summary, User $user, $flags ) {
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		// Update article, but only if changed.
		$status = Status::newGood(
			new DeprecatablePropertyArray(
				[ 'new' => false, 'revision' => null, 'revision-record' => null ],
				[ 'revision' => '1.35' ],
				__METHOD__ . ' status'
			)
		);

		$oldRev = $this->grabParentRevision();
		$oldid = $oldRev ? $oldRev->getId() : 0;

		if ( !$oldRev ) {
			// Article gone missing
			$status->fatal( 'edit-gone-missing' );

			return $status;
		}

		$newRevisionRecord = $this->makeNewRevision(
			$summary,
			$user,
			$flags,
			$status
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$now = $newRevisionRecord->getTimestamp();

		// XXX: we may want a flag that allows a null revision to be forced!
		$changed = $this->derivedDataUpdater->isChange();

		// We build the EditResult before the $change if/else branch in order to pass
		// the correct $newRevisionRecord to EditResultBuilder. In case this is a null
		// edit, $newRevisionRecord will be later overridden to its parent revision, which
		// would confuse EditResultBuilder.
		if ( !$changed ) {
			// This is a null edit, ensure original revision ID is set properly
			$this->editResultBuilder->setOriginalRevisionId( $oldid );
		}
		$this->buildEditResult( $newRevisionRecord, false );

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

			// Update page_latest and friends to reflect the new revision
			// TODO: move to storage service
			$wasRedirect = $this->derivedDataUpdater->wasRedirect();
			if ( !$wikiPage->updateRevisionOn( $dbw, $newRevisionRecord, null, $wasRedirect ) ) {
				throw new PageUpdateException( "Failed to update page row to use new revision." );
			}

			$editResult = $this->getEditResult();
			$tags = $this->computeEffectiveTags( $flags );
			$this->hookRunner->onRevisionFromEditComplete(
				$wikiPage, $newRevisionRecord, $editResult->getOriginalRevisionId(), $user, $tags
			);

			// Hook is hard deprecated since 1.35
			if ( $this->hookContainer->isRegistered( 'NewRevisionFromEditComplete' ) ) {
				// Only create Revision object if needed
				$newLegacyRevision = new Revision( $newRevisionRecord );
				$this->hookRunner->onNewRevisionFromEditComplete(
					$wikiPage,
					$newLegacyRevision,
					$editResult->getOriginalRevisionId(),
					$user,
					$tags
				);
			}

			// Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				// Add RC row to the DB
				RecentChange::notifyEdit(
					$now,
					$this->getTitle(),
					$newRevisionRecord->isMinor(),
					$user,
					$summary->text, // TODO: pass object when that becomes possible
					$oldid,
					$newRevisionRecord->getTimestamp(),
					( $flags & EDIT_FORCE_BOT ) > 0,
					'',
					$oldRev->getSize(),
					$newRevisionRecord->getSize(),
					$newRevisionRecord->getId(),
					$this->rcPatrolStatus,
					$tags
				);
			}

			$user->incEditCount();

			$dbw->endAtomic( __METHOD__ );

			// Return the new revision to the caller
			$status->value['revision-record'] = $newRevisionRecord;

			// Deprecated via DeprecatablePropertyArray
			$status->value['revision'] = function () use ( $newRevisionRecord ) {
				return new Revision( $newRevisionRecord );
			};
		} else {
			// T34948: revision ID must be set to page {{REVISIONID}} and
			// related variables correctly. Likewise for {{REVISIONUSER}} (T135261).
			// Since we don't insert a new revision into the database, the least
			// error-prone way is to reuse given old revision.
			$newRevisionRecord = $oldRev;

			$status->warning( 'edit-no-change' );
			// Update page_touched as updateRevisionOn() was not called.
			// Other cache updates are managed in WikiPage::onArticleEdit()
			// via WikiPage::doEditUpdates().
			$this->getTitle()->invalidateCache( $now );
		}

		// Do secondary updates once the main changes have been committed...
		// NOTE: the updates have to be processed before sending the response to the client
		// (DeferredUpdates::PRESEND), otherwise the client may already be following the
		// HTTP redirect to the standard view before derived data has been created - most
		// importantly, before the parser cache has been updated. This would cause the
		// content to be parsed a second time, or may cause stale content to be shown.
		DeferredUpdates::addUpdate(
			$this->getAtomicSectionUpdate(
				$dbw,
				$wikiPage,
				$newRevisionRecord,
				$user,
				$summary,
				$flags,
				$status,
				[ 'changed' => $changed, ]
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * @param CommentStoreComment $summary The edit summary
	 * @param User $user The revision's author
	 * @param int $flags EXIT_XXX constants
	 *
	 * @throws DBUnexpectedError
	 * @throws MWException
	 * @return Status
	 */
	private function doCreate( CommentStoreComment $summary, User $user, $flags ) {
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		if ( !$this->derivedDataUpdater->getSlots()->hasSlot( SlotRecord::MAIN ) ) {
			throw new PageUpdateException( 'Must provide a main slot when creating a page!' );
		}

		$status = Status::newGood(
			new DeprecatablePropertyArray(
				[ 'new' => true, 'revision' => null, 'revision-record' => null ],
				[ 'revision' => '1.35' ],
				__METHOD__ . ' status'
			)
		);

		$newRevisionRecord = $this->makeNewRevision(
			$summary,
			$user,
			$flags,
			$status
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$this->buildEditResult( $newRevisionRecord, true );
		$now = $newRevisionRecord->getTimestamp();

		$dbw = $this->getDBConnectionRef( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		// TODO: move to storage service
		$newid = $wikiPage->insertOn( $dbw );
		if ( $newid === false ) {
			$dbw->endAtomic( __METHOD__ );
			$status->fatal( 'edit-already-exists' );

			return $status;
		}

		// At this point we are now comitted to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).
		$newRevisionRecord->setPageId( $newid );

		// Save the revision text...
		$newRevisionRecord = $this->revisionStore->insertRevisionOn( $newRevisionRecord, $dbw );

		// Update the page record with revision data
		// TODO: move to storage service
		if ( !$wikiPage->updateRevisionOn( $dbw, $newRevisionRecord, 0 ) ) {
			throw new PageUpdateException( "Failed to update page row to use new revision." );
		}

		$tags = $this->computeEffectiveTags( $flags );
		$this->hookRunner->onRevisionFromEditComplete(
			$wikiPage, $newRevisionRecord, false, $user, $tags
		);

		// Hook is deprecated since 1.35
		if ( $this->hookContainer->isRegistered( 'NewRevisionFromEditComplete' ) ) {
			// ONly create Revision object if needed
			$newLegacyRevision = new Revision( $newRevisionRecord );
			$this->hookRunner->onNewRevisionFromEditComplete(
				$wikiPage,
				$newLegacyRevision,
				false,
				$user,
				$tags
			);
		}

		// Update recentchanges
		if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
			// Add RC row to the DB
			RecentChange::notifyNew(
				$now,
				$this->getTitle(),
				$newRevisionRecord->isMinor(),
				$user,
				$summary->text, // TODO: pass object when that becomes possible
				( $flags & EDIT_FORCE_BOT ) > 0,
				'',
				$newRevisionRecord->getSize(),
				$newRevisionRecord->getId(),
				$this->rcPatrolStatus,
				$tags
			);
		}

		$user->incEditCount();

		if ( $this->usePageCreationLog ) {
			// Log the page creation
			// @TODO: Do we want a 'recreate' action?
			$logEntry = new ManualLogEntry( 'create', 'create' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getTitle() );
			$logEntry->setComment( $summary->text );
			$logEntry->setTimestamp( $now );
			$logEntry->setAssociatedRevId( $newRevisionRecord->getId() );
			$logEntry->insert();
			// Note that we don't publish page creation events to recentchanges
			// (i.e. $logEntry->publish()) since this would create duplicate entries,
			// one for the edit and one for the page creation.
		}

		$dbw->endAtomic( __METHOD__ );

		// Return the new revision to the caller
		$status->value['revision-record'] = $newRevisionRecord;

		// Deprecated via DeprecatablePropertyArray
		$status->value['revision'] = function () use ( $newRevisionRecord ) {
			return new Revision( $newRevisionRecord );
		};

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			$this->getAtomicSectionUpdate(
				$dbw,
				$wikiPage,
				$newRevisionRecord,
				$user,
				$summary,
				$flags,
				$status,
				[ 'created' => true ]
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	private function getAtomicSectionUpdate(
		IDatabase $dbw,
		WikiPage $wikiPage,
		RevisionRecord $newRevisionRecord,
		User $user,
		CommentStoreComment $summary,
		$flags,
		Status $status,
		$hints = []
	) {
		return new AtomicSectionUpdate(
			$dbw,
			__METHOD__,
			function () use (
				$wikiPage, $newRevisionRecord, $user,
				$summary, $flags, $status, $hints
			) {
				// set debug data
				$hints['causeAction'] = 'edit-page';
				$hints['causeAgent'] = $user->getName();

				$mainContent = $newRevisionRecord->getContent( SlotRecord::MAIN, RevisionRecord::RAW );
				$editResult = $this->getEditResult();

				// Update links tables, site stats, etc.
				$this->derivedDataUpdater->prepareUpdate( $newRevisionRecord, $hints );
				$this->derivedDataUpdater->doUpdates();

				$created = $hints['created'] ?? false;
				$flags |= ( $created ? EDIT_NEW : EDIT_UPDATE );

				// PageSaveComplete replaces the other two since 1.35
				$this->hookRunner->onPageSaveComplete(
					$wikiPage,
					$user,
					$summary->text,
					$flags,
					$newRevisionRecord,
					$editResult
				);

				// Both hooks are hard deprecated since 1.35
				if ( !$this->hookContainer->isRegistered( 'PageContentInsertComplete' )
					&& !$this->hookContainer->isRegistered( 'PageContentSaveComplete' )
				) {
					// Don't go on to create a Revision unless its needed
					return;
				}

				$newLegacyRevision = new Revision( $newRevisionRecord );
				if ( $created ) {
					// Trigger post-create hook
					$this->hookRunner->onPageContentInsertComplete( $wikiPage, $user,
						$mainContent, $summary->text, $flags & EDIT_MINOR,
						null, null, $flags, $newLegacyRevision );
				}

				// Trigger post-save hook
				$this->hookRunner->onPageContentSaveComplete( $wikiPage, $user, $mainContent,
					$summary->text, $flags & EDIT_MINOR, null,
					null, $flags, $newLegacyRevision, $status,
					$editResult->getOriginalRevisionId(), $editResult->getUndidRevId() );
			}
		);
	}

	/**
	 * @return string[] Slots required for this page update, as a list of role names.
	 */
	private function getRequiredSlotRoles() {
		return $this->slotRoleRegistry->getRequiredRoles( $this->getTitle() );
	}

	/**
	 * @return string[] Slots allowed for this page update, as a list of role names.
	 */
	private function getAllowedSlotRoles() {
		return $this->slotRoleRegistry->getAllowedRoles( $this->getTitle() );
	}

	private function ensureRoleAllowed( $role ) {
		$allowedRoles = $this->getAllowedSlotRoles();
		if ( !in_array( $role, $allowedRoles ) ) {
			throw new PageUpdateException( "Slot role `$role` is not allowed." );
		}
	}

	private function ensureRoleNotRequired( $role ) {
		$requiredRoles = $this->getRequiredSlotRoles();
		if ( in_array( $role, $requiredRoles ) ) {
			throw new PageUpdateException( "Slot role `$role` is required." );
		}
	}

	private function checkAllRolesAllowed( array $roles, Status $status ) {
		$allowedRoles = $this->getAllowedSlotRoles();

		$forbidden = array_diff( $roles, $allowedRoles );
		if ( !empty( $forbidden ) ) {
			$status->error(
				'edit-slots-cannot-add',
				count( $forbidden ),
				implode( ', ', $forbidden )
			);
		}
	}

	private function checkNoRolesRequired( array $roles, Status $status ) {
		$requiredRoles = $this->getRequiredSlotRoles();

		$needed = array_diff( $roles, $requiredRoles );
		if ( !empty( $needed ) ) {
			$status->error(
				'edit-slots-cannot-remove',
				count( $needed ),
				implode( ', ', $needed )
			);
		}
	}

	private function checkAllRequiredRoles( array $roles, Status $status ) {
		$requiredRoles = $this->getRequiredSlotRoles();

		$missing = array_diff( $requiredRoles, $roles );
		if ( !empty( $missing ) ) {
			$status->error(
				'edit-slots-missing',
				count( $missing ),
				implode( ', ', $missing )
			);
		}
	}

}
