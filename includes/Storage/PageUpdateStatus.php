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

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;

/**
 * Status object representing the outcome of a page update.
 *
 * @see PageUpdater
 *
 * @since 1.40
 * @ingroup Page
 * @author Daniel Kinzler
 * @extends Status<array>
 * TODO: Document the exact shape of this array, it's created with different keys in different places
 */
class PageUpdateStatus extends Status {

	/**
	 * @internal for use by PageUpdater only
	 * @param bool $newPage
	 *
	 * @return PageUpdateStatus
	 */
	public static function newEmpty( bool $newPage ): PageUpdateStatus {
		return static::newGood( [
			'new' => $newPage,
			'revision-record' => null
		] );
	}

	/**
	 * @internal for use by PageUpdater only
	 * @param RevisionRecord $rev
	 */
	public function setNewRevision( RevisionRecord $rev ) {
		$this->value['revision-record'] = $rev;
	}

	/**
	 * The revision created by PageUpdater::saveRevision().
	 *
	 * Will return null if no revision was created because there was an error,
	 * or because the content didn't change (null edit or derived slot update).
	 *
	 * Call isOK() to distinguish these cases.
	 */
	public function getNewRevision(): ?RevisionRecord {
		if ( !$this->isOK() ) {
			return null;
		}

		return $this->value['revision-record'] ?? null;
	}

	/**
	 * Whether the update created a revision.
	 * If this returns false even though isOK() returns true, this means that
	 * no new revision was created because the content didn't change,
	 * including updates to derived slots.
	 */
	public function wasRevisionCreated(): bool {
		return $this->getNewRevision() !== null;
	}

	/**
	 * Whether the update created the page.
	 */
	public function wasPageCreated(): bool {
		return $this->wasRevisionCreated()
			&& ( $this->value['new'] ?? false );
	}

	/**
	 * Whether the update failed because page creation was required, but the page already exists.
	 */
	public function failedBecausePageExists(): bool {
		return !$this->isOK() && $this->hasMessage( 'edit-already-exists' );
	}

	/**
	 * Whether the update failed because page modification was required, but the page does not exist.
	 */
	public function failedBecausePageMissing(): bool {
		return !$this->isOK() && $this->hasMessage( 'edit-gone-missing' );
	}

	/**
	 * Whether the update failed because a conflicting update happened concurrently.
	 */
	public function failedBecauseOfConflict(): bool {
		return !$this->isOK() && $this->hasMessage( 'edit-conflict' );
	}

}
