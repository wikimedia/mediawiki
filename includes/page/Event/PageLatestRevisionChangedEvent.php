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

namespace MediaWiki\Page\Event;

use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing a change to the page's latest revision.
 *
 * PageLatestRevisionChanged events emitted for the same page ID represent a
 * continuous chain of changes to pages' latest revision, even if the content
 * did not change (for a dummy revision). This change is observable as the
 * difference between getPageRecordBefore()->getLatest() and
 * getPageRecordAfter()->getLatest(), resp. between getLatestRevisionBefore()
 * and getLatestRevisionAfter().
 *
 * For two consecutive PageLatestRevisionChangedEvents for the same page ID, the
 * return value of getLatestRevisionAfter() on the first event will match
 * the return value of getLatestRevisionBefore() on the second event.
 * Other aspects of the page, such as the title, may change independently.
 *
 * A reconciliation version of this event may be triggered even when the page's
 * latest version did not change (on null edits), to provide an opportunity to
 * listeners to recover from data loss and corruption by re-generating any derived
 * data. In that case, getPageRecordBefore() and getPageRecordAfter() return the
 * same value.
 *
 * PageLatestRevisionChangedEvents are emitted by DerivedPageDataUpdater, typically
 * triggered by PageUpdater.
 *
 * User activities that trigger PageLatestRevisionChangeds event include:
 * - editing, including page creation and null-edits
 * - moving pages
 * - undeleting pages
 * - importing revisions
 * - uploading media files
 * - Any activity that creates a dummy revision, such as changing the page's
 *   protection level.
 *
 * @note Events may be delivered out of order! The continuity semantics apply
 * to the sequence in which the events were emitted. The event dispatcher tries to
 * deliver events in the order they were emitted, but this cannot be guaranteed
 * under all circumstances.
 *
 * @unstable until 1.45
 */
class PageLatestRevisionChangedEvent extends PageRecordChangedEvent implements PageUpdateCauses {

	public const TYPE = 'PageLatestRevisionChanged';

	/**
	 * @var string Do not notify other users (e.g. via RecentChanges or
	 * watchlist).
	 * See EDIT_SILENT.
	 */
	public const FLAG_SILENT = 'silent';

	/**
	 * @var string The update was performed by a bot.
	 * See EDIT_FORCE_BOT.
	 */
	public const FLAG_BOT = 'bot';

	/**
	 * @var string The page update is a side effect and does not represent an
	 * active user contribution.
	 * See EDIT_IMPLICIT.
	 */
	public const FLAG_IMPLICIT = 'implicit';

	/**
	 * All available flags and their default values.
	 */
	public const DEFAULT_FLAGS = [
		self::FLAG_SILENT => false,
		self::FLAG_BOT => false,
		self::FLAG_IMPLICIT => false,
	];

	private RevisionSlotsUpdate $slotsUpdate;
	private RevisionRecord $latestRevisionAfter;
	private ?RevisionRecord $latestRevisionBefore;
	private ?EditResult $editResult;

	private int $patrolStatus;

	/**
	 * @param string $cause See the self::CAUSE_XXX constants.
	 * @param ?ExistingPageRecord $pageRecordBefore The page record before the change.
	 * @param ExistingPageRecord $pageRecordAfter The page record after the change.
	 * @param ?RevisionRecord $latestRevisionBefore The revision that used
	 *        to be the latest before the updated.
	 * @param RevisionRecord $latestRevisionAfter The revision object that became
	 *        the latest as a result of the update.
	 * @param RevisionSlotsUpdate $slotsUpdate Page content changed by the update.
	 * @param ?EditResult $editResult An EditResult representing the effects
	 *        of an edit.
	 * @param UserIdentity $performer The user performing the update.
	 * @param array<string> $tags Applicable tags, see ChangeTags.
	 * @param array<string,bool> $flags See the self::FLAG_XXX constants.
	 * @param int $patrolStatus See PageUpdater::setRcPatrolStatus()
	 */
	public function __construct(
		string $cause,
		?ExistingPageRecord $pageRecordBefore,
		ExistingPageRecord $pageRecordAfter,
		?RevisionRecord $latestRevisionBefore,
		RevisionRecord $latestRevisionAfter,
		RevisionSlotsUpdate $slotsUpdate,
		?EditResult $editResult,
		UserIdentity $performer,
		array $tags = [],
		array $flags = [],
		int $patrolStatus = 0
	) {
		parent::__construct(
			$cause,
			$pageRecordBefore,
			$pageRecordAfter,
			$performer,
			$tags,
			$flags,
			$latestRevisionAfter->getTimestamp()
		);
		$this->declareEventType( self::TYPE );

		// Legacy event type name, deprecated.
		$this->declareEventType( 'PageRevisionUpdated' );

		Assert::parameter(
			$pageRecordAfter->isSamePageAs( $latestRevisionAfter->getPage() ),
			'$latestRevisionAfter',
			'must match to $pageRecordAfter'
		);

		if ( $latestRevisionBefore && $pageRecordBefore ) {
			Assert::parameter(
				$pageRecordBefore->isSamePageAs( $latestRevisionBefore->getPage() ),
				'$latestRevisionBefore',
				'must match to $pageRecordBefore'
			);
		} else {
			Assert::parameter( $pageRecordBefore === null,
				'$pageRecordBefore',
				'must be null if $latestRevisionBefore is null'
			);
			Assert::parameter( $latestRevisionBefore === null,
				'$latestRevisionBefore',
				'must be null if $pageRecordBefore is null'
			);
		}

		$this->slotsUpdate = $slotsUpdate;
		$this->latestRevisionAfter = $latestRevisionAfter;
		$this->latestRevisionBefore = $latestRevisionBefore;
		$this->editResult = $editResult;
		$this->patrolStatus = $patrolStatus;
	}

	/**
	 * @inheritDoc
	 */
	public function getPageRecordAfter(): ExistingPageRecord {
		// Overwritten to guarantee that the return value is not null.
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable
		return parent::getPageRecordAfter();
	}

	/**
	 * Returns the page that was updated.
	 */
	public function getPage(): ProperPageIdentity {
		// Deprecated on the base class, not deprecated here.
		return $this->getPageRecordAfter();
	}

	/**
	 * Whether the updated created the page.
	 * A deleted/archived page is not considered to "exist".
	 * When undeleting a page, the page will be restored using its old page ID,
	 * so the "created" page may have an ID that was seen previously.
	 */
	public function isCreation(): bool {
		return $this->latestRevisionBefore === null;
	}

	/**
	 * Whether this event represents a change to the latest revision ID
	 * associated with the page. In other words, the page's latest revision
	 * after the change is different from the page's latest revision before
	 * the change.
	 *
	 * This method will return true under most circumstances.
	 * It will however return false for reconciliation requests like null edits.
	 * In that case, isReconciliationRequest() should return true.
	 *
	 * @note Listeners should generally not use this method to check if
	 * event processing can be skipped, since that would mean ignoring
	 * reconciliation requests used to recover from data loss or corruption.
	 * The preferred way to check if processing would be redundant is
	 * isNominalContentChange().
	 *
	 * @see DomainEvent::isReconciliationRequest()
	 * @see DomainEvent::isNominalContentChange()
	 */
	public function changedLatestRevisionId(): bool {
		return $this->latestRevisionBefore === null
			|| $this->latestRevisionBefore->getId() !== $this->latestRevisionAfter->getId();
	}

	/**
	 * Whether the update nominally changed the content of the page.
	 * This is the case if:
	 * - the update actually changed the page's content, see isEffectiveContentChange().
	 * - the event is a reconciliation request, see isReconciliationRequest().
	 *
	 * On other words, this will return true for actual changes and null edits,
	 * but will return false for "dummy revisions".
	 *
	 * @note This is preferred over isEffectiveContentChange() for listeners
	 * aiming to avoid redundant processing when the content didn't change.
	 * The purpose of reconciliation requests is to re-trigger such processing
	 * to recover from data loss and corruption, even when there was no actual
	 * change in content.
	 *
	 * @see isEffectiveContentChange()
	 * @see DomainEvent::isReconciliationRequest()
	 */
	public function isNominalContentChange(): bool {
		return $this->isEffectiveContentChange() || $this->isReconciliationRequest();
	}

	/**
	 * Whether the update effectively changed the content of the page.
	 *
	 * This will return false for "dummy revisions" that represent an entry
	 * in the page history but do not modify the content. It will also be false
	 * for reconciliation events (null edits).
	 *
	 * @note Listeners aiming to skip processing of events that didn't change
	 * the content for optimization should use isNominalContentChange() instead.
	 * That way, they would not skip processing for reconciliation requests,
	 * providing a way to recover from data loss and corruption.
	 *
	 * @see isNominalContentChange()
	 */
	public function isEffectiveContentChange(): bool {
		return $this->latestRevisionBefore === null
			|| $this->latestRevisionBefore->getSha1() !== $this->latestRevisionAfter->getSha1();
	}

	/**
	 * Returns the author of the new revision.
	 * Note that this may be different from the user returned by
	 * getPerformer() for update events caused e.g. by
	 * undeletion or imports.
	 */
	public function getAuthor(): UserIdentity {
		return $this->latestRevisionAfter->getUser( RevisionRecord::RAW );
	}

	/**
	 * Returns which slots were changed, added, or removed by the update.
	 */
	public function getSlotsUpdate(): RevisionSlotsUpdate {
		return $this->slotsUpdate;
	}

	/**
	 * Whether the given slot was modified by the page update.
	 * Slots that were removed do not count as modified.
	 * This is a convenience method for
	 * $this->getSlotsUpdate()->isModifiedSlot( $slotRole ).
	 */
	public function isModifiedSlot( string $slotRole ): bool {
		return $this->getSlotsUpdate()->isModifiedSlot( $slotRole );
	}

	/**
	 * An EditResult representing the effects of the update.
	 * Can be used to determine whether the edit was a revert
	 * and which edits were reverted.
	 *
	 * This may return null for updates that do not result from edits,
	 * such as imports or undeletions.
	 */
	public function getEditResult(): ?EditResult {
		return $this->editResult;
	}

	/**
	 * Returned the revision that used to be latest before the update.
	 * Will be null if the edit created the page.
	 * Will be the same as getLatestRevisionAfter() if the edit was a
	 * "null-edit".
	 *
	 * Note that this is not necessarily the new revision's parent revision.
	 * For instance, when undeleting a page, getLatestRevisionBefore() will
	 * return null because the page didn't exist before, even if the undeleted
	 * page has many revisions and the new latest revision indeed has a parent
	 * revision.
	 *
	 * The parent revision can be determined by calling
	 * getLatestRevisionAfter()->getParentId().
	 */
	public function getLatestRevisionBefore(): ?RevisionRecord {
		return $this->latestRevisionBefore;
	}

	/**
	 * The revision that became the latest as a result of the update.
	 */
	public function getLatestRevisionAfter(): RevisionRecord {
		return $this->latestRevisionAfter;
	}

	/**
	 * Returns the page update's initial patrol status.
	 * @see PageUpdater::setRcPatrolStatus()
	 * @see RecentChange::PRC_XXX
	 */
	public function getPatrolStatus(): int {
		return $this->patrolStatus;
	}

	/**
	 * Whether the update should be omitted from update feeds presented to the
	 * user.
	 */
	public function isSilent(): bool {
		return $this->hasFlag( self::FLAG_SILENT );
	}

	/**
	 * Whether the update was performed automatically without the user's
	 * initiative.
	 */
	public function isImplicit(): bool {
		return $this->hasFlag( self::FLAG_IMPLICIT );
	}

	/**
	 * Whether the update reverts an earlier update to the same page.
	 * Note that an "undo" style revert may create a new revision that is
	 * different from any previous revision by applying the inverse of a
	 * past update to the latest revision.
	 *
	 * @see EditResult::isRevert
	 */
	public function isRevert(): bool {
		return $this->editResult && $this->editResult->isRevert();
	}

	/**
	 * Whether the update was performed by a bot.
	 */
	public function isBotUpdate(): bool {
		return $this->hasFlag( self::FLAG_BOT );
	}

}

// @deprecated temporary alias, remove before 1.45 release
class_alias( PageLatestRevisionChangedEvent::class, 'MediaWiki\Page\Event\PageRevisionUpdatedEvent' );
