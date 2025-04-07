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

namespace MediaWiki\Storage;

use InvalidArgumentException;
use LogicException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Deferred\AtomicSectionUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\User;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\NormalizedException\NormalizedException;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Controller-like object for creating and updating pages by creating new revisions.
 *
 * PageUpdater instances provide compare-and-swap (CAS) protection against concurrent updates
 * between the time grabParentRevision() is called and saveRevision() inserts a new revision.
 * This allows application logic to safely perform edit conflict resolution using the parent
 * revision's content.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage.
 *
 * @see docs/pageupdater.md for more information.
 *
 * @since 1.32
 * @ingroup Page
 * @author Daniel Kinzler
 */
class PageUpdater implements PageUpdateCauses {

	/**
	 * Options that have to be present in the ServiceOptions object passed to the constructor.
	 * @note When adding options here, also add them to PageUpdaterFactory::CONSTRUCTOR_OPTIONS.
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ManualRevertSearchRadius,
		MainConfigNames::UseRCPatrol,
	];

	/**
	 * @var UserIdentity
	 */
	private $author;

	/**
	 * TODO Remove this eventually.
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var PageIdentity
	 */
	private $pageIdentity;

	/**
	 * @var DerivedPageDataUpdater
	 */
	private $derivedDataUpdater;

	/**
	 * @var IConnectionProvider
	 */
	private $dbProvider;

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

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var TitleFormatter */
	private $titleFormatter;

	/**
	 * @var bool see $wgUseAutomaticEditSummaries
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
	 * @var bool Whether null-edits create a revision.
	 */
	private $forceEmptyRevision = false;

	/**
	 * @var bool Whether to prevent new revision creation by throwing if it is
	 *   attempted.
	 */
	private $preventChange = false;

	/**
	 * @var array
	 */
	private $tags = [];

	/**
	 * @var RevisionSlotsUpdate
	 */
	private $slotsUpdate;

	/**
	 * @var PageUpdateStatus|null
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
	 * @var ServiceOptions
	 */
	private $serviceOptions;

	/**
	 * @var int
	 */
	private $flags = 0;

	/**
	 * @var array Hints for use with DerivedPageDataUpdater::prepareUpdate
	 */
	private array $hints = [];

	/** @var string[] */
	private $softwareTags = [];

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param UserIdentity $author
	 * @param PageIdentity $page
	 * @param DerivedPageDataUpdater $derivedDataUpdater
	 * @param IConnectionProvider $dbProvider
	 * @param RevisionStore $revisionStore
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param HookContainer $hookContainer
	 * @param UserGroupManager $userGroupManager
	 * @param TitleFormatter $titleFormatter
	 * @param ServiceOptions $serviceOptions
	 * @param string[] $softwareTags Array of currently enabled software change tags. Can be
	 *        obtained from ChangeTagsStore->getSoftwareTags()
	 * @param LoggerInterface $logger
	 * @param WikiPageFactory $wikiPageFactory
	 */
	public function __construct(
		UserIdentity $author,
		PageIdentity $page,
		DerivedPageDataUpdater $derivedDataUpdater,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		SlotRoleRegistry $slotRoleRegistry,
		IContentHandlerFactory $contentHandlerFactory,
		HookContainer $hookContainer,
		UserGroupManager $userGroupManager,
		TitleFormatter $titleFormatter,
		ServiceOptions $serviceOptions,
		array $softwareTags,
		LoggerInterface $logger,
		WikiPageFactory $wikiPageFactory
	) {
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $serviceOptions;

		$this->author = $author;
		$this->pageIdentity = $page;
		$this->wikiPage = $wikiPageFactory->newFromTitle( $page );
		$this->derivedDataUpdater = $derivedDataUpdater;
		$this->derivedDataUpdater->setCause( self::CAUSE_EDIT );

		$this->dbProvider = $dbProvider;
		$this->revisionStore = $revisionStore;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userGroupManager = $userGroupManager;
		$this->titleFormatter = $titleFormatter;

		$this->slotsUpdate = new RevisionSlotsUpdate();
		$this->editResultBuilder = new EditResultBuilder(
			$revisionStore,
			$softwareTags,
			new ServiceOptions(
				EditResultBuilder::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::ManualRevertSearchRadius =>
						$serviceOptions->get( MainConfigNames::ManualRevertSearchRadius )
				]
			)
		);
		$this->softwareTags = $softwareTags;
		$this->logger = $logger;
	}

	/**
	 * Set the cause of the update. Will be used for the PageRevisionUpdatedEvent
	 * and for tracing/logging in jobs, etc.
	 *
	 * @param string $cause See PageRevisionUpdatedEvent::CAUSE_XXX
	 * @return $this
	 */
	public function setCause( string $cause ): self {
		$this->derivedDataUpdater->setCause( $cause );
		return $this;
	}

	public function setHints( array $hints ): self {
		$this->hints = $hints + $this->hints;
		return $this;
	}

	/**
	 * Sets any flags to use when performing the update.
	 * Flags passed in subsequent calls to this method as well as calls to prepareUpdate()
	 * or saveRevision() are aggregated using bitwise OR.
	 *
	 * @param int $flags Bitfield, see the EDIT_XXX constants such as EDIT_NEW
	 *        or EDIT_FORCE_BOT.
	 *
	 * @return $this
	 */
	public function setFlags( int $flags ) {
		$this->flags |= $flags;
		return $this;
	}

	/**
	 * Prepare the update.
	 * This sets up the RevisionRecord to be saved.
	 * @since 1.37
	 *
	 * @param int $flags Bitfield, will be combined with flags set via setFlags().
	 *        EDIT_FORCE_BOT and EDIT_INTERNAL will bypass the edit stash.
	 *
	 * @return PreparedUpdate
	 */
	public function prepareUpdate( int $flags = 0 ): PreparedUpdate {
		$this->setFlags( $flags );

		// Load the data from the primary database if needed. Needed to check flags.
		$this->grabParentRevision();
		if ( !$this->derivedDataUpdater->isUpdatePrepared() ) {
			// Avoid statsd noise and wasted cycles check the edit stash (T136678)
			$useStashed = !( ( $this->flags & EDIT_INTERNAL ) || ( $this->flags & EDIT_FORCE_BOT ) );
			// Prepare the update. This performs PST and generates the canonical ParserOutput.
			$this->derivedDataUpdater->prepareContent(
				$this->author,
				$this->slotsUpdate,
				$useStashed
			);
		}

		return $this->derivedDataUpdater;
	}

	/**
	 * @param UserIdentity $user
	 *
	 * @return User
	 */
	private static function toLegacyUser( UserIdentity $user ) {
		return User::newFromIdentity( $user );
	}

	/**
	 * After creation of the user during the save process, update the stored
	 * UserIdentity.
	 * @since 1.39
	 *
	 * @param UserIdentity $author
	 */
	public function updateAuthor( UserIdentity $author ) {
		if ( $this->author->getName() !== $author->getName() ) {
			throw new InvalidArgumentException( 'Cannot replace the author with an author ' .
				'of a different name, since DerivedPageDataUpdater may have stored the ' .
				'old name.' );
		}
		$this->author = $author;
	}

	/**
	 * Can be used to enable or disable automatic summaries that are applied to certain kinds of
	 * changes, like completely blanking a page.
	 *
	 * @param bool $useAutomaticEditSummaries
	 * @return $this
	 * @see $wgUseAutomaticEditSummaries
	 */
	public function setUseAutomaticEditSummaries( $useAutomaticEditSummaries ) {
		$this->useAutomaticEditSummaries = $useAutomaticEditSummaries;
		return $this;
	}

	/**
	 * Sets the "patrolled" status of the edit.
	 * Callers should check the "patrol" and "autopatrol" permissions as appropriate.
	 *
	 * @see $wgUseRCPatrol
	 * @see $wgUseNPPatrol
	 *
	 * @param int $status RC patrol status, e.g. RecentChange::PRC_AUTOPATROLLED.
	 * @return $this
	 */
	public function setRcPatrolStatus( $status ) {
		$this->rcPatrolStatus = $status;
		return $this;
	}

	/**
	 * Whether to create a log entry for new page creations.
	 *
	 * @see $wgPageCreationLog
	 *
	 * @param bool $use
	 * @return $this
	 */
	public function setUsePageCreationLog( $use ) {
		$this->usePageCreationLog = $use;
		return $this;
	}

	/**
	 * Set whether null-edits should create a revision. Enabling this allows the creation of dummy
	 * revisions ("null revisions") to mark events such as renaming in the page history.
	 *
	 * Callers should typically also call setOriginalRevisionId() to indicate the ID of the revision
	 * that is being repeated. That ID can be obtained from grabParentRevision()->getId().
	 *
	 * @since 1.38
	 *
	 * @note this calls $this->setOriginalRevisionId() with the ID of the current revision,
	 * starting the CAS bracket by virtue of calling $this->grabParentRevision().
	 *
	 * @note saveRevision() will fail with a LogicException if setForceEmptyRevision( true )
	 * was called and also content was changed via setContent(), removeSlot(), or inheritSlot().
	 *
	 * @param bool $forceEmptyRevision
	 * @return $this
	 */
	public function setForceEmptyRevision( bool $forceEmptyRevision ): self {
		$this->forceEmptyRevision = $forceEmptyRevision;

		if ( $forceEmptyRevision ) {
			// XXX: throw if there is no current/parent revision?
			$original = $this->grabParentRevision();
			$this->setOriginalRevisionId( $original ? $original->getId() : false );
		}

		$this->derivedDataUpdater->setForceEmptyRevision( $forceEmptyRevision );
		return $this;
	}

	/** @return string|false */
	private function getWikiId() {
		return $this->revisionStore->getWikiId();
	}

	/**
	 * Get the page we're currently updating.
	 */
	public function getPage(): PageIdentity {
		return $this->pageIdentity;
	}

	/**
	 * @return Title
	 */
	private function getTitle() {
		// NOTE: eventually, this won't use WikiPage any more
		return $this->wikiPage->getTitle();
	}

	/**
	 * @return WikiPage
	 */
	private function getWikiPage() {
		// NOTE: eventually, this won't use WikiPage any more
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
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function grabParentRevision() {
		return $this->derivedDataUpdater->grabCurrentRevision();
	}

	/**
	 * Set the new content for the given slot role
	 *
	 * @param string $role A slot role name (such as SlotRecord::MAIN)
	 * @param Content $content
	 * @return $this
	 */
	public function setContent( $role, Content $content ) {
		$this->ensureRoleAllowed( $role );

		$this->slotsUpdate->modifyContent( $role, $content );
		return $this;
	}

	/**
	 * Set the new slot for the given slot role
	 *
	 * @param SlotRecord $slot
	 * @return $this
	 */
	public function setSlot( SlotRecord $slot ) {
		$this->ensureRoleAllowed( $slot->getRole() );

		$this->slotsUpdate->modifySlot( $slot );
		return $this;
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
	 * @return $this
	 */
	public function inheritSlot( SlotRecord $originalSlot ) {
		// NOTE: slots can be inherited even if the role is not "allowed" on the title.
		// NOTE: this slot is inherited from some other revision, but it's
		// a "modified" slot for the RevisionSlotsUpdate and DerivedPageDataUpdater,
		// since it's not implicitly inherited from the parent revision.
		$inheritedSlot = SlotRecord::newInherited( $originalSlot );
		$this->slotsUpdate->modifySlot( $inheritedSlot );
		return $this;
	}

	/**
	 * Removes the slot with the given role.
	 *
	 * This discontinues the "stream" of slots with this role on the page,
	 * preventing the new revision, and any subsequent revisions, from
	 * inheriting the slot with this role.
	 *
	 * @param string $role A slot role name (but not SlotRecord::MAIN)
	 */
	public function removeSlot( $role ) {
		$this->ensureRoleNotRequired( $role );

		$this->slotsUpdate->removeSlot( $role );
	}

	/**
	 * Sets the ID of an earlier revision that is being repeated or restored by this update.
	 * The new revision is expected to have the exact same content as the given original revision.
	 * This is used with rollbacks and with dummy "null" revisions which are created to record
	 * things like page moves. setForceEmptyRevision() calls this implicitly.
	 *
	 * @param int|bool $originalRevId The original revision id, or false if no earlier revision
	 * is known to be repeated or restored by this update.
	 * @return $this
	 */
	public function setOriginalRevisionId( $originalRevId ) {
		$this->editResultBuilder->setOriginalRevision( $originalRevId );
		return $this;
	}

	/**
	 * Marks this edit as a revert and applies relevant information.
	 * Will also cause the PageUpdater to add a relevant change tag when saving the edit.
	 *
	 * @param int $revertMethod The method used to make the revert:
	 *        REVERT_UNDO, REVERT_ROLLBACK or REVERT_MANUAL
	 * @param int $newestRevertedRevId the revision ID of the latest reverted revision.
	 * @param int|null $revertAfterRevId the revision ID after which revisions
	 *   are being reverted. Defaults to the revision before the $newestRevertedRevId.
	 * @return $this
	 * @see EditResultBuilder::markAsRevert()
	 */
	public function markAsRevert(
		int $revertMethod,
		int $newestRevertedRevId,
		?int $revertAfterRevId = null
	) {
		$this->editResultBuilder->markAsRevert(
			$revertMethod, $newestRevertedRevId, $revertAfterRevId
		);
		return $this;
	}

	/**
	 * Returns the EditResult associated with this PageUpdater.
	 * Will return null if PageUpdater::saveRevision() wasn't called yet.
	 * Will also return null if the update was not successful.
	 *
	 * @return EditResult|null
	 */
	public function getEditResult(): ?EditResult {
		return $this->editResult;
	}

	/**
	 * Sets a tag to apply to this update.
	 * Callers are responsible for permission checks,
	 * using ChangeTags::canAddTagsAccompanyingChange.
	 * @param string $tag
	 * @return $this
	 */
	public function addTag( string $tag ) {
		$this->tags[] = trim( $tag );
		return $this;
	}

	/**
	 * Sets tags to apply to this update.
	 * Callers are responsible for permission checks,
	 * using ChangeTags::canAddTagsAccompanyingChange.
	 * @param string[] $tags
	 * @return $this
	 */
	public function addTags( array $tags ) {
		Assert::parameterElementType( 'string', $tags, '$tags' );
		foreach ( $tags as $tag ) {
			$this->addTag( $tag );
		}
		return $this;
	}

	/**
	 * Sets software tag to this update. If the tag is not defined in the
	 * current software tags, it's ignored.
	 *
	 * @since 1.38
	 * @param string $tag
	 * @return $this
	 */
	public function addSoftwareTag( string $tag ): self {
		if ( in_array( $tag, $this->softwareTags ) ) {
			$this->addTag( $tag );
		}
		return $this;
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
	 * @return string[]
	 */
	private function computeEffectiveTags() {
		$tags = $this->tags;
		$editResult = $this->getEditResult();

		foreach ( $this->slotsUpdate->getModifiedRoles() as $role ) {
			$old_content = $this->getParentContent( $role );

			$handler = $this->getContentHandler( $role );
			$content = $this->slotsUpdate->getModifiedSlot( $role )->getContent();

			// TODO: MCR: Do this for all slots. Also add tags for removing roles!
			$tag = $handler->getChangeTag( $old_content, $content, $this->flags );
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
				throw new RevisionAccessException(
					'No such slot: {role}',
					[ 'role' => $role ]
				);
			}
		}

		return $this->contentHandlerFactory->getContentHandler( $slot->getModel() );
	}

	/**
	 * @return CommentStoreComment
	 */
	private function makeAutoSummary() {
		if ( !$this->useAutomaticEditSummaries || ( $this->flags & EDIT_AUTOSUMMARY ) === 0 ) {
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
		$summary = $handler->getAutosummary( $old_content, $content, $this->flags );

		return CommentStoreComment::newUnsavedComment( $summary );
	}

	/**
	 * Creates a dummy revision that does not change the content.
	 * Dummy revisions are typically used to record some event in the
	 * revision history, such as the page getting renamed.
	 *
	 * @param CommentStoreComment|string $summary Edit summary
	 * @param int $flags Bitfield, will be combined with the flags set via setFlags().
	 *        Callers should use this to set the EDIT_SILENT and EDIT_MINOR flag
	 *        if appropriate. The EDIT_UPDATE | EDIT_INTERNAL | EDIT_IMPLICIT
	 *        flags will always be set.
	 *
	 * @return RevisionRecord The newly created dummy revision
	 *
	 * @since 1.44
	 */
	public function saveDummyRevision( $summary, int $flags = 0 ) {
		$flags |= EDIT_UPDATE | EDIT_INTERNAL | EDIT_IMPLICIT;

		$this->setForceEmptyRevision( true );
		$rev = $this->saveRevision( $summary, $flags );

		if ( $rev === null ) {
			throw new NormalizedException( 'Failed to create dummy revision on ' .
				'{page} (page ID {id})',
				[
					'page' => (string)$this->getPage(),
					'id' => (string)$this->getPage()->getId(),
				]
			);
		}

		return $rev;
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
	 * MCR migration note: this replaces WikiPage::doUserEditContent. Callers that change to using
	 * saveRevision() now need to check the "minoredit" themselves before using EDIT_MINOR.
	 *
	 * @param CommentStoreComment|string $summary Edit summary
	 * @param int $flags Bitfield, will be combined with the flags set via setFlags(). See
	 *        there for details.
	 *
	 * @note If neither EDIT_NEW nor EDIT_UPDATE is specified, the expected state is detected
	 * automatically via grabParentRevision(). In this case, the "edit-already-exists" or
	 * "edit-gone-missing" errors may still be triggered due to race conditions, if the page
	 * was unexpectedly created or deleted while revision creation is in progress. This can be
	 * viewed as part of the CAS mechanism described above.
	 *
	 * @return RevisionRecord|null The new revision, or null if no new revision was created due
	 *         to a failure or a null-edit. Use wasRevisionCreated(), wasSuccessful() and getStatus()
	 *         to determine the outcome of the revision creation.
	 */
	public function saveRevision( $summary, int $flags = 0 ) {
		Assert::parameterType(
			[ 'string', CommentStoreComment::class, ],
			$summary,
			'$summary'
		);

		if ( is_string( $summary ) ) {
			$summary = CommentStoreComment::newUnsavedComment( $summary );
		}

		$this->setFlags( $flags );

		if ( $this->wasCommitted() ) {
			throw new RuntimeException(
				'saveRevision() or updateRevision() has already been called on this PageUpdater!'
			);
		}

		// Low-level check
		if ( $this->getPage()->getDBkey() === '' ) {
			throw new RuntimeException( 'Something is trying to edit an article with an empty title' );
		}

		// NOTE: slots can be inherited even if the role is not "allowed" on the title.
		$status = PageUpdateStatus::newGood();
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

			if ( !$roleHandler->isAllowedModel( $slot->getModel(), $this->getPage() ) ) {
				$contentHandler = $this->contentHandlerFactory
					->getContentHandler( $slot->getModel() );
				$this->status = PageUpdateStatus::newFatal( 'content-not-allowed-here',
					ContentHandler::getLocalizedName( $contentHandler->getModelID() ),
					$this->titleFormatter->getPrefixedText( $this->getPage() ),
					wfMessage( $roleHandler->getNameMessageKey() )
					// TODO: defer message lookup to caller
				);
				return null;
			}
		}

		// Load the data from the primary database if needed. Needed to check flags.
		// NOTE: This grabs the parent revision as the CAS token, if grabParentRevision
		// wasn't called yet. If the page is modified by another process before we are done with
		// it, this method must fail (with status 'edit-conflict')!
		// NOTE: The parent revision may be different from the edit's base revision.
		$this->prepareUpdate();

		// Detect whether update or creation should be performed.
		if ( !( $this->flags & EDIT_NEW ) && !( $this->flags & EDIT_UPDATE ) ) {
			$this->flags |= ( $this->derivedDataUpdater->pageExisted() ) ? EDIT_UPDATE : EDIT_NEW;
		}

		// Trigger pre-save hook (using provided edit summary)
		$renderedRevision = $this->derivedDataUpdater->getRenderedRevision();
		$hookStatus = PageUpdateStatus::newGood( [] );
		$allowedByHook = $this->hookRunner->onMultiContentSave(
			$renderedRevision, $this->author, $summary, $this->flags, $hookStatus
		);
		if ( $allowedByHook && $this->hookContainer->isRegistered( 'PageContentSave' ) ) {
			// Also run the legacy hook.
			// NOTE: WikiPage should only be used for the legacy hook,
			// and only if something uses the legacy hook.
			$mainContent = $this->derivedDataUpdater->getSlots()->getContent( SlotRecord::MAIN );

			$legacyUser = self::toLegacyUser( $this->author );

			// Deprecated since 1.35.
			$allowedByHook = $this->hookRunner->onPageContentSave(
				$this->getWikiPage(), $legacyUser, $mainContent, $summary,
				(bool)( $this->flags & EDIT_MINOR ), null, null, $this->flags, $hookStatus
			);
		}

		if ( !$allowedByHook ) {
			// The hook has prevented this change from being saved.
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			$this->status = $hookStatus;
			$this->logger->info( "Hook prevented page save", [ 'status' => $hookStatus ] );
			return null;
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		// XXX: $summary == null seems logical, but the empty string may actually come from the user
		// XXX: Move this logic out of the storage layer! It does not belong here! Use a callback?
		if ( $summary->text === '' && $summary->data === null ) {
			$summary = $this->makeAutoSummary();
		}

		// Actually create the revision and create/update the page.
		// Do NOT yet set $this->status!
		if ( $this->flags & EDIT_UPDATE ) {
			$status = $this->doModify( $summary );
		} else {
			$status = $this->doCreate( $summary );
		}

		// Promote user to any groups they meet the criteria for
		DeferredUpdates::addCallableUpdate( function () {
			$this->userGroupManager->addUserToAutopromoteOnceGroups( $this->author, 'onEdit' );
			// Also run 'onView' for backwards compatibility
			$this->userGroupManager->addUserToAutopromoteOnceGroups( $this->author, 'onView' );
		} );

		// NOTE: set $this->status only after all hooks have been called,
		// so wasCommitted doesn't return true when called indirectly from a hook handler!
		$this->status = $status;

		// TODO: replace bad status with Exceptions!
		return $this->status
			? $this->status->getNewRevision()
			: null;
	}

	/**
	 * Updates derived slots of an existing article. Does not update RC. Updates all necessary
	 * caches, optionally via the deferred update array. This does not check user permissions.
	 * Does not do a PST.
	 *
	 * Use wasRevisionCreated(), wasSuccessful() and getStatus() to determine the outcome of the
	 * revision update.
	 *
	 * @param int $revId
	 * @since 1.36
	 */
	public function updateRevision( int $revId = 0 ) {
		if ( $this->wasCommitted() ) {
			throw new RuntimeException(
				'saveRevision() or updateRevision() has already been called on this PageUpdater!'
			);
		}

		// Low-level check
		if ( $this->getPage()->getDBkey() === '' ) {
			throw new RuntimeException( 'Something is trying to edit an article with an empty title' );
		}

		$status = PageUpdateStatus::newGood();
		$this->checkAllRolesAllowed(
			$this->slotsUpdate->getModifiedRoles(),
			$status
		);
		$this->checkAllRolesDerived(
			$this->slotsUpdate->getModifiedRoles(),
			$status
		);
		$this->checkAllRolesDerived(
			$this->slotsUpdate->getRemovedRoles(),
			$status
		);

		if ( $revId === 0 ) {
			$revision = $this->grabParentRevision();
		} else {
			$revision = $this->revisionStore->getRevisionById( $revId, IDBAccessObject::READ_LATEST );
		}
		if ( $revision === null ) {
			$status->fatal( 'edit-gone-missing' );
		}

		if ( !$status->isOK() ) {
			$this->status = $status;
			return;
		}

		// Make sure the given content is allowed in the respective slots of this page
		foreach ( $this->slotsUpdate->getModifiedRoles() as $role ) {
			$slot = $this->slotsUpdate->getModifiedSlot( $role );
			$roleHandler = $this->slotRoleRegistry->getRoleHandler( $role );

			if ( !$roleHandler->isAllowedModel( $slot->getModel(), $this->getPage() ) ) {
				$contentHandler = $this->contentHandlerFactory
					->getContentHandler( $slot->getModel() );
				$this->status = PageUpdateStatus::newFatal(
					'content-not-allowed-here',
					ContentHandler::getLocalizedName( $contentHandler->getModelID() ),
					$this->titleFormatter->getPrefixedText( $this->getPage() ),
					wfMessage( $roleHandler->getNameMessageKey() )
				// TODO: defer message lookup to caller
				);
				return;
			}
		}

		// XXX: do we need PST?

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable revision is checked
		$this->status = $this->doUpdate( $revision );
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
	 * The Status object indicating whether saveRevision() was successful.
	 * Must not be called before saveRevision() or updateRevision() was called on this instance.
	 *
	 * @note This is here for compatibility with WikiPage::doUserEditContent. It may be deprecated
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
	 *     revision-record: The RevisionRecord object for the inserted revision, or null.
	 *
	 * @return PageUpdateStatus
	 */
	public function getStatus(): PageUpdateStatus {
		if ( !$this->status ) {
			throw new LogicException(
				'getStatus() is undefined before saveRevision() or updateRevision() have been called'
			);
		}
		return $this->status;
	}

	/**
	 * Whether saveRevision() completed successfully. This is not the same as wasRevisionCreated():
	 * when the new content is exactly the same as the old one (DerivedPageDataUpdater::isChange()
	 * returns false) and setForceEmptyRevision( true ) is not set, no new revision is created, but
	 * the save is considered successful. This behavior constitutes a "null edit".
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
		return $this->status && $this->status->wasPageCreated();
	}

	/**
	 * Whether saveRevision() did create a revision because the content didn't change: (null-edit).
	 * Whether the content changed or not is determined by DerivedPageDataUpdater::isChange().
	 *
	 * @deprecated since 1.38, use wasRevisionCreated() instead.
	 * @return bool
	 */
	public function isUnchanged() {
		return !$this->wasRevisionCreated();
	}

	/**
	 * Whether the prepared edit is a change compared to the previous revision.
	 *
	 * @return bool
	 */
	public function isChange() {
		return $this->derivedDataUpdater->isChange();
	}

	/**
	 * Disable new revision creation, throwing an exception if it is attempted.
	 *
	 * @return $this
	 */
	public function preventChange() {
		$this->preventChange = true;
		return $this;
	}

	/**
	 * Whether saveRevision() did create a revision. This is not the same as wasSuccessful():
	 * when the new content is exactly the same as the old one (DerivedPageDataUpdater::isChange()
	 * returns false) and setForceEmptyRevision( true ) is not set, no new revision is created, but
	 * the save is considered successful. This behavior constitutes a "null edit".
	 *
	 * @since 1.38
	 *
	 * @return bool
	 */
	public function wasRevisionCreated(): bool {
		return $this->status
			&& $this->status->wasRevisionCreated();
	}

	/**
	 * The new revision created by saveRevision(), or null if saveRevision() has not yet been
	 * called, failed, or did not create a new revision because the content did not change.
	 *
	 * @return RevisionRecord|null
	 */
	public function getNewRevision() {
		return $this->status
			? $this->status->getNewRevision()
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
	 * @param PageUpdateStatus $status
	 *
	 * @return MutableRevisionRecord
	 */
	private function makeNewRevision(
		CommentStoreComment $comment,
		PageUpdateStatus $status
	) {
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
			$masterPageId = $title->getArticleID( IDBAccessObject::READ_LATEST );

			if ( $revPageId === $masterPageId ) {
				wfWarn( __METHOD__ . ": Encountered stale Title object: old ID was $titlePageId, "
					. "continuing with new ID from primary DB, $masterPageId" );
			} else {
				throw new InvalidArgumentException(
					"Revision inherited page ID $revPageId from its parent, "
					. "but the provided Title object belongs to page ID $masterPageId"
				);
			}
		}

		if ( $parent ) {
			$oldid = $parent->getId();
			$rev->setParentId( $oldid );

			if ( $title->getArticleID() !== $parent->getPageId() ) {
				wfWarn( __METHOD__ . ': Encountered stale Title object with no page ID! '
					. 'Using page ID from parent revision: ' . $parent->getPageId() );
			}
		} else {
			$oldid = 0;
		}

		$rev->setComment( $comment );
		$rev->setUser( $this->author );
		$rev->setMinorEdit( ( $this->flags & EDIT_MINOR ) > 0 );

		foreach ( $rev->getSlots()->getSlots() as $slot ) {
			$content = $slot->getContent();

			// XXX: We may push this up to the "edit controller" level, see T192777.
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $content->getModel() );
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable getId is not null here
			$validationParams = new ValidationParams( $this->getPage(), $this->flags, $oldid );
			$prepStatus = $contentHandler->validateSave( $content, $validationParams );

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
	 * Update derived slots in an existing revision. If the revision is the current revision,
	 * this will update page_touched and trigger secondary updates.
	 *
	 * We do not have sufficient information to know whether to or how to update recentchanges
	 * here, so, as opposed to doCreate(), updating recentchanges is left as the responsibility
	 * of the caller.
	 *
	 * @param RevisionRecord $revision
	 * @return PageUpdateStatus
	 */
	private function doUpdate( RevisionRecord $revision ): PageUpdateStatus {
		$currentRevision = $this->grabParentRevision();
		if ( !$currentRevision ) {
			// Article gone missing
			return PageUpdateStatus::newFatal( 'edit-gone-missing' );
		}

		$dbw = $this->dbProvider->getPrimaryDatabase( $this->getWikiId() );
		$dbw->startAtomic( __METHOD__ );

		$slots = $this->revisionStore->updateSlotsOn( $revision, $this->slotsUpdate, $dbw );

		// Return the slots and revision to the caller
		$newRevisionRecord = MutableRevisionRecord::newUpdatedRevisionRecord( $revision, $slots );
		$status = PageUpdateStatus::newGood( [
			'revision-record' => $newRevisionRecord,
			'slots' => $slots,
		] );

		$isCurrent = $revision->getId( $this->getWikiId() ) ===
			$currentRevision->getId( $this->getWikiId() );

		if ( $isCurrent ) {
			// Update page_touched
			$this->getTitle()->invalidateCache( $newRevisionRecord->getTimestamp() );

			$this->buildEditResult( $newRevisionRecord, false );

			// NOTE: don't trigger a PageRevisionUpdated event!
			$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!
			$this->prepareDerivedDataUpdater(
				$wikiPage,
				$newRevisionRecord,
				$revision->getComment(),
				[],
				[
					PageRevisionUpdatedEvent::FLAG_SILENT => true,
					PageRevisionUpdatedEvent::FLAG_IMPLICIT => true,
					'emitEvents' => false,
				]
			);

			DeferredUpdates::addUpdate(
				$this->getAtomicSectionUpdate(
					$dbw,
					$wikiPage,
					$newRevisionRecord,
					$revision->getComment(),
					[ 'changed' => false ]
				),
				DeferredUpdates::PRESEND
			);
		}

		// Mark the earliest point where the transaction round can be committed in CLI mode.
		// We want to make sure that the event was bound to a round of transactions. We also
		// want the deferred update to enqueue similarly in both web and CLI modes, in order
		// to simplify testing assertions.
		$dbw->endAtomic( __METHOD__ );

		return $status;
	}

	/**
	 * @param CommentStoreComment $summary The edit summary
	 * @return PageUpdateStatus
	 */
	private function doModify( CommentStoreComment $summary ): PageUpdateStatus {
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		// Update article, but only if changed.
		$status = PageUpdateStatus::newEmpty( false );

		$oldRev = $this->grabParentRevision();
		$oldid = $oldRev ? $oldRev->getId() : 0;

		if ( !$oldRev ) {
			// Article gone missing
			return $status->fatal( 'edit-gone-missing' );
		}

		$newRevisionRecord = $this->makeNewRevision(
			$summary,
			$status
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$now = $newRevisionRecord->getTimestamp();

		$changed = $this->derivedDataUpdater->isChange();

		if ( $changed ) {
			if ( $this->forceEmptyRevision ) {
				throw new LogicException(
					"Content was changed even though forceEmptyRevision() was called."
				);
			}
			if ( $this->preventChange ) {
				throw new LogicException(
					"Content was changed even though preventChange() was called."
				);
			}
		}

		// We build the EditResult before the $change if/else branch in order to pass
		// the correct $newRevisionRecord to EditResultBuilder. In case this is a null
		// edit, $newRevisionRecord will be later overridden to its parent revision, which
		// would confuse EditResultBuilder.
		if ( !$changed ) {
			// This is a null edit, ensure original revision ID is set properly
			$this->editResultBuilder->setOriginalRevision( $oldRev );
		}
		$this->buildEditResult( $newRevisionRecord, false );

		$dbw = $this->dbProvider->getPrimaryDatabase( $this->getWikiId() );
		$dbw->startAtomic( __METHOD__ );

		if ( $changed || $this->forceEmptyRevision ) {
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
				return $status->fatal( 'edit-conflict' );
			}

			// At this point we are now committed to returning an OK
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
			$tags = $this->computeEffectiveTags();
			$this->hookRunner->onRevisionFromEditComplete(
				$wikiPage,
				$newRevisionRecord,
				$editResult->getOriginalRevisionId(),
				$this->author,
				$tags
			);

			$this->prepareDerivedDataUpdater(
				$wikiPage,
				$newRevisionRecord,
				$summary,
				$tags
			);

			// Return the new revision to the caller
			$status->setNewRevision( $newRevisionRecord );

			// Notify the dispatcher of the PageRevisionUpdatedEvent during the transaction round
			$this->emitEvents();
		} else {
			// T34948: revision ID must be set to page {{REVISIONID}} and
			// related variables correctly. Likewise for {{REVISIONUSER}} (T135261).
			// Since we don't insert a new revision into the database, the least
			// error-prone way is to reuse given old revision.
			$newRevisionRecord = $oldRev;

			$this->prepareDerivedDataUpdater(
				$wikiPage,
				$newRevisionRecord,
				$summary,
				[],
				[ 'changed' => false ]
			);

			$status->warning( 'edit-no-change' );
			// Update page_touched as updateRevisionOn() was not called.
			// Other cache updates are managed in WikiPage::onArticleEdit()
			// via WikiPage::doEditUpdates().
			$this->getTitle()->invalidateCache( $now );

			// Notify the dispatcher of the PageRevisionUpdatedEvent during the transaction round
			$this->emitEvents();
		}

		// Schedule the secondary updates to run after the transaction round commits.
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
				$summary,
				[ 'changed' => $changed, ]
			),
			DeferredUpdates::PRESEND
		);

		// Mark the earliest point where the transaction round can be committed in CLI mode.
		// We want to make sure that the event was bound to a round of transactions. We also
		// want the deferred update to enqueue similarly in both web and CLI modes, in order
		// to simplify testing assertions.
		$dbw->endAtomic( __METHOD__ );

		return $status;
	}

	/**
	 * @param CommentStoreComment $summary The edit summary
	 * @return PageUpdateStatus
	 */
	private function doCreate( CommentStoreComment $summary ): PageUpdateStatus {
		if ( $this->preventChange ) {
			throw new LogicException(
				"Content was changed even though preventChange() was called."
			);
		}
		$wikiPage = $this->getWikiPage(); // TODO: use for legacy hooks only!

		if ( !$this->derivedDataUpdater->getSlots()->hasSlot( SlotRecord::MAIN ) ) {
			throw new PageUpdateException( 'Must provide a main slot when creating a page!' );
		}

		$status = PageUpdateStatus::newEmpty( true );

		$newRevisionRecord = $this->makeNewRevision(
			$summary,
			$status
		);

		if ( !$status->isOK() ) {
			return $status;
		}

		$this->buildEditResult( $newRevisionRecord, true );
		$now = $newRevisionRecord->getTimestamp();

		$dbw = $this->dbProvider->getPrimaryDatabase( $this->getWikiId() );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		// TODO: move to storage service
		$newid = $wikiPage->insertOn( $dbw );
		if ( $newid === false ) {
			$dbw->endAtomic( __METHOD__ );
			return $status->fatal( 'edit-already-exists' );
		}

		// At this point we are now committed to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).
		$newRevisionRecord->setPageId( $newid );

		// Save the revision text...
		$newRevisionRecord = $this->revisionStore->insertRevisionOn( $newRevisionRecord, $dbw );

		// Update the page record with revision data
		// TODO: move to storage service
		if ( !$wikiPage->updateRevisionOn( $dbw, $newRevisionRecord, 0, false ) ) {
			throw new PageUpdateException( "Failed to update page row to use new revision." );
		}

		$tags = $this->computeEffectiveTags();
		$this->hookRunner->onRevisionFromEditComplete(
			$wikiPage, $newRevisionRecord, false, $this->author, $tags
		);

		if ( $this->usePageCreationLog ) {
			// Log the page creation
			// @TODO: Do we want a 'recreate' action?
			$logEntry = new ManualLogEntry( 'create', 'create' );
			$logEntry->setPerformer( $this->author );
			$logEntry->setTarget( $this->getPage() );
			$logEntry->setComment( $summary->text );
			$logEntry->setTimestamp( $now );
			$logEntry->setAssociatedRevId( $newRevisionRecord->getId() );
			$logEntry->insert();
			// Note that we don't publish page creation events to recentchanges
			// (i.e. $logEntry->publish()) since this would create duplicate entries,
			// one for the edit and one for the page creation.
		}

		$this->prepareDerivedDataUpdater(
			$wikiPage,
			$newRevisionRecord,
			$summary,
			$tags
		);

		// Return the new revision to the caller
		$status->setNewRevision( $newRevisionRecord );

		// Notify the dispatcher of the PageRevisionUpdatedEvent during the transaction round
		$this->emitEvents();
		// Schedule the secondary updates to run after the transaction round commits
		DeferredUpdates::addUpdate(
			$this->getAtomicSectionUpdate(
				$dbw,
				$wikiPage,
				$newRevisionRecord,
				$summary,
				[ 'created' => true ]
			),
			DeferredUpdates::PRESEND
		);

		// Mark the earliest point where the transaction round can be committed in CLI mode.
		// We want to make sure that the event was bound to a round of transactions. We also
		// want the deferred update to enqueue similarly in both web and CLI modes, in order
		// to simplify testing assertions.
		$dbw->endAtomic( __METHOD__ );

		return $status;
	}

	private function prepareDerivedDataUpdater(
		WikiPage $wikiPage,
		RevisionRecord $newRevisionRecord,
		CommentStoreComment $summary,
		array $tags,
		array $hintOverrides = []
	) {
		static $flagMap = [
			EDIT_SILENT => PageRevisionUpdatedEvent::FLAG_SILENT,
			EDIT_FORCE_BOT => PageRevisionUpdatedEvent::FLAG_BOT,
			EDIT_IMPLICIT => PageRevisionUpdatedEvent::FLAG_IMPLICIT,
		];

		$hints = $this->hints;
		foreach ( $flagMap as $bit => $name ) {
			$hints[$name] = ( $this->flags & $bit ) === $bit;
		}

		$hints += PageRevisionUpdatedEvent::DEFAULT_FLAGS;
		$hints = $hintOverrides + $hints;

		// set debug data
		$hints['causeAction'] = 'edit-page';
		$hints['causeAgent'] = $this->author->getName();

		$editResult = $this->getEditResult();
		$hints['editResult'] = $editResult;

		// Prepare to update links tables, site stats, etc.
		$hints['rcPatrolStatus'] = $this->rcPatrolStatus;
		$hints['tags'] = $tags;

		$this->derivedDataUpdater->setPerformer( $this->author );
		$this->derivedDataUpdater->prepareUpdate( $newRevisionRecord, $hints );
	}

	private function emitEvents(): void {
		$this->derivedDataUpdater->emitEvents();
	}

	private function getAtomicSectionUpdate(
		IDatabase $dbw,
		WikiPage $wikiPage,
		RevisionRecord $newRevisionRecord,
		CommentStoreComment $summary,
		array $hints = []
	): AtomicSectionUpdate {
		return new AtomicSectionUpdate(
			$dbw,
			__METHOD__,
			function () use (
				$wikiPage, $newRevisionRecord,
				$summary, $hints
			) {
				$this->derivedDataUpdater->doUpdates();

				$created = $hints['created'] ?? false;
				$this->flags |= ( $created ? EDIT_NEW : EDIT_UPDATE );

				// PageSaveComplete replaced old PageContentInsertComplete and
				// PageContentSaveComplete hooks since 1.35
				$this->hookRunner->onPageSaveComplete(
					$wikiPage,
					$this->author,
					$summary->text,
					$this->flags,
					$newRevisionRecord,
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Not null already checked
					$this->getEditResult()
				);
			}
		);
	}

	/**
	 * @return string[] Slots required for this page update, as a list of role names.
	 */
	private function getRequiredSlotRoles() {
		return $this->slotRoleRegistry->getRequiredRoles( $this->getPage() );
	}

	/**
	 * @return string[] Slots allowed for this page update, as a list of role names.
	 */
	private function getAllowedSlotRoles() {
		return $this->slotRoleRegistry->getAllowedRoles( $this->getPage() );
	}

	private function ensureRoleAllowed( string $role ) {
		$allowedRoles = $this->getAllowedSlotRoles();
		if ( !in_array( $role, $allowedRoles ) ) {
			throw new PageUpdateException( "Slot role `$role` is not allowed." );
		}
	}

	private function ensureRoleNotRequired( string $role ) {
		$requiredRoles = $this->getRequiredSlotRoles();
		if ( in_array( $role, $requiredRoles ) ) {
			throw new PageUpdateException( "Slot role `$role` is required." );
		}
	}

	private function checkAllRolesAllowed( array $roles, PageUpdateStatus $status ) {
		$allowedRoles = $this->getAllowedSlotRoles();

		$forbidden = array_diff( $roles, $allowedRoles );
		if ( $forbidden ) {
			$status->error(
				'edit-slots-cannot-add',
				count( $forbidden ),
				implode( ', ', $forbidden )
			);
		}
	}

	private function checkAllRolesDerived( array $roles, PageUpdateStatus $status ) {
		$notDerived = array_filter(
			$roles,
			function ( $role ) {
				return !$this->slotRoleRegistry->getRoleHandler( $role )->isDerived();
			}
		);
		if ( $notDerived ) {
			$status->error(
				'edit-slots-not-derived',
				count( $notDerived ),
				implode( ', ', $notDerived )
			);
		}
	}

	private function checkNoRolesRequired( array $roles, PageUpdateStatus $status ) {
		$requiredRoles = $this->getRequiredSlotRoles();

		$needed = array_diff( $roles, $requiredRoles );
		if ( $needed ) {
			$status->error(
				'edit-slots-cannot-remove',
				count( $needed ),
				implode( ', ', $needed )
			);
		}
	}

	private function checkAllRequiredRoles( array $roles, PageUpdateStatus $status ) {
		$requiredRoles = $this->getRequiredSlotRoles();

		$missing = array_diff( $requiredRoles, $roles );
		if ( $missing ) {
			$status->error(
				'edit-slots-missing',
				count( $missing ),
				implode( ', ', $missing )
			);
		}
	}

}
