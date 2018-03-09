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
use MediaWiki\Render\RevisionRenderer;
use MediaWiki\Render\SlotRenderingProvider;
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
 * grabParentrevision (options), prepareEdit (optional), prepareUpdate (required
 * for doUpdates). getCaninicalParserOutput, getSlots, and getSecondaryDataUpdates
 * require prepareEdit or prepareUpdate to have been called to initialized the
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
class PageMetaDataUpdater implements IDBAccessObject, SlotRenderingProvider {

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
	 * @var array
	 */
	private $oldPageState = null;

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
	 * @var RevisionRenderer
	 */
	private $revisionRenderer;

	/**
	 * @param WikiPage $wikiPage ,
	 * @param RevisionStore $revisionStore
	 * @param RevisionRenderer $revisionRenderer
	 * @param ParserCache $parserCache
	 * @param JobQueueGroup $jobQueueGroup
	 * @param MessageCache $messageCache
	 * @param Language $contentLanguage
	 * @param LoggerInterface $saveParseLogger
	 */
	public function __construct(
		WikiPage $wikiPage,
		RevisionStore $revisionStore,
		RevisionRenderer $revisionRenderer,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		MessageCache $messageCache,
		LoggerInterface $saveParseLogger = null
	) {
		$this->wikiPage = $wikiPage;

		$this->parserCache = $parserCache;
		$this->revisionStore = $revisionStore;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->messageCache = $messageCache;

		// XXX: replace all wfDebug calls with a Logger. Do we nede more than one logger here?
		$this->saveParseLogger = $saveParseLogger ?: new NullLogger();
		$this->revisionRenderer = $revisionRenderer;
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

		if ( $this->oldPageState
			&& $revision
			&& $revision->getParentId() !== null
			&& $this->oldPageState['oldId'] !== $revision->getParentId()
		) {
			return false;
		}

		if ( $this->oldPageState
			&& $parentId !== null
			&& $this->oldPageState['oldId'] !== $parentId
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

		if ( $this->stopSlots
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
	 * Only defined after calling grabParentRevision()!
	 *
	 * @return bool
	 * @throws LogicException if called before grabParentRevis
	 */
	public function pageExisted() {
		if ( !$this->hasPageState() ) {
			throw new LogicException(
				'Existence is not known before grabParentRevision() has been called'
			);
		}

		return $this->oldPageState['oldId'] > 0;
	}

	/**
	 * Returns the parent revision of the update. When called before prepareUpdate(),
	 * this is the page's current revision at the time when grabParentRevision() was first called.
	 * When called after prepareUpdate(), it is the parent revision of the revision supplied
	 * to prepareUpdate().
	 *
	 * @return RevisionRecord|null the parent revision, or null of the page does not yet exist.
	 */
	public function grabParentRevision() {
		if ( $this->oldPageState ) {
			// If 'oldRevision' is not set, load it!
			// Useful if $this->oldPageState is initialized by prepareUpdate.
			if ( !array_key_exists( 'oldRevision', $this->oldPageState ) ) {
				$oldId = $this->oldPageState['oldId'];
				$flags = $this->useMaster() ? RevisionStore::READ_LATEST : 0;
				$this->oldPageState['oldRevision'] = $oldId
					? $this->revisionStore->getRevisionById( $oldId, $flags )
					: null;

			}

			return $this->oldPageState['oldRevision'];
		}

		// NOTE: eventually, we won't get a WikiPage passed into the constructor any more
		$wikiPage = $this->getWikiPage();

		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$wikiPage->loadPageData( self::READ_LATEST );
		$rev = $wikiPage->getRevision();
		$parent = $rev ? $rev->getRevisionRecord() : null;

		$this->oldPageState = [
			'oldRevision' => $parent,
			'oldId' => $rev ? $rev->getId() : 0,
			'oldIsRedirect' => $wikiPage->isRedirect(), // NOTE: uses page table
			'oldCountable' => $wikiPage->isCountable(), // NOTE: uses pagelinks table
		];

		return $this->oldPageState['oldRevision'];
	}

	private function hasPageState() {
		return $this->oldPageState !== null;
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
			return !$this->revision->isDeleted( RevisionRecord::DELETED_TEXT );
		} else {
			// If the content has not been saved yet, it can also not have been suppressed yet.
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
		// XXX: Keep in sync with WikiPage::isCountable.

		if ( $this->articleCountMethod === 'any' ) {
			return true;
		}

		if ( !$this->isContentPublic() ) {
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
	 *        Should restricted to UserIdentity at some point.
	 * @param RevisionSlots $newContentSlots The new content of the slots to be updated
	 *        by this edit, before PST.
	 *
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
			if ( $this->user && $this->user->getName() !== $user->getName() ) {
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

		$parentRevision = $this->grabParentRevision();

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
			$output->setCacheTime( $stashedEdit->timestamp );

			// TODO: MCR: allow output for all slots to be stashed.
			$this->slotsOutput['main'] = (object)[
				'hasHtml' => true,
				'output' => $output,
			];
		}

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

		foreach ( $stopSlots as $role ) {
			if ( $role === 'main' ) {
				throw new InvalidArgumentException(
					'Cannot stop the main slot from being inherited.'
				);
			}

			$this->pstContentSlots->removeSlot( $role );
		}

		$userPopts = $this->revisionRenderer->makeUserParserOptions(
			$this->getTitle(),
			$this->pstContentSlots,
			$user
		);
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $userPopts ] );

		foreach ( $newContentSlots->getSlots() as $role => $slot ) {
			// TODO: MCR: allow output for all slots to be stashed.
			if ( $role === 'main' && $stashedEdit ) {
				$pstContent = $stashedEdit->pstContent;
			} else {
				$content = $slot->getContent();
				$pstContent = $content->preSaveTransform( $title, $this->user, $userPopts );
			}

			$this->pstContentSlots->setContent( $role, $pstContent );
		}

		$this->options['created'] = ( !$this->oldPageState['oldRevision'] );
		$this->options['changed'] = $this->options['created']
			|| !$this->pstContentSlots->hasSameContent( $parentRevision->getSlots() );
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
	 * Whether the edit changes the page's content (that is, it's not a null-edit).
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
		$this->assertPrepared( __METHOD__ );

		if ( $this->oldPageState['oldIsRedirect'] === null ) {
			/** @var RevisionRecord $rev */
			$rev = $this->oldPageState['oldRevision'];
			if ( $rev ) {
				$this->oldPageState['oldIsRedirect'] = $this->revisionIsRedirect( $rev );
			} else {
				$this->oldPageState['oldIsRedirect'] = false;
			}
		}

		return $this->oldPageState['oldIsRedirect'];
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
	 * @note: If grabParentRevision() (or prepareEdit()) has been called before calling this
	 * method, $revision->getParentRevision() has to refer to the revision that was the current
	 * revision at the time grabParentRevision() was called.
	 *
	 * @param RevisionRecord $revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
	 * - parseroutput: The canonical ParserOutput of $revision (default null)
	 * - triggeringuser: The user triggering the updated (object, default null)
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
					'Trying to re-use PageMetaDataUpdater with revision '
					.$revision->getId()
					. ', but it\'s  alsready bound to revision '
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

		$this->options = array_merge( $this->options, $options );

		if ( isset( $this->oldPageState['oldId'] ) ) {
			$oldId = $this->oldPageState['oldId'];
		} elseif ( isset( $this->options['oldrevision'] ) ) {
			// may be a Revision or RevisionRecord
			$oldId = $this->options['oldrevision']->getId();
		} else {
			$oldId = null;
		}

		if ( $oldId !== null ) {
			if ( $oldId === $revision->getParentId() ) {
				// New revision, force!
				$this->options['changed'] = true;
			} elseif ( $oldId === $revision->getId() ) {
				// Null-edit, force!
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

		if ( !$this->oldPageState ) {
			$this->oldPageState = [
				'oldId' => $oldId === null ? $revision->getParentId() : $oldId,
				'oldIsRedirect' => isset( $this->options['oldredirect'] )
						&& is_bool( $this->options['oldredirect'] )
					? $this->options['oldredirect']
					: null,
				'oldCountable' => isset( $this->options['oldcountable'] )
						&& is_bool( $this->options['oldcountable'] )
					? $this->options['oldcountable']
					: null,
			];

			if ( isset( $this->options['oldrevision'] ) ) {
				$rev = $this->options['oldrevision'];
				$this->oldPageState['oldRevision'] = $rev instanceof Revision
					? $rev->getRevisionRecord()
					: $rev;
			}
		}

		// "created" is forced here
		$this->options['created'] = ( $this->oldPageState['oldId'] === 0 );

		$this->revision = $revision;
		$this->pstContentSlots = $revision->getSlots();

		// NOTE: in case we have a User object, don't override with a UserIdentity.
		// We already checked that $revision->getUser() mathces $this->user;
		if ( !$this->user ) {
			$this->user = $revision->getUser( RevisionRecord::RAW );
		}

		// Prune any output that depends on the revision ID.
		if ( $this->canonicalParserOutput && !$this->slotsOutput ) {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared output...\n" );
		} else {
			foreach ( $this->slotsOutput as $role => $prep ) {
				$keep = false;
				if ( $prep->output->getFlag( 'vary-revision' ) ) {
					$this->saveParseLogger->info(
						__METHOD__ . ": Prepared output has vary-revision...\n"
					);
				} elseif ( $prep->output->getFlag( 'vary-revision-id' )
					&& $prep->output->getSpeculativeRevIdUsed() !== $this->revision->getId()
				) {
					$this->saveParseLogger->info(
						__METHOD__ . ": Prepared output has vary-revision-id with wrong ID...\n"
					);
				} elseif ( $prep->output->getFlag( 'vary-user' )
					&& !$this->options['changed']
				) {
					// TODO: verify this logic, it seems strange [dk 2018-03]
					$this->saveParseLogger->info(
						__METHOD__ . ": Prepared output has vary-user and is null...\n"
					);
				} else {
					wfDebug( __METHOD__ . ": Keeping prepared output...\n" );
					$keep = true;
				}

				if ( !$keep ) {
					unset( $this->slotsOutput[$role] );

					// We don't know which slots the canonical output depends on, so reset it.
					$this->canonicalParserOutput = null;
				}
			}
		}

		// reset ParserOptions, to the actual revision ID is used in future ParserOutput generation
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
	 * @deprecated This only exists for B/C, use the getters on PageMetaDataUpdater directly!
	 */
	public function getPreparedEdit() {
		$this->assertPrepared( __METHOD__ );

		$preparedEdit = new PreparedEdit();

		$preparedEdit->popts = $this->getCanonicalParserOptions();
		$preparedEdit->output = $this->getCanonicalParserOutput();
		$preparedEdit->pstContent = $this->pstContentSlots->getContent( 'main' );
		$preparedEdit->newContent = $this->newContentSlots
			? $this->newContentSlots->getContent( 'main' )
			: $preparedEdit->pstContent;
		$preparedEdit->oldContent = null; // unused. // XXX: could get this from the parent revision
		$preparedEdit->revid = $this->revision ? $this->revision->getId() : 0;
		$preparedEdit->timestamp = $preparedEdit->output->getCacheTime();
		$preparedEdit->format = $preparedEdit->newContent->getDefaultFormat();

		return $preparedEdit;
	}

	/**
	 * @return ParserOutput
	 */
	public function getSlotRendering( $role, $generateHtml = true ) {
		// TODO: factor this out into a LazySlotRenderingProvider

		$this->assertPrepared( __METHOD__ );

		if ( isset( $this->slotsOutput[$role] ) ) {
			$entry = $this->slotsOutput[$role];

			if ( $entry->hasHtml || !$generateHtml ) {
				return $entry->output;
			}
		}

		if ( !$this->isContentPublic() ) {
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

		$this->canonicalParserOutput = $this->revisionRenderer->renderRevisionSlots(
			$this->getTitle(),
			$this->getSlots(),
			$this->getCanonicalParserOptions(),
			$this,
			$this->revision ? $this->revision->getId() : null
		);

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
		$this->canonicalParserOptions = $this->revisionRenderer->makeParserOptions(
			$this->getTitle(),
			$this->getSlots(),
			'canonical'
		);

		if ( $this->revision ) {
			// Make sure we use the appropriate revision ID when generating output
			$title = $this->getTitle();
			$oldCallback = $this->canonicalParserOptions->getCurrentRevisionCallback();
			$this->canonicalParserOptions->setCurrentRevisionCallback(
				function ( Title $parserTitle, $parser = false )
				use ( $title, &$oldCallback )
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
		// FIXME: we need something similar to replace Content::getDeletionUpdates!
		// FIXME: we need to use ContentHandler::getDeletionUpdates when individual slots are removed!

		if ( !$this->isContentPublic() ) {
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

		foreach ( $this->getSlots()->getSlots() as $role => $slot ) {
			$content = $slot->getContent();
			$handler = $content->getContentHandler();

			$updates = $handler->getSecondaryDataUpdates( $content, $role, $this );
			$allUpdates = array_merge( $allUpdates, $updates );

			// TODO: remove B/C hack in 1.32!
			// XXX: in theory, we should pass $this->getSlotRendering( $role ) instead
			// of $output. In practice, the ParserOutput is only used for LinksUpdate.
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
	 * Every 100th edit, prune the recent changes table.
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
		$updates = $this->getSecondaryDataUpdates(
			$recursive
		);
		foreach ( $updates as $update ) {
			// XXX: is is always the correct cause? Take from $options?
			$update->setCause( 'edit-page', $legacyUser->getName() );
			if ( $update instanceof LinksUpdate ) {
				$update->setRevision( $legacyRevision );

				if ( !empty( $this->options['triggeringuser'] ) ) {
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
		// it's a minor edit and the user doesn't want notifications for those.
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
			// XXX: move method here?
			WikiPage::onArticleCreate( $title );
		} elseif ( $this->options['changed'] ) { // T52785
			// XXX: move method here?
			WikiPage::onArticleEdit( $title, $legacyRevision, $this->getTouchedSlotRoles() );
		}

		$oldRevision = $this->grabParentRevision();
		$oldLegacyRevision = $oldRevision ? new Revision( $oldRevision ) : null;

		// XXX: move method here?
		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $oldLegacyRevision, $legacyRevision, $this->getWikiId()
		);
	}

}
