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
use DeferredUpdates;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use JobQueueGroup;
use Language;
use LinksUpdate;
use LogicException;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use MessageCache;
use ParserCache;
use ParserOptions;
use ParserOutput;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecentChangesUpdateJob;
use ResourceLoaderWikiModule;
use Revision;
use SearchUpdate;
use SiteStatsUpdate;
use Title;
use User;
use Wikimedia\Assert\Assert;
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
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod;

	/**
	 * @var boolean see $wgRCWatchCategoryMembership
	 */
	private $rcWatchCategoryMembership = false;

	/**
	 * See $options on prepareUpdate.
	 */
	private $options = [
		'changed' => true,
		'created' => false,
		'moved' => false,
		'restored' => false,
		'oldcountable' => null,
		'oldredirect' => null,
	];

	/**
	 * The state of the relevant row in page table before the edit.
	 * This is determined by the first call to grabCurrentRevision, prepareContent,
	 * or prepareUpdate.
	 * If pageState was not initialized when prepareUpdate() is called, prepareUpdate() will
	 * attempt to emulate the state of the page table before the edit.
	 *
	 * @var array
	 */
	private $pageState = null;

	/**
	 * @var RevisionSlotsUpdate|null
	 */
	private $slotsUpdate = null;

	/**
	 * @var MutableRevisionSlots|null
	 */
	private $pstContentSlots = null;

	/**
	 * @var object[] anonymous objects with two fields, using slot roles as keys:
	 *  - hasHtml: whether the output contains HTML
	 *  - ParserOutput: the slot's parser output
	 */
	private $slotsOutput = [];

	/**
	 * @var ParserOutput|null
	 */
	private $canonicalParserOutput = null;

	/**
	 * @var ParserOptions|null
	 */
	private $canonicalParserOptions = null;

	/**
	 * @var RevisionRecord
	 */
	private $revision = null;

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
	 * @param ParserCache $parserCache
	 * @param JobQueueGroup $jobQueueGroup
	 * @param MessageCache $messageCache
	 * @param Language $contentLanguage
	 * @param LoggerInterface $saveParseLogger
	 */
	public function __construct(
		WikiPage $wikiPage,
		RevisionStore $revisionStore,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		MessageCache $messageCache,
		Language $contentLanguage,
		LoggerInterface $saveParseLogger = null
	) {
		$this->wikiPage = $wikiPage;

		$this->parserCache = $parserCache;
		$this->revisionStore = $revisionStore;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->messageCache = $messageCache;
		$this->contentLanguage = $contentLanguage;

		// XXX: replace all wfDebug calls with a Logger. Do we nede more than one logger here?
		$this->saveParseLogger = $saveParseLogger ?: new NullLogger();
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
	 * the the given revision.
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

		if ( $revision
			&& $user
			&& $revision->getUser( RevisionRecord::RAW )->getName() !== $user->getName()
		) {
			throw new InvalidArgumentException( '$user should match the author of $revision' );
		}

		if ( $user && $this->user && $user->getName() !== $this->user->getName() ) {
			return false;
		}

		if ( $revision && $this->revision && $this->revision->getId() !== $revision->getId() ) {
			return false;
		}

		if ( $revision && !$user ) {
			$user = $revision->getUser( RevisionRecord::RAW );
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

		if ( $this->revision
			&& $user
			&& $this->revision->getUser( RevisionRecord::RAW )->getName() !== $user->getName()
		) {
			return false;
		}

		if ( $revision
			&& $this->user
			&& $revision->getUser( RevisionRecord::RAW )->getName() !== $this->user->getName()
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

		if ( $this->pstContentSlots
			&& $revision
			&& !$this->pstContentSlots->hasSameContent( $revision->getSlots() )
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
	 * Returns the revision that was current before the edit. This would be null if the edit
	 * created the page, or the revision's parent for a regular edit, or the revision itself
	 * for a null-edit.
	 * Only defined after calling grabCurrentRevision() or prepareContent() or prepareUpdate()!
	 *
	 * @return RevisionRecord|null the revision that was current before the edit, or null if
	 *         the edit created the page.
	 */
	private function getOldRevision() {
		$this->assertHasPageState( __METHOD__ );

		// If 'oldRevision' is not set, load it!
		// Useful if $this->oldPageState is initialized by prepareUpdate.
		if ( !array_key_exists( 'oldRevision', $this->pageState ) ) {
			/** @var int $oldId */
			$oldId = $this->pageState['oldId'];
			$flags = $this->useMaster() ? RevisionStore::READ_LATEST : 0;
			$this->pageState['oldRevision'] = $oldId
				? $this->revisionStore->getRevisionById( $oldId, $flags )
				: null;
		}

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
	 * @see docs/pageupdater.txt for more information on when thie method can and should be called.
	 *
	 * @note After prepareUpdate() was called, grabCurrentRevision() will throw an exception
	 * to avoid confusion, since the page's current revision is then the new revision after
	 * the edit, which was presumably passed to prepareUpdate() as the $revision parameter.
	 * Use getOldRevision() instead to access the revision that used to be current before the
	 * edit.
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
		return $this->pstContentSlots !== null;
	}

	/**
	 * Whether prepareUpdate() has been called on this instance.
	 *
	 * @return bool
	 */
	public function isUpdatePrepared() {
		return $this->revision !== null;
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
	 * Whether the content of the target revision is publicly visible.
	 *
	 * @return bool
	 */
	public function isContentPublic() {
		if ( $this->revision ) {
			// XXX: if that revision is the current revision, this can be skipped
			return !$this->revision->isDeleted( RevisionRecord::DELETED_TEXT );
		} else {
			// If the content has not been saved yet, it cannot have been suppressed yet.
			return true;
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

		if ( !$this->isContentPublic() ) {
			// This should be irrelevant: countability only applies to the current revision,
			// and the current revision is never suppressed.
			return false;
		}

		if ( $this->isRedirect() ) {
			return false;
		}

		$hasLinks = null;

		if ( $this->articleCountMethod === 'link' ) {
			$hasLinks = (bool)count( $this->getCanonicalParserOutput()->getLinks() );
		}

		// TODO: MCR: ask all slots if they have links [SlotHandler/PageTypeHandler]
		$mainContent = $this->getRawContent( 'main' );
		return $mainContent->isCountable( $hasLinks );
	}

	/**
	 * @return bool
	 */
	public function isRedirect() {
		// NOTE: main slot determines redirect status
		$mainContent = $this->getRawContent( 'main' );

		return $mainContent->isRedirect();
	}

	/**
	 * @param RevisionRecord $rev
	 *
	 * @return bool
	 */
	private function revisionIsRedirect( RevisionRecord $rev ) {
		// NOTE: main slot determines redirect status
		$mainContent = $rev->getContent( 'main', RevisionRecord::RAW );

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
	 * @note: Calling this method more than once with the same $slotsUpdate
	 * has no effect. Calling this method multiple times with different content will cause
	 * an exception.
	 *
	 * @note: Calling this method after prepareUpdate() has been called will cause an exception.
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
		$this->canonicalParserOptions = null;

		// The edit may have already been prepared via api.php?action=stashedit
		$stashedEdit = false;

		// TODO: MCR: allow output for all slots to be stashed.
		if ( $useStash && $slotsUpdate->isModifiedSlot( 'main' ) ) {
			$mainContent = $slotsUpdate->getModifiedSlot( 'main' )->getContent();
			$legacyUser = User::newFromIdentity( $user );
			$stashedEdit = ApiStashEdit::checkCache( $title, $mainContent, $legacyUser );
		}

		if ( $stashedEdit ) {
			/** @var ParserOutput $output */
			$output = $stashedEdit->output;

			// TODO: this should happen when stashing the ParserOutput, not now!
			$output->setCacheTime( $stashedEdit->timestamp );

			// TODO: MCR: allow output for all slots to be stashed.
			$this->canonicalParserOutput = $output;
		}

		$userPopts = ParserOptions::newFromUserAndLang( $user, $this->contentLanguage );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $userPopts ] );

		$this->user = $user;
		$this->slotsUpdate = $slotsUpdate;

		if ( $parentRevision ) {
			// start out by inheriting all parent slots
			$this->pstContentSlots = MutableRevisionSlots::newFromParentRevisionSlots(
				$parentRevision->getSlots()->getSlots()
			);
		} else {
			$this->pstContentSlots = new MutableRevisionSlots();
		}

		foreach ( $slotsUpdate->getModifiedRoles() as $role ) {
			$slot = $slotsUpdate->getModifiedSlot( $role );

			if ( $slot->isInherited() ) {
				// No PST for inherited slots! Note that "modified" slots may still be inherited
				// from an earlier version, e.g. for rollbacks.
				$pstSlot = $slot;
			} elseif ( $role === 'main' && $stashedEdit ) {
				// TODO: MCR: allow PST content for all slots to be stashed.
				$pstSlot = SlotRecord::newUnsaved( $role, $stashedEdit->pstContent );
			} else {
				$content = $slot->getContent();
				$pstContent = $content->preSaveTransform( $title, $this->user, $userPopts );
				$pstSlot = SlotRecord::newUnsaved( $role, $pstContent );
			}

			$this->pstContentSlots->setSlot( $pstSlot );
		}

		foreach ( $slotsUpdate->getRemovedRoles() as $role ) {
			$this->pstContentSlots->removeSlot( $role );
		}

		$this->options['created'] = ( $parentRevision === null );
		$this->options['changed'] = ( $parentRevision === null
			|| !$this->pstContentSlots->hasSameContent( $parentRevision->getSlots() ) );

		$this->doTransition( 'has-content' );
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
		if ( !$this->pstContentSlots ) {
			throw new LogicException(
				'Must call prepareContent() or prepareUpdate() before calling ' . $method
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
	 * @warning: at present, "null-revisions" that do not change content but do have a revision
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
	 * @return RevisionSlots
	 */
	public function getSlots() {
		$this->assertPrepared( __METHOD__ );
		return $this->pstContentSlots;
	}

	/**
	 * Returns the RevisionSlotsUpdate for this updater.
	 *
	 * @return RevisionSlotsUpdate
	 */
	private function getRevisionSlotsUpdate() {
		$this->assertPrepared( __METHOD__ );

		if ( !$this->slotsUpdate ) {
			if ( !$this->revision ) {
				// This should not be possible: if assertPrepared() returns true,
				// at least one of $this->slotsUpdate or $this->revision should be set.
				throw new LogicException( 'No revision nor a slots update is known!' );
			}

			$old = $this->getOldRevision();
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
	 * @note: Calling this method more than once with the same revision has no effect.
	 * $options are only used for the first call. Calling this method multiple times with
	 * different revisions will cause an exception.
	 *
	 * @note: If grabCurrentRevision() (or prepareContent()) has been called before
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
	 * - parseroutput: The canonical ParserOutput of $revision (default null)
	 * - triggeringuser: The user triggering the update (UserIdentity, default null)
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
	 *
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
			!isset( $options['parseroutput'] )
			|| $options['parseroutput'] instanceof ParserOutput,
			'$options["parseroutput"]',
			'must be a ParserOutput'
		);
		Assert::parameter(
			!isset( $options['triggeringuser'] )
			|| $options['triggeringuser'] instanceof UserIdentity,
			'$options["triggeringuser"]',
			'must be a UserIdentity'
		);

		if ( !$revision->getId() ) {
			throw new InvalidArgumentException(
				'Revision must have an ID set for it to be used with prepareUpdate()!'
			);
		}

		if ( $this->revision ) {
			if ( $this->revision->getId() === $revision->getId() ) {
				return; // nothing to do!
			} else {
				throw new LogicException(
					'Trying to re-use DerivedPageDataUpdater with revision '
					.$revision->getId()
					. ', but it\'s already bound to revision '
					. $this->revision->getId()
				);
			}
		}

		if ( $this->pstContentSlots
			&& !$this->pstContentSlots->hasSameContent( $revision->getSlots() )
		) {
			throw new LogicException(
				'The Revision provided has mismatching content!'
			);
		}

		// Override fields defined in $this->options with values from $options.
		$this->options = array_intersect_key( $options, $this->options ) + $this->options;

		if ( isset( $this->pageState['oldId'] ) ) {
			$oldId = $this->pageState['oldId'];
		} elseif ( isset( $this->options['oldrevision'] ) ) {
			/** @var Revision|RevisionRecord $oldRev */
			$oldRev = $this->options['oldrevision'];
			$oldId = $oldRev->getId();
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
					.$this->user->getName()
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
		$this->pstContentSlots = $revision->getSlots();

		$this->doTransition( 'has-revision' );

		// NOTE: in case we have a User object, don't override with a UserIdentity.
		// We already checked that $revision->getUser() mathces $this->user;
		if ( !$this->user ) {
			$this->user = $revision->getUser( RevisionRecord::RAW );
		}

		// Prune any output that depends on the revision ID.
		if ( $this->canonicalParserOutput ) {
			if ( $this->outputVariesOnRevisionMetaData( $this->canonicalParserOutput, __METHOD__ ) ) {
				$this->canonicalParserOutput = null;
			}
		} else {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared canonical output...\n" );
		}

		if ( $this->slotsOutput ) {
			foreach ( $this->slotsOutput as $role => $prep ) {
				if ( $this->outputVariesOnRevisionMetaData( $prep->output, __METHOD__ ) ) {
					unset( $this->slotsOutput[$role] );
				}
			}
		} else {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared output...\n" );
		}

		// reset ParserOptions, so the actual revision ID is used in future ParserOutput generation
		$this->canonicalParserOptions = null;

		// Avoid re-generating the canonical ParserOutput if it's known.
		// We just trust that the caller is passing the correct ParserOutput!
		if ( isset( $options['parseroutput'] ) ) {
			$this->canonicalParserOutput = $options['parseroutput'];
		}

		// TODO: optionally get ParserOutput from the ParserCache here.
		// Move the logic used by RefreshLinksJob here!
	}

	/**
	 * @param ParserOutput $out
	 * @param string $method
	 * @return bool
	 */
	private function outputVariesOnRevisionMetaData( ParserOutput $out, $method = __METHOD__ ) {
		if ( $out->getFlag( 'vary-revision' ) ) {
			// XXX: Just keep the output if the speculative revision ID was correct, like below?
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-revision...\n"
			);
			return true;
		} elseif ( $out->getFlag( 'vary-revision-id' )
			&& $out->getSpeculativeRevIdUsed() !== $this->revision->getId()
		) {
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-revision-id with wrong ID...\n"
			);
			return true;
		} elseif ( $out->getFlag( 'vary-user' )
			&& !$this->options['changed']
		) {
			// When Alice makes a null-edit on top of Bob's edit,
			// {{REVISIONUSER}} must resolve to "Bob", not "Alice", see T135261.
			// TODO: to avoid this, we should check for null-edits in makeCanonicalparserOptions,
			// and set setCurrentRevisionCallback to return the existing revision when appropriate.
			// See also the comment there [dk 2018-05]
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-user and is null-edit...\n"
			);
			return true;
		} else {
			wfDebug( "$method: Keeping prepared output...\n" );
			return false;
		}
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
		$preparedEdit->pstContent = $this->pstContentSlots->getContent( 'main' );
		$preparedEdit->newContent =
			$slotsUpdate->isModifiedSlot( 'main' )
			? $slotsUpdate->getModifiedSlot( 'main' )->getContent()
			: $this->pstContentSlots->getContent( 'main' ); // XXX: can we just remove this?
		$preparedEdit->oldContent = null; // unused. // XXX: could get this from the parent revision
		$preparedEdit->revid = $this->revision ? $this->revision->getId() : null;
		$preparedEdit->timestamp = $preparedEdit->output->getCacheTime();
		$preparedEdit->format = $preparedEdit->pstContent->getDefaultFormat();

		return $preparedEdit;
	}

	/**
	 * @return bool
	 */
	private function isContentAccessible() {
		// XXX: when we move this to a RevisionHtmlProvider, the audience may be configurable!
		return $this->isContentPublic();
	}

	/**
	 * @param string $role
	 * @param bool $generateHtml
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, $generateHtml = true ) {
		// TODO: factor this out into a RevisionHtmlProvider that can also be used for viewing.

		$this->assertPrepared( __METHOD__ );

		if ( isset( $this->slotsOutput[$role] ) ) {
			$entry = $this->slotsOutput[$role];

			if ( $entry->hasHtml || !$generateHtml ) {
				return $entry->output;
			}
		}

		if ( !$this->isContentAccessible() ) {
			// empty output
			$output = new ParserOutput();
		} else {
			$content = $this->getRawContent( $role );

			$output = $content->getParserOutput(
				$this->getTitle(),
				$this->revision ? $this->revision->getId() : null,
				$this->getCanonicalParserOptions(),
				$generateHtml
			);
		}

		$this->slotsOutput[$role] = (object)[
			'output' => $output,
			'hasHtml' => $generateHtml,
		];

		$output->setCacheTime( $this->getTimestampNow() );

		return $output;
	}

	/**
	 * @return ParserOutput
	 */
	public function getCanonicalParserOutput() {
		if ( $this->canonicalParserOutput ) {
			return $this->canonicalParserOutput;
		}

		// TODO: MCR: logic for combining the output of multiple slot goes here!
		// TODO: factor this out into a RevisionHtmlProvider that can also be used for viewing.
		$this->canonicalParserOutput = $this->getSlotParserOutput( 'main' );

		return $this->canonicalParserOutput;
	}

	/**
	 * @return ParserOptions
	 */
	public function getCanonicalParserOptions() {
		if ( $this->canonicalParserOptions ) {
			return $this->canonicalParserOptions;
		}

		// TODO: ParserOptions should *not* be controlled by the ContentHandler!
		// See T190712 for how to fix this for Wikibase.
		$this->canonicalParserOptions = $this->wikiPage->makeParserOptions( 'canonical' );

		//TODO: if $this->revision is not set but we already know that we pending update is a
		// null-edit, we should probably use the page's current revision here.
		// That would avoid the need for the !$this->options['changed'] branch in
		// outputVariesOnRevisionMetaData [dk 2018-05]

		if ( $this->revision ) {
			// Make sure we use the appropriate revision ID when generating output
			$title = $this->getTitle();
			$oldCallback = $this->canonicalParserOptions->getCurrentRevisionCallback();
			$this->canonicalParserOptions->setCurrentRevisionCallback(
				function ( Title $parserTitle, $parser = false ) use ( $title, &$oldCallback ) {
					if ( $parserTitle->equals( $title ) ) {
						$legacyRevision = new Revision( $this->revision );
						return $legacyRevision;
					} else {
						return call_user_func( $oldCallback, $parserTitle, $parser );
					}
				}
			);
		} else {
			// NOTE: we only get here without READ_LATEST if called directly by application logic
			$dbIndex = $this->useMaster()
				? DB_MASTER // use the best possible guess
				: DB_REPLICA; // T154554

			$this->canonicalParserOptions->setSpeculativeRevIdCallback(
				function () use ( $dbIndex ) {
					// TODO: inject LoadBalancer!
					$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
					// Use a fresh connection in order to see the latest data, by avoiding
					// stale data from REPEATABLE-READ snapshots.
					// HACK: But don't use a fresh connection in unit tests, since it would not have
					// the fake tables. This should be handled by the LoadBalancer!
					$flags = defined( 'MW_PHPUNIT_TEST' ) ? 0 : $lb::CONN_TRX_AUTO;
					$db = $lb->getConnectionRef( $dbIndex, [], $this->getWikiId(), $flags );

					return 1 + (int)$db->selectField(
						'revision',
						'MAX(rev_id)',
						[],
						__METHOD__
					);
				}
			);
		}

		return $this->canonicalParserOptions;
	}

	/**
	 * @param bool $recursive
	 *
	 * @return DataUpdate[]
	 */
	public function getSecondaryDataUpdates( $recursive = false ) {
		// TODO: MCR: getSecondaryDataUpdates() needs a complete overhaul to avoid DataUpdates
		// from different slots overwriting each other in the database. Plan:
		// * replace direct calls to Content::getSecondaryDataUpdates() with calls to this method
		// * Construct LinksUpdate here, on the combined ParserOutput, instead of in AbstractContent
		//   for each slot.
		// * Pass $slot into getSecondaryDataUpdates() - probably be introducing a new duplicate
		//   version of this function in ContentHandler.
		// * The new method gets the PreparedEdit, but no $recursive flag (that's for LinksUpdate)
		// * Hack: call both the old and the new getSecondaryDataUpdates method here; Pass
		//   the per-slot ParserOutput to the old method, for B/C.
		// * Hack: If there is more than one slot, filter LinksUpdate from the DataUpdates
		//   returned by getSecondaryDataUpdates, and use a LinksUpdated for the combined output
		//   instead.
		// * Call the SecondaryDataUpdates hook here (or kill it - its signature doesn't make sense)

		$content = $this->getSlots()->getContent( 'main' );

		// NOTE: $output is the combined output, to be shown in the default view.
		$output = $this->getCanonicalParserOutput();

		$updates = $content->getSecondaryDataUpdates(
			$this->getTitle(), null, $recursive, $output
		);

		return $updates;
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

		// NOTE: this may trigger the first parsing of the new content after an edit (when not
		// using pre-generated stashed output).
		// XXX: we may want to use the PoolCounter here. This would perhaps allow the initial parse
		// to be perform post-send. The client could already follow a HTTP redirect to the
		// page view, but would then have to wait for a response until rendering is complete.
		$output = $this->getCanonicalParserOutput();

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		$this->parserCache->save(
			$output, $wikiPage, $this->getCanonicalParserOptions(),
			$this->revision->getTimestamp(),  $this->revision->getId()
		);

		$legacyUser = User::newFromIdentity( $this->user );
		$legacyRevision = new Revision( $this->revision );

		// Update the links tables and other secondary data
		$recursive = $this->options['changed']; // T52785
		$updates = $this->getSecondaryDataUpdates( $recursive );

		foreach ( $updates as $update ) {
			// TODO: make an $option field for the cause
			$update->setCause( 'edit-page', $this->user->getName() );
			if ( $update instanceof LinksUpdate ) {
				$update->setRevision( $legacyRevision );

				if ( !empty( $this->options['triggeringuser'] ) ) {
					/** @var UserIdentity|User $triggeringUser */
					$triggeringUser = $this->options['triggeringuser'];
					if ( !$triggeringUser instanceof User ) {
						$triggeringUser = User::newFromIdentity( $triggeringUser );
					}

					$update->setTriggeringUser( $triggeringUser );
				}
			}
			DeferredUpdates::addUpdate( $update );
		}

		// TODO: MCR: check if *any* changed slot supports categories!
		if ( $this->rcWatchCategoryMembership
			&& $this->getContentHandler( 'main' )->supportsCategories() === true
			&& ( $this->options['changed'] || $this->options['created'] )
			&& !$this->options['restored']
		) {
			// Note: jobs are pushed after deferred updates, so the job should be able to see
			// the recent change entry (also done via deferred updates) and carry over any
			// bot/deletion/IP flags, ect.
			$this->jobQueueGroup->lazyPush(
				new CategoryMembershipChangeJob(
					$this->getTitle(),
					[
						'pageId' => $this->getPageId(),
						'revTimestamp' => $this->revision->getTimestamp(),
					]
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
		} else {
			$good = 0;
		}
		$edits = $this->options['changed'] ? 1 : 0;
		$pages = $this->options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
			[ 'edits' => $edits, 'articles' => $good, 'pages' => $pages ]
		) );

		// TODO: make search infrastructure aware of slots!
		$mainSlot = $this->revision->getSlot( 'main' );
		if ( !$mainSlot->isInherited() && $this->isContentPublic() ) {
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
			&& $this->getRevisionSlotsUpdate()->isModifiedSlot( 'main' )
		) {
			$mainContent = $this->isContentPublic() ? $this->getRawContent( 'main' ) : null;

			$this->messageCache->updateMessageOverride( $title, $mainContent );
		}

		// TODO: move onArticleCreate and onArticle into a PageEventEmitter service
		if ( $this->options['created'] ) {
			WikiPage::onArticleCreate( $title );
		} elseif ( $this->options['changed'] ) { // T52785
			WikiPage::onArticleEdit( $title, $legacyRevision, $this->getTouchedSlotRoles() );
		}

		$oldRevision = $this->getOldRevision();
		$oldLegacyRevision = $oldRevision ? new Revision( $oldRevision ) : null;

		// TODO: In the wiring, register a listener for this on the new PageEventEmitter
		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $oldLegacyRevision, $legacyRevision, $this->getWikiId()
		);

		$this->doTransition( 'done' );
	}

}
