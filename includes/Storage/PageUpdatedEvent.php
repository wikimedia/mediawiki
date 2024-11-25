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

use MediaWiki\DomainEvent\DomainEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;

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
 * @unstable until 1.45
 */
class PageUpdatedEvent extends DomainEvent {

	public const TYPE = 'PageUpdated';

	private RevisionSlotsUpdate $slotsUpdate;
	private RevisionRecord $newRevision;
	private ?RevisionRecord $oldRevision;
	private ?EditResult $editResult;
	private array $tags;
	private int $flags;

	/**
	 * @param RevisionSlotsUpdate $slotsUpdate Page content changed by the edit.
	 * @param RevisionRecord $newRevision The revision object resulting from the
	 *        edit.
	 * @param RevisionRecord|null $oldRevision The revision that used to be
	 *        current before the edit.
	 * @param EditResult|null $editResult An EditResult representing the effects
	 *        of an edit.
	 * @param array $tags Applicable tags, see ChangeTags.
	 * @param int $flags See PageUpdater::setFlags
	 * @param int $patrolStatus See PageUpdater::setRcPatrolStatus()
	 */
	public function __construct(
		RevisionSlotsUpdate $slotsUpdate,
		RevisionRecord $newRevision,
		?RevisionRecord $oldRevision,
		?EditResult $editResult,
		array $tags = [],
		int $flags = 0,
		int $patrolStatus = 0
	) {
		parent::__construct( self::TYPE, $newRevision->getTimestamp() );

		$this->slotsUpdate = $slotsUpdate;
		$this->newRevision = $newRevision;
		$this->oldRevision = $oldRevision;
		$this->editResult = $editResult;
		$this->tags = $tags;
		$this->flags = $flags;
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
	 * Returns the page that was edited.
	 */
	public function getPage(): PageIdentity {
		return $this->newRevision->getPage();
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
	 * Returns any flags set for the edit.
	 * @see PageUpdater::setFlags()
	 * @see global EDIT_XXX constants
	 */
	public function getFlags(): int {
		return $this->flags;
	}

	/**
	 * Checks flags set for the edit.
	 * This is a bitmap check.
	 * This returns true if all bits that are set in $flags were also
	 * set for the edit.
	 *
	 * @see PageUpdater::setFlags()
	 * @see global EDIT_XXX constants
	 */
	public function hasFlag( int $flag ): bool {
		return ( $this->flags & $flag ) === $flag;
	}

	/**
	 * Returns any tags applied to the edit.
	 * @see ChangeTags
	 */
	public function getTags(): array {
		return $this->tags;
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
