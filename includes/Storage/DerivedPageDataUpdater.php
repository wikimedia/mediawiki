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
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Deferred\DeferrableUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Deferred\RefreshSecondaryDataUpdate;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob;
use MediaWiki\Language\Language;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Assert\Assert;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILBFactory;

/**
 * A handle for managing updates for derived page data on edit, import, purge, etc.
 *
 * @note Avoid direct usage of DerivedPageDataUpdater.
 *
 * @todo Define interfaces for the different use cases of DerivedPageDataUpdater, particularly
 * providing access to post-PST content and ParserOutput to callbacks during revision creation,
 * which currently use WikiPage::prepareContentForEdit, and allowing updates to be triggered on
 * purge, import, and undeletion, which currently use WikiPage::doEditUpdates() and
 * Content::getSecondaryDataUpdates().
 *
 * DerivedPageDataUpdater instances are designed to be cached inside a WikiPage instance,
 * and re-used by callback code over the course of an update operation. It's a stepping stone
 * on the way to a more complete refactoring of WikiPage.
 *
 * When using a DerivedPageDataUpdater, the following life cycle must be observed:
 * grabCurrentRevision (optional), prepareContent (optional), prepareUpdate (required
 * for doUpdates). getCanonicalParserOutput, getSlots, and getSecondaryDataUpdates
 * require prepareContent or prepareUpdate to have been called first, to initialize the
 * DerivedPageDataUpdater.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage, and covers the use cases
 * of PreparedEdit.
 *
 * @see docs/pageupdater.md for more information.
 *
 * @internal
 * @since 1.32
 * @ingroup Page
 */
class DerivedPageDataUpdater implements LoggerAwareInterface, PreparedUpdate {

	/**
	 * @var UserIdentity|null
	 */
	private $user = null;

	/**
	 * @var WikiPage
	 */
	private $wikiPage;

	/**
	 * @var ParserCache
	 */
	private $parserCache;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var Language
	 */
	private $contLang;

	/**
	 * @var JobQueueGroup
	 */
	private $jobQueueGroup;

	/**
	 * @var ILBFactory
	 */
	private $loadbalancerFactory;

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	/**
	 * @var DomainEventDispatcher
	 */
	private $eventDispatcher;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod;

	/**
	 * Stores (most of) the $options parameter of prepareUpdate().
	 * @see prepareUpdate()
	 *
	 * @var array
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-var array{changed:bool,created:bool,moved:bool,cause:string,oldrevision:null|RevisionRecord,triggeringUser:null|UserIdentity,oldredirect:bool|null|string,oldcountable:bool|null|string,causeAction:null|string,causeAgent:null|string,editResult:null|EditResult}
	 */
	private $options = [
		'changed' => true,
		// newrev is true if prepareUpdate is handling the creation of a new revision,
		// as opposed to a null edit or a forced update.
		'newrev' => false,
		'created' => false,
		'oldtitle' => null,
		'oldrevision' => null,
		'oldcountable' => null,
		'oldredirect' => null,
		'triggeringUser' => null,
		// causeAction/causeAgent default to 'unknown' but that's handled where it's read,
		// to make the life of prepareUpdate() callers easier.
		'causeAction' => null,
		'causeAgent' => null,
		'editResult' => null,
		'rcPatrolStatus' => 0,
		'tags' => [],
		'cause' => 'edit',
		'emitEvents' => true,
	] + PageRevisionUpdatedEvent::DEFAULT_FLAGS;

	/**
	 * The state of the relevant row in page table before the edit.
	 * This is determined by the first call to grabCurrentRevision, prepareContent,
	 * or prepareUpdate (so it is only accessible in 'knows-current' or a later stage).
	 * If pageState was not initialized when prepareUpdate() is called, prepareUpdate() will
	 * attempt to emulate the state of the page table before the edit.
	 *
	 * Contains the following fields:
	 * - oldRevision (RevisionRecord|null): the revision that was current before the change
	 *   associated with this update. Might not be set, use getParentRevision().
	 * - oldId (int|null): the id of the above revision. 0 if there is no such revision (the change
	 *   was about creating a new page); null if not known (that should not happen).
	 * - oldIsRedirect (bool|null): whether the page was a redirect before the change. Lazy-loaded,
	 *   can be null; use wasRedirect() instead of direct access.
	 * - oldCountable (bool|null): whether the page was countable before the change (or null
	 *   if we don't have that information)
	 * - oldRecord (ExistingPageRecord|null): the page record before the update (or null
	 *   if the page didn't exist)
	 *
	 * @var array
	 */
	private $pageState = null;

	/**
	 * @var RevisionSlotsUpdate|null
	 */
	private $slotsUpdate = null;

	/**
	 * @var RevisionRecord|null
	 */
	private $parentRevision = null;

	/**
	 * @var RevisionRecord|null
	 */
	private $revision = null;

	/**
	 * @var RenderedRevision
	 */
	private $renderedRevision = null;

	/** @var ?PageRevisionUpdatedEvent */
	private $pageRevisionUpdatedEvent = null;

	/**
	 * @var RevisionRenderer
	 */
	private $revisionRenderer;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/**
	 * @var bool Whether null-edits create a revision.
	 */
	private $forceEmptyRevision = false;

	/**
	 * A stage identifier for managing the life cycle of this instance.
	 * Possible stages are 'new', 'knows-current', 'has-content', 'has-revision', and 'done'.
	 *
	 * @see docs/pageupdater.md for documentation of the life cycle.
	 *
	 * @var string
	 */
	private $stage = 'new';

	/**
	 * Transition table for managing the life cycle of DerivedPageDateUpdater instances.
	 *
	 * XXX: Overkill. This is a linear order, we could just count. Names are nice though,
	 * and constants are also overkill...
	 *
	 * @see docs/pageupdater.md for documentation of the life cycle.
	 */
	private const TRANSITIONS = [
		'new' => [
			'new' => true,
			'knows-current' => true,
			'has-content' => true,
			'has-revision' => true,
		],
		'knows-current' => [
			'knows-current' => true,
			'has-content' => true,
			'has-revision' => true,
		],
		'has-content' => [
			'has-content' => true,
			'has-revision' => true,
		],
		'has-revision' => [
			'has-revision' => true,
			'done' => true,
		],
	];

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var EditResultCache */
	private $editResultCache;

	/** @var ContentTransformer */
	private $contentTransformer;

	/** @var PageEditStash */
	private $pageEditStash;

	/** @var WANObjectCache */
	private $mainWANObjectCache;

	/** @var bool */
	private $warmParsoidParserCache;

	/** @var bool */
	private $useRcPatrol;

	private ChangeTagsStore $changeTagsStore;

	public function __construct(
		ServiceOptions $options,
		PageIdentity $page,
		RevisionStore $revisionStore,
		RevisionRenderer $revisionRenderer,
		SlotRoleRegistry $slotRoleRegistry,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		Language $contLang,
		ILBFactory $loadbalancerFactory,
		IContentHandlerFactory $contentHandlerFactory,
		HookContainer $hookContainer,
		DomainEventDispatcher $eventDispatcher,
		EditResultCache $editResultCache,
		ContentTransformer $contentTransformer,
		PageEditStash $pageEditStash,
		WANObjectCache $mainWANObjectCache,
		WikiPageFactory $wikiPageFactory,
		ChangeTagsStore $changeTagsStore
	) {
		// TODO: Remove this cast eventually
		$this->wikiPage = $wikiPageFactory->newFromTitle( $page );

		$this->parserCache = $parserCache;
		$this->revisionStore = $revisionStore;
		$this->revisionRenderer = $revisionRenderer;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->contLang = $contLang;
		// XXX only needed for waiting for replicas to catch up; there should be a narrower
		// interface for that.
		$this->loadbalancerFactory = $loadbalancerFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->eventDispatcher = $eventDispatcher;
		$this->editResultCache = $editResultCache;
		$this->contentTransformer = $contentTransformer;
		$this->pageEditStash = $pageEditStash;
		$this->mainWANObjectCache = $mainWANObjectCache;
		$this->changeTagsStore = $changeTagsStore;

		$this->logger = new NullLogger();

		$this->warmParsoidParserCache = $options
			->get( MainConfigNames::ParsoidCacheConfig )['WarmParsoidParserCache'];
		$this->useRcPatrol = $options
			->get( MainConfigNames::UseRCPatrol );
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Set the cause of the update. Will be used for the PageRevisionUpdatedEvent
	 * and for tracing/logging in jobs, etc.
	 *
	 * @param string $cause See PageRevisionUpdatedEvent::CAUSE_XXX
	 *
	 * @return void
	 */
	public function setCause( string $cause ) {
		// 'cause' is for use in PageRevisionUpdatedEvent, 'causeAction' is for
		// use in tracing in updates, jobs, and RevisionRenderer.
		// Note that PageRevisionUpdatedEvent uses causes like "edit" and "move", but
		// the convention for causeAction is to use "page-edit", etc.
		$this->options['cause'] = $cause;
		$this->options['causeAction'] = 'page-' . $cause;
	}

	/**
	 * Set the performer of the action.
	 *
	 * @return void
	 */
	public function setPerformer( UserIdentity $performer ) {
		$this->options['triggeringUser'] = $performer;
		$this->options['causeAgent'] = $performer->getName();
	}

	/**
	 * @return string[] [ $causeAction, $causeAgent ]
	 */
	private function getCauseForTracing(): array {
		return [
			$this->options['causeAction'] ?? 'unknown',
			$this->options['causeAgent']
				?? ( $this->user ? $this->user->getName() : 'unknown' ),
		];
	}

	/**
	 * Transition function for managing the life cycle of this instances.
	 *
	 * @see docs/pageupdater.md for documentation of the life cycle.
	 *
	 * @param string $newStage
	 * @return string the previous stage
	 */
	private function doTransition( $newStage ) {
		$this->assertTransition( $newStage );

		$oldStage = $this->stage;
		$this->stage = $newStage;

		return $oldStage;
	}

	/**
	 * Asserts that a transition to the given stage is possible, without performing it.
	 *
	 * @see docs/pageupdater.md for documentation of the life cycle.
	 *
	 * @param string $newStage
	 */
	private function assertTransition( $newStage ) {
		if ( empty( self::TRANSITIONS[$this->stage][$newStage] ) ) {
			throw new LogicException( "Cannot transition from {$this->stage} to $newStage" );
		}
	}

	/**
	 * Checks whether this DerivedPageDataUpdater can be re-used for running updates targeting
	 * the given revision.
	 *
	 * @param UserIdentity|null $user The user creating the revision in question
	 * @param RevisionRecord|null $revision New revision (after save, if already saved)
	 * @param RevisionSlotsUpdate|null $slotsUpdate New content (before PST)
	 * @param null|int $parentId Parent revision of the edit (use 0 for page creation)
	 *
	 * @return bool
	 */
	public function isReusableFor(
		?UserIdentity $user = null,
		?RevisionRecord $revision = null,
		?RevisionSlotsUpdate $slotsUpdate = null,
		$parentId = null
	) {
		if ( $revision
			&& $parentId
			&& $revision->getParentId() !== $parentId
		) {
			throw new InvalidArgumentException( '$parentId should match the parent of $revision' );
		}

		// NOTE: For null revisions, $user may be different from $this->revision->getUser
		// and also from $revision->getUser.
		// But $user should always match $this->user.
		if ( $user && $this->user && $user->getName() !== $this->user->getName() ) {
			return false;
		}

		if ( $revision && $this->revision && $this->revision->getId()
			&& $this->revision->getId() !== $revision->getId()
		) {
			return false;
		}

		if ( $this->pageState
			&& $revision
			&& $revision->getParentId() !== null
			&& $this->pageState['oldId'] !== $revision->getParentId()
		) {
			return false;
		}

		if ( $this->pageState
			&& $parentId !== null
			&& $this->pageState['oldId'] !== $parentId
		) {
			return false;
		}

		// NOTE: this check is the primary reason for having the $this->slotsUpdate field!
		if ( $this->slotsUpdate
			&& $slotsUpdate
			&& !$this->slotsUpdate->hasSameUpdates( $slotsUpdate )
		) {
			return false;
		}

		if ( $revision
			&& $this->revision
			&& !$this->revision->getSlots()->hasSameContent( $revision->getSlots() )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Set whether null-edits should create a revision. Enabling this allows the creation of dummy
	 * revisions ("null revisions") to mark events such as renaming in the page history.
	 *
	 * Must not be called once prepareContent() or prepareUpdate() have been called.
	 *
	 * @since 1.38
	 * @see PageUpdater setForceEmptyRevision
	 *
	 * @param bool $forceEmptyRevision
	 */
	public function setForceEmptyRevision( bool $forceEmptyRevision ) {
		if ( $this->revision ) {
			throw new LogicException( 'prepareContent() or prepareUpdate() was already called.' );
		}

		$this->forceEmptyRevision = $forceEmptyRevision;
	}

	/**
	 * @param string $articleCountMethod "any" or "link".
	 * @see $wgArticleCountMethod
	 */
	public function setArticleCountMethod( $articleCountMethod ) {
		$this->articleCountMethod = $articleCountMethod;
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
	 * Returns the page being updated.
	 * @since 1.37
	 * @return ProperPageIdentity (narrowed to ProperPageIdentity in 1.44)
	 */
	public function getPage(): ProperPageIdentity {
		return $this->wikiPage;
	}

	/**
	 * Determines whether the page being edited already existed.
	 * Only defined after calling grabCurrentRevision() or prepareContent() or prepareUpdate()!
	 *
	 * @return bool
	 * @throws LogicException if called before grabCurrentRevision
	 */
	public function pageExisted() {
		$this->assertHasPageState( __METHOD__ );

		return $this->pageState['oldId'] > 0;
	}

	/**
	 * Returns the parent revision of the new revision wrapped by this update.
	 * If the update is a null-edit, this will return the parent of the current (and new) revision.
	 * This will return null if the revision wrapped by this update created the page.
	 * Only defined after calling prepareContent() or prepareUpdate()!
	 *
	 * @return RevisionRecord|null the parent revision of the new revision, or null if
	 *         the update created the page.
	 */
	private function getParentRevision() {
		$this->assertPrepared( __METHOD__ );

		if ( $this->parentRevision ) {
			return $this->parentRevision;
		}

		if ( !$this->pageState['oldId'] ) {
			// If there was no current revision, there is no parent revision,
			// since the page didn't exist.
			return null;
		}

		$oldId = $this->revision->getParentId();
		$flags = $this->usePrimary() ? IDBAccessObject::READ_LATEST : 0;
		$this->parentRevision = $oldId
			? $this->revisionStore->getRevisionById( $oldId, $flags )
			: null;

		return $this->parentRevision;
	}

	/**
	 * Returns the revision that was the page's current revision when grabCurrentRevision()
	 * was first called.
	 *
	 * @return RevisionRecord|null the original revision before the update, or null
	 *         if the page did not yet exist.
	 */
	private function getOldRevision() {
		$this->assertPrepared( __METHOD__ );
		return $this->pageState['oldRevision'];
	}

	/**
	 * Returns the revision that was the page's current revision when grabCurrentRevision()
	 * was first called.
	 *
	 * During an edit, that revision will act as the logical parent of the new revision.
	 *
	 * Some updates are performed based on the difference between the database state at the
	 * moment this method is first called, and the state after the edit.
	 *
	 * @see docs/pageupdater.md for more information on when thie method can and should be called.
	 *
	 * @note After prepareUpdate() was called, grabCurrentRevision() will throw an exception
	 * to avoid confusion, since the page's current revision is then the new revision after
	 * the edit, which was presumably passed to prepareUpdate() as the $revision parameter.
	 * Use getParentRevision() instead to access the revision that is the parent of the
	 * new revision.
	 *
	 * @return RevisionRecord|null the page's current revision, or null if the page does not
	 * yet exist.
	 */
	public function grabCurrentRevision() {
		if ( $this->pageState ) {
			return $this->pageState['oldRevision'];
		}

		$this->assertTransition( 'knows-current' );

		// NOTE: eventually, this won't use WikiPage any more
		$wikiPage = $this->getWikiPage();

		// Do not call WikiPage::clear(), since the caller may already have caused page data
		// to be loaded with SELECT FOR UPDATE. Just assert it's loaded now.
		$wikiPage->loadPageData( IDBAccessObject::READ_LATEST );
		$current = $wikiPage->getRevisionRecord();

		$this->pageState = [
			'oldRevision' => $current,
			'oldId' => $current ? $current->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(), // NOTE: uses page table
			'oldCountable' => $wikiPage->isCountable(), // NOTE: uses pagelinks table
			'oldRecord' => $wikiPage->exists() ? $wikiPage->toPageRecord() : null,
		];

		$this->doTransition( 'knows-current' );

		return $this->pageState['oldRevision'];
	}

	/**
	 * Whether prepareUpdate() or prepareContent() have been called on this instance.
	 *
	 * @return bool
	 */
	public function isContentPrepared() {
		return $this->revision !== null;
	}

	/**
	 * Whether prepareUpdate() has been called on this instance.
	 *
	 * @note will also return null in case of a null-edit!
	 *
	 * @return bool
	 */
	public function isUpdatePrepared() {
		return $this->revision !== null && $this->revision->getId() !== null;
	}

	/**
	 * @return int
	 */
	private function getPageId() {
		// NOTE: eventually, this won't use WikiPage any more
		return $this->wikiPage->getId();
	}

	/**
	 * Whether the content is deleted and thus not visible to the public.
	 *
	 * @return bool
	 */
	public function isContentDeleted() {
		if ( $this->revision ) {
			return $this->revision->isDeleted( RevisionRecord::DELETED_TEXT );
		} else {
			// If the content has not been saved yet, it cannot have been deleted yet.
			return false;
		}
	}

	/**
	 * Returns the slot, modified or inherited, after PST, with no audience checks applied.
	 *
	 * @param string $role slot role name
	 *
	 * @throws PageUpdateException If the slot is neither set for update nor inherited from the
	 *        parent revision.
	 * @return SlotRecord
	 */
	public function getRawSlot( $role ) {
		return $this->getSlots()->getSlot( $role );
	}

	/**
	 * Returns the content of the given slot, with no audience checks.
	 *
	 * @throws PageUpdateException If the slot is neither set for update nor inherited from the
	 *        parent revision.
	 * @param string $role slot role name
	 * @return Content
	 */
	public function getRawContent( string $role ): Content {
		return $this->getRawSlot( $role )->getContent();
	}

	private function usePrimary(): bool {
		// TODO: can we just set a flag to true in prepareContent()?
		return $this->wikiPage->wasLoadedFrom( IDBAccessObject::READ_LATEST );
	}

	public function isCountable(): bool {
		// NOTE: Keep in sync with WikiPage::isCountable.

		if ( !$this->getTitle()->isContentPage() ) {
			return false;
		}

		if ( $this->isContentDeleted() ) {
			// This should be irrelevant: countability only applies to the current revision,
			// and the current revision is never suppressed.
			return false;
		}

		if ( $this->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $this->articleCountMethod === 'link' ) {
			// NOTE: it would be more appropriate to determine for each slot separately
			// whether it has links, and use that information with that slot's
			// isCountable() method. However, that would break parity with
			// WikiPage::isCountable, which uses the pagelinks table to determine
			// whether the current revision has links.
			$hasLinks = $this->getParserOutputForMetaData()->hasLinks();
		}

		foreach ( $this->getSlots()->getSlotRoles() as $role ) {
			$roleHandler = $this->slotRoleRegistry->getRoleHandler( $role );
			if ( $roleHandler->supportsArticleCount() ) {
				$content = $this->getRawContent( $role );

				if ( $content->isCountable( $hasLinks ) ) {
					return true;
				}
			}
		}

		return false;
	}

	public function isRedirect(): bool {
		// NOTE: main slot determines redirect status
		// TODO: MCR: this should be controlled by a PageTypeHandler
		$mainContent = $this->getRawContent( SlotRecord::MAIN );

		return $mainContent->isRedirect();
	}

	/**
	 * @param RevisionRecord $rev
	 *
	 * @return bool
	 */
	private function revisionIsRedirect( RevisionRecord $rev ) {
		// NOTE: main slot determines redirect status
		$mainContent = $rev->getMainContentRaw();

		return $mainContent->isRedirect();
	}

	/**
	 * Prepare updates based on an update which has not yet been saved.
	 *
	 * This may be used to create derived data that is needed when creating a new revision;
	 * particularly, this makes available the slots of the new revision via the getSlots()
	 * method, after applying PST and slot inheritance.
	 *
	 * The derived data prepared for revision creation may then later be re-used by doUpdates(),
	 * without the need to re-calculate.
	 *
	 * @see docs/pageupdater.md for more information on when thie method can and should be called.
	 *
	 * @note Calling this method more than once with the same $slotsUpdate
	 * has no effect. Calling this method multiple times with different content will cause
	 * an exception.
	 *
	 * @note Calling this method after prepareUpdate() has been called will cause an exception.
	 *
	 * @param UserIdentity $user The user to act as context for pre-save transformation (PST).
	 * @param RevisionSlotsUpdate $slotsUpdate The new content of the slots to be updated
	 *        by this edit, before PST.
	 * @param bool $useStash Whether to use stashed ParserOutput
	 */
	public function prepareContent(
		UserIdentity $user,
		RevisionSlotsUpdate $slotsUpdate,
		$useStash = true
	) {
		if ( $this->slotsUpdate ) {
			if ( !$this->user ) {
				throw new LogicException(
					'Unexpected state: $this->slotsUpdate was initialized, '
					. 'but $this->user was not.'
				);
			}

			if ( $this->user->getName() !== $user->getName() ) {
				throw new LogicException( 'Can\'t call prepareContent() again for different user! '
					. 'Expected ' . $this->user->getName() . ', got ' . $user->getName()
				);
			}

			if ( !$this->slotsUpdate->hasSameUpdates( $slotsUpdate ) ) {
				throw new LogicException(
					'Can\'t call prepareContent() again with different slot content!'
				);
			}

			return; // prepareContent() already done, nothing to do
		}

		$this->assertTransition( 'has-content' );

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!
		$title = $this->getTitle();

		$parentRevision = $this->grabCurrentRevision();

		// The edit may have already been prepared via api.php?action=stashedit
		$stashedEdit = false;

		// TODO: MCR: allow output for all slots to be stashed.
		if ( $useStash && $slotsUpdate->isModifiedSlot( SlotRecord::MAIN ) ) {
			$stashedEdit = $this->pageEditStash->checkCache(
				$title,
				$slotsUpdate->getModifiedSlot( SlotRecord::MAIN )->getContent(),
				$user
			);
		}

		$userPopts = ParserOptions::newFromUserAndLang( $user, $this->contLang );
		$userPopts->setRenderReason( $this->options['causeAgent'] ?? 'unknown' );

		$this->hookRunner->onArticlePrepareTextForEdit( $wikiPage, $userPopts );

		$this->user = $user;
		$this->slotsUpdate = $slotsUpdate;

		if ( $parentRevision ) {
			$this->revision = MutableRevisionRecord::newFromParentRevision( $parentRevision );
		} else {
			$this->revision = new MutableRevisionRecord( $title );
		}

		// NOTE: user and timestamp must be set, so they can be used for
		// {{subst:REVISIONUSER}} and {{subst:REVISIONTIMESTAMP}} in PST!
		$this->revision->setTimestamp( MWTimestamp::now( TS_MW ) );
		$this->revision->setUser( $user );

		// Set up ParserOptions to operate on the new revision
		$oldCallback = $userPopts->getCurrentRevisionRecordCallback();
		$userPopts->setCurrentRevisionRecordCallback(
			function ( Title $parserTitle, $parser = null ) use ( $title, $oldCallback ) {
				if ( $parserTitle->equals( $title ) ) {
					return $this->revision;
				} else {
					return $oldCallback( $parserTitle, $parser );
				}
			}
		);

		$pstContentSlots = $this->revision->getSlots();

		foreach ( $slotsUpdate->getModifiedRoles() as $role ) {
			$slot = $slotsUpdate->getModifiedSlot( $role );

			if ( $slot->isInherited() ) {
				// No PST for inherited slots! Note that "modified" slots may still be inherited
				// from an earlier version, e.g. for rollbacks.
				$pstSlot = $slot;
			} elseif ( $role === SlotRecord::MAIN && $stashedEdit ) {
				// TODO: MCR: allow PST content for all slots to be stashed.
				$pstSlot = SlotRecord::newUnsaved( $role, $stashedEdit->pstContent );
			} else {
				$pstContent = $this->contentTransformer->preSaveTransform(
					$slot->getContent(),
					$title,
					$user,
					$userPopts
				);

				$pstSlot = SlotRecord::newUnsaved( $role, $pstContent );
			}

			$pstContentSlots->setSlot( $pstSlot );
		}

		foreach ( $slotsUpdate->getRemovedRoles() as $role ) {
			$pstContentSlots->removeSlot( $role );
		}

		$this->options['created'] = ( $parentRevision === null );
		$this->options['changed'] = ( $parentRevision === null
			|| !$pstContentSlots->hasSameContent( $parentRevision->getSlots() ) );

		$this->doTransition( 'has-content' );

		if ( !$this->options['changed'] ) {
			if ( $this->forceEmptyRevision ) {
				// dummy revision, inherit all slots
				foreach ( $parentRevision->getSlotRoles() as $role ) {
					$this->revision->inheritSlot( $parentRevision->getSlot( $role ) );
				}
			} else {
				// null-edit, the new revision *is* the old revision.

				// TODO: move this into MutableRevisionRecord
				$this->revision->setId( $parentRevision->getId() );
				$this->revision->setTimestamp( $parentRevision->getTimestamp() );
				$this->revision->setPageId( $parentRevision->getPageId() );
				$this->revision->setParentId( $parentRevision->getParentId() );
				$this->revision->setUser( $parentRevision->getUser( RevisionRecord::RAW ) );
				$this->revision->setComment( $parentRevision->getComment( RevisionRecord::RAW ) );
				$this->revision->setMinorEdit( $parentRevision->isMinor() );
				$this->revision->setVisibility( $parentRevision->getVisibility() );

				// prepareUpdate() is redundant for null-edits (but not for dummy revisions)
				$this->doTransition( 'has-revision' );
			}
		} else {
			$this->parentRevision = $parentRevision;
		}

		$renderHints = [ 'use-master' => $this->usePrimary(), 'audience' => RevisionRecord::RAW ];

		if ( $stashedEdit ) {
			/** @var ParserOutput $output */
			$output = $stashedEdit->output;
			// TODO: this should happen when stashing the ParserOutput, not now!
			$output->setCacheTime( $stashedEdit->timestamp );

			$renderHints['known-revision-output'] = $output;

			$this->logger->debug( __METHOD__ . ': using stashed edit output...' );
		}

		$renderHints['generate-html'] = $this->shouldGenerateHTMLOnEdit();

		[ $causeAction, ] = $this->getCauseForTracing();
		$renderHints['causeAction'] = $causeAction;

			// NOTE: we want a canonical rendering, so don't pass $this->user or ParserOptions
		// NOTE: the revision is either new or current, so we can bypass audience checks.
		$this->renderedRevision = $this->revisionRenderer->getRenderedRevision(
			$this->revision,
			null,
			null,
			$renderHints
		);
	}

	/**
	 * Returns the update's target revision - that is, the revision that will be the current
	 * revision after the update.
	 *
	 * @note Callers must treat the returned RevisionRecord's content as immutable, even
	 * if it is a MutableRevisionRecord instance. Other aspects of a MutableRevisionRecord
	 * returned from here, such as the user or the comment, may be changed, but may not
	 * be reflected in ParserOutput until after prepareUpdate() has been called.
	 *
	 * @todo This is currently used by PageUpdater::makeNewRevision() to construct an unsaved
	 * MutableRevisionRecord instance. Introduce something like an UnsavedRevisionFactory service
	 * for that purpose instead!
	 *
	 * @return RevisionRecord
	 */
	public function getRevision(): RevisionRecord {
		$this->assertPrepared( __METHOD__ );
		return $this->revision;
	}

	public function getRenderedRevision(): RenderedRevision {
		$this->assertPrepared( __METHOD__ );

		return $this->renderedRevision;
	}

	private function assertHasPageState( string $method ) {
		if ( !$this->pageState ) {
			throw new LogicException(
				'Must call grabCurrentRevision() or prepareContent() '
				. 'or prepareUpdate() before calling ' . $method
			);
		}
	}

	private function assertPrepared( string $method ) {
		if ( !$this->revision ) {
			throw new LogicException(
				'Must call prepareContent() or prepareUpdate() before calling ' . $method
			);
		}
	}

	private function assertHasRevision( string $method ) {
		if ( !$this->revision->getId() ) {
			throw new LogicException(
				'Must call prepareUpdate() before calling ' . $method
			);
		}
	}

	/**
	 * Whether the edit creates the page.
	 *
	 * @return bool
	 */
	public function isCreation() {
		$this->assertPrepared( __METHOD__ );
		return $this->options['created'];
	}

	/**
	 * Whether the content of the current revision after the edit is different from the content of the
	 * current revision before the edit. This will return false for a null-edit (no revision created),
	 * as well as for a dummy revision (a "null-revision" that has the same content as its parent).
	 *
	 * @warning at present, dummy revision would return false after prepareContent(),
	 * but true after prepareUpdate()!
	 *
	 * @todo This should probably be fixed.
	 *
	 * @return bool
	 */
	public function isChange() {
		$this->assertPrepared( __METHOD__ );
		return $this->options['changed'];
	}

	/**
	 * Whether the page was a redirect before the edit.
	 *
	 * @return bool
	 */
	public function wasRedirect() {
		$this->assertHasPageState( __METHOD__ );

		if ( $this->pageState['oldIsRedirect'] === null ) {
			/** @var RevisionRecord $rev */
			$rev = $this->pageState['oldRevision'];
			if ( $rev ) {
				$this->pageState['oldIsRedirect'] = $this->revisionIsRedirect( $rev );
			} else {
				$this->pageState['oldIsRedirect'] = false;
			}
		}

		return $this->pageState['oldIsRedirect'];
	}

	/**
	 * Returns the slots of the target revision, after PST.
	 *
	 * @note Callers must treat the returned RevisionSlots instance as immutable, even
	 * if it is a MutableRevisionSlots instance.
	 *
	 * @return RevisionSlots
	 */
	public function getSlots() {
		$this->assertPrepared( __METHOD__ );
		return $this->revision->getSlots();
	}

	/**
	 * Returns the RevisionSlotsUpdate for this updater.
	 *
	 * @return RevisionSlotsUpdate
	 */
	private function getRevisionSlotsUpdate() {
		$this->assertPrepared( __METHOD__ );

		if ( !$this->slotsUpdate ) {
			$old = $this->getParentRevision();
			$this->slotsUpdate = RevisionSlotsUpdate::newFromRevisionSlots(
				$this->revision->getSlots(),
				$old ? $old->getSlots() : null
			);
		}
		return $this->slotsUpdate;
	}

	/**
	 * Returns the role names of the slots touched by the new revision,
	 * including removed roles.
	 *
	 * @return string[]
	 */
	public function getTouchedSlotRoles() {
		return $this->getRevisionSlotsUpdate()->getTouchedRoles();
	}

	/**
	 * Returns the role names of the slots modified by the new revision,
	 * not including removed roles.
	 *
	 * @return string[]
	 */
	public function getModifiedSlotRoles(): array {
		return $this->getRevisionSlotsUpdate()->getModifiedRoles();
	}

	/**
	 * Returns the role names of the slots removed by the new revision.
	 *
	 * @return string[]
	 */
	public function getRemovedSlotRoles(): array {
		return $this->getRevisionSlotsUpdate()->getRemovedRoles();
	}

	/**
	 * Prepare derived data updates targeting the given RevisionRecord.
	 *
	 * Calling this method requires the given revision to be present in the database.
	 * This may be right after a new revision has been created, or when re-generating
	 * derived data e.g. in ApiPurge, RefreshLinksJob, and the refreshLinks
	 * script.
	 *
	 * @see docs/pageupdater.md for more information on when thie method can and should be called.
	 *
	 * @note Calling this method more than once with the same revision has no effect.
	 * $options are only used for the first call. Calling this method multiple times with
	 * different revisions will cause an exception.
	 *
	 * @note If grabCurrentRevision() (or prepareContent()) has been called before
	 * calling this method, $revision->getParentRevision() has to refer to the revision that
	 * was the current revision at the time grabCurrentRevision() was called.
	 *
	 * @param RevisionRecord $revision
	 * @param array $options Array of options. Supports the flags defined by
	 * PageRevisionUpdatedEvent. In addition, the following keys are supported used:
	 * - oldtitle: PageIdentity, if the page was moved this is the source title (default null)
	 * - oldrevision: RevisionRecord object for the pre-update revision (default null)
	 * - triggeringUser: The user triggering the update (UserIdentity, defaults to the
	 *   user who created the revision)
	 * - oldredirect: bool, null, or string 'no-change' (default null):
	 *    - bool: whether the page was counted as a redirect before that
	 *      revision, only used in changed is true and created is false
	 *    - null or 'no-change': don't update the redirect status.
	 * - oldcountable: bool, null, or string 'no-change' (default null):
	 *    - bool: whether the page was counted as an article before that
	 *      revision, only used in changed is true and created is false
	 *    - null: if created is false, don't update the article count; if created
	 *      is true, do update the article count
	 *    - 'no-change': don't update the article count, ever
	 *    When set to null, pageState['oldCountable'] will be used instead if available.
	 *  - cause: the reason for the update, see PageRevisionUpdatedEvent::CAUSE_XXX.
	 *  - known-revision-output: a combined canonical ParserOutput for the revision, perhaps
	 *    from some cache. The caller is responsible for ensuring that the ParserOutput indeed
	 *    matched the $rev and $options. This mechanism is intended as a temporary stop-gap,
	 *    for the time until caches have been changed to store RenderedRevision states instead
	 *    of ParserOutput objects. (default: null) (since 1.33)
	 *  - editResult: EditResult object created during the update. Required to perform reverted
	 *    tag update using RevertedTagUpdateJob. (default: null) (since 1.36)
	 */
	public function prepareUpdate( RevisionRecord $revision, array $options = [] ) {
		Assert::parameter(
			!isset( $options['oldrevision'] )
			|| $options['oldrevision'] instanceof RevisionRecord,
			'$options["oldrevision"]',
			'must be a RevisionRecord'
		);
		Assert::parameter(
			!isset( $options['triggeringUser'] )
			|| $options['triggeringUser'] instanceof UserIdentity,
			'$options["triggeringUser"]',
			'must be a UserIdentity'
		);
		Assert::parameter(
			!isset( $options['editResult'] )
			|| $options['editResult'] instanceof EditResult,
			'$options["editResult"]',
			'must be an EditResult'
		);

		if ( !$revision->getId() ) {
			throw new InvalidArgumentException(
				'Revision must have an ID set for it to be used with prepareUpdate()!'
			);
		}

		if ( !$this->wikiPage->exists() ) {
			// If the ongoing edit is creating the page, the state of $this->wikiPage
			// may be out of whack. This would only happen if the page creation was
			// done using a different WikiPage instance, which shouldn't be the case.
			$this->logger->warning(
				__METHOD__ . ': Reloading page meta-data after page creation',
				[
					'page' => (string)$this->wikiPage,
					'rev_id' => $revision->getId(),
				]
			);

			$this->wikiPage->clear();
			$this->wikiPage->loadPageData( IDBAccessObject::READ_LATEST );
		}

		if ( $this->revision && $this->revision->getId() ) {
			if ( $this->revision->getId() === $revision->getId() ) {
				$this->options['changed'] = false; // null-edit
			} else {
				throw new LogicException(
					'Trying to re-use DerivedPageDataUpdater with revision '
					. $revision->getId()
					. ', but it\'s already bound to revision '
					. $this->revision->getId()
				);
			}
		}

		if ( $this->revision
			&& !$this->revision->getSlots()->hasSameContent( $revision->getSlots() )
		) {
			throw new LogicException(
				'The revision provided has mismatching content!'
			);
		}

		// Override fields defined in $this->options with values from $options.
		$this->options = array_intersect_key( $options, $this->options ) + $this->options;

		if ( $this->revision ) {
			$oldId = $this->pageState['oldId'] ?? 0;
			$this->options['newrev'] = ( $revision->getId() !== $oldId );
		} elseif ( isset( $this->options['oldrevision'] ) ) {
			/** @var RevisionRecord $oldRev */
			$oldRev = $this->options['oldrevision'];
			$oldId = $oldRev->getId();
			$this->options['newrev'] = ( $revision->getId() !== $oldId );
		} else {
			$oldId = $revision->getParentId();
		}

		if ( $oldId !== null ) {
			// XXX: what if $options['changed'] disagrees?
			// MovePage creates a dummy revision with changed = false!
			// We may want to explicitly distinguish between "no new revision" (null-edit)
			// and "new revision without new content" (dummy revision).

			if ( $oldId === $revision->getParentId() ) {
				// NOTE: this may still be a NullRevision!
				// New revision!
				$this->options['changed'] = true;
			} elseif ( $oldId === $revision->getId() ) {
				// Null-edit!
				$this->options['changed'] = false;
			} else {
				// This indicates that calling code has given us the wrong RevisionRecord object
				throw new LogicException(
					'The RevisionRecord mismatches old revision ID: '
					. 'Old ID is ' . $oldId
					. ', parent ID is ' . $revision->getParentId()
					. ', revision ID is ' . $revision->getId()
				);
			}
		}

		// If prepareContent() was used to generate the PST content (which is indicated by
		// $this->slotsUpdate being set), and this is not a null-edit, then the given
		// revision must have the acting user as the revision author. Otherwise, user
		// signatures generated by PST would mismatch the user in the revision record.
		if ( $this->user !== null && $this->options['changed'] && $this->slotsUpdate ) {
			$user = $revision->getUser();
			if ( !$this->user->equals( $user ) ) {
				throw new LogicException(
					'The RevisionRecord provided has a mismatching actor: expected '
					. $this->user->getName()
					. ', got '
					. $user->getName()
				);
			}
		}

		// If $this->pageState was not yet initialized by grabCurrentRevision or prepareContent,
		// emulate the state of the page table before the edit, as good as we can.
		if ( !$this->pageState ) {
			$this->pageState = [
				'oldIsRedirect' => isset( $this->options['oldredirect'] )
					&& is_bool( $this->options['oldredirect'] )
						? $this->options['oldredirect']
						: null,
				'oldCountable' => isset( $this->options['oldcountable'] )
					&& is_bool( $this->options['oldcountable'] )
						? $this->options['oldcountable']
						: null,
			];

			if ( $this->options['changed'] ) {
				// The edit created a new revision
				$this->pageState['oldId'] = $revision->getParentId();
				// Old revision is null if this is a page creation
				$this->pageState['oldRevision'] = $this->options['oldrevision'] ?? null;
			} else {
				// This is a null-edit, so the old revision IS the new revision!
				$this->pageState['oldId'] = $revision->getId();
				$this->pageState['oldRevision'] = $revision;
			}
		}

		// "created" is forced here
		$this->options['created'] = ( $this->options['created'] ||
						( $this->pageState['oldId'] === 0 ) );

		$this->revision = $revision;

		$this->doTransition( 'has-revision' );

		// NOTE: in case we have a User object, don't override with a UserIdentity.
		// We already checked that $revision->getUser() matches $this->user;
		if ( !$this->user ) {
			$this->user = $revision->getUser( RevisionRecord::RAW );
		}

		// Prune any output that depends on the revision ID.
		if ( $this->renderedRevision ) {
			$this->renderedRevision->updateRevision( $revision );
		} else {
			[ $causeAction, ] = $this->getCauseForTracing();
			// NOTE: we want a canonical rendering, so don't pass $this->user or ParserOptions
			// NOTE: the revision is either new or current, so we can bypass audience checks.
			$this->renderedRevision = $this->revisionRenderer->getRenderedRevision(
				$this->revision,
				null,
				null,
				[
					'use-master' => $this->usePrimary(),
					'audience' => RevisionRecord::RAW,
					'known-revision-output' => $options['known-revision-output'] ?? null,
					'causeAction' => $causeAction
				]
			);

			// XXX: Since we presumably are dealing with the current revision,
			// we could try to get the ParserOutput from the parser cache.
		}

		// TODO: optionally get ParserOutput from the ParserCache here.
		// Move the logic used by RefreshLinksJob here!
	}

	/**
	 * @deprecated since 1.43; This only exists for B/C, use the getters on DerivedPageDataUpdater directly!
	 * @return PreparedEdit
	 */
	public function getPreparedEdit() {
		$this->assertPrepared( __METHOD__ );

		$slotsUpdate = $this->getRevisionSlotsUpdate();
		$preparedEdit = new PreparedEdit();

		$preparedEdit->popts = $this->getCanonicalParserOptions();
		$preparedEdit->parserOutputCallback = [ $this, 'getCanonicalParserOutput' ];
		$preparedEdit->pstContent = $this->revision->getContent( SlotRecord::MAIN );
		$preparedEdit->newContent =
			$slotsUpdate->isModifiedSlot( SlotRecord::MAIN )
			? $slotsUpdate->getModifiedSlot( SlotRecord::MAIN )->getContent()
			: $this->revision->getContent( SlotRecord::MAIN ); // XXX: can we just remove this?
		$preparedEdit->oldContent = null; // unused. // XXX: could get this from the parent revision
		$preparedEdit->revid = $this->revision ? $this->revision->getId() : null;
		$preparedEdit->format = $preparedEdit->pstContent->getDefaultFormat();

		return $preparedEdit;
	}

	/**
	 * @param string $role
	 * @param bool $generateHtml
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, $generateHtml = true ) {
		return $this->getRenderedRevision()->getSlotParserOutput(
			$role,
			[ 'generate-html' => $generateHtml ]
		);
	}

	/**
	 * @since 1.37
	 * @return ParserOutput
	 */
	public function getParserOutputForMetaData(): ParserOutput {
		return $this->getRenderedRevision()->getRevisionParserOutput( [ 'generate-html' => false ] );
	}

	/**
	 * @inheritDoc
	 * @return ParserOutput
	 */
	public function getCanonicalParserOutput(): ParserOutput {
		return $this->getRenderedRevision()->getRevisionParserOutput();
	}

	public function getCanonicalParserOptions(): ParserOptions {
		return $this->getRenderedRevision()->getOptions();
	}

	/**
	 * @param bool $recursive
	 *
	 * @return DeferrableUpdate[]
	 */
	public function getSecondaryDataUpdates( $recursive = false ) {
		if ( $this->isContentDeleted() ) {
			// This shouldn't happen, since the current content is always public,
			// and DataUpdates are only needed for current content.
			return [];
		}

		$wikiPage = $this->getWikiPage();
		$wikiPage->loadPageData( IDBAccessObject::READ_LATEST );
		if ( !$wikiPage->exists() ) {
			// page deleted while deferring the update
			return [];
		}

		$title = $wikiPage->getTitle();
		$allUpdates = [];
		$parserOutput = $this->shouldGenerateHTMLOnEdit() ?
			$this->getCanonicalParserOutput() : $this->getParserOutputForMetaData();

		// Construct a LinksUpdate for the combined canonical output.
		$linksUpdate = new LinksUpdate(
			$title,
			$parserOutput,
			$recursive,
			// Redirect target may have changed if the page is or was a redirect.
			// (We can't check if it was definitely changed without additional queries.)
			$this->isRedirect() || $this->wasRedirect()
		);
		if ( $this->options['cause'] === PageRevisionUpdatedEvent::CAUSE_MOVE ) {
			// @phan-suppress-next-line PhanTypeMismatchArgument Oldtitle is set along with moved
			$linksUpdate->setMoveDetails( $this->options['oldtitle'] );
		}

		$allUpdates[] = $linksUpdate;
		// NOTE: Run updates for all slots, not just the modified slots! Otherwise,
		// info for an inherited slot may end up being removed. This is also needed
		// to ensure that purges are effective.
		$renderedRevision = $this->getRenderedRevision();

		foreach ( $this->getSlots()->getSlotRoles() as $role ) {
			$slot = $this->getRawSlot( $role );
			$content = $slot->getContent();
			$handler = $content->getContentHandler();

			$updates = $handler->getSecondaryDataUpdates(
				$title,
				$content,
				$role,
				$renderedRevision
			);

			$allUpdates = array_merge( $allUpdates, $updates );
		}

		// XXX: if a slot was removed by an earlier edit, but deletion updates failed to run at
		// that time, we don't know for which slots to run deletion updates when purging a page.
		// We'd have to examine the entire history of the page to determine that. Perhaps there
		// could be a "try extra hard" mode for that case that would run a DB query to find all
		// roles/models ever used on the page. On the other hand, removing slots should be quite
		// rare, so perhaps this isn't worth the trouble.

		// TODO: consolidate with similar logic in WikiPage::getDeletionUpdates()
		$parentRevision = $this->getParentRevision();
		foreach ( $this->getRemovedSlotRoles() as $role ) {
			// HACK: we should get the content model of the removed slot from a SlotRoleHandler!
			// For now, find the slot in the parent revision - if the slot was removed, it should
			// always exist in the parent revision.
			$parentSlot = $parentRevision->getSlot( $role, RevisionRecord::RAW );
			$content = $parentSlot->getContent();
			$handler = $content->getContentHandler();

			$updates = $handler->getDeletionUpdates(
				$title,
				$role
			);

			$allUpdates = array_merge( $allUpdates, $updates );
		}

		// TODO: hard deprecate SecondaryDataUpdates in favor of RevisionDataUpdates in 1.33!
		$this->hookRunner->onRevisionDataUpdates( $title, $renderedRevision, $allUpdates );

		return $allUpdates;
	}

	/**
	 * @return bool true if at least one of slots require rendering HTML on edit, false otherwise.
	 *              This is needed for example in populating ParserCache.
	 */
	private function shouldGenerateHTMLOnEdit(): bool {
		foreach ( $this->getSlots()->getSlotRoles() as $role ) {
			$slot = $this->getRawSlot( $role );
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $slot->getModel() );
			if ( $contentHandler->generateHTMLOnEdit() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Do standard updates after page edit, purge, or import.
	 * Update links tables and other derived data.
	 * Purges pages that depend on this page when appropriate.
	 * With a 10% chance, triggers pruning the recent changes table.
	 *
	 * Further updates may be triggered by core components and extensions
	 * that listen to the PageRevisionUpdated event. Search for method names
	 * starting with "handlePageRevisionUpdatedEvent" to find listeners.
	 *
	 * @note prepareUpdate() must be called before calling this method!
	 *
	 * MCR migration note: this replaces WikiPage::doEditUpdates.
	 */
	public function doUpdates() {
		$this->assertTransition( 'done' );

		if ( $this->options['emitEvents'] ) {
			$this->emitEvents();
		}

		// TODO: move more logic into ingress objects subscribed to PageRevisionUpdatedEvent!
		$event = $this->getPageRevisionUpdatedEvent();

		if ( $this->shouldGenerateHTMLOnEdit() ) {
			$this->triggerParserCacheUpdate();
		}

		$this->doSecondaryDataUpdates( [
			// T52785 do not update any other pages on dummy revisions and null edits
			'recursive' => $event->isEffectiveContentChange(),
			// Defer the getCanonicalParserOutput() call made by getSecondaryDataUpdates()
			'defer' => DeferredUpdates::POSTSEND
		] );

		$id = $this->getPageId();
		$title = $this->getTitle();
		$wikiPage = $this->getWikiPage();

		if ( !$title->exists() ) {
			wfDebug( __METHOD__ . ": Page doesn't exist any more, bailing out" );

			$this->doTransition( 'done' );
			return;
		}

		DeferredUpdates::addCallableUpdate( function () use ( $event ) {
			if (
				$this->options['oldcountable'] === 'no-change' ||
				( !$event->isEffectiveContentChange() && !$event->hasCause( PageRevisionUpdatedEvent::CAUSE_MOVE ) )
			) {
				$good = 0;
			} elseif ( $event->isCreation() ) {
				$good = (int)$this->isCountable();
			} elseif ( $this->options['oldcountable'] !== null ) {
				$good = (int)$this->isCountable()
					- (int)$this->options['oldcountable'];
			} elseif ( isset( $this->pageState['oldCountable'] ) ) {
				$good = (int)$this->isCountable()
					- (int)$this->pageState['oldCountable'];
			} else {
				$good = 0;
			}
			$edits = $event->isEffectiveContentChange() ? 1 : 0;
			$pages = $event->isCreation() ? 1 : 0;

			DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
				[ 'edits' => $edits, 'articles' => $good, 'pages' => $pages ]
			) );
		} );

		// TODO: move onArticleCreate and onArticleEdit into a PageEventEmitter service
		if ( $event->isCreation() ) {
			// Deferred update that adds a mw-recreated tag to edits that create new pages
			// which have an associated deletion log entry for the specific namespace/title combination
			// and which are not undeletes
			if ( !( $event->hasCause( PageRevisionUpdatedEvent::CAUSE_UNDELETE ) ) ) {
				$revision = $this->revision;
				DeferredUpdates::addCallableUpdate( function () use ( $revision, $wikiPage ) {
					$this->maybeAddRecreateChangeTag( $wikiPage, $revision->getId() );
				} );
			}
			WikiPage::onArticleCreate( $title, $this->isRedirect() );
		} elseif ( $event->isEffectiveContentChange() ) { // T52785
			// TODO: Check $event->isNominalContentChange() instead so we still
			//       trigger updates on null edits, but pass a flag to suppress
			//       backlink purges through queueBacklinksJobs() id
			//       $event->changedLatestRevisionId() returns false.
			WikiPage::onArticleEdit(
				$title,
				$this->revision,
				$this->getTouchedSlotRoles(),
				// Redirect target may have changed if the page is or was a redirect.
				// (We can't check if it was definitely changed without additional queries.)
				$this->isRedirect() || $this->wasRedirect()
			);
		}

		if ( $event->hasCause( PageRevisionUpdatedEvent::CAUSE_UNDELETE ) ) {
			$this->mainWANObjectCache->touchCheckKey(
				"DerivedPageDataUpdater:restore:page:$id"
			);
		}

		$editResult = $event->getEditResult();

		if ( $editResult && !$editResult->isNullEdit() ) {
			// Cache EditResult for future use, via
			// RevertTagUpdateManager::approveRevertedTagForRevision().
			// This drives RevertedTagUpdateManager::approveRevertedTagForRevision.
			// It is only needed if RCPatrolling is enabled and the edit is a revert.
			// Skip in other cases to avoid flooding the cache, see T386217 and T388573.
			if ( $editResult->isRevert() && $this->useRcPatrol ) {
				$this->editResultCache->set(
					$this->revision->getId(),
					$editResult
				);
			}
		}

		$this->doTransition( 'done' );
	}

	/**
	 * @internal
	 */
	public function emitEvents(): void {
		if ( !$this->options['emitEvents'] ) {
			throw new LogicException( 'emitEvents was disabled on this updater' );
		}

		$event = $this->getPageRevisionUpdatedEvent();

		// don't dispatch again!
		$this->options['emitEvents'] = false;

		$this->eventDispatcher->dispatch( $event, $this->loadbalancerFactory );
	}

	private function getPageRevisionUpdatedEvent(): PageRevisionUpdatedEvent {
		if ( $this->pageRevisionUpdatedEvent ) {
			return $this->pageRevisionUpdatedEvent;
		}

		$this->assertHasRevision( __METHOD__ );

		$flags = array_intersect_key(
			$this->options,
			PageRevisionUpdatedEvent::DEFAULT_FLAGS
		);

		$pageRecordBefore = $this->pageState['oldRecord'] ?? null;
		$pageRecordAfter = $this->getWikiPage()->toPageRecord();

		$revisionBefore = $this->getOldRevision();
		$revisionAfter = $this->getRevision();

		if ( $this->options['created'] ) {
			// Page creation. No prior state.
			// Force null to make sure we don't get confused during imports when
			// updates are triggered after importing the last revision of several.
			// In that case, the page and older revisions do already exist when
			// the DerivedPageDataUpdater is initialized, because they were
			// created during the import. But they didn't exist prior to the
			// import (based on the fact that the 'created' flag is set).
			$pageRecordBefore = null;
			$revisionBefore = null;
		} elseif ( !$this->options['changed'] ) {
			// Null edit. Should already be the same, just make sure.
			$pageRecordBefore = $pageRecordAfter;
		}

		if ( $revisionBefore && $revisionAfter->getId() === $revisionBefore->getId() ) {
			// This is a null edit, flag it as a reconciliation request.
			$flags[ PageRevisionUpdatedEvent::FLAG_RECONCILIATION_REQUEST ] = true;
		}

		if ( $pageRecordBefore === null && !$this->options['created'] ) {
			// If the page wasn't just created, we need the state before.
			// If we are not actually emitting the event, we can ignore the issue.
			// This is needed to support the deprecated WikiPage::doEditUpdates()
			// method. Once that is gone, we can remove this conditional.
			if ( $this->options['emitEvents'] ) {
				throw new LogicException( 'Missing page state before update' );
			}
		}

		/** @var UserIdentity $performer */
		$performer = $this->options['triggeringUser'] ?? $this->user;
		'@phan-var UserIdentity $performer';

		$this->pageRevisionUpdatedEvent = new PageRevisionUpdatedEvent(
			$this->options['cause'] ?? PageUpdateCauses::CAUSE_EDIT,
			$pageRecordBefore,
			$pageRecordAfter,
			$revisionBefore,
			$revisionAfter,
			$this->getRevisionSlotsUpdate(),
			$this->options['editResult'] ?? null,
			$performer,
			$this->options['tags'] ?? [],
			$flags,
			$this->options['rcPatrolStatus'] ?? 0,
		);

		return $this->pageRevisionUpdatedEvent;
	}

	private function triggerParserCacheUpdate() {
		$this->assertHasRevision( __METHOD__ );

		$userParserOptions = ParserOptions::newFromUser( $this->user );

		// Decide whether to save the final canonical parser output based on the fact that
		// users are typically redirected to viewing pages right after they edit those pages.
		// Due to vary-revision-id, getting/saving that output here might require a reparse.
		if ( $userParserOptions->matchesForCacheKey( $this->getCanonicalParserOptions() ) ) {
			// Whether getting the final output requires a reparse or not, the user will
			// need canonical output anyway, since that is what their parser options use.
			// A reparse now at least has the benefit of various warm process caches.
			$this->doParserCacheUpdate();
		} else {
			// If the user does not have canonical parse options, then don't risk another parse
			// to make output they cannot use on the page refresh that typically occurs after
			// editing. Doing the parser output save post-send will still benefit *other* users.
			DeferredUpdates::addCallableUpdate( function () {
				$this->doParserCacheUpdate();
			} );
		}
	}

	/**
	 * Checks deletion logs for the specific article title and namespace combination
	 * if a deletion log exists, we can assume this is a new page recreation and are tagging it with `mw-recreated`.
	 * This does not consider deletions that were suppressed and therefore will not tag those.
	 *
	 * @param WikiPage $wikiPage
	 * @param int $revisionId
	 */
	private function maybeAddRecreateChangeTag( WikiPage $wikiPage, int $revisionId ) {
		$dbr = $this->loadbalancerFactory->getReplicaDatabase();

		if ( $dbr->newSelectQueryBuilder()
				->select( [ '1' ] )
				->from( 'logging' )
				->where( [
					'log_type' => 'delete',
					'log_title' => $wikiPage->getTitle()->getDBkey(),
					'log_namespace' => $wikiPage->getNamespace(),
				] )
				->where(
					$dbr->bitAnd( 'log_deleted', LogPage::DELETED_ACTION ) .
						' != ' . LogPage::DELETED_ACTION // T385792
				)->caller( __METHOD__ )->limit( 1 )->fetchField() ) {
			$this->changeTagsStore->addTags(
				[ ChangeTags::TAG_RECREATE ],
				null,
				$revisionId );
		}
	}

	/**
	 * Do secondary data updates (e.g. updating link tables) or schedule them as deferred updates
	 *
	 * @note This does not update the parser cache. Use doParserCacheUpdate() for that.
	 * @note Application logic should use Wikipage::doSecondaryDataUpdates instead.
	 *
	 * @param array $options
	 *   - recursive: make the update recursive, i.e. also update pages which transclude the
	 *     current page or otherwise depend on it (default: false)
	 *   - defer: one of the DeferredUpdates constants, or false to run immediately after waiting
	 *     for replication of the changes from the SecondaryDataUpdates hooks (default: false)
	 *   - freshness: used with 'defer'; forces an update if the last update was before the given timestamp,
	 *     even if the page and its dependencies didn't change since then (TS_MW; default: false)
	 * @since 1.32
	 */
	public function doSecondaryDataUpdates( array $options = [] ) {
		$this->assertHasRevision( __METHOD__ );
		$options += [ 'recursive' => false, 'defer' => false, 'freshness' => false ];
		$deferValues = [ false, DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND ];
		if ( !in_array( $options['defer'], $deferValues, true ) ) {
			throw new InvalidArgumentException( 'Invalid value for defer: ' . $options['defer'] );
		}

		$triggeringUser = $this->options['triggeringUser'] ?? $this->user;
		[ $causeAction, $causeAgent ] = $this->getCauseForTracing();
		if ( isset( $options['known-revision-output'] ) ) {
			$this->getRenderedRevision()->setRevisionParserOutput( $options['known-revision-output'] );
		}

		// Bundle all of the data updates into a single deferred update wrapper so that
		// any failure will cause at most one refreshLinks job to be enqueued by
		// DeferredUpdates::doUpdates(). This is hard to do when there are many separate
		// updates that are not defined as being related.
		$update = new RefreshSecondaryDataUpdate(
			$this->loadbalancerFactory,
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Already checked
			$triggeringUser,
			$this->wikiPage,
			$this->revision,
			$this,
			[ 'recursive' => $options['recursive'], 'freshness' => $options['freshness'] ]
		);
		$update->setCause( $causeAction, $causeAgent );

		if ( $options['defer'] === false ) {
			DeferredUpdates::attemptUpdate( $update );
		} else {
			DeferredUpdates::addUpdate( $update, $options['defer'] );
		}
	}

	/**
	 * Causes parser cache entries to be updated.
	 *
	 * @note This does not update links tables. Use doSecondaryDataUpdates() for that.
	 * @note Application logic should use Wikipage::updateParserCache instead.
	 */
	public function doParserCacheUpdate() {
		$this->assertHasRevision( __METHOD__ );

		$wikiPage = $this->getWikiPage(); // TODO: ParserCache should accept a RevisionRecord instead

		// NOTE: this may trigger the first parsing of the new content after an edit (when not
		// using pre-generated stashed output).
		// XXX: we may want to use the PoolCounter here. This would perhaps allow the initial parse
		// to be performed post-send. The client could already follow a HTTP redirect to the
		// page view, but would then have to wait for a response until rendering is complete.
		$output = $this->getCanonicalParserOutput();

		// Save it to the parser cache. Use the revision timestamp in the case of a
		// freshly saved edit, as that matches page_touched and a mismatch would trigger an
		// unnecessary reparse.
		$timestamp = $this->options['newrev'] ? $this->revision->getTimestamp()
			: $output->getCacheTime();
		$this->parserCache->save(
			$output, $wikiPage, $this->getCanonicalParserOptions(),
			$timestamp, $this->revision->getId()
		);

		// If we enable cache warming with parsoid outputs, let's do it at the same
		// time we're populating the parser cache with pre-generated HTML.
		// Use OPT_FORCE_PARSE to avoid a useless cache lookup.
		if ( $this->warmParsoidParserCache ) {
			$cacheWarmingParams = $this->getCauseForTracing();
			$cacheWarmingParams['options'] = ParserOutputAccess::OPT_FORCE_PARSE;

			$this->jobQueueGroup->lazyPush(
				ParsoidCachePrewarmJob::newSpec(
					$this->revision->getId(),
					$wikiPage->toPageRecord(),
					$cacheWarmingParams
				)
			);
		}
	}

}
