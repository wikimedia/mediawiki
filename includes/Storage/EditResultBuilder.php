<?php
/**
 * Builder class for the EditResult object.
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
 * @author Ostrzyciel
 */

namespace MediaWiki\Storage;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;

/**
 * Builder class for the EditResult object.
 *
 * @internal Only for use by PageUpdater
 * @since 1.35
 */
class EditResultBuilder {

	/** @var RevisionRecord|null */
	private $revisionRecord = null;

	/** @var bool */
	private $isNew = false;

	/** @var bool|int */
	private $originalRevisionId = false;

	/** @var RevisionRecord|null */
	private $originalRevision = null;

	/** @var int|null */
	private $revertMethod = null;

	/** @var int|null */
	private $newestRevertedRevId = null;

	/** @var int|null */
	private $oldestRevertedRevId = null;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var string[] */
	private $softwareTags;

	/**
	 * EditResultBuilder constructor.
	 *
	 * @param RevisionStore $revisionStore
	 * @param string[] $softwareTags Array of currently enabled software change tags. Can be
	 *        obtained from ChangeTags::getSoftwareTags()
	 */
	public function __construct( RevisionStore $revisionStore, array $softwareTags ) {
		$this->revisionStore = $revisionStore;
		$this->softwareTags = $softwareTags;
	}

	/**
	 * Builds the EditResult object.
	 *
	 * @return EditResult
	 */
	public function buildEditResult() : EditResult {
		if ( $this->revisionRecord === null ) {
			throw new PageUpdateException(
				'Revision was not set prior to building an EditResult'
			);
		}

		return new EditResult(
			$this->isNew,
			$this->originalRevisionId,
			$this->revertMethod,
			$this->oldestRevertedRevId,
			$this->newestRevertedRevId,
			$this->isExactRevert(),
			$this->isNullEdit(),
			$this->getRevertTags()
		);
	}

	/**
	 * Set the revision associated with this edit.
	 * Should only be called by PageUpdater when saving an edit.
	 *
	 * @param RevisionRecord $revisionRecord
	 */
	public function setRevisionRecord( RevisionRecord $revisionRecord ) {
		$this->revisionRecord = $revisionRecord;
	}

	/**
	 * Set whether the edit created a new page.
	 * Should only be called by PageUpdater when saving an edit.
	 *
	 * @param bool $isNew
	 */
	public function setIsNew( bool $isNew ) {
		$this->isNew = $isNew;
	}

	/**
	 * Marks this edit as a revert and applies relevant information.
	 * Will do nothing if $oldestRevertedRevId is 0.
	 *
	 * @param int $revertMethod The method used to make the revert:
	 * REVERT_UNDO, REVERT_ROLLBACK or REVERT_MANUAL
	 * @param int $oldestRevertedRevId The ID of the oldest revision that was reverted.
	 * @param int $newestRevertedRevId The ID of the newest revision that was reverted. This
	 *        parameter is optional, default value is $oldestRevertedRevId
	 */
	public function markAsRevert(
		int $revertMethod,
		int $oldestRevertedRevId,
		int $newestRevertedRevId = 0
	) {
		if ( $oldestRevertedRevId === 0 ) {
			return;
		}
		if ( $newestRevertedRevId === 0 ) {
			$newestRevertedRevId = $oldestRevertedRevId;
		}

		$this->revertMethod = $revertMethod;
		$this->oldestRevertedRevId = $oldestRevertedRevId;
		$this->newestRevertedRevId = $newestRevertedRevId;
	}

	/**
	 * Sets the ID of an earlier revision that is being repeated or restored.
	 *
	 * @param int|bool $originalRevId
	 */
	public function setOriginalRevisionId( $originalRevId ) {
		$this->originalRevisionId = $originalRevId;
	}

	/**
	 * Returns the revision that is being repeated or restored.
	 * Returns null if not set for this edit.
	 *
	 * @param int $flags Access flags, e.g. RevisionStore::READ_LATEST
	 *
	 * @return RevisionRecord|null
	 */
	private function getOriginalRevision(
		int $flags = RevisionStore::READ_NORMAL
	) : ?RevisionRecord {
		if ( $this->originalRevision ) {
			return $this->originalRevision;
		}
		if ( $this->originalRevisionId === false ) {
			return null;
		}

		$this->originalRevision = $this->revisionStore->getRevisionById(
			$this->originalRevisionId,
			$flags
		);
		return $this->originalRevision;
	}

	/**
	 * Whether the edit was an exact revert, i.e. the contents of the revert
	 * revision and restored revision match
	 *
	 * @return bool
	 */
	private function isExactRevert() : bool {
		if ( $this->isNew || $this->oldestRevertedRevId === null ) {
			return false;
		}

		if ( $this->getOriginalRevision() === null ) {
			// we can't find the original revision for some reason, better return false
			return false;
		}

		return $this->revisionRecord->hasSameContent( $this->getOriginalRevision() );
	}

	/**
	 * An edit is a null edit if the original revision is equal to the parent revision.
	 *
	 * @return bool
	 */
	private function isNullEdit() : bool {
		if ( $this->isNew ) {
			return false;
		}

		return $this->getOriginalRevision() &&
			$this->originalRevisionId === $this->revisionRecord->getParentId();
	}

	/**
	 * Returns an array of revert-related tags that will be applied automatically to this edit.
	 *
	 * @return string[]
	 */
	private function getRevertTags() : array {
		if ( $this->revertMethod === EditResult::REVERT_UNDO &&
			in_array( 'mw-undo', $this->softwareTags )
		) {
			return [ 'mw-undo' ];
		} elseif ( $this->revertMethod === EditResult::REVERT_ROLLBACK &&
			in_array( 'mw-rollback', $this->softwareTags )
		) {
			return [ 'mw-rollback' ];
		}

		return [];
	}
}
