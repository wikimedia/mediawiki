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

use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing a page edit.
 *
 * This event is emitted by PageUpdater. It can be used by core components and
 * extensions to follow up on page edits.
 *
 * Extensions that want to subscribe to this event should list "PageUpdated"
 * as a subscribed event type.
 *
 * Subscribers based on EventSubscriberBase should implement the
 * handlePageUpdatedEventAfterCommit() listener method to be informed when
 * a page edit has been committed to the database.
 *
 * See the documentation of EventSubscriberBase and DomainEventSource for
 * more options and details.
 *
 * @todo: rename to something more descriptive, like
 * PageContentUpdatedEvent.
 *
 * @unstable until 1.45
 */
class PageUpdatedEvent extends PageEvent {

	public const TYPE = 'PageUpdated';

	/**
	 * @var string The update reverted to an earlier revision.
	 * Best effort, false negatives are possible.
	 */
	public const FLAG_REVERTED = 'revert';

	/** @var string The update should not be reported to users in feeds. */
	public const FLAG_SILENT = 'silent';

	/** @var string The update should be attributed to a bot. */
	public const FLAG_BOT = 'bot';

	/**
	 * @var string The update was automated and should not be counted as
	 * user activity.
	 */
	public const FLAG_AUTOMATED = 'automated';

	/** @var string The update was an undeletion. */
	public const FLAG_RESTORED = 'restored';

	/** @var string The update was an import. */
	public const FLAG_IMPORTED = 'imported';

	/** @var string The update was due to a page move. */
	public const FLAG_MOVED = 'moved';

	/** @var string The update was for a derived slot. */
	public const FLAG_DERIVED = 'derived';

	/**
	 * All available flags and their default values.
	 */
	public const DEFAULT_FLAGS = [
		self::FLAG_REVERTED => false,
		self::FLAG_SILENT => false,
		self::FLAG_BOT => false,
		self::FLAG_AUTOMATED => false,
		self::FLAG_RESTORED => false,
		self::FLAG_IMPORTED => false,
		self::FLAG_MOVED => false,
		self::FLAG_DERIVED => false,
	];

	private RevisionSlotsUpdate $slotsUpdate;
	private RevisionRecord $newRevision;
	private ?RevisionRecord $oldRevision;
	private ?EditResult $editResult;

	/**
	 * @param ProperPageIdentity $page The page affected by the update.
	 * @param UserIdentity $performer The user performing the update.
	 * @param RevisionSlotsUpdate $slotsUpdate Page content changed by the edit.
	 * @param RevisionRecord $newRevision The revision object resulting from the
	 *        edit.
	 * @param RevisionRecord|null $oldRevision The revision that used to be
	 *        current before the edit.
	 * @param EditResult|null $editResult An EditResult representing the effects
	 *        of an edit.
	 * @param array<string> $tags Applicable tags, see ChangeTags.
	 * @param array<string,bool> $flags See the self::FLAG_XXX constants.
	 * @param int $patrolStatus See PageUpdater::setRcPatrolStatus()
	 */
	public function __construct(
		ProperPageIdentity $page,
		UserIdentity $performer,
		RevisionSlotsUpdate $slotsUpdate,
		RevisionRecord $newRevision,
		?RevisionRecord $oldRevision,
		?EditResult $editResult,
		array $tags = [],
		array $flags = [],
		int $patrolStatus = 0
	) {
		parent::__construct( $page, $performer, $tags, $flags, $newRevision->getTimestamp() );
		$this->declareEventType( self::TYPE );

		Assert::parameter( $page->exists(), '$page', 'must exist' );
		Assert::parameter(
			$page->isSamePageAs( $newRevision->getPage() ),
			'$newRevision',
			'must belong to $page'
		);

		if ( $oldRevision ) {
			Assert::parameter(
				$page->isSamePageAs( $newRevision->getPage() ),
				'$oldRevision',
				'must belong to $page'
			);
		}

		$this->slotsUpdate = $slotsUpdate;
		$this->newRevision = $newRevision;
		$this->oldRevision = $oldRevision;
		$this->editResult = $editResult;
		$this->patrolStatus = $patrolStatus;
	}

	private int $patrolStatus;

	/**
	 * Whether the edit created the page.
	 */
	public function isNew(): bool {
		return $this->oldRevision === null;
	}

	/**
	 * Whether this is an actual revision change, as opposed to a "null-edit"
	 * or purge.
	 * This returns false if no new revision was created and the event was
	 * generated in order to trigger re-generation of derived data.
	 * This will also return true for derived data updates, since they do not
	 * create a new revision. Such updates may however still be relevant to
	 * listeners.
	 */
	public function isRevisionChange(): bool {
		return $this->oldRevision === null
			|| $this->oldRevision->getId() !== $this->newRevision->getId();
	}

	/**
	 * Whether the update changed the content of the page.
	 * This will return false for "dummy revisions" that represent an entry
	 * in the page history but do not modify the content.
	 * Note that the creation of a dummy revision may not always trigger a
	 * PageUpdatedEvent. This is only required if there was a change that is
	 * relevant to the interpretation or association of the content, such as
	 * a page move.
	 */
	public function isContentChange(): bool {
		return $this->oldRevision === null
			|| $this->oldRevision->getSha1() !== $this->newRevision->getSha1();
	}

	/**
	 * Returns the user that performed the edit.
	 */
	public function getAuthor(): UserIdentity {
		return $this->newRevision->getUser( RevisionRecord::RAW );
	}

	/**
	 * Page content changed by the edit. Can be used to determine which slots
	 * were changed, and whether slots were added or removed.
	 */
	public function getSlotsUpdate(): RevisionSlotsUpdate {
		return $this->slotsUpdate;
	}

	/**
	 * Whether the given slot was modified by the edit.
	 * Slots that were removed do not count as modified.
	 */
	public function isModifiedSlot( string $slotRole ): bool {
		return $this->getSlotsUpdate()->isModifiedSlot( $slotRole );
	}

	/**
	 * An EditResult representing the effects of an edit.
	 * Can be used to determine whether the edit was a revert
	 * and which edits were reverted.
	 * Will be null for page creations.
	 */
	public function getEditResult(): ?EditResult {
		return $this->editResult;
	}

	/**
	 * Returned the revision that used to be current before the edit.
	 * Will be null if the edit created the page.
	 * Will be the same as $newRevision if the edit was a "null-edit".
	 * Note that this is not necessarily the revision the edit was based
	 * on: in the case of edit conflicts, manual reverts, or imports,
	 * the base revision at the beginning of the edit process may be
	 * different from the parent revision after the conclusion of the edit
	 * process.
	 */
	public function getOldRevision(): ?RevisionRecord {
		return $this->oldRevision;
	}

	/**
	 * The revision that became the current one because of the edit.
	 */
	public function getNewRevision(): RevisionRecord {
		return $this->newRevision;
	}

	/**
	 * Returns the edit's initial patrol status.
	 * @see PageUpdater::setRcPatrolStatus()
	 * @see RecentChange::PRC_XXX
	 */
	public function getPatrolStatus(): int {
		return $this->patrolStatus;
	}

}

/** @deprecated temporary alias, remove before 1.44 release */
class_alias( PageUpdatedEvent::class, 'MediaWiki\Storage\PageUpdatedEvent' );
