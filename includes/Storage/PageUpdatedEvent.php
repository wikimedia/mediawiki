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
 * @unstable until 1.45
 */
class PageUpdatedEvent extends DomainEvent {

	public const TYPE = 'PageUpdated';
	private RevisionRecord $newRevision;
	private ?RevisionRecord $oldRevision;
	private ?EditResult $editResult;
	private array $tags;
	private int $flags;
	private int $patrolStatus;

	public function __construct(
		RevisionRecord $newRevision,
		?RevisionRecord $oldrevision,
		?EditResult $editResult,
		array $tags = [],
		int $flags = 0,
		int $patrolStatus = 0
	) {
		parent::__construct( self::TYPE, $newRevision->getTimestamp() );

		$this->newRevision = $newRevision;
		$this->oldRevision = $oldrevision;
		$this->editResult = $editResult;
		$this->tags = $tags;
		$this->flags = $flags;
		$this->patrolStatus = $patrolStatus;
	}

	public function isNew(): bool {
		return $this->oldRevision === null;
	}

	public function getPage(): PageIdentity {
		return $this->newRevision->getPage();
	}

	public function getAuthor(): UserIdentity {
		return $this->newRevision->getUser( RevisionRecord::RAW );
	}

	/**
	 * @return ?EditResult
	 */
	public function getEditResult(): ?EditResult {
		return $this->editResult;
	}

	/**
	 * @return ?RevisionRecord
	 */
	public function getOldRevision(): ?RevisionRecord {
		return $this->oldRevision;
	}

	/**
	 * @return RevisionRecord
	 */
	public function getNewRevision(): RevisionRecord {
		return $this->newRevision;
	}

	/**
	 * @see global EDIT_XXX constants
	 * @return int
	 */
	public function getFlags(): int {
		return $this->flags;
	}

	/**
	 * @see global EDIT_XXX constants
	 */
	public function hasFlag( int $flag ): bool {
		return ( $this->flags & $flag ) === $flag;
	}

	/**
	 * @see ChangeTags
	 * @return string[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * @see RecentChange::PRC_XXX
	 * @return int
	 */
	public function getPatrolStatus(): int {
		return $this->patrolStatus;
	}

}
