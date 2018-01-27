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
use Hooks;
use InvalidArgumentException;
use LogicException;
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
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\LoadBalancer;
use WikiPage;

/**
 * Controller-like object for creating and updating pages by creating new revisions.
 *
 * PageUpdater instances provide compare-and-swap (CAS) protection against concurrent updates
 * between the time grabParentRevision() is called and createRevision() inserts a new revision.
 * This allows application logic to safely perform edit conflict resolution using the parent
 * revision's content.
 *
 * @see docs/pageupdater.txt for more information.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage.
 *
 * @since 1.32
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
	 * @var DerivedPageDataUpdater
	 */
	private $derivedDataUpdater;

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
	 * @see $wgUseAutomaticEditSummaries
	 */
	private $useAutomaticEditSummaries = true;

	/**
	 * @var int the RC patrol status the new revision should be marked with.
	 */
	private $rcPatrolStatus = RecentChange::PRC_UNPATROLLED;

	/**
	 * @var boolean see $wgAjaxEditStash
	 */
	private $ajaxEditStash = true;

	/**
	 * The ID of the logical base revision the content of the new revision is based on.
	 * Not to be confused with the immediate parent revision (the current revision before the
	 * new revision is created).
	 * The base revision is the last revision known to the client, while the parent revision
	 * is determined on the server by grabParentRevision().
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
	 * @var RevisionSlotsUpdate
	 */
	private $slotsUpdate;

	/**
	 * @var Status|null
	 */
	private $status = null;

	/**
	 * @param User $user
	 * @param WikiPage $wikiPage
	 * @param DerivedPageDataUpdater $derivedDataUpdater
	 * @param LoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 */
	public function __construct(
		User $user,
		WikiPage $wikiPage,
		DerivedPageDataUpdater $derivedDataUpdater,
		LoadBalancer $loadBalancer,
		RevisionStore $revisionStore
	) {
		$this->user = $user;
		$this->wikiPage = $wikiPage;
		$this->derivedDataUpdater = $derivedDataUpdater;

		$this->loadBalancer = $loadBalancer;
		$this->revisionStore = $revisionStore;

		$this->slotsUpdate = new RevisionSlotsUpdate();
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
	 * Checks whether this update conflicts with another update performed since the specified base
	 * revision. A user level "edit conflict" is detected when the base revision known to the client
	 * and specified via setBaseRevisionId() is not the ID of the current revision before the
	 * update. If setBaseRevisionId() was not called, this method always returns false.
	 *
	 * Note that an update expected to be based on a non-existing page will have base revision ID 0,
	 * and is considered to have a conflict if a current revision exists (that is, the page was
	 * created since the base revision was determined by the client).
	 *
	 * This method returning true indicates to calling code that edit conflict resolution should
	 * be applied before saving any data. It does not prevent the update from being performed, and
	 * it should not be confused with a "late" conflict indicated by the "edit-conflict" status.
	 * A "late" conflict is a CAS failure caused by an update being performed concurrently, between
	 * the time grabParentRevision() was called and the time createRevision() trying to insert the
	 * new revision.
	 *
	 * @note A user level edit conflict is not the same as the "edit-conflict" status triggered by
	 * a CAS failure. Calling this method establishes the CAS token, it does not check against it:
	 * This method calls grabParentRevision(), and thus causes the expected parent revision
	 * for the update to be fixed to the page's current revision at this point in time.
	 * It acts as a compare-and-swap (CAS) token in that it is guaranteed that createRevision()
	 * will fail with the "edit-conflict" status if the current revision of the page changes after
	 * hasEditConflict() was called and before createRevision() could insert a new revision.
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
	 * Returns the revision that was the page's current revision when grabParentRevision()
	 * was first called. This revision is the expected parent revision of the update, and will be
	 * recorded as the new revision's parent revision (unless no new revision is created because
	 * the content was not changed).
	 *
	 * This method MUST not be called after createRevision() was called!
	 *
	 * The current revision determined by the first call to this methods effectively acts a
	 * compare-and-swap (CAS) token which is checked by createRevision(), which fails if any
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
	 * @throws LogicException if called after createRevision().
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function grabParentRevision() {
		return $this->derivedDataUpdater->grabCurrentRevision();
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
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->baseRevId === false ) {
				$flags |= ( $this->derivedDataUpdater->pageExisted() ) ? EDIT_UPDATE : EDIT_NEW;
			} else {
				$flags |= ( $this->baseRevId > 0 ) ? EDIT_UPDATE : EDIT_NEW;
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
		// TODO: MCR: check the role and the content's model against the list of supported
		// roles, see T194046.

		$this->slotsUpdate->modifyContent( $role, $content );
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
		if ( $role === 'main' ) {
			throw new InvalidArgumentException( 'Cannot remove the main slot!' );
		}

		$this->slotsUpdate->removeSlot( $role );
	}

	/**
	 * Returns the ID of the logical base revision of the update. Not to be confused with the
	 * immediate parent revision. The base revision is set via setBaseRevisionId(),
	 * the parent revision is determined by grabParentRevision().
	 *
	 * Application may use this information to detect user level edit conflicts. Edit conflicts
	 * can be resolved by performing a 3-way merge, using the revision returned by this method as
	 * the common base of the conflicting revisions, namely the new revision being saved,
	 * and the revision returned by grabParentRevision().
	 *
	 * @return bool|int The ID of the base revision, 0 if the base is a non-existing page, false
	 *         if no base revision was specified.
	 */
	public function getBaseRevisionId() {
		return $this->baseRevId;
	}

	/**
	 * Sets the ID of the revision the content of this update is based on, if any.
	 * The base revision ID is not to be confused with the new revision's parent revision:
	 * the parent revision is the page's current revision immediately before the new revision
	 * is created; the base revision indicates what revision the client based the content of
	 * the new revision on. If base revision and parent revision are not the same, the update is
	 * considered to require edit conflict resolution.
	 *
	 * @param int|bool $baseRevId The ID of the base revision, or 0 if the update is expected to be
	 *        performed on a non-existing page. false can be used to indicate that the caller
	 *        doesn't care about the base revision.
	 */
	public function setBaseRevisionId( $baseRevId ) {
		Assert::parameterType( 'integer|boolean', $baseRevId, '$baseRevId' );
		$this->baseRevId = $baseRevId;
	}

	/**
	 * Returns the revision ID set by setUndidRevisionId(), indicating what revision is being
	 * undone by this edit.
	 *
	 * @return int
	 */
	public function getUndidRevisionId() {
		return $this->undidRevId;
	}

	/**
	 * Sets the ID of revision that was undone by the present update.
	 * This is used with the "undo" action, and is expected to hold the oldest revision ID
	 * in case more then one revision is being undone.
	 *
	 * @param int $undidRevId
	 */
	public function setUndidRevisionId( $undidRevId ) {
		Assert::parameterType( 'integer', $undidRevId, '$undidRevId' );
		$this->undidRevId = $undidRevId;
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

		// Check for undo tag
		if ( $this->undidRevId !== 0 && in_array( 'mw-undo', ChangeTags::getSoftwareTags() ) ) {
			$tags[] = 'mw-undo';
		}

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
		// TODO: inject something like a ContentHandlerRegistry
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

		return ContentHandler::getForModelID( $slot->getModel() );
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
	 * It is guaranteed that createRevision() will fail if the current revision of the page
	 * changes after grabParentRevision() was called and before createRevision() can insert
	 * a new revision, as per the CAS mechanism described above.
	 *
	 * However, the actual parent revision is allowed to be different from the revision set
	 * with setBaseRevisionId(). The caller is responsible for checking this via
	 * hasEditConflict() and adjusting the content of the new revision accordingly,
	 * using a 3-way-merge if desired.
	 *
	 * MCR migration note: this replaces WikiPage::doEditContent. Callers that change to using
	 * createRevision() now need to check the "minoredit" themselves before using EDIT_MINOR.
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
	public function createRevision( CommentStoreComment $summary, $flags = 0 ) {
		// Defend against mistakes caused by differences with the
		// signature of WikiPage::doEditContent.
		Assert::parameterType( 'integer', $flags, '$flags' );
		Assert::parameterType( 'CommentStoreComment', $summary, '$summary' );

		if ( $this->wasCommitted() ) {
			throw new RuntimeException( 'createRevision() has already been called on this PageUpdater!' );
		}

		// Low-level sanity check
		if ( $this->getLinkTarget()->getText() === '' ) {
			throw new RuntimeException( 'Something is trying to edit an article with an empty title' );
		}

		// TODO: MCR: check the role and the content's model against the list of supported
		// and required roles, see T194046.

		// Make sure the given content type is allowed for this page
		// TODO: decide: Extend check to other slots? Consider the role in check? [PageType]
		$mainContentHandler = $this->getContentHandler( 'main' );
		if ( !$mainContentHandler->canBeUsedOn( $this->getTitle() ) ) {
			$this->status = Status::newFatal( 'content-not-allowed-here',
				ContentHandler::getLocalizedName( $mainContentHandler->getModelID() ),
				$this->getTitle()->getPrefixedText()
			);
			return null;
		}

		// Load the data from the master database if needed. Needed to check flags.
		// NOTE: This grabs the parent revision as the CAS token, if grabParentRevision
		// wasn't called yet. If the page is modified by another process before we are done with
		// it, this method must fail (with status 'edit-conflict')!
		// NOTE: The actual parent revision may be different from $this->baseRevisionId.
		// The caller is responsible for checking this via hasEditConflict and adjusting the
		// content of the new revision accordingly, using a 3-way-merge.
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
			createRevision(), which calls DerivedPageDataUpdater::prepareContent() and
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

		$mainContent = $this->derivedDataUpdater->getSlots()->getContent( 'main' );

		// Trigger pre-save hook (using provided edit summary)
		$hookStatus = Status::newGood( [] );
		// TODO: replace legacy hook!
		// TODO: avoid pass-by-reference, see T193950
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
		// so wasCommitted doesn't return true wehn called indirectly from a hook handler!
		$this->status = $status;

		// TODO: replace bad status with Exceptions!
		return ( $this->status && $this->status->isOK() )
			? $this->status->value['revision-record']
			: null;
	}

	/**
	 * Whether createRevision() has been called on this instance
	 *
	 * @return bool
	 */
	public function wasCommitted() {
		return $this->status !== null;
	}

	/**
	 * The Status object indicating whether createRevision() was successful, or null if
	 * createRevision() was not yet called on this instance.
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
	 * Whether createRevision() completed successfully
	 *
	 * @return bool
	 */
	public function wasSuccessful() {
		return $this->status && $this->status->isOK();
	}

	/**
	 * Whether createRevision() was called and created a new page.
	 *
	 * @return bool
	 */
	public function isNew() {
		return $this->status && $this->status->isOK() && $this->status->value['new'];
	}

	/**
	 * Whether createRevision() did not create a revision because the content didn't change
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
	 * The new revision created by createRevision(), or null if createRevision() has not yet been
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
	 * Updates $rec with the new revision's content (and removes any slots that are to be
	 * discontinued).
	 *
	 * Calls DerivedPageDataUpdater::getSlots() to get post-PST slots and inherited slots,
	 * and Content::prepareSave() to verify that the slot content can be saved.
	 * The $status parameter is updated with any errors or warnings found by Content::prepareSave().
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

		foreach ( $this->derivedDataUpdater->getSlots()->getSlots() as $slot ) {
			$content = $slot->getContent();

			// XXX: We may push this up to the "edit controller" level, see T192777.
			// TODO: change the signature of PrepareSave to not take a WikiPage!
			$prepStatus = $content->prepareSave( $wikiPage, $flags, $oldid, $user );

			if ( $prepStatus->isOK() ) {
				$rec->setSlot( $slot );
			}

			// TODO: MCR: record which problem arose in which slot.
			$status->merge( $prepStatus );
		}
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
		$newRevisionRecord->setMinorEdit( ( $flags & EDIT_MINOR ) > 0 );

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

		// XXX: we may want a flag that allows a null revision to be forced!
		$changed = $this->derivedDataUpdater->isChange();
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
			$wasRedirect = $this->derivedDataUpdater->wasRedirect();
			if ( !$wikiPage->updateRevisionOn( $dbw, $newLegacyRevision, null, $wasRedirect ) ) {
				throw new PageUpdateException( "Failed to update page row to use new revision." );
			}

			// TODO: replace legacy hook!
			$tags = $this->computeEffectiveTags( $flags );
			Hooks::run(
				'NewRevisionFromEditComplete',
				[ $wikiPage, $newLegacyRevision, $this->baseRevId, $user, &$tags ]
			);

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
			// Other cache updates are managed in WikiPage::onArticleEdit()
			// via WikiPage::doEditUpdates().
			$this->getTitle()->invalidateCache( $now );
		}

		// Do secondary updates once the main changes have been committed...
		// NOTE: the updates have to be processed before sending the response to the client
		// (DeferredUpdates::PRESEND), otherwise the client may already be following the
		// HTTP redirect to the standard view before dervide data has been created - most
		// importantly, before the parser cache has been updated. This would cause the
		// content to be parsed a second time, or may cause stale content to be shown.
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$wikiPage, $newRevisionRecord, $newLegacyRevision, $user, $mainContent,
					$summary, $flags, $changed, $status
				) {
					// Update links tables, site stats, etc.
					$this->derivedDataUpdater->prepareUpdate(
						$newRevisionRecord,
						[
							'changed' => $changed,
						]
					);
					$this->derivedDataUpdater->doUpdates();

					// Trigger post-save hook
					// TODO: replace legacy hook!
					// TODO: avoid pass-by-reference, see T193950
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

		if ( !$this->derivedDataUpdater->getSlots()->hasSlot( 'main' ) ) {
			throw new PageUpdateException( 'Must provide a main slot when creating a page!' );
		}

		$status = Status::newGood( [ 'new' => true, 'revision' => null, 'revision-record' => null ] );

		$now = $this->getTimestampNow();

		$newRevisionRecord = new MutableRevisionRecord( $this->getTitle(), $this->getWikiId() );
		$newRevisionRecord->setComment( $summary );
		$newRevisionRecord->setMinorEdit( ( $flags & EDIT_MINOR ) > 0 );
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
		$tags = $this->computeEffectiveTags( $flags );
		Hooks::run(
			'NewRevisionFromEditComplete',
			[ $wikiPage, $newLegacyRevision, false, $user, &$tags ]
		);

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

		$dbw->endAtomic( __METHOD__ );

		// Return the new revision to the caller
		// TODO: globally replace usages of 'revision' with getNewRevision()
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
					$user,
					$mainContent,
					$summary,
					$flags,
					$status
				) {
					// Update links, etc.
					$this->derivedDataUpdater->prepareUpdate(
						$newRevisionRecord,
						[ 'created' => true ]
					);
					$this->derivedDataUpdater->doUpdates();

					// Trigger post-create hook
					// TODO: replace legacy hook!
					// TODO: avoid pass-by-reference, see T193950
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
