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
 * A handle for managing updates for page meta-data on edit, import, purge, etc.
 *
 * PageMetaDataUpdater instances are designed to be cached inside a WikiPage instance,
 * and re-used by callback code over the course of an update operation. Re-use is
 * controlled by the canUseFor() method.
 *
 * When using a PageMetaDataUpdater, the following life cycle must be observed:
 * grabParentrevision (optional), prepareEdit (optional), prepareUpdate (required
 * for doUpdates). getCaninicalParserOutput, getSlots, and getSecondaryDataUpdates
 * require prepareEdit or prepareUpdate to have been called first, to initialize the
 * PageMetaDataUpdater.
 *
 * @see docs/pageupdater.txt for more information.
 *
 * MCR migration note: this replaces the relevant methods in WikiPage, and covers the use cases
 * of PreparedEdit.
 *
 * @since 1.31
 * @ingroup Page
 */
class PageMetaDataUpdater implements IDBAccessObject {

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
	 * The state of the page table before the edit. This is determined by the first call to
	 * grabCurrentRevision, prepareEdit, or prepareUpdate. If pageState was not
	 * initialized when prepareUpdate() is called, prepareUpdate() will attempt to emulate
	 * the state of the page table before the edit.
	 *
	 * @var array
	 */
	private $pageState = null;

	/**
	 * @var MutableRevisionSlots|null
	 */
	private $newContentSlots = null;

	/**
	 * @var string[]|null
	 */
	private $stopSlots = null;

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
	 * @return bool|string
	 */
	private function getWikiId() {
		// TODO: get from RevisionStore
		return false;
	}

	/**
	 * @param string[] $a
	 * @param string[] $b
	 *
	 * @return bool
	 */
	private static function sameRoles( array $a, array $b ) {
		sort( $a );
		sort( $b );
		return $a == $b;
	}

	/**
	 * Checks whether this PageMetaDataUpdater can be re-used for running updates targeting
	 * the the given revision.
	 *
	 * @param UserIdentity|null $user The user creating the revision in question
	 * @param RevisionRecord|null $revision New revision (after save, if already saved)
	 * @param RevisionSlots|null $newSlots New content (before PST)
	 * @param string[] $stopSlots Slots to be removed in the edit
	 * @param null|int $parentId Parent revision of the edit (use 0 for page creation)
	 *
	 * @return bool
	 */
	public function canUseFor(
		UserIdentity $user = null,
		RevisionRecord $revision = null,
		RevisionSlots $newSlots = null,
		array $stopSlots = [],
		$parentId = null
	) {
		if ( $user && $this->user && $user->getName() !== $this->user->getName() ) {
			return false;
		}

		if ( $revision && $this->revision && $this->revision->getId() !== $revision->getId() ) {
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

		// NOTE: this check is the primary reason for having the $this->newContentSlots field!
		if ( $this->newContentSlots
			&& $newSlots
			&& !$this->newContentSlots->hasSameContent( $newSlots )
		) {
			return false;
		}

		if ( $this->stopSlots !== null
			&& !self::sameRoles( $this->stopSlots, $stopSlots )
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
	 * Only defined after calling grabCurrentRevision()!
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
	 *
	 * @return RevisionRecord|null the revision that was current before the edit, or null if
	 *         the edit created the page.
	 */
	private function getOldRevision() {
		$this->assertHasPageState( __METHOD__ );

		// If 'oldRevision' is not set, load it!
		// Useful if $this->oldPageState is initialized by prepareUpdate.
		if ( !array_key_exists( 'oldRevision', $this->pageState ) ) {
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
	 * @note After prepareUpdate() was called, grabCurrentRevision() will throw an exception
	 * to avoid confusion, since the page's current revision is then the new revision after
	 * the edit, which was presumably passed to prepareUpdate() as the $revision parameters.
	 * Instead, use getOldRevision() to access the revision that used to be current before the
	 * edit.
	 *
	 * @return RevisionRecord|null the page's current revision, or null if the page does not
	 * yet exist.
	 */
	public function grabCurrentRevision() {
		if ( $this->revision ) {
			throw new LogicException( 'Do not call grabCurrentRevision() after prepareUpdate(), '
				. 'use getOldRevision() instead!' );
		}

		if ( $this->pageState ) {
			// If 'oldRevision' is not set, load it!
			// Useful if $this->oldPageState is initialized by prepareUpdate.
			if ( !array_key_exists( 'oldRevision', $this->pageState ) ) {
			}

			return $this->pageState['oldRevision'];
		}

		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$wikiPage = $this->getWikiPage();

		// The caller may already have loaded it from the master or even have loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$wikiPage->loadPageData( self::READ_LATEST );
		$rev = $wikiPage->getRevision();
		$current = $rev ? $rev->getRevisionRecord() : null;

		$this->pageState = [
			'oldRevision' => $current,
			'oldId' => $rev ? $rev->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(), // NOTE: uses page table
			'oldCountable' => $wikiPage->isCountable(), // NOTE: uses pagelinks table
		];

		return $this->pageState['oldRevision'];
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
	 * Returns the slot, new or inherited, with no audience checks applied.
	 *
	 * @param string $role slot role name
	 * @return SlotRecord
	 *
	 * @throws PageUpdateException If the slot is neither set for update nor inherited from the
	 *        parent revision.
	 */
	public function getRawSlot( $role ) {
		return $this->getSlots()->getSlot( $role );
	}

	/**
	 * Returns the content of the given slot, with no audience checks.
	 *
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
		// TODO: can we just set a flag to true in prepareEdit()?
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

		// TODO: MCR: ask all slots, based on whether *they* have links.
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
	 * Prepare updates based on content which is about to be saved.
	 * This may be used to make meta-data that will be needed by doUpdates() after saving
	 * available early, without the need to re-calculate it later.
	 *
	 * @note: Calling this method more than once with the same $newContentSlots and $stopSlots
	 * has no effect. Calling this method multiple times with different content will cause
	 * an exception.
	 *
	 * @note: Calling this method after prepareUpdate() has been called will cause an exception.
	 *
	 * @param User $user The user to act as context for pre-save transformation (PST).
	 *        Type hint should be reduced to UserIdentity at some point.
	 * @param RevisionSlots $newContentSlots The new content of the slots to be updated
	 *        by this edit, before PST.
	 * @param string[] $stopSlots Slots to remove (streams to discontinue).
	 * @param bool $useStash Whether to use stashed ParserOutput
	 */
	public function prepareEdit(
		User $user,
		RevisionSlots $newContentSlots,
		$stopSlots = [],
		$useStash = true
	) {
		if ( $this->newContentSlots ) {
			if ( !$this->user ) {
				throw new LogicException(
					'Unexpected state: $this->newContentSlots was initialized, '
					. 'but $this->user was not.'
				);
			}

			if ( $this->stopSlots === null ) {
				throw new LogicException(
					'Unexpected state: $this->newContentSlots was initialized, '
					. 'but $this->stopSlots was not.'
				);
			}

			if ( $this->user->getName() !== $user->getName() ) {
				throw new LogicException( 'Can\'t call prepareEdit() again for different user! '
					. 'Expected ' . $this->user->getName() . ', got ' . $user->getName()
				);
			}

			if ( !$this->newContentSlots->hasSameContent( $newContentSlots )
				|| !self::sameRoles( $stopSlots, $this->stopSlots )
			) {
				throw new LogicException(
					'Can\'t call prepareEdit() again with different slot content!'
				);
			}

			return; // preparedEdit() already done, nothing to do
		}

		if ( $this->revision ) {
			throw new LogicException( 'Can\'t call prepareEdit() after prepareUpdate() has been called' );
		}

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!
		$title = $this->getTitle();

		$parentRevision = $this->grabCurrentRevision();

		$this->slotsOutput = [];
		$this->canonicalParserOutput = null;
		$this->canonicalParserOptions = null;

		// The edit may have already been prepared via api.php?action=stashedit
		$stashedEdit = false;

		// TODO: MCR: allow output for all slots to be stashed.
		if ( $useStash && $newContentSlots->hasSlot( 'main' ) ) {
			$mainContent = $newContentSlots->getContent( 'main' );
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
		$this->newContentSlots = $newContentSlots;
		$this->stopSlots = $stopSlots;

		if ( $parentRevision ) {
			$this->pstContentSlots = MutableRevisionSlots::newFromParentRevisionSlots(
				$parentRevision->getSlots()->getSlots()
			);
		} else {
			$this->pstContentSlots = new MutableRevisionSlots();
		}

		// XXX: should we throw an InvalidArgumentException if a slot that is in $newContentSlots
		// is also in $stopSlots?
		foreach ( $stopSlots as $role ) {
			if ( $role === 'main' ) {
				throw new InvalidArgumentException(
					'Cannot stop the main slot from being inherited.'
				);
			}

			$this->pstContentSlots->removeSlot( $role );
		}

		foreach ( $newContentSlots->getSlots() as $role => $slot ) {
			// TODO: MCR: allow PST content for all slots to be stashed.
			if ( $role === 'main' && $stashedEdit ) {
				$pstContent = $stashedEdit->pstContent;
			} else {
				$content = $slot->getContent();
				$pstContent = $content->preSaveTransform( $title, $this->user, $userPopts );
			}

			$this->pstContentSlots->setContent( $role, $pstContent );
		}

		$this->options['created'] = ( $parentRevision === null );
		$this->options['changed'] = ( $parentRevision === null
			|| !$this->pstContentSlots->hasSameContent( $parentRevision->getSlots() ) );

		// FIXME: don't force initialization here!
		// This is only here because late initialization causes
		// ApiFlowEditHeaderTest::testCache to fail. Whether that failure indicates an actual
		// problem, or is just an issue with the test setup, remains to be determined.
		$this->getCanonicalParserOutput();
	}

	private function assertHasPageState( $method ) {
		if ( !$this->pageState ) {
			throw new LogicException(
				'Must call grabCurrentRevision() or prepareEdit() or prepareUpdate() before calling '
				. $method
			);
		}
	}

	private function assertPrepared( $method ) {
		if ( !$this->pstContentSlots ) {
			throw new LogicException(
				'Must call prepareEdit() or prepareUpdate() before calling ' . $method
			);
		}
	}

	/**
	 * Whether the edit creates the page.
	 *
	 * @return array
	 */
	public function isCreation() {
		$this->assertPrepared( __METHOD__ );
		return $this->options['created'];
	}

	/**
	 * Whether the edit created, or should create, a new revision (that is, it's not a null-edit).
	 *
	 * @warning: at present, "null-revisions" that do not change content but do have a revision
	 * record would return false after prepareEdit(), but true after prepareUpdate()! This should
	 * probably be fixed.
	 *
	 * @return array
	 */
	public function isChange() {
		$this->assertPrepared( __METHOD__ );
		return $this->options['changed'];
	}

	/**
	 * Whether the page was a redirect before the edit.
	 *
	 * @return array
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
	 * Returns the role names of the slots touched by the target revision.
	 *
	 * @return string[]
	 */
	public function getTouchedSlotRoles() {
		$this->assertPrepared( __METHOD__ );
		$touched = array_keys( $this->getSlots()->getTouchedSlots() );

		if ( $this->stopSlots ) {
			// TODO: if prepareEdit wasn't called, we don't know which slots were stopped.
			// We would need to check against the parent revision.
			$touched = array_unique( array_merge( $touched + $this->stopSlots ) );
		}

		return $touched;
	}

	/**
	 * Prepare the update targeting the given Revision.
	 *
	 * Calling this method indicates that the given revision is already in the database.
	 *
	 * @note: Calling this method more than once with the same revision has no effect.
	 * $options are only used for the first call. Calling this method multiple times with
	 * different revisions will cause an exception.
	 *
	 * @note: If grabCurrentRevision() (or prepareEdit()) has been called before calling this
	 * method, $revision->getParentRevision() has to refer to the revision that was the current
	 * revision at the time grabCurrentRevision() was called.
	 *
	 * @param RevisionRecord $revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
	 * - parseroutput: The canonical ParserOutput of $revision (default null)
	 * - triggeringUser: The user triggering the updated (UserIdentity, default null)
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

		if ( $this->revision ) {
			if ( $this->revision->getId() === $revision->getId() ) {
				return; // nothing to do!
			} else {
				throw new LogicException(
					'Trying to re-use PageMetaDataUpdater with revision '
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
			// may be a Revision or RevisionRecord
			$oldId = $this->options['oldrevision']->getId();
		} else {
			$oldId = null;
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

		// If prepareEdit() was used to generate the PST content (which is indicated by
		// $this->newContentSlots being set), and this is not a null-edit, then the given
		// revision must have the acting user as the revision author. Otherwise, user
		// signatures generated by PST would mismatch the use in the revision record.
		if ( $this->user !== null && $this->options['changed'] && $this->newContentSlots ) {
			$user = $revision->getUser();
			// XXX: can't use User::equals, since $user may not be a User!
			if ( $this->user->getName() !== $user->getName() ) {
				throw new LogicException(
					'The Revision provided has a mismatching actor: expected '
					.$this->user->getName()
					. ', got '
					. $user->getName()
				);
			}
		}

		// If $this->pageState was not yet initialized by grabCurrentRevision or prepareEdit,
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

		if ( !$this->slotsOutput ) {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared output...\n" );
		} else {
			foreach ( $this->slotsOutput as $role => $prep ) {
				if ( $this->outputVariesOnRevisionMetaData( $prep->output, __METHOD__ ) ) {
					unset( $this->slotsOutput[$role] );
				}
			}
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
			// TODO: verify this logic, it seems strange [dk 2018-03]
			// Looks like it was added to prevent T135261 [anomie]
			// Idea: magic words in the cached parser output should
			// use the last *stored* edit, [dk 2018-03]
			$this->saveParseLogger->info(
				"$method: Prepared output has vary-user and is null...\n"
			);
			return true;
		} else {
			wfDebug( "$method: Keeping prepared output...\n" );
			return false;
		}
	}

	/**
	 * @deprecated This only exists for B/C, use the getters on PageMetaDataUpdater directly!
	 * @return PreparedEdit
	 */
	public function getPreparedEdit() {
		$this->assertPrepared( __METHOD__ );

		$preparedEdit = new PreparedEdit();

		$preparedEdit->popts = $this->getCanonicalParserOptions();
		$preparedEdit->output = $this->getCanonicalParserOutput();
		$preparedEdit->pstContent = $this->pstContentSlots->getContent( 'main' );
		$preparedEdit->newContent = $this->newContentSlots
			? $this->newContentSlots->getContent( 'main' )
			: null;
		$preparedEdit->oldContent = null; // unused. // XXX: could get this from the parent revision
		$preparedEdit->revid = $this->revision ? $this->revision->getId() : 0;
		$preparedEdit->timestamp = $preparedEdit->output->getCacheTime();
		$preparedEdit->format = $preparedEdit->pstContent->getDefaultFormat();

		return $preparedEdit;
	}

	/**
	 * @return bool
	 */
	private function isContentAccessible() {
		// TODO: when we move this to a RevisionHtmlProvider, the audience will be configurable!
		return $this->isContentPublic();
	}

	/**
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

		// TODO: MCR: combine parser options for all slots?! Include inherited (or use cached output)
		// XXX: Even though this *says* canonical, it may *still* depend on the user language!
		// TODO: ParserOptions should *not* be controlled by the ContentHandler!
		// We need a better way to control the splitting of the parser cache.
		$this->canonicalParserOptions = $this->wikiPage->makeParserOptions( 'canonical' );

		if ( $this->revision ) {
			// Make sure we use the appropriate revision ID when generating output
			$title = $this->getTitle();
			$oldCallback = $this->canonicalParserOptions->getCurrentRevisionCallback();
			$this->canonicalParserOptions->setCurrentRevisionCallback(
				function ( Title $parserTitle, $parser = false ) use ( $title, &$oldCallback )
				{
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
					return 1 + (int)wfGetDB( $dbIndex )->selectField(
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
		// * replace direct calls to Content::getSecondaryDatUpdates() with calls to this method
		// * Construct LinksUpdate here, on the combined ParserOutput, instead of in AbstractContent
		//   for each slot.
		// * Pass $slot into getSecondaryDatUpdates() - probably be introducing a new duplicate
		//   version of this function in ContentHandler.
		// * The new method gets the PreparedEdit, but no $recursive flag (that's for LinksUpdate)
		// * Hack: call both the old and the new getSecondaryDatUpdates method here; Pass
		//   the per-slot ParserOutput to the old method, for B/C.
		// * Hack: If there is more than one slot, filter LinksUpdate from the DataUpdates
		//   returned by getSecondaryDatUpdates, and use a LinksUpdated for the combined output
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
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * With a 10% chance, triggers pruning the recent changes table.
	 *
	 * @note prepareUpdate() must be called before calling this method!
	 *
	 * MCR migration note: this replaces WikiPage::doEditUpdates.
	 */
	public function doUpdates() {
		if ( !$this->revision ) {
			throw new LogicException( 'Must call prepareUpdate() before calling ' . __METHOD__ );
		}

		$wikiPage = $this->getWikiPage(); // TODO: use only for legacy hooks!

		// Save it to the parser cache.
		// NOTE: cache time should match page_touched to avoid double parsing.
		$this->updateParserCache( $this->revision->getTimestamp() );

		$legacyUser = User::newFromIdentity( $this->user );
		$legacyRevision = new Revision( $this->revision );

		// Update the links tables and other secondary data
		$recursive = $this->options['changed']; // T52785
		$this->runSecondaryDataUpdates( [
			'recursive' => $recursive,
			'causeAction' => isset( $this->options['causeAction'] )
				? $this->options['causeAction'] : 'edit-page',
			'causeActor' => $this->user->getName(),
			'triggeringUser' => isset( $this->options['triggeringUser'] )
				? $this->options['triggeringUser'] : null,
			'stage' => DeferredUpdates::POSTSEND,
		] );

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

		// TODO: replace legacy hook!
		$editInfo = $this->getPreparedEdit();
		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $this->options['changed'] ] );

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

		$touchedSlots = $this->revision->getTouchedSlots();
		if ( $title->getNamespace() == NS_MEDIAWIKI && $touchedSlots->hasSlot( 'main' ) ) {
			$mainContent = $this->isContentPublic() ? $touchedSlots->getContent( 'main' ) : null;

			$this->messageCache->updateMessageOverride( $title, $mainContent );
		}

		if ( $this->options['created'] ) {
			// TODO: move onArticleCreate here
			WikiPage::onArticleCreate( $title );
		} elseif ( $this->options['changed'] ) { // T52785
			// TODO: move onArticleEdit here
			WikiPage::onArticleEdit( $title, $legacyRevision, $this->getTouchedSlotRoles() );
		}

		$oldRevision = $this->getOldRevision();
		$oldLegacyRevision = $oldRevision ? new Revision( $oldRevision ) : null;

		// XXX: move method here?
		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $oldLegacyRevision, $legacyRevision, $this->getWikiId()
		);
	}

	/**
	 * @param array $options Array of options, following indexes are used:
	 * - triggeringUser: The user triggering the updated (UserIdentity, default null)
	 * - recursive: Whether data updates should be performed recursively (bool, default false)
	 * - stage: Whether to execute the deferred updates pre-send or post-send.
	 *   Use the DeferredUpdates::POSTSEND and DeferredUpdates::PRESEND constants.
	 *   If omitted, the updates are executed immediately.
	 * - causeAction: first parameter to be passed to DeferredUpdate::setCause().
	 *   If omitted, DeferredUpdate::setCause() is not used.
	 * - causeActor: second parameter to be passed to DeferredUpdate::setCause().
	 *   If omitted, DeferredUpdate::setCause() is not used.
	 * - transactionTicket: transaction ticket to use wile executing the updates,
	 *   as returned by LBFactory::getEmptyTransactionTicket(). Should not be used
	 *   when a stage is set.
	 */
	public function runSecondaryDataUpdates( $options = [] ) {
		if ( !$this->revision ) {
			throw new LogicException( 'Must call prepareUpdate() before calling ' . __METHOD__ );
		}

		$recursive = isset( $options['triggeringUser'] ) ? $options['triggeringUser'] : false;
		$triggeringUser = null;

		$updates = $this->getSecondaryDataUpdates( $recursive );

		if ( isset( $options['triggeringUser'] ) && !empty( isset( $options['triggeringUser'] ) ) ) {
			$triggeringUser = $options['triggeringUser'];
			if ( !$triggeringUser instanceof User ) {
				$triggeringUser = User::newFromIdentity( $triggeringUser );
			}
		}

		foreach ( $updates as $update ) {
			if ( isset( $options['causeAction'] ) && isset( $options['causeAgent'] ) ) {
				$update->setCause( $options['causeAction'], $options['causeAgent'] );
			}

			if ( $update instanceof LinksUpdate ) {
				$legacyRevision = new Revision( $this->revision );
				$update->setRevision( $legacyRevision );

				if ( $triggeringUser ) {
					$update->setTriggeringUser( $triggeringUser );
				}
			}

			if ( isset( $options['transactionTicket'] ) ) {
				$update->setTransactionTicket( $options['transactionTicket'] );
			}

			if ( isset( $options['stage'] ) ) {
				DeferredUpdates::addUpdate( $update, $options['stage'] );
			} else {
				$update->doUpdate();
			}
		}
	}

	/**
	 * Updates the ParserCache with the new ParserOutput
	 */
	public function updateParserCache( $cacheTime = null ) {
		if ( !$this->revision ) {
			throw new LogicException( 'Must call prepareUpdate() before calling ' . __METHOD__ );
		}

		$output = $this->getCanonicalParserOutput();

		if ( !$output->isCacheable() ) {
			return;
		}

		$options = $this->getCanonicalParserOptions();

		$cacheTime = $cacheTime ?: $output->getCacheTime();
		$revId = $this->revision->getId();

		$page = $this->getWikiPage(); // TODO: ParserCache should not need a WikiPage

		$this->parserCache->save( $output, $page, $options, $cacheTime, $revId );
	}

}
