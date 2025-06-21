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

use InvalidArgumentException;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\User\UserIdentity;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Domain event representing a change to the visibility attributes of one of
 * the page's revisions. This does not cover changes to archived revisions.
 *
 * @unstable until 1.45
 */
class PageHistoryVisibilityChangedEvent extends PageEvent {

	public const TYPE = 'PageHistoryVisibilityChanged';

	public const FLAG_SUPPRESSED = 'suppressed';

	private array $visibilityChangeMap;
	private string $reason;
	private int $currentRevisionId;
	private ProperPageIdentity $page;
	private int $bitsSet;
	private int $bitsUnset;

	/**
	 * @param ProperPageIdentity $page The page affected by the update.
	 * @param UserIdentity $performer The user performing the update.
	 * @param int $currentRevisionId
	 * @param int $bitsSet Bitmap indicating which bits got set by the change
	 * @param int $bitsUnset Bitmap indicating which bits got unset by the change
	 * @param array $visibilityChangeMap a map from revision IDs to visibility changes.
	 * @param string $reason
	 * @param array $tags
	 * @param array $flags
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		ProperPageIdentity $page,
		UserIdentity $performer,
		int $currentRevisionId,
		int $bitsSet,
		int $bitsUnset,
		array $visibilityChangeMap,
		string $reason,
		array $tags,
		array $flags,
		$timestamp
	) {
		parent::__construct(
			'revision-deletion',
			$page->getId(),
			$performer,
			$tags,
			$flags,
			$timestamp
		);

		$this->declareEventType( self::TYPE );

		$this->page = $page;
		$this->bitsSet = $bitsSet;
		$this->bitsUnset = $bitsUnset;
		$this->visibilityChangeMap = $visibilityChangeMap;
		$this->currentRevisionId = $currentRevisionId;
		$this->reason = $reason;
	}

	/**
	 * Returns the bits that were set by the change
	 * @return int
	 */
	public function getBitsSet(): int {
		return $this->bitsSet;
	}

	/**
	 * Returns the bits that were unset by the change
	 * @return int
	 */
	public function getBitsUnset(): int {
		return $this->bitsUnset;
	}

	/**
	 * Whether the log entry for the visibility change was suppressed,
	 * because the RevisionRecord::DELETED_RESTRICTED flag was present
	 * in the old or the new visibility bitmap of any of the affected
	 * revisions.
	 *
	 * @note Listeners should use this information to protect information from
	 * suppressed deletions from access by unauthorized users.
	 */
	public function isSuppressed(): bool {
		return $this->hasFlag( self::FLAG_SUPPRESSED );
	}

	public function getPage(): ProperPageIdentity {
		return $this->page;
	}

	public function getReason(): string {
		return $this->reason;
	}

	public function getAffectedRevisionIDs(): array {
		return array_keys( $this->visibilityChangeMap );
	}

	public function wasCurrentRevisionAffected() {
		return isset( $this->visibilityChangeMap[ $this->currentRevisionId ] );
	}

	public function getCurrentRevisionVisibilityBefore(): int {
		return $this->getVisibilityBefore( $this->currentRevisionId );
	}

	public function getCurrentRevisionVisibilityAfter(): int {
		return $this->getVisibilityAfter( $this->currentRevisionId );
	}

	public function getVisibilityBefore( int $revId ): int {
		if ( !isset( $this->visibilityChangeMap[ $revId ] ) ) {
			throw new InvalidArgumentException( 'Unexpected revision ID: ' . $revId );
		}

		return $this->visibilityChangeMap[ $revId ]['oldBits'];
	}

	public function getVisibilityAfter( int $revId ): int {
		if ( !isset( $this->visibilityChangeMap[ $revId ] ) ) {
			throw new InvalidArgumentException( 'Unexpected revision ID: ' . $revId );
		}

		return $this->visibilityChangeMap[ $revId ]['newBits'];
	}

}
