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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use Wikimedia\Assert\Assert;

/**
 * Builder class for the EditResult object.
 *
 * @internal Only for use by PageUpdater
 * @since 1.35
 * @author Ostrzyciel
 */
class EditResultBuilder {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ManualRevertSearchRadius,
	];

	/**
	 * A mapping from EditResult's revert methods to relevant change tags.
	 * For use by getRevertTags()
	 */
	private const REVERT_METHOD_TO_CHANGE_TAG = [
		EditResult::REVERT_UNDO => 'mw-undo',
		EditResult::REVERT_ROLLBACK => 'mw-rollback',
		EditResult::REVERT_MANUAL => 'mw-manual-revert'
	];

	/** @var RevisionRecord|null */
	private $revisionRecord = null;

	/** @var bool */
	private $isNew = false;

	/** @var int|false */
	private $originalRevisionId = false;

	/** @var RevisionRecord|null */
	private $originalRevision = null;

	/** @var int|null */
	private $revertMethod = null;

	/** @var int|null */
	private $newestRevertedRevId = null;

	/** @var int|null */
	private $oldestRevertedRevId = null;

	/** @var int|null */
	private $revertAfterRevId = null;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var string[] */
	private $softwareTags;

	/** @var ServiceOptions */
	private $options;

	/**
	 * @param RevisionStore $revisionStore
	 * @param string[] $softwareTags Array of currently enabled software change tags. Can be
	 *        obtained from ChangeTags::getSoftwareTags()
	 * @param ServiceOptions $options Options for this instance.
	 */
	public function __construct(
		RevisionStore $revisionStore,
		array $softwareTags,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->revisionStore = $revisionStore;
		$this->softwareTags = $softwareTags;
		$this->options = $options;
	}

	/**
	 * @return EditResult
	 */
	public function buildEditResult(): EditResult {
		if ( $this->revisionRecord === null ) {
			throw new PageUpdateException(
				'Revision was not set prior to building an EditResult'
			);
		}

		// If we don't know the original revision ID, but know which one was undone, try to find out
		$this->guessOriginalRevisionId();

		// do a last-minute check if this was a manual revert
		$this->detectManualRevert();

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
	 *
	 * @param int $revertMethod The method used to make the revert:
	 *   REVERT_UNDO, REVERT_ROLLBACK or REVERT_MANUAL
	 * @param int $newestRevertedRevId the revision ID of the latest reverted revision.
	 * @param int|null $revertAfterRevId the revision ID after which revisions
	 *   are being reverted. Defaults to the revision before the $newestRevertedRevId.
	 */
	public function markAsRevert(
		int $revertMethod,
		int $newestRevertedRevId,
		int $revertAfterRevId = null
	) {
		Assert::parameter(
			in_array(
				$revertMethod,
				[ EditResult::REVERT_UNDO, EditResult::REVERT_ROLLBACK, EditResult::REVERT_MANUAL ]
			),
			'$revertMethod',
			'must be one of REVERT_UNDO, REVERT_ROLLBACK, REVERT_MANUAL'
		);
		$this->revertAfterRevId = $revertAfterRevId;

		if ( $newestRevertedRevId ) {
			$this->revertMethod = $revertMethod;
			$this->newestRevertedRevId = $newestRevertedRevId;
			$revertAfterRevision = $revertAfterRevId ?
				$this->revisionStore->getRevisionById( $revertAfterRevId ) :
				null;
			$oldestRevertedRev = $revertAfterRevision ?
				$this->revisionStore->getNextRevision( $revertAfterRevision ) : null;
			if ( $oldestRevertedRev ) {
				$this->oldestRevertedRevId = $oldestRevertedRev->getId();
			} else {
				// Can't find the oldest reverted revision.
				// Oh well, just mark the one we know was undone.
				$this->oldestRevertedRevId = $this->newestRevertedRevId;
			}
		}
	}

	/**
	 * @param RevisionRecord|int|false|null $originalRevision
	 *   RevisionRecord or revision ID for the original revision.
	 *   False or null to unset.
	 */
	public function setOriginalRevision( $originalRevision ) {
		if ( $originalRevision instanceof RevisionRecord ) {
			$this->originalRevision = $originalRevision;
			$this->originalRevisionId = $originalRevision->getId();
		} else {
			$this->originalRevisionId = $originalRevision ?? false;
			$this->originalRevision = null; // Will be lazy-loaded.
		}
	}

	/**
	 * If this edit was not already marked as a revert using EditResultBuilder::markAsRevert(),
	 * tries to establish whether this was a manual revert, i.e. someone restored the page to
	 * an exact previous state manually.
	 *
	 * If successful, mutates the builder accordingly.
	 */
	private function detectManualRevert() {
		$searchRadius = $this->options->get( MainConfigNames::ManualRevertSearchRadius );
		if ( !$searchRadius ||
			// we already marked this as a revert
			$this->revertMethod !== null ||
			// it's a null edit, nothing was reverted
			$this->isNullEdit() ||
			// we wouldn't be able to figure out what was the newest reverted edit
			// this also discards new pages
			!$this->revisionRecord->getParentId()
		) {
			return;
		}

		$revertedToRev = $this->revisionStore->findIdenticalRevision( $this->revisionRecord, $searchRadius );
		if ( !$revertedToRev ) {
			return;
		}
		$oldestReverted = $this->revisionStore->getNextRevision( $revertedToRev );
		if ( !$oldestReverted ) {
			return;
		}

		$this->setOriginalRevision( $revertedToRev );
		$this->revertMethod = EditResult::REVERT_MANUAL;
		$this->oldestRevertedRevId = $oldestReverted->getId();
		$this->newestRevertedRevId = $this->revisionRecord->getParentId();
		$this->revertAfterRevId = $revertedToRev->getId();
	}

	/**
	 * In case we have not got the original revision ID, try to guess.
	 */
	private function guessOriginalRevisionId() {
		if ( !$this->originalRevisionId ) {
			if ( $this->revertAfterRevId ) {
				$this->setOriginalRevision( $this->revertAfterRevId );
			} elseif ( $this->newestRevertedRevId ) {
				// Try finding the original revision ID by assuming it's the one before the edit
				// that is being reverted.
				$undidRevision = $this->revisionStore->getRevisionById( $this->newestRevertedRevId );
				if ( $undidRevision ) {
					$originalRevision = $this->revisionStore->getPreviousRevision( $undidRevision );
					if ( $originalRevision ) {
						$this->setOriginalRevision( $originalRevision );
					}
				}
			}
		}

		// Make sure original revision's content is the same as
		// the new content and save the original revision ID.
		if ( $this->getOriginalRevision() &&
			!$this->getOriginalRevision()->hasSameContent( $this->revisionRecord )
		) {
			$this->setOriginalRevision( false );
		}
	}

	/**
	 * Returns the revision that is being repeated or restored.
	 * Returns null if not set for this edit.
	 *
	 * @return RevisionRecord|null
	 */
	private function getOriginalRevision(): ?RevisionRecord {
		if ( $this->originalRevision ) {
			return $this->originalRevision;
		}
		if ( !$this->originalRevisionId ) {
			return null;
		}

		$this->originalRevision = $this->revisionStore->getRevisionById( $this->originalRevisionId );
		return $this->originalRevision;
	}

	/**
	 * Whether the edit was an exact revert, i.e. the contents of the revert
	 * revision and restored revision match
	 *
	 * @return bool
	 */
	private function isExactRevert(): bool {
		if ( $this->isNew || $this->oldestRevertedRevId === null ) {
			return false;
		}

		$originalRevision = $this->getOriginalRevision();
		if ( !$originalRevision ) {
			// we can't find the original revision for some reason, better return false
			return false;
		}

		return $this->revisionRecord->hasSameContent( $originalRevision );
	}

	/**
	 * An edit is a null edit if the original revision is equal to the parent revision.
	 *
	 * @return bool
	 */
	private function isNullEdit(): bool {
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
	private function getRevertTags(): array {
		if ( isset( self::REVERT_METHOD_TO_CHANGE_TAG[$this->revertMethod] ) ) {
			$revertTag = self::REVERT_METHOD_TO_CHANGE_TAG[$this->revertMethod];
			if ( in_array( $revertTag, $this->softwareTags ) ) {
				return [ $revertTag ];
			}
		}
		return [];
	}
}
