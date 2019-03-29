<?php
/**
 * A handle for managing updates for derived page data on edit, import, purge, etc.
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
 */

namespace MediaWiki\Storage;

use ApiStashEdit;
use CategoryMembershipChangeJob;
use Content;
use ContentHandler;
use DataUpdate;
use DeferrableUpdate;
use DeferredUpdates;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use JobQueueGroup;
use Language;
use LinksDeletionUpdate;
use LinksUpdate;
use LogicException;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentity;
use MessageCache;
use ParserCache;
use ParserOptions;
use ParserOutput;
use RecentChangesUpdateJob;
use ResourceLoaderWikiModule;
use Revision;
use SearchUpdate;
use SiteStatsUpdate;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\LBFactory;
use WikiPage;

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
 * one the way to a more complete refactoring of WikiPage.
 *
 * When using a DerivedPageDataUpdater, the following life cycle must be observed:
 * grabCurrentRevision (optional), prepareContent (optional), prepareUpdate (required
 * for doUpdates). getCanonicalParserOutput, getSlots, and getSecondaryDataUpdates
 * require prepareContent or prepareUpdate to have been called first, to initialize the
 * DerivedPageDataUpdater.
 *
 * @see docs/pageupdater.txt for more information.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage, and covers the use cases
 * of PreparedEdit.
 *
 * @internal
 *
 * @since 1.32
 * @ingroup Page
 */
class DerivedPageDataUpdater implements IDBAccessObject {

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
	 * @var MessageCache
	 */
	private $messageCache;

	/**
	 * @var LBFactory
	 */
	private $loadbalancerFactory;

	/**
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod;

	/**
	 * @var boolean see $wgRCWatchCategoryMembership
	 */
	private $rcWatchCategoryMembership = false;

	/**
	 * Stores (most of) the $options parameter of prepareUpdate().
	 * @see prepareUpdate()
	 */
	private $options = [
		'changed' => true,
		// newrev is true if prepareUpdate is handling the creation of a new revision,
		// as opposed to a null edit or a forced update.
		'newrev' => false,
		'created' => false,
		'moved' => false,
		'restored' => false,
		'oldrevision' => null,
		'oldcountable' => null,
		'oldredirect' => null,
		'triggeringUser' => null,
		// causeAction/causeAgent default to 'unknown' but that's handled where it's read,
		// to make the life of prepareUpdate() callers easier.
		'causeAction' => null,
		'causeAgent' => null,
	];

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

	/**
	 * @var RevisionRenderer
	 */
	private $revisionRenderer;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/**
	 * A stage identifier for managing the life cycle of this instance.
	 * Possible stages are 'new', 'knows-current', 'has-content', 'has-revision', and 'done'.
	 *
	 * @see docs/pageupdater.txt for documentation of the life cycle.
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
	 * @see docs/pageupdater.txt for documentation of the life cycle.
	 *
	 * @var array[]
	 */
	private static $transitions = [
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

	/**
	 * @param WikiPage $wikiPage ,
	 * @param RevisionStore $revisionStore
	 * @param RevisionRenderer $revisionRenderer
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param ParserCache $parserCache
	 * @param JobQueueGroup $jobQueueGroup
	 * @param MessageCache $messageCache
	 * @param Language $contLang
	 * @param LBFactory $loadbalancerFactory
	 */
	public function __construct(
		WikiPage $wikiPage,
		RevisionStore $revisionStore,
		RevisionRenderer $revisionRenderer,
		SlotRoleRegistry $slotRoleRegistry,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		MessageCache $messageCache,
		Language $contLang,
		LBFactory $loadbalancerFactory
	) {
		$this->wikiPage = $wikiPage;

		$this->parserCache = $parserCache;
		$this->revisionStore = $revisionStore;
		$this->revisionRenderer = $revisionRenderer;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->messageCache = $messageCache;
		$this->contLang = $contLang;
		// XXX only needed for waiting for replicas to catch up; there should be a narrower
		// interface for that.
		$this->loadbalancerFactory = $loadbalancerFactory;
	}

	/**
	 * Transition function for managing the life cycle of this instances.
	 *
	 * @see docs/pageupdater.txt for documentation of the life cycle.
	 *
	 * @param string $newStage the new stage
	 * @return string the previous stage
	 *
	 * @throws LogicException If a transition to the given stage is not possible in the current
	 *         stage.
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
	 * @see docs/pageupdater.txt for documentation of the life cycle.
	 *
	 * @param string $newStage the new stage
	 *
	 * @throws LogicException If this instance is not in the expected stage
	 */
	private function assertTransition( $newStage ) {
		if ( empty( self::$transitions[$this->stage][$newStage] ) ) {
			throw new LogicException( "Cannot transition from {$this->stage} to $newStage" );
		}
	}

	/**
	 * @return bool|string
	 */
	private function getWikiId() {
		// TODO: get from RevisionStore
		return false;
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
		UserIdentity $user = null,
		RevisionRecord $revision = null,
		RevisionSlotsUpdate $slotsUpdate = null,
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
	 * @param string $articleCountMethod "any" or "link".
	 * @see $wgArticleCountMethod
	 */
	public function setArticleCountMethod( $articleCountMethod ) {
		$this->articleCountMethod = $articleCountMethod;
	}

	/**
	 * @param bool $rcWatchCategoryMembership
	 * @see $wgRCWatchCategoryMembership
	 */
	public function setRcWatchCategoryMembership( $rcWatchCategoryMembership ) {
		$this->rcWatchCategoryMembership = $rcWatchCategoryMembership;
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
		$flags = $this->useMaster() ? RevisionStore::READ_LATEST : 0;
		$this->parentRevision = $oldId
			? $this->revisionStore->getRevisionById( $oldId, $flags )
			: null;

		return $this->parentRevision;
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
	 * @see docs/pageupdater.txt for more information on when thie method can and should be called.
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

		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$wikiPage = $this->getWikiPage();

		// Do not call WikiPage::clear(), since the caller may already have caused page data
		// to be loaded with SELECT FOR UPDATE. Just assert it's loaded now.
		$wikiPage->loadPageData( self::READ_LATEST );
		$rev = $wikiPage->getRevision();
		$current = $rev ? $rev->getRevisionRecord() : null;

		$this->pageState = [
			'oldRevision' => $current,
			'oldId' => $rev ? $rev->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(), // NOTE: uses page table
			'oldCountable' => $wikiPage->isCountable(), // NOTE: uses pagelinks table
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
		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		return $this->wikiPage->getId();
	}

	/**
	 * Whether the content is deleted and thus not visible to the public.
	 *
	 * @return bool
	 */
	public function isContentDeleted() {
		if ( $this->revision ) {
			// XXX: if that revision is the current revision, this should be skipped
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
	public function getRawContent( $role ) {
		return $this->getRawSlot( $role )->getContent();
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

	private function useMaster() {
		// TODO: can we just set a flag to true in prepareContent()?
		return $this->wikiPage->wasLoadedFrom( self::READ_LATEST );
	}

	/**
	 * @return bool
	 */
	public function isCountable() {
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
			$hasLinks = (bool)count( $this->getCanonicalParserOutput()->getLinks() );
		}

		foreach ( $this->getModifiedSlotRoles() as $role ) {
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

	/**
	 * @return bool
	 */
	public function isRedirect() {
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
		$mainContent = $rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW );

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
	 * @see docs/pageupdater.txt for more information on when thie method can and should be called.
	 *
	 * @note Calling this method more than once with the same $slotsUpdate
	 * has no effect. Calling this method multiple times with different content will cause
	 * an exception.
	 *
	 * @note Calling this method after prepareUpdate() has been called will cause an exception.
	 *
	 * @param User $user The user to act as context for pre-save transformation (PST).
	 *        Type hint should be reduced to UserIdentity at some point.
	 * @param RevisionSlotsUpdate $slotsUpdate The new content of the slots to be updated
	 *        by this edit, before PST.
	 * @param bool $useStash Whether to use stashed ParserOutput
	 */
	public function prepareContent(
		User $user,
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

		$this->slotsOutput = [];
		$this->canonicalParserOutput = null;

		// The edit may have already been prepared via api.php?action=stashedit
		$stashedEdit = false;

		// TODO: MCR: allow output for all slots to be stashed.
		if ( $useStash && $slotsUpdate->isModifiedSlot( SlotRecord::MAIN ) ) {
			$mainContent = $slotsUpdate->getModifiedSlot( SlotRecord::MAIN )->getContent();
			$legacyUser = User::newFromIdentity( $user );
			$stashedEdit = ApiStashEdit::checkCache( $title, $mainContent, $legacyUser );
		}

		$userPopts = ParserOptions::newFromUserAndLang( $user, $this->contLang );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $userPopts ] );

		$this->user = $user;
		$this->slotsUpdate = $slotsUpdate;

		if ( $parentRevision ) {
			$this->revision = MutableRevisionRecord::newFromParentRevision( $parentRevision );
		} else {
			$this->revision = new MutableRevisionRecord( $title );
		}

		// NOTE: user and timestamp must be set, so they can be used for
		// {{subst:REVISIONUSER}} and {{subst:REVISIONTIMESTAMP}} in PST!
		$this->revision->setTimestamp( wfTimestampNow() );
		$this->revision->setUser( $user );

		// Set up ParserOptions to operate on the new revision
		$oldCallback = $userPopts->getCurrentRevisionCallback();
		$userPopts->setCurrentRevisionCallback(
			function ( Title $parserTitle, $parser = false ) use ( $title, $oldCallback ) {
				if ( $parserTitle->equals( $title ) ) {
					$legacyRevision = new Revision( $this->revision );
					return $legacyRevision;
				} else {
					return call_user_func( $oldCallback, $parserTitle, $parser );
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
				$content = $slot->getContent();
				$pstContent = $content->preSaveTransform( $title, $this->user, $userPopts );
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
			// null-edit!

			// TODO: move this into MutableRevisionRecord
			// TODO: This needs to behave differently for a forced dummy edit!
			$this->revision->setId( $parentRevision->getId() );
			$this->revision->setTimestamp( $parentRevision->getTimestamp() );
			$this->revision->setPageId( $parentRevision->getPageId() );
			$this->revision->setParentId( $parentRevision->getParentId() );
			$this->revision->setUser( $parentRevision->getUser( RevisionRecord::RAW ) );
			$this->revision->setComment( $parentRevision->getComment( RevisionRecord::RAW ) );
			$this->revision->setMinorEdit( $parentRevision->isMinor() );
			$this->revision->setVisibility( $parentRevision->getVisibility() );

			// prepareUpdate() is redundant for null-edits
			$this->doTransition( 'has-revision' );
		} else {
			$this->parentRevision = $parentRevision;
		}

		$renderHints = [ 'use-master' => $this->useMaster(), 'audience' => RevisionRecord::RAW ];

		if ( $stashedEdit ) {
			/** @var ParserOutput $output */
			$output = $stashedEdit->output;

			// TODO: this should happen when stashing the ParserOutput, not now!
			$output->setCacheTime( $stashedEdit->timestamp );

			$renderHints['known-revision-output'] = $output;
		}

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
	public function getRevision() {
		$this->assertPrepared( __METHOD__ );
		return $this->revision;
	}

	/**
	 * @return RenderedRevision
	 */
	public function getRenderedRevision() {
		$this->assertPrepared( __METHOD__ );

		return $this->renderedRevision;
	}

	private function assertHasPageState( $method ) {
		if ( !$this->pageState ) {
			throw new LogicException(
				'Must call grabCurrentRevision() or prepareContent() '
				. 'or prepareUpdate() before calling ' . $method
			);
		}
	}

	private function assertPrepared( $method ) {
		if ( !$this->revision ) {
			throw new LogicException(
				'Must call prepareContent() or prepareUpdate() before calling ' . $method
			);
		}
	}

	private function assertHasRevision( $method ) {
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
	 * Whether the edit created, or should create, a new revision (that is, it's not a null-edit).
	 *
	 * @warning at present, "null-revisions" that do not change content but do have a revision
	 * record would return false after prepareContent(), but true after prepareUpdate()!
	 * This should probably be fixed.
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
	public function getModifiedSlotRoles() {
		return $this->getRevisionSlotsUpdate()->getModifiedRoles();
	}

	/**
	 * Returns the role names of the slots removed by the new revision.
	 *
	 * @return string[]
	 */
	public function getRemovedSlotRoles() {
		return $this->getRevisionSlotsUpdate()->getRemovedRoles();
	}

	/**
	 * Prepare derived data updates targeting the given Revision.
	 *
	 * Calling this method requires the given revision to be present in the database.
	 * This may be right after a new revision has been created, or when re-generating
	 * derived data e.g. in ApiPurge, RefreshLinksJob, and the refreshLinks
	 * script.
	 *
	 * @see docs/pageupdater.txt for more information on when thie method can and should be called.
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
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
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
	 *  - causeAction: an arbitrary string identifying the reason for the update.
	 *    See DataUpdate::getCauseAction(). (default 'unknown')
	 *  - causeAgent: name of the user who caused the update. See DataUpdate::getCauseAgent().
	 *    (string, default 'unknown')
	 */
	public function prepareUpdate( RevisionRecord $revision, array $options = [] ) {
		Assert::parameter(
			!isset( $options['oldrevision'] )
			|| $options['oldrevision'] instanceof Revision
			|| $options['oldrevision'] instanceof RevisionRecord,
			'$options["oldrevision"]',
			'must be a RevisionRecord (or Revision)'
		);
		Assert::parameter(
			!isset( $options['triggeringUser'] )
			|| $options['triggeringUser'] instanceof UserIdentity,
			'$options["triggeringUser"]',
			'must be a UserIdentity'
		);

		if ( !$revision->getId() ) {
			throw new InvalidArgumentException(
				'Revision must have an ID set for it to be used with prepareUpdate()!'
			);
		}

		if ( $this->revision && $this->revision->getId() ) {
			if ( $this->revision->getId() === $revision->getId() ) {
				return; // nothing to do!
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
				'The Revision provided has mismatching content!'
			);
		}

		// Override fields defined in $this->options with values from $options.
		$this->options = array_intersect_key( $options, $this->options ) + $this->options;

		if ( $this->revision ) {
			$oldId = $this->pageState['oldId'] ?? 0;
			$this->options['newrev'] = ( $revision->getId() !== $oldId );
		} elseif ( isset( $this->options['oldrevision'] ) ) {
			/** @var Revision|RevisionRecord $oldRev */
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
				// This indicates that calling code has given us the wrong Revision object
				throw new LogicException(
					'The Revision mismatches old revision ID: '
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
					'The Revision provided has a mismatching actor: expected '
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

				if ( isset( $this->options['oldrevision'] ) ) {
					$rev = $this->options['oldrevision'];
					$this->pageState['oldRevision'] = $rev instanceof Revision
						? $rev->getRevisionRecord()
						: $rev;
				}
			} else {
				// This is a null-edit, so the old revision IS the new revision!
				$this->pageState['oldId'] = $revision->getId();
				$this->pageState['oldRevision'] = $revision;
			}
		}

		// "created" is forced here
		$this->options['created'] = ( $this->pageState['oldId'] === 0 );

		$this->revision = $revision;

		$this->doTransition( 'has-revision' );

		// NOTE: in case we have a User object, don't override with a UserIdentity.
		// We already checked that $revision->getUser() mathces $this->user;
		if ( !$this->user ) {
			$this->user = $revision->getUser( RevisionRecord::RAW );
		}

		// Prune any output that depends on the revision ID.
		if ( $this->renderedRevision ) {
			$this->renderedRevision->updateRevision( $revision );
		} else {

			// NOTE: we want a canonical rendering, so don't pass $this->user or ParserOptions
			// NOTE: the revision is either new or current, so we can bypass audience checks.
			$this->renderedRevision = $this->revisionRenderer->getRenderedRevision(
				$this->revision,
				null,
				null,
				[ 'use-master' => $this->useMaster(), 'audience' => RevisionRecord::RAW ]
			);

			// XXX: Since we presumably are dealing with the current revision,
			// we could try to get the ParserOutput from the parser cache.
		}

		// TODO: optionally get ParserOutput from the ParserCache here.
		// Move the logic used by RefreshLinksJob here!
	}

	/**
	 * @deprecated This only exists for B/C, use the getters on DerivedPageDataUpdater directly!
	 * @return PreparedEdit
	 */
	public function getPreparedEdit() {
		$this->assertPrepared( __METHOD__ );

		$slotsUpdate = $this->getRevisionSlotsUpdate();
		$preparedEdit = new PreparedEdit();

		$preparedEdit->popts = $this->getCanonicalParserOptions();
		$preparedEdit->output = $this->getCanonicalParserOutput();
		$preparedEdit->pstContent = $this->revision->getContent( SlotRecord::MAIN );
		$preparedEdit->newContent =
			$slotsUpdate->isModifiedSlot( SlotRecord::MAIN )
			? $slotsUpdate->getModifiedSlot( SlotRecord::MAIN )->getContent()
			: $this->revision->getContent( SlotRecord::MAIN ); // XXX: can we just remove this?
		$preparedEdit->oldContent = null; // unused. // XXX: could get this from the parent revision
		$preparedEdit->revid = $this->revision ? $this->revision->getId() : null;
		$preparedEdit->timestamp = $preparedEdit->output->getCacheTime();
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
	 * @return ParserOutput
	 */
	public function getCanonicalParserOutput() {
		return $this->getRenderedRevision()->getRevisionParserOutput();
	}

	/**
	 * @return ParserOptions
	 */
	public function getCanonicalParserOptions() {
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
			// and DataUpates are only needed for current content.
			return [];
		}

		$output = $this->getCanonicalParserOutput();

		// Construct a LinksUpdate for the combined canonical output.
		$linksUpdate = new LinksUpdate(
			$this->getTitle(),
			$output,
			$recursive
		);

		$allUpdates = [ $linksUpdate ];

		// NOTE: Run updates for all slots, not just the modified slots! Otherwise,
		// info for an inherited slot may end up being removed. This is also needed
		// to ensure that purges are effective.
		$renderedRevision = $this->getRenderedRevision();
		foreach ( $this->getSlots()->getSlotRoles() as $role ) {
			$slot = $this->getRawSlot( $role );
			$content = $slot->getContent();
			$handler = $content->getContentHandler();

			$updates = $handler->getSecondaryDataUpdates(
				$this->getTitle(),
				$content,
				$role,
				$renderedRevision
			);
			$allUpdates = array_merge( $allUpdates, $updates );

			// TODO: remove B/C hack in 1.32!
			// NOTE: we assume that the combined output contains all relevant meta-data for
			// all slots!
			$legacyUpdates = $content->getSecondaryDataUpdates(
				$this->getTitle(),
				null,
				$recursive,
				$output
			);

			// HACK: filter out redundant and incomplete LinksUpdates
			$legacyUpdates = array_filter( $legacyUpdates, function ( $update ) {
				return !( $update instanceof LinksUpdate );
			} );

			$allUpdates = array_merge( $allUpdates, $legacyUpdates );
		}

		// XXX: if a slot was removed by an earlier edit, but deletion updates failed to run at
		// that time, we don't know for which slots to run deletion updates when purging a page.
		// We'd have to examine the entire history of the page to determine that. Perhaps there
		// could be a "try extra hard" mode for that case that would run a DB query to find all
		// roles/models ever used on the page. On the other hand, removing slots should be quite
		// rare, so perhaps this isn't worth the trouble.

		// TODO: consolidate with similar logic in WikiPage::getDeletionUpdates()
		$wikiPage = $this->getWikiPage();
		$parentRevision = $this->getParentRevision();
		foreach ( $this->getRemovedSlotRoles() as $role ) {
			// HACK: we should get the content model of the removed slot from a SlotRoleHandler!
			// For now, find the slot in the parent revision - if the slot was removed, it should
			// always exist in the parent revision.
			$parentSlot = $parentRevision->getSlot( $role, RevisionRecord::RAW );
			$content = $parentSlot->getContent();
			$handler = $content->getContentHandler();

			$updates = $handler->getDeletionUpdates(
				$this->getTitle(),
				$role
			);
			$allUpdates = array_merge( $allUpdates, $updates );

			// TODO: remove B/C hack in 1.32!
			$legacyUpdates = $content->getDeletionUpdates( $wikiPage );

			// HACK: filter out redundant and incomplete LinksDeletionUpdate
			$legacyUpdates = array_filter( $legacyUpdates, function ( $update ) {
				return !( $update instanceof LinksDeletionUpdate );
			} );

			$allUpdates = array_merge( $allUpdates, $legacyUpdates );
		}

		// TODO: hard deprecate SecondaryDataUpdates in favor of RevisionDataUpdates in 1.33!
		Hooks::run(
			'RevisionDataUpdates',
			[ $this->getTitle(), $renderedRevision, &$allUpdates ]
		);

		return $allUpdates;
	}

	/**
	 * Do standard updates after page edit, purge, or import.
	 * Update links tables, site stats, search index, title cache, message cache, etc.
	 * Purges pages that depend on this page when appropriate.
	 * With a 10% chance, triggers pruning the recent changes table.
	 *
	 * @note prepareUpdate() must be called before calling this method!
	 *
	 * MCR migration note: this replaces WikiPage::doEditUpdates.
	 */
	public function doUpdates() {
		$this->assertTransition( 'done' );

		// TODO: move logic into a PageEventEmitter service

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!

		$legacyUser = User::newFromIdentity( $this->user );
		$legacyRevision = new Revision( $this->revision );

		$this->doParserCacheUpdate();

		$this->doSecondaryDataUpdates( [
			// T52785 do not update any other pages on a null edit
			'recursive' => $this->options['changed'],
			'defer' => DeferredUpdates::POSTSEND,
		] );

		// TODO: MCR: check if *any* changed slot supports categories!
		if ( $this->rcWatchCategoryMembership
			&& $this->getContentHandler( SlotRecord::MAIN )->supportsCategories() === true
			&& ( $this->options['changed'] || $this->options['created'] )
			&& !$this->options['restored']
		) {
			// Note: jobs are pushed after deferred updates, so the job should be able to see
			// the recent change entry (also done via deferred updates) and carry over any
			// bot/deletion/IP flags, ect.
			$this->jobQueueGroup->lazyPush(
				CategoryMembershipChangeJob::newSpec(
					$this->getTitle(),
					$this->revision->getTimestamp()
				)
			);
		}

		// TODO: replace legacy hook! Use a listener on PageEventEmitter instead!
		$editInfo = $this->getPreparedEdit();
		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $this->options['changed'] ] );

		// TODO: replace legacy hook! Use a listener on PageEventEmitter instead!
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

			$this->doTransition( 'done' );
			return;
		}

		if ( $this->options['oldcountable'] === 'no-change' ||
			( !$this->options['changed'] && !$this->options['moved'] )
		) {
			$good = 0;
		} elseif ( $this->options['created'] ) {
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
		$edits = $this->options['changed'] ? 1 : 0;
		$pages = $this->options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => $edits, 'articles' => $good, 'pages' => $pages ]
		) );

		// TODO: make search infrastructure aware of slots!
		$mainSlot = $this->revision->getSlot( SlotRecord::MAIN );
		if ( !$mainSlot->isInherited() && !$this->isContentDeleted() ) {
			DeferredUpdates::addUpdate( new SearchUpdate( $id, $dbKey, $mainSlot->getContent() ) );
		}

		// If this is another user's talk page, update newtalk.
		// Don't do this if $options['changed'] = false (null-edits) nor if
		// it's a minor edit and the user making the edit doesn't generate notifications for those.
		if ( $this->options['changed']
			&& $title->getNamespace() == NS_USER_TALK
			&& $shortTitle != $legacyUser->getTitleKey()
			&& !( $this->revision->isMinor() && $legacyUser->isAllowed( 'nominornewtalk' ) )
		) {
			$recipient = User::newFromName( $shortTitle, false );
			if ( !$recipient ) {
				wfDebug( __METHOD__ . ": invalid username\n" );
			} else {
				// Allow extensions to prevent user notification
				// when a new message is added to their talk page
				// TODO: replace legacy hook!  Use a listener on PageEventEmitter instead!
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

		if ( $title->getNamespace() == NS_MEDIAWIKI
			&& $this->getRevisionSlotsUpdate()->isModifiedSlot( SlotRecord::MAIN )
		) {
			$mainContent = $this->isContentDeleted() ? null : $this->getRawContent( SlotRecord::MAIN );

			$this->messageCache->updateMessageOverride( $title, $mainContent );
		}

		// TODO: move onArticleCreate and onArticle into a PageEventEmitter service
		if ( $this->options['created'] ) {
			WikiPage::onArticleCreate( $title );
		} elseif ( $this->options['changed'] ) { // T52785
			WikiPage::onArticleEdit( $title, $legacyRevision, $this->getTouchedSlotRoles() );
		}

		$oldRevision = $this->getParentRevision();
		$oldLegacyRevision = $oldRevision ? new Revision( $oldRevision ) : null;

		// TODO: In the wiring, register a listener for this on the new PageEventEmitter
		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $oldLegacyRevision, $legacyRevision, $this->getWikiId() ?: wfWikiID()
		);

		$this->doTransition( 'done' );
	}

	/**
	 * Do secondary data updates (such as updating link tables).
	 *
	 * MCR note: this method is temporarily exposed via WikiPage::doSecondaryDataUpdates.
	 *
	 * @param array $options
	 *   - recursive: make the update recursive, i.e. also update pages which transclude the
	 *     current page or otherwise depend on it (default: false)
	 *   - defer: one of the DeferredUpdates constants, or false to run immediately after waiting
	 *     for replication of the changes from the SecondaryDataUpdates hooks (default: false)
	 *   - transactionTicket: a transaction ticket from LBFactory::getEmptyTransactionTicket(),
	 *     only when defer is false (default: null)
	 * @since 1.32
	 */
	public function doSecondaryDataUpdates( array $options = [] ) {
		$this->assertHasRevision( __METHOD__ );
		$options += [
			'recursive' => false,
			'defer' => false,
			'transactionTicket' => null,
		];
		$deferValues = [ false, DeferredUpdates::PRESEND, DeferredUpdates::POSTSEND ];
		if ( !in_array( $options['defer'], $deferValues, true ) ) {
			throw new InvalidArgumentException( 'invalid value for defer: ' . $options['defer'] );
		}
		Assert::parameterType( 'integer|null', $options['transactionTicket'],
			'$options[\'transactionTicket\']' );

		$updates = $this->getSecondaryDataUpdates( $options['recursive'] );

		$triggeringUser = $this->options['triggeringUser'] ?? $this->user;
		if ( !$triggeringUser instanceof User ) {
			$triggeringUser = User::newFromIdentity( $triggeringUser );
		}
		$causeAction = $this->options['causeAction'] ?? 'unknown';
		$causeAgent = $this->options['causeAgent'] ?? 'unknown';
		$legacyRevision = new Revision( $this->revision );

		if ( $options['defer'] === false && $options['transactionTicket'] !== null ) {
			// For legacy hook handlers doing updates via LinksUpdateConstructed, make sure
			// any pending writes they made get flushed before the doUpdate() calls below.
			// This avoids snapshot-clearing errors in LinksUpdate::acquirePageLock().
			$this->loadbalancerFactory->commitAndWaitForReplication(
				__METHOD__, $options['transactionTicket']
			);
		}

		foreach ( $updates as $update ) {
			if ( $update instanceof DataUpdate ) {
				$update->setCause( $causeAction, $causeAgent );
			}
			if ( $update instanceof LinksUpdate ) {
				$update->setRevision( $legacyRevision );
				$update->setTriggeringUser( $triggeringUser );
			}

			if ( $options['defer'] === false ) {
				if ( $update instanceof DataUpdate && $options['transactionTicket'] !== null ) {
					$update->setTransactionTicket( $options['transactionTicket'] );
				}
				$update->doUpdate();
			} else {
				DeferredUpdates::addUpdate( $update, $options['defer'] );
			}
		}
	}

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
	}

}
